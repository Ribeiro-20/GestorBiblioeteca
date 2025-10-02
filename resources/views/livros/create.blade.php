@extends('layouts.app')

@section('content')
<div class="container">
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif
    <h1>Adicionar Livro</h1>
    <form action="{{ route('livros.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" required>
        </div>
        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" class="form-control" id="autor" name="autor" required>
        </div>
        <div class="mb-3">
            <label for="isbn" class="form-label">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" required>
        </div>
        <div class="mb-3">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" required>
        </div>
        <div class="mb-3">
            <label for="ano_publicacao" class="form-label">Ano de Publicação</label>
            <input type="number" class="form-control" id="ano_publicacao" name="ano_publicacao" required>
        </div>
        <div class="mb-3">
            <label for="imagem" class="form-label">Imagem (URL)</label>
            <input type="text" class="form-control" id="imagem" name="imagem">
        </div>
        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select class="form-control" id="estado" name="estado" required>
                <option value="disponivel">Disponível</option>
                <option value="emprestado">Emprestado</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Salvar</button>
        <a href="{{ route('livros.index') }}" class="btn btn-secondary">Voltar</a>
    </form>
</div>
@endsection
