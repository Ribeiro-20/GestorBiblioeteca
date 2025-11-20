<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\OpenLibraryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OpenLibraryController extends Controller
{
    private OpenLibraryService $openLibraryService;

    public function __construct(OpenLibraryService $openLibraryService)
    {
        $this->openLibraryService = $openLibraryService;
    }

    /**
     * Buscar livro por ISBN na Open Library
     */
    public function buscarPorISBN(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string|min:10|max:13'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $livro = $this->openLibraryService->buscarPorISBN($request->isbn);

        if (!$livro) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado na Open Library'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $livro
        ]);
    }

    /**
     * Buscar livros por título
     */
    public function buscarPorTitulo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $limit = $request->input('limit', 10);
        $livros = $this->openLibraryService->buscarPorTitulo($request->titulo, $limit);

        return response()->json([
            'success' => true,
            'total' => count($livros),
            'data' => $livros
        ]);
    }

    /**
     * Buscar livros por autor
     */
    public function buscarPorAutor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'autor' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $limit = $request->input('limit', 10);
        $livros = $this->openLibraryService->buscarPorAutor($request->autor, $limit);

        return response()->json([
            'success' => true,
            'total' => count($livros),
            'data' => $livros
        ]);
    }

    /**
     * Buscar livros (busca geral)
     */
    public function buscar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'q' => 'required|string|min:2',
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $limit = $request->input('limit', 10);
        $livros = $this->openLibraryService->buscar($request->q, $limit);

        return response()->json([
            'success' => true,
            'total' => count($livros),
            'data' => $livros
        ]);
    }

    /**
     * Importar livro da Open Library para o banco de dados
     */
    public function importarPorISBN(Request $request)
    {
        // Normalizar ISBN removendo espaços e hífens antes de validar
        $normalizedIsbn = preg_replace('/[^0-9X]/', '', (string) $request->isbn);

        $validator = Validator::make(['isbn' => $normalizedIsbn], [
            'isbn' => 'required|string|min:10|max:13|unique:livros,isbn'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $dadosLivro = $this->openLibraryService->buscarPorISBN($normalizedIsbn);

        if (!$dadosLivro) {
            return response()->json([
                'success' => false,
                'message' => 'Livro não encontrado na Open Library'
            ], 404);
        }

        // Garantir que ano_publicacao seja inteiro ou null
        $anoPublicacao = $dadosLivro['ano_publicacao'];
        if ($anoPublicacao && !is_int($anoPublicacao)) {
            // Se vier string, extrair apenas o ano
            if (preg_match('/\b(19|20)\d{2}\b/', $anoPublicacao, $matches)) {
                $anoPublicacao = (int) $matches[0];
            } else {
                $anoPublicacao = null;
            }
        }

        // Importar para o banco de dados
        $livro = \App\Models\Livro::create([
            'titulo' => $dadosLivro['titulo'],
            'autor' => $dadosLivro['autor'],
            // Garantir que o ISBN gravado seja o normalizado
            'isbn' => preg_replace('/[^0-9X]/', '', (string) ($dadosLivro['isbn'] ?? $normalizedIsbn)),
            'categoria' => $dadosLivro['categoria'],
            'ano_publicacao' => $anoPublicacao,
            'imagem' => $dadosLivro['imagem'],
            'estado' => 'disponivel'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Livro importado com sucesso!',
            'data' => $livro
        ], 201);
    }

    /**
     * Obter URL da capa por ISBN
     */
    public function obterCapa(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'isbn' => 'required|string|min:10|max:13',
            'size' => 'nullable|in:S,M,L'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $size = $request->input('size', 'M');
        $url = $this->openLibraryService->obterUrlCapaPorISBN($request->isbn, $size);

        return response()->json([
            'success' => true,
            'data' => [
                'url' => $url,
                'isbn' => $request->isbn,
                'size' => $size
            ]
        ]);
    }
}
