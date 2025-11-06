<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-home text-purple-600 mr-3"></i>
            {{ __('Dashboard') }}
        </h2>
        <p class="text-gray-600 mt-1">
            @if(Auth::user()->podeGerenciarEmprestimos())
                Visão geral do sistema de biblioteca
            @else
                Seus empréstimos e atividades
            @endif
        </p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-2xl shadow-xl p-8 mb-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Olá, {{ Auth::user()->nome }}! 👋</h3>
                        <p class="text-purple-100">
                            @if(Auth::user()->tipo === 'admin')
                                Bem-vindo à administração do sistema
                            @elseif(Auth::user()->tipo === 'bibliotecario')
                                Bem-vindo ao painel de gestão bibliotecária
                            @else
                                Bem-vindo à sua biblioteca digital
                            @endif
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                            <i class="fas fa-user text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user()->podeGerenciarEmprestimos())
                {{-- DASHBOARD PARA ADMIN/BIBLIOTECÁRIO --}}
                
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Livros -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book text-blue-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Total</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\Livro::count() }}</h4>
                        <p class="text-sm text-gray-600">Livros no Catálogo</p>
                    </div>

                    <!-- Empréstimos Ativos -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-reader text-green-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Ativos</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Emprestimo::where('estado', 'ativo')->count() }}
                        </h4>
                        <p class="text-sm text-gray-600">Empréstimos Ativos</p>
                    </div>

                    <!-- Empréstimos Atrasados -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Atrasados</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">
                            {{ \App\Models\Emprestimo::where('estado', 'ativo')->where('data_limite', '<', now())->count() }}
                        </h4>
                        <p class="text-sm text-gray-600">Devoluções Atrasadas</p>
                    </div>

                    <!-- Utilizadores -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Utilizadores</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">{{ \App\Models\User::count() }}</h4>
                        <p class="text-sm text-gray-600">Total de Utilizadores</p>
                    </div>
                </div>

                <!-- Quick Actions ADMIN/BIBLIOTECÁRIO -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Ações Rápidas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('biblioteca') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition group">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-search text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Pesquisar Livros</h4>
                                <p class="text-xs text-gray-600">Explorar catálogo</p>
                            </div>
                        </a>

                        <a href="{{ route('emprestimos') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition group">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-tasks text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Gerir Empréstimos</h4>
                                <p class="text-xs text-gray-600">Administrar empréstimos</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition group">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-user-cog text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Meu Perfil</h4>
                                <p class="text-xs text-gray-600">Editar informações</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Info Section ADMIN/BIBLIOTECÁRIO -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Sistema Info -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                            Informações do Sistema
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Tipo de Conta:</span>
                                <span class="font-semibold text-gray-900 capitalize">{{ Auth::user()->tipo }}</span>
                            </li>
                            <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ Auth::user()->email }}</span>
                            </li>
                            <li class="flex items-center justify-between py-2">
                                <span class="text-gray-600">Membro desde:</span>
                                <span class="font-medium text-gray-900">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Help Section -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-md p-6 border border-purple-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                            Precisa de Ajuda?
                        </h3>
                        <p class="text-gray-700 mb-4">
                            Explore o sistema utilizando o menu de navegação acima. Comece por pesquisar livros na Biblioteca ou gerir os seus empréstimos.
                        </p>
                        <div class="flex space-x-3">
                            <a href="{{ route('biblioteca') }}" class="flex-1 bg-white text-purple-600 font-semibold py-2 px-4 rounded-lg text-center hover:bg-purple-50 transition">
                                <i class="fas fa-book mr-1"></i> Explorar Biblioteca
                            </a>
                        </div>
                    </div>
                </div>

            @else
                {{-- DASHBOARD PARA LEITOR --}}
                
                @php
                    $meusEmprestimos = \App\Models\Emprestimo::where('user_id', Auth::id())
                        ->where('estado', 'ativo')
                        ->with('livro')
                        ->get();
                    $meusAtrasados = $meusEmprestimos->filter(function($emp) {
                        return \Carbon\Carbon::parse($emp->data_limite)->isPast();
                    });
                    $totalEmprestimos = \App\Models\Emprestimo::where('user_id', Auth::id())->count();
                @endphp

                <!-- Stats Grid LEITOR -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Meus Empréstimos Ativos -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-book-reader text-green-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Ativos</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">{{ $meusEmprestimos->count() }}</h4>
                        <p class="text-sm text-gray-600">Livros Emprestados</p>
                    </div>

                    <!-- Meus Atrasados -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Atrasados</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">{{ $meusAtrasados->count() }}</h4>
                        <p class="text-sm text-gray-600">Devoluções Pendentes</p>
                    </div>

                    <!-- Total Histórico -->
                    <div class="bg-white rounded-xl shadow-md p-6 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-history text-purple-600 text-xl"></i>
                            </div>
                            <span class="text-xs font-semibold text-gray-500 uppercase">Histórico</span>
                        </div>
                        <h4 class="text-3xl font-bold text-gray-900 mb-1">{{ $totalEmprestimos }}</h4>
                        <p class="text-sm text-gray-600">Total de Empréstimos</p>
                    </div>
                </div>

                @if($meusAtrasados->count() > 0)
                    <!-- Alerta de Livros Atrasados -->
                    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-8">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                            <div>
                                <h4 class="text-red-800 font-bold text-lg">Atenção! Você tem livros atrasados</h4>
                                <p class="text-red-700">Por favor, devolva os livros o quanto antes para evitar penalidades.</p>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @foreach($meusAtrasados->take(3) as $emprestimo)
                                @php
                                    $diasAtraso = \Carbon\Carbon::parse($emprestimo->data_limite)->diffInDays(now());
                                @endphp
                                <div class="bg-white rounded-lg p-4 flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if($emprestimo->livro->imagem)
                                            <img src="{{ $emprestimo->livro->imagem }}" alt="{{ $emprestimo->livro->titulo }}" 
                                                 class="w-12 h-16 object-cover rounded mr-4">
                                        @endif
                                        <div>
                                            <h5 class="font-semibold text-gray-900">{{ $emprestimo->livro->titulo }}</h5>
                                            <p class="text-sm text-gray-600">{{ $emprestimo->livro->autor }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <span class="text-red-600 font-bold">{{ $diasAtraso }} dia(s) atrasado</span>
                                        <p class="text-xs text-gray-500">Limite: {{ \Carbon\Carbon::parse($emprestimo->data_limite)->format('d/m/Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('meus-emprestimos') }}" 
                           class="mt-4 inline-block bg-red-600 text-white font-semibold py-2 px-6 rounded-lg hover:bg-red-700 transition">
                            <i class="fas fa-undo mr-2"></i>Ver Todos e Devolver
                        </a>
                    </div>
                @endif

                <!-- Meus Livros Ativos -->
                @if($meusEmprestimos->count() > 0)
                    <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <i class="fas fa-book-open text-green-600 mr-2"></i>
                            Meus Livros Emprestados ({{ $meusEmprestimos->count() }})
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($meusEmprestimos->take(4) as $emprestimo)
                                @php
                                    $atrasado = \Carbon\Carbon::parse($emprestimo->data_limite)->isPast();
                                    $diasRestantes = $atrasado ? 0 : \Carbon\Carbon::now()->diffInDays($emprestimo->data_limite);
                                @endphp
                                <div class="bg-gray-50 rounded-lg overflow-hidden hover:shadow-lg transition border {{ $atrasado ? 'border-red-300' : 'border-gray-200' }}">
                                    @if($emprestimo->livro->imagem)
                                        <img src="{{ $emprestimo->livro->imagem }}" alt="{{ $emprestimo->livro->titulo }}" 
                                             class="w-full h-48 object-cover">
                                    @endif
                                    <div class="p-4">
                                        <h5 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $emprestimo->livro->titulo }}</h5>
                                        <p class="text-sm text-gray-600 mb-3">{{ $emprestimo->livro->autor }}</p>
                                        @if($atrasado)
                                            <span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>Atrasado
                                            </span>
                                        @else
                                            <span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full">
                                                <i class="fas fa-clock mr-1"></i>{{ $diasRestantes }} dia(s) restantes
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <a href="{{ route('meus-emprestimos') }}" 
                           class="mt-6 inline-block text-purple-600 font-semibold hover:text-purple-700">
                            Ver todos os meus empréstimos <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                @else
                    <!-- Sem Empréstimos -->
                    <div class="bg-white rounded-xl shadow-md p-12 mb-8 text-center">
                        <i class="fas fa-book-open text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">Nenhum livro emprestado</h3>
                        <p class="text-gray-600 mb-6">Você ainda não possui nenhum livro emprestado no momento.</p>
                        <a href="{{ route('meus-emprestimos') }}" 
                           class="inline-block bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-semibold py-3 px-8 rounded-lg hover:from-purple-700 hover:to-indigo-700 transition">
                            <i class="fas fa-history mr-2"></i>Ver Histórico
                        </a>
                    </div>
                @endif

                <!-- Quick Actions LEITOR -->
                <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                        Ações Rápidas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('meus-emprestimos') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition group">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-book-reader text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Meus Empréstimos</h4>
                                <p class="text-xs text-gray-600">Ver e devolver livros</p>
                            </div>
                        </a>

                        <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition group">
                            <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-user-cog text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Meu Perfil</h4>
                                <p class="text-xs text-gray-600">Editar informações</p>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Info Section LEITOR -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Minha Conta -->
                    <div class="bg-white rounded-xl shadow-md p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-user-circle text-purple-600 mr-2"></i>
                            Minha Conta
                        </h3>
                        <ul class="space-y-3">
                            <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Nome:</span>
                                <span class="font-semibold text-gray-900">{{ Auth::user()->nome }}</span>
                            </li>
                            <li class="flex items-center justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-medium text-gray-900 text-sm">{{ Auth::user()->email }}</span>
                            </li>
                            <li class="flex items-center justify-between py-2">
                                <span class="text-gray-600">Membro desde:</span>
                                <span class="font-medium text-gray-900">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Dicas -->
                    <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-md p-6 border border-purple-100">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-lightbulb text-purple-600 mr-2"></i>
                            Dicas
                        </h3>
                        <ul class="space-y-3 text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                                <span>Devolva os livros dentro do prazo para evitar penalidades</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                                <span>Consulte regularmente seus empréstimos ativos</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-600 mr-2 mt-1"></i>
                                <span>Entre em contato com a biblioteca se precisar de ajuda</span>
                            </li>
                        </ul>
                    </div>
                </div>

            @endif
            <div class="bg-white rounded-xl shadow-md p-6 mb-8">
                <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-bolt text-yellow-500 mr-2"></i>
                    Ações Rápidas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-{{ Auth::user()->podeGerenciarEmprestimos() ? '3' : '2' }} gap-4">
                    @if(Auth::user()->podeGerenciarEmprestimos())
                        <a href="{{ route('biblioteca') }}" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg hover:from-blue-100 hover:to-blue-200 transition group">
                            <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-search text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Pesquisar Livros</h4>
                                <p class="text-xs text-gray-600">Explorar catálogo</p>
                            </div>
                        </a>
                    @endif

                    @if(Auth::user()->podeGerenciarEmprestimos())
                        <a href="{{ route('emprestimos') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition group">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-tasks text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Gerir Empréstimos</h4>
                                <p class="text-xs text-gray-600">Administrar empréstimos</p>
                            </div>
                        </a>
                    @else
                        <a href="{{ route('meus-emprestimos') }}" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-lg hover:from-green-100 hover:to-green-200 transition group">
                            <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                                <i class="fas fa-book-reader text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Meus Empréstimos</h4>
                                <p class="text-xs text-gray-600">Ver meus livros</p>
                            </div>
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-lg hover:from-purple-100 hover:to-purple-200 transition group">
                        <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-4 group-hover:scale-110 transition">
                            <i class="fas fa-user-cog text-white text-xl"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Meu Perfil</h4>
                            <p class="text-xs text-gray-600">Editar informações</p>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Info Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Sistema Info -->
                <div class="bg-white rounded-xl shadow-md p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informações do Sistema
                    </h3>
                    <ul class="space-y-3">
                        <li class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Tipo de Conta:</span>
                            <span class="font-semibold text-gray-900 capitalize">{{ Auth::user()->tipo }}</span>
                        </li>
                        <li class="flex items-center justify-between py-2 border-b border-gray-100">
                            <span class="text-gray-600">Email:</span>
                            <span class="font-medium text-gray-900 text-sm">{{ Auth::user()->email }}</span>
                        </li>
                        <li class="flex items-center justify-between py-2">
                            <span class="text-gray-600">Membro desde:</span>
                            <span class="font-medium text-gray-900">{{ Auth::user()->created_at->format('d/m/Y') }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Help Section -->
                <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl shadow-md p-6 border border-purple-100">
                    <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>
                        Precisa de Ajuda?
                    </h3>
                    <p class="text-gray-700 mb-4">
                        @if(Auth::user()->podeGerenciarEmprestimos())
                            Explore o sistema utilizando o menu de navegação acima. Comece por pesquisar livros na Biblioteca ou gerir os seus empréstimos.
                        @else
                            Use o menu de navegação para consultar seus empréstimos ativos e o histórico de devoluções.
                        @endif
                    </p>
                    <div class="flex space-x-3">
                        @if(Auth::user()->podeGerenciarEmprestimos())
                            <a href="{{ route('biblioteca') }}" class="flex-1 bg-white text-purple-600 font-semibold py-2 px-4 rounded-lg text-center hover:bg-purple-50 transition">
                                <i class="fas fa-book mr-1"></i> Explorar Biblioteca
                            </a>
                        @else
                            <a href="{{ route('meus-emprestimos') }}" class="flex-1 bg-white text-purple-600 font-semibold py-2 px-4 rounded-lg text-center hover:bg-purple-50 transition">
                                <i class="fas fa-book-reader mr-1"></i> Ver Meus Empréstimos
                            </a>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
