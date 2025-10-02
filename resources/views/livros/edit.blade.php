@extends('layouts.app')

@section('content')

<style>
    .edit-header {
        background: linear-gradient(90deg, #6366f1 0%, #3b82f6 100%);
        color: #fff;
        padding: 2rem 1rem 1.5rem 1rem;
        border-radius: 1rem 1rem 0 0;
        box-shadow: 0 2px 8px rgba(60,60,120,0.08);
        margin-bottom: -1px;
    }
    .edit-form {
        background: #f8fafc;
        border-radius: 0 0 1rem 1rem;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(60,60,120,0.04);
    }
    .edit-form label {
        font-weight: 600;
        color: #374151;
    }
    .edit-form input, .edit-form select {
        font-size: 1.08rem;
        padding: 0.6em 1em;
        border-radius: 0.5em;
        border: 1px solid #cbd5e1;
        background: #fff;
    }
    .edit-form .btn-success { background: #22c55e; border: none; font-weight: 600; }
    .edit-form .btn-success:hover { background: #16a34a; }
    .edit-form .btn-secondary { background: #64748b; border: none; font-weight: 600; }
    .edit-form .btn-secondary:hover { background: #334155; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="edit-header">
                    <h2 class="mb-1" style="font-weight:700;letter-spacing:0.02em;">Editar Livro</h2>
                    <p class="mb-0" style="font-size:1.1rem;opacity:0.85;">Atualize as informações do livro abaixo.</p>
                </div>
                <div class="edit-form">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif
                    <form action="{{ route('livros.update', $livro->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" value="{{ $livro->titulo }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="autor" class="form-label">Autor</label>
                            <input type="text" class="form-control" id="autor" name="autor" value="{{ $livro->autor }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="isbn" class="form-label">ISBN</label>
                            <input type="text" class="form-control" id="isbn" name="isbn" value="{{ $livro->isbn }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoria</label>
                            <input type="text" class="form-control" id="categoria" name="categoria" value="{{ $livro->categoria }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
                            <input type="number" class="form-control" id="ano_publicacao" name="ano_publicacao" value="{{ $livro->ano_publicacao }}" required>
                        </div>
                        <div class="mb-3">
                            <label for="imagem" class="form-label">Imagem (URL)</label>
                            <input type="text" class="form-control" id="imagem" name="imagem" value="{{ $livro->imagem }}">
                        </div>
                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado" required>
                                <option value="disponivel" {{ $livro->estado == 'disponivel' ? 'selected' : '' }}>Disponível</option>
                                <option value="emprestado" {{ $livro->estado == 'emprestado' ? 'selected' : '' }}>Emprestado</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-end gap-2">
                            <button type="submit" class="btn btn-success px-4">Salvar</button>
                            <a href="{{ route('livros.index') }}" class="btn btn-secondary px-4">Voltar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
