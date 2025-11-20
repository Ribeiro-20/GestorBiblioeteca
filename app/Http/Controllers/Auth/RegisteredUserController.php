<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Validação dos campos
        $request->validate([
            'nome' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'numero_cartao' => ['nullable', 'string', 'max:255', 'unique:users,numero_cartao'],
            'data_inscricao' => ['nullable', 'date'],
        ]);

        // Criação do usuário - sempre como "leitor"
        $user = User::create([
            'nome' => $request->nome,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'tipo' => 'leitor', // Todos os novos usuários são leitores
            'numero_cartao' => $request->numero_cartao,
            'data_inscricao' => $request->data_inscricao,
        ]);

        // Dispara evento de registro
        event(new Registered($user));

        // Login automático do usuário
        Auth::login($user);

        // Redireciona para dashboard
        return redirect()->route('dashboard');
    }
}
