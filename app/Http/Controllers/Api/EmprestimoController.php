<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emprestimo;
use App\Models\Livro;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EmprestimoController extends Controller
{
    /**
     * Listar todos os empréstimos
     */
    public function index(Request $request)
    {
        $query = Emprestimo::with(['livro', 'user']);

        // Filtros
        if ($request->has('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('atrasados')) {
            $query->where('estado', 'ativo')
                  ->where('data_limite', '<', Carbon::now());
        }

        $emprestimos = $query->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $emprestimos
        ]);
    }

    /**
     * Criar novo empréstimo (Emprestar livro)
     * APENAS admin e bibliotecario podem criar empréstimos
     */
    public function store(Request $request)
    {
        // Verifica permissão
        if (!auth()->check() || !auth()->user()->podeGerenciarEmprestimos()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para realizar empréstimos. Apenas administradores e bibliotecários.'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'livro_id' => 'required|exists:livros,id',
            'user_id' => 'required|exists:users,id',
            'dias_emprestimo' => 'nullable|integer|min:1|max:30'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Verifica se o livro está disponível
        $livro = Livro::find($request->livro_id);
        if (!$livro->isDisponivel()) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não está disponível para empréstimo'
            ], 400);
        }

        // Verifica se o usuário já tem empréstimo ativo deste livro
        $emprestimoExistente = Emprestimo::where('livro_id', $request->livro_id)
            ->where('user_id', $request->user_id)
            ->where('estado', 'ativo')
            ->exists();

        if ($emprestimoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário já possui empréstimo ativo deste livro'
            ], 400);
        }

    $diasEmprestimo = (int) ($request->dias_emprestimo ?? 14); // Padrão 14 dias, garantir int para Carbon

        $emprestimo = Emprestimo::create([
            'livro_id' => $request->livro_id,
            'user_id' => $request->user_id,
            'data_emprestimo' => Carbon::now(),
            'data_limite' => Carbon::now()->addDays($diasEmprestimo),
            'estado' => 'ativo'
        ]);

        // Atualiza o estado do livro para "emprestado"
        $livro->update(['estado' => 'emprestado']);

        return response()->json([
            'success' => true,
            'message' => 'Empréstimo realizado com sucesso!',
            'data' => $emprestimo->load(['livro', 'user'])
        ], 201);
    }

    /**
     * Ver detalhes de um empréstimo
     */
    public function show(string $id)
    {
        $emprestimo = Emprestimo::with(['livro', 'user'])->find($id);

        if (!$emprestimo) {
            return response()->json([
                'success' => false,
                'message' => 'Empréstimo não encontrado'
            ], 404);
        }

        // Verifica se está atrasado
        $atrasado = $emprestimo->isAtrasado();
        $diasAtraso = $emprestimo->diasAtraso();

        return response()->json([
            'success' => true,
            'data' => array_merge($emprestimo->toArray(), [
                'atrasado' => $atrasado,
                'dias_atraso' => $diasAtraso
            ])
        ]);
    }

    /**
     * Devolver livro emprestado
     * Leitores podem devolver seus próprios empréstimos
     * Admin e bibliotecário podem devolver qualquer empréstimo
     */
    public function devolver(string $id)
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $emprestimo = Emprestimo::find($id);

        if (!$emprestimo) {
            return response()->json([
                'success' => false,
                'message' => 'Empréstimo não encontrado'
            ], 404);
        }

        $user = auth()->user();
        
        // Verifica se o usuário pode devolver este empréstimo
        // Pode devolver se: é o dono do empréstimo OU tem permissão de gestão
        if ($emprestimo->user_id !== $user->id && !$user->podeGerenciarEmprestimos()) {
            return response()->json([
                'success' => false,
                'message' => 'Você não tem permissão para devolver este empréstimo'
            ], 403);
        }

        if ($emprestimo->estado !== 'ativo') {
            return response()->json([
                'success' => false,
                'message' => 'Este empréstimo não está ativo'
            ], 400);
        }

        $emprestimo->update([
            'data_devolucao' => Carbon::now(),
            'estado' => 'devolvido'
        ]);

        // Atualiza o estado do livro para "disponível"
        $emprestimo->livro->update(['estado' => 'disponivel']);

        return response()->json([
            'success' => true,
            'message' => 'Livro devolvido com sucesso!',
            'data' => $emprestimo->load(['livro', 'user'])
        ]);
    }

    /**
     * Listar empréstimos ativos
     */
    public function ativos()
    {
        $emprestimos = Emprestimo::with(['livro', 'user'])
            ->where('estado', 'ativo')
            ->orderBy('data_limite', 'asc')
            ->get();

        // Adiciona informação de atraso
        $emprestimos = $emprestimos->map(function($emprestimo) {
            $emprestimo->atrasado = $emprestimo->isAtrasado();
            $emprestimo->dias_atraso = $emprestimo->diasAtraso();
            return $emprestimo;
        });

        return response()->json([
            'success' => true,
            'total' => $emprestimos->count(),
            'data' => $emprestimos
        ]);
    }

    /**
     * Listar empréstimos atrasados
     */
    public function atrasados()
    {
        $emprestimos = Emprestimo::with(['livro', 'user'])
            ->where('estado', 'ativo')
            ->where('data_limite', '<', Carbon::now())
            ->orderBy('data_limite', 'asc')
            ->get();

        $emprestimos = $emprestimos->map(function($emprestimo) {
            $emprestimo->dias_atraso = $emprestimo->diasAtraso();
            return $emprestimo;
        });

        return response()->json([
            'success' => true,
            'total' => $emprestimos->count(),
            'data' => $emprestimos
        ]);
    }

    /**
     * Listar empréstimos por usuário
     */
    public function porUsuario(string $userId)
    {
        $emprestimos = Emprestimo::with(['livro'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $emprestimos
        ]);
    }

    /**
     * Listar todos os usuários LEITORES (para o formulário de empréstimo)
     */
    public function listarUsuarios()
    {
        // Lista apenas leitores (quem pode pegar livros emprestados)
        $usuarios = User::select('id', 'nome', 'email', 'numero_cartao', 'tipo')
            ->where('tipo', 'leitor')
            ->orderBy('nome')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $usuarios
        ]);
    }

    /**
     * Listar empréstimos do usuário autenticado (para leitores verem seus próprios empréstimos)
     */
    public function meus()
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 401);
        }

        $emprestimos = Emprestimo::with(['livro'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Adiciona informação de atraso para empréstimos ativos
        $emprestimos = $emprestimos->map(function($emprestimo) {
            if ($emprestimo->estado === 'ativo') {
                $emprestimo->atrasado = $emprestimo->isAtrasado();
                $emprestimo->dias_atraso = $emprestimo->diasAtraso();
            }
            return $emprestimo;
        });

        return response()->json([
            'success' => true,
            'user_id' => $user->id,
            'data' => $emprestimos
        ]);
    }
}
