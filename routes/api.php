<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OpenLibraryController;
use App\Http\Controllers\Api\EmprestimoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rotas da Open Library API
Route::prefix('v1')->middleware(['web', 'auth'])->group(function () {
    
    // LIVROS - Catálogo local
    Route::get('livros', function () {
        $livros = \App\Models\Livro::orderBy('created_at', 'desc')->get();
        return response()->json([
            'success' => true,
            'data' => $livros
        ]);
    });
    
    // USUÁRIOS - Listar para empréstimos
    Route::get('usuarios', [EmprestimoController::class, 'listarUsuarios']);
    
    // EMPRÉSTIMOS - Sistema de empréstimos
    Route::prefix('emprestimos')->group(function () {
        Route::get('/', [EmprestimoController::class, 'index']);
        Route::post('/', [EmprestimoController::class, 'store']);
        Route::get('/meus', [EmprestimoController::class, 'meus']); // Empréstimos do usuário autenticado
        Route::get('/{id}', [EmprestimoController::class, 'show']);
        Route::post('/{id}/devolver', [EmprestimoController::class, 'devolver']);
        Route::get('/lista/ativos', [EmprestimoController::class, 'ativos']);
        Route::get('/lista/atrasados', [EmprestimoController::class, 'atrasados']);
        Route::get('/usuario/{userId}', [EmprestimoController::class, 'porUsuario']);
    });
    
    // OPEN LIBRARY - Busca externa
    Route::prefix('openlibrary')->group(function () {
        Route::get('buscar-isbn', [OpenLibraryController::class, 'buscarPorISBN']);
        Route::get('buscar-titulo', [OpenLibraryController::class, 'buscarPorTitulo']);
        Route::get('buscar-autor', [OpenLibraryController::class, 'buscarPorAutor']);
        Route::get('buscar', [OpenLibraryController::class, 'buscar']);
        Route::post('importar-isbn', [OpenLibraryController::class, 'importarPorISBN']);
        Route::get('capa', [OpenLibraryController::class, 'obterCapa']);
    });

    // Rota de diagnóstico rápida
    Route::get('ping', function () {
        return response()->json(['success' => true, 'message' => 'pong', 'time' => now()]);
    });
    
});
