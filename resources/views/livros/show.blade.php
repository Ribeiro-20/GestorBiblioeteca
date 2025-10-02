@extends('layouts.app')

@section('content')

<style>
    .show-header {
        background: linear-gradient(90deg, #6366f1 0%, #3b82f6 100%);
        color: #fff;
        padding: 2rem 1rem 1.5rem 1rem;
        border-radius: 1rem 1rem 0 0;
        box-shadow: 0 2px 8px rgba(60,60,120,0.08);
        margin-bottom: -1px;
    }
    .show-card {
        background: #f8fafc;
        border-radius: 0 0 1rem 1rem;
        padding: 2rem;
        box-shadow: 0 2px 8px rgba(60,60,120,0.04);
    }
    .show-label {
        font-weight: 600;
        color: #374151;
        min-width: 120px;
        display: inline-block;
    }
    .show-value {
        font-size: 1.08rem;
        color: #334155;
    }
    .show-img {
        max-width: 220px;
        border-radius: 0.5em;
        box-shadow: 0 2px 8px rgba(60,60,120,0.08);
        margin-top: 0.5em;
    }
    .show-badge {
        font-size: 1rem;
        padding: 0.5em 1em;
        border-radius: 2em;
        font-weight: 600;
    }
    .btn-secondary { background: #64748b; border: none; font-weight: 600; }
    .btn-secondary:hover { background: #334155; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-10">
            <div class="card shadow-lg border-0">
                <div class="show-header">
                    <h2 class="mb-1" style="font-weight:700;letter-spacing:0.02em;">Detalhes do Livro</h2>
                    <p class="mb-0" style="font-size:1.1rem;opacity:0.85;">Veja todas as informações do livro cadastrado.</p>
                </div>
                <div class="show-card">
                    <div class="mb-3"><span class="show-label">Título:</span> <span class="show-value">{{ $livro->titulo }}</span></div>
                    <div class="mb-3"><span class="show-label">Autor:</span> <span class="show-value">{{ $livro->autor }}</span></div>
                    <div class="mb-3"><span class="show-label">ISBN:</span> <span class="show-value">{{ $livro->isbn }}</span></div>
                    <div class="mb-3"><span class="show-label">Categoria:</span> <span class="show-value">{{ $livro->categoria }}</span></div>
                    <div class="mb-3"><span class="show-label">Ano de Publicação:</span> <span class="show-value">{{ $livro->ano_publicacao }}</span></div>
                    <div class="mb-3"><span class="show-label">Estado:</span>
                        <span class="show-badge {{ $livro->estado == 'disponivel' ? 'bg-success text-white' : 'bg-warning text-dark' }}">
                            {{ ucfirst($livro->estado) }}
                        </span>
                    </div>
                    @if($livro->imagem)
                        <div class="mb-3">
                            <span class="show-label">Imagem:</span><br>
                            <img src="{{ $livro->imagem }}" alt="Imagem do Livro" class="show-img">
                        </div>
                    @endif
                    <div class="d-flex justify-content-end">
                        <a href="{{ route('livros.index') }}" class="btn btn-secondary px-4">Voltar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
