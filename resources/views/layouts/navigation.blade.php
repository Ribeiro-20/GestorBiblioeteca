<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="bg-gradient-to-br from-purple-600 to-indigo-600 p-2 rounded-lg group-hover:scale-110 transition-transform">
                            <i class="fas fa-book-open text-white text-xl"></i>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">
                            Gestor de Biblioteca
                        </span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-2 sm:-my-px sm:ms-10 sm:flex items-center">
                    <a href="{{ route('dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="fas fa-home mr-2"></i>
                        Dashboard
                    </a>
                    
                    @if(Auth::user()->podeGerenciarEmprestimos())
                        <a href="{{ route('biblioteca') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('biblioteca') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-book mr-2"></i>
                            Biblioteca
                        </a>
                        
                        <a href="{{ route('emprestimos') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('emprestimos') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-tasks mr-2"></i>
                            Gerenciar Empréstimos
                        </a>
                    @else
                        <a href="{{ route('meus-emprestimos') }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium rounded-lg transition {{ request()->routeIs('meus-emprestimos') ? 'bg-purple-50 text-purple-700' : 'text-gray-700 hover:bg-gray-50' }}">
                            <i class="fas fa-book-reader mr-2"></i>
                            Meus Empréstimos
                        </a>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- User Badge -->
                <div class="mr-4 px-3 py-1 bg-gradient-to-r from-purple-100 to-indigo-100 rounded-full">
                    <span class="text-xs font-semibold text-purple-700">
                        @if(Auth::user()->tipo === 'admin')
                            <i class="fas fa-crown mr-1"></i> Admin
                        @elseif(Auth::user()->tipo === 'bibliotecario')
                            <i class="fas fa-user-tie mr-1"></i> Bibliotecário
                        @else
                            <i class="fas fa-user mr-1"></i> Leitor
                        @endif
                    </span>
                </div>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 transition">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center mr-2">
                                    <span class="text-white text-sm font-semibold">
                                        {{ substr(Auth::user()->nome, 0, 1) }}
                                    </span>
                                </div>
                                <span>{{ Auth::user()->nome }}</span>
                            </div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-medium text-gray-900">{{ Auth::user()->nome }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            <i class="fas fa-user-cog mr-2"></i>
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();"
                                    class="text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                {{ __('Sair') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-200">
        <div class="pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" 
               class="flex items-center px-4 py-3 {{ request()->routeIs('dashboard') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600' : 'text-gray-700' }}">
                <i class="fas fa-home mr-3 w-5"></i>
                Dashboard
            </a>
            
            @if(Auth::user()->podeGerenciarEmprestimos())
                <a href="{{ route('biblioteca') }}" 
                   class="flex items-center px-4 py-3 {{ request()->routeIs('biblioteca') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600' : 'text-gray-700' }}">
                    <i class="fas fa-book mr-3 w-5"></i>
                    Biblioteca
                </a>
                
                <a href="{{ route('emprestimos') }}" 
                   class="flex items-center px-4 py-3 {{ request()->routeIs('emprestimos') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600' : 'text-gray-700' }}">
                    <i class="fas fa-tasks mr-3 w-5"></i>
                    Gerenciar Empréstimos
                </a>
            @else
                <a href="{{ route('meus-emprestimos') }}" 
                   class="flex items-center px-4 py-3 {{ request()->routeIs('meus-emprestimos') ? 'bg-purple-50 text-purple-700 border-l-4 border-purple-600' : 'text-gray-700' }}">
                    <i class="fas fa-book-reader mr-3 w-5"></i>
                    Meus Empréstimos
                </a>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4 mb-3">
                <div class="flex items-center">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center mr-3">
                        <span class="text-white font-semibold">
                            {{ substr(Auth::user()->nome, 0, 1) }}
                        </span>
                    </div>
                    <div>
                        <div class="font-medium text-base text-gray-800">{{ Auth::user()->nome }}</div>
                        <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="space-y-1">
                <a href="{{ route('profile.edit') }}" 
                   class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-user-cog mr-3 w-5"></i>
                    Perfil
                </a>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50">
                        <i class="fas fa-sign-out-alt mr-3 w-5"></i>
                        Sair
                    </a>
                </form>
            </div>
        </div>
    </div>
</nav>
