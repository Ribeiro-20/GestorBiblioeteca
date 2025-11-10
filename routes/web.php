<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\EmprestimoController;
use Illuminate\Support\Facades\Route;

require __DIR__.'/auth.php';

Route::get('/', function () {
    return view('welcome');
});

Route::get('/biblioteca', function () {
    return view('biblioteca');
})->middleware(['auth', 'bibliotecario'])->name('biblioteca');

Route::get('/emprestimos', function () {
    return view('emprestimos');
})->middleware(['auth', 'bibliotecario'])->name('emprestimos');

Route::get('/meus-emprestimos', function () {
    return view('meus-emprestimos');
})->middleware(['auth'])->name('meus-emprestimos');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rotas AJAX - Organizadas por nível de permissão
Route::middleware(['auth'])->prefix('ajax')->group(function () {
    
    // ROTAS PARA TODOS AUTENTICADOS
    // Meus empréstimos (leitor pode ver seus próprios)
    Route::get('emprestimos/meus', [EmprestimoController::class, 'meus']);
    
    // ROTAS APENAS PARA ADMIN/BIBLIOTECARIO
    Route::middleware(['bibliotecario'])->group(function () {
        // LIVROS - Catálogo local
        Route::get('livros', function () {
            $livros = \App\Models\Livro::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $livros
            ]);
        });
        
        // USUÁRIOS - Listar leitores
        Route::get('usuarios', [EmprestimoController::class, 'listarUsuarios']);
        
        // EMPRÉSTIMOS (gestão)
        Route::prefix('emprestimos')->group(function () {
            Route::get('/', [EmprestimoController::class, 'index']);
            Route::post('/', [EmprestimoController::class, 'store']);
            Route::get('/{id}', [EmprestimoController::class, 'show']);
            Route::post('/{id}/devolver', [EmprestimoController::class, 'devolver']);
            Route::get('/lista/ativos', [EmprestimoController::class, 'ativos']);
            Route::get('/lista/atrasados', [EmprestimoController::class, 'atrasados']);
            Route::get('/usuario/{userId}', [EmprestimoController::class, 'porUsuario']);
        });
    });
});

require __DIR__.'/auth.php';
