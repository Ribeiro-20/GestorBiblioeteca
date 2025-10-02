<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
           $livros = \App\Models\Livro::all();
           return view('livros.index', compact('livros'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
           return view('livros.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
              $request->validate([
                     'titulo' => 'required',
                     'autor' => 'required',
                     'isbn' => 'required',
                     'categoria' => 'required',
                     'ano_publicacao' => 'required|integer',
                     'estado' => 'required',
              ]);

              try {
                     $livro = new \App\Models\Livro();
                     $livro->titulo = $request->titulo;
                     $livro->autor = $request->autor;
                     $livro->isbn = $request->isbn;
                     $livro->categoria = $request->categoria;
                     $livro->ano_publicacao = $request->ano_publicacao;
                     $livro->imagem = $request->imagem;
                     $livro->estado = $request->estado;
                     $livro->save();
                     return redirect()->route('livros.index')->with('success', 'Livro cadastrado com sucesso!');
              } catch (\Exception $e) {
                     return back()->withErrors(['erro' => 'Erro ao cadastrar livro: ' . $e->getMessage()]);
              }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          $livro = \App\Models\Livro::findOrFail($id);
          return view('livros.show', compact('livro'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
           $livro = \App\Models\Livro::findOrFail($id);
           return view('livros.edit', compact('livro'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
           $request->validate([
              'titulo' => 'required',
              'autor' => 'required',
              'isbn' => 'required',
              'categoria' => 'required',
              'ano_publicacao' => 'required|integer',
              'estado' => 'required',
           ]);

           $livro = \App\Models\Livro::findOrFail($id);
           $livro->titulo = $request->titulo;
           $livro->autor = $request->autor;
           $livro->isbn = $request->isbn;
           $livro->categoria = $request->categoria;
           $livro->ano_publicacao = $request->ano_publicacao;
           $livro->imagem = $request->imagem;
           $livro->estado = $request->estado;
           $livro->save();

           return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
          $livro = \App\Models\Livro::findOrFail($id);
          $livro->delete();
          return redirect()->route('livros.index')->with('success', 'Livro excluído com sucesso!');
    }
}
