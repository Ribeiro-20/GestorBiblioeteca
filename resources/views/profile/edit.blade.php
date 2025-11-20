<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Profile summary card -->
            <div class="p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-600 to-indigo-600 flex items-center justify-center text-white text-2xl font-semibold">
                        {{ strtoupper(substr($user->nome, 0, 1)) }}
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->nome }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</p>

                        <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm text-gray-700 dark:text-gray-300">
                            <div>
                                <div class="text-xs text-gray-500">Tipo</div>
                                <div class="font-medium">{{ ucfirst($user->tipo ?? 'leitor') }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Nº Cartão</div>
                                <div class="font-medium">{{ $user->numero_cartao ?? '-' }}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Inscrito em</div>
                                <div class="font-medium">{{ $user->data_inscricao ? \Carbon\Carbon::parse($user->data_inscricao)->format('d/m/Y') : '-' }}</div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <a href="#update-profile-information" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg hover:opacity-90">
                                <i class="fas fa-user-edit mr-2"></i> Editar perfil
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="update-profile-information" class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @includeWhen(View::exists('profile.partials.update-profile-information-form'), 'profile.partials.update-profile-information-form')
                    @unless(View::exists('profile.partials.update-profile-information-form'))
                        <!-- Fallback form if partial missing: basic profile update -->
                        <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="nome" :value="__('Nome')" />
                                <x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $user->nome)" required />
                                <x-input-error :messages="$errors->get('nome')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg">
                                    Salvar
                                </button>
                                <a href="{{ route('dashboard') }}" class="text-sm text-gray-600">Cancelar</a>
                            </div>
                        </form>
                    @endunless
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
