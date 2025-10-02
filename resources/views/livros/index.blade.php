@extends('layouts.app')

@section('content')


<style>
    .livros-header {
        background: linear-gradient(90deg, #3b82f6 0%, #6366f1 100%);
        color: #fff;
        padding: 2rem 1rem 1.5rem 1rem;
        border-radius: 1rem 1rem 0 0;
        box-shadow: 0 2px 8px rgba(60,60,120,0.08);
        margin-bottom: -1px;
    }
    .livros-table th, .livros-table td {
        vertical-align: middle !important;
        font-size: 1.08rem;
    }
    .livros-table th {
        background: #1e293b;
        color: #fff;
        font-weight: 600;
        letter-spacing: 0.03em;
    }
    .livros-table tbody tr {
        transition: background 0.2s;
    }
    .livros-table tbody tr:hover {
        background: #f1f5f9;
    }
    .livros-table .badge {
        font-size: 1rem;
        padding: 0.5em 1em;
    }
    .livros-table .btn {
        font-size: 0.98rem;
        font-weight: 500;
        border-radius: 0.4em;
    }
    .livros-table .btn-info { background: #0ea5e9; border: none; color: #fff; }
    .livros-table .btn-warning { background: #f59e42; border: none; color: #fff; }
    .livros-table .btn-danger { background: #ef4444; border: none; color: #fff; }
    .livros-table .btn-info:hover { background: #0284c7; }
    .livros-table .btn-warning:hover { background: #d97706; }
    .livros-table .btn-danger:hover { background: #b91c1c; }
    .livros-table .btn-secondary { background: #64748b; border: none; color: #fff; }
    .livros-table .btn-secondary:hover { background: #334155; }
    .livros-table .btn-light { background: #e0e7ef; color: #1e293b; }
    .livros-table .btn-light:hover { background: #cbd5e1; }
    .livros-table .fw-bold { font-weight: 700; }
    .livros-table .text-muted { color: #64748b !important; }
    .livros-table .text-center { text-align: center; }
    .livros-table .px-3 { padding-left: 1rem !important; padding-right: 1rem !important; }
    .livros-table .mx-1 { margin-left: 0.25rem !important; margin-right: 0.25rem !important; }
    .livros-table .px-4 { padding-left: 1.5rem !important; padding-right: 1.5rem !important; }
    .livros-table .py-2 { padding-top: 0.5rem !important; padding-bottom: 0.5rem !important; }
    .livros-table .py-5 { padding-top: 3rem !important; padding-bottom: 3rem !important; }
    .livros-table .rounded-pill { border-radius: 2em !important; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-11 col-md-12">
            <div class="card shadow-lg border-0">
                <div class="livros-header d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1" style="font-weight:700;letter-spacing:0.02em;">Livros Cadastrados</h2>
                        <p class="mb-0" style="font-size:1.1rem;opacity:0.85;">Veja, edite, exclua ou adicione novos livros à biblioteca.</p>
                    </div>
                    <a href="{{ route('livros.create') }}" class="btn btn-light fw-bold px-4 py-2">+ Adicionar Livro</a>
                </div>
                <div class="card-body bg-light" style="border-radius:0 0 1rem 1rem;">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table table-bordered livros-table align-middle" style="background:#fff;">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Autor</th>
                                    <th>ISBN</th>
                                    <th>Categoria</th>
                                    <th>Ano Publicação</th>
                                    <th>Estado</th>
                                    <th class="text-center">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($livros as $livro)
                                <tr>
                                    <td class="fw-bold">{{ $livro->id }}</td>
                                    <td>{{ $livro->titulo }}</td>
                                    <td>{{ $livro->autor }}</td>
                                    <td>{{ $livro->isbn }}</td>
                                    <td>{{ $livro->categoria }}</td>
                                    <td>{{ $livro->ano_publicacao }}</td>
                                    <td>
                                        <span class="badge rounded-pill {{ $livro->estado == 'disponivel' ? 'bg-success' : 'bg-warning text-dark' }} px-3 py-2" style="font-size:1rem;">
                                            {{ ucfirst($livro->estado) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('livros.show', $livro->id) }}" class="btn btn-info btn-sm mx-1 px-3">Ver</a>
                                        <a href="{{ route('livros.edit', $livro->id) }}" class="btn btn-warning btn-sm mx-1 px-3">Editar</a>
                                        <form action="{{ route('livros.destroy', $livro->id) }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm mx-1 px-3" onclick="return confirm('Tem certeza que deseja excluir este livro?')">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Nenhum livro cadastrado.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
