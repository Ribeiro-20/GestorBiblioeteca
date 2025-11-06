<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckBibliotecario
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Você precisa estar autenticado.');
        }

        $user = auth()->user();
        
        // Apenas admin e bibliotecario podem acessar
        if (!$user->podeGerenciarEmprestimos()) {
            return redirect()->route('dashboard')->with('error', 'Você não tem permissão para acessar esta área. Apenas administradores e bibliotecários podem gerenciar empréstimos e biblioteca.');
        }

        return $next($request);
    }
}
