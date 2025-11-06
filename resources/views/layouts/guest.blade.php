<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Gestor de Biblioteca') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .biblioteca-gradient {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left Side - Branding -->
            <div class="hidden lg:flex lg:w-1/2 biblioteca-gradient items-center justify-center p-12 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-20 left-20 w-64 h-64 bg-white rounded-full blur-3xl"></div>
                    <div class="absolute bottom-20 right-20 w-96 h-96 bg-white rounded-full blur-3xl"></div>
                </div>
                
                <div class="relative z-10 text-center text-white">
                    <div class="mb-8">
                        <i class="fas fa-book-open text-8xl mb-6 animate-pulse"></i>
                    </div>
                    <h1 class="text-5xl font-bold mb-4">Gestor de Biblioteca</h1>
                    <p class="text-xl opacity-90 mb-8">Sistema completo de gestão bibliotecária</p>
                    <div class="flex items-center justify-center space-x-8 text-sm">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Empréstimos</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Catálogo Online</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Gestão Completa</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side - Form -->
            <div class="flex-1 flex items-center justify-center p-8">
                <div class="w-full max-w-md">
                    <!-- Mobile Logo -->
                    <div class="lg:hidden text-center mb-8">
                        <i class="fas fa-book-open text-6xl text-purple-600 mb-4"></i>
                        <h1 class="text-3xl font-bold text-gray-900">Gestor de Biblioteca</h1>
                    </div>

                    <!-- Form Card -->
                    <div class="bg-white rounded-2xl shadow-xl p-8">
                        {{ $slot }}
                    </div>

                    <!-- Footer -->
                    <p class="text-center text-sm text-gray-500 mt-8">
                        &copy; {{ date('Y') }} Gestor de Biblioteca. Todos os direitos reservados.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
