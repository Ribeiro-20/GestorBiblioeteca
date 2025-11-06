<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-book text-purple-600 mr-3"></i>
            {{ __('Biblioteca') }}
        </h2>
        <p class="text-gray-600 mt-1">Pesquisar e importar livros do catálogo Open Library</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl shadow-md border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button onclick="showTab('buscar')" id="tab-buscar" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-purple-600 border-b-2 border-purple-600 whitespace-nowrap">
                        <i class="fas fa-search mr-2"></i>Buscar Livros
                    </button>
                    <button onclick="showTab('importar')" id="tab-importar" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-file-import mr-2"></i>Importar ISBN
                    </button>
                    <button onclick="showTab('catalogo')" id="tab-catalogo" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-book-open mr-2"></i>Meu Catálogo
                    </button>
                </div>
            </div>

            <!-- Tab Content Container -->
            <div class="bg-white rounded-b-2xl shadow-md">
                
                <!-- Tab: Buscar Livros -->
                <div id="content-buscar" class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-search text-purple-600 mr-3"></i>
                        Pesquisar na Open Library
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <!-- Busca por ISBN -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-barcode text-white text-xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Por ISBN</h4>
                            </div>
                            <input type="text" id="isbn-search" placeholder="9780132350884" 
                                   class="w-full border border-blue-300 rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <button onclick="buscarPorISBN()" 
                                    class="w-full bg-blue-600 text-white rounded-lg px-4 py-3 hover:bg-blue-700 font-semibold transition">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                        </div>

                        <!-- Busca por Título -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-heading text-white text-xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Por Título</h4>
                            </div>
                            <input type="text" id="titulo-search" placeholder="Harry Potter" 
                                   class="w-full border border-green-300 rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-green-500 focus:border-transparent">
                            <button onclick="buscarPorTitulo()" 
                                    class="w-full bg-green-600 text-white rounded-lg px-4 py-3 hover:bg-green-700 font-semibold transition">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                        </div>

                        <!-- Busca por Autor -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                            <div class="flex items-center mb-4">
                                <div class="w-12 h-12 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-user-edit text-white text-xl"></i>
                                </div>
                                <h4 class="font-bold text-gray-900">Por Autor</h4>
                            </div>
                            <input type="text" id="autor-search" placeholder="J.K. Rowling" 
                                   class="w-full border border-purple-300 rounded-lg px-4 py-3 mb-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <button onclick="buscarPorAutor()" 
                                    class="w-full bg-purple-600 text-white rounded-lg px-4 py-3 hover:bg-purple-700 font-semibold transition">
                                <i class="fas fa-search mr-2"></i>Buscar
                            </button>
                        </div>
                    </div>

                    <!-- Resultados da Busca -->
                    <div id="search-results"></div>
                </div>

                <!-- Tab: Importar por ISBN -->
                <div id="content-importar" class="p-8 hidden">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-file-import text-purple-600 mr-3"></i>
                        Importar Livro por ISBN
                    </h3>
                    
                    <div class="max-w-2xl mx-auto">
                        <div class="bg-gradient-to-br from-purple-50 to-indigo-50 rounded-xl p-8 border border-purple-200 mb-6">
                            <label class="block font-bold text-gray-900 mb-3 text-lg">
                                <i class="fas fa-barcode mr-2 text-purple-600"></i>ISBN do Livro:
                            </label>
                            <input type="text" id="isbn-import" placeholder="9780132350884" 
                                   class="w-full border-2 border-purple-300 rounded-lg px-4 py-3 text-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent mb-3">
                            <p class="text-sm text-gray-600 mb-4">
                                <i class="fas fa-info-circle mr-1"></i>Digite o ISBN sem hífens ou espaços
                            </p>

                            <button onclick="importarLivro()" 
                                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg px-6 py-4 hover:from-purple-700 hover:to-indigo-700 font-bold text-lg transition transform hover:scale-[1.02]">
                                <i class="fas fa-download mr-2"></i>Importar para o Catálogo
                            </button>
                        </div>

                        <!-- ISBNs de Teste -->
                        <div class="bg-white rounded-xl border border-gray-200 p-6">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-flask text-blue-600 mr-2"></i>ISBNs para Teste:
                            </h4>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <span class="text-sm font-medium text-gray-700">9780132350884 - Clean Code</span>
                                    <button onclick="document.getElementById('isbn-import').value='9780132350884'" 
                                            class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                                        <i class="fas fa-arrow-right mr-1"></i>Usar
                                    </button>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <span class="text-sm font-medium text-gray-700">9788533613379 - O Senhor dos Anéis</span>
                                    <button onclick="document.getElementById('isbn-import').value='9788533613379'" 
                                            class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                                        <i class="fas fa-arrow-right mr-1"></i>Usar
                                    </button>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                    <span class="text-sm font-medium text-gray-700">9780451524935 - 1984</span>
                                    <button onclick="document.getElementById('isbn-import').value='9780451524935'" 
                                            class="text-purple-600 hover:text-purple-700 font-semibold text-sm">
                                        <i class="fas fa-arrow-right mr-1"></i>Usar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Resultado da Importação -->
                    <div id="import-result" class="mt-6"></div>
                </div>

                <!-- Tab: Meu Catálogo -->
                <div id="content-catalogo" class="p-8 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-book-open text-purple-600 mr-3"></i>
                            Meu Catálogo de Livros
                        </h3>
                        <button onclick="carregarCatalogo()" 
                                class="bg-purple-600 text-white rounded-lg px-6 py-3 hover:bg-purple-700 font-semibold transition">
                            <i class="fas fa-sync-alt mr-2"></i>Atualizar
                        </button>
                    </div>
                    
                    <div id="catalogo-results"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const API_BASE = '/api/v1';

        // Controle de Tabs
        function showTab(tab) {
            // Esconder todos os conteúdos
            document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
            // Remover estilo ativo de todos os botões
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('text-purple-600', 'border-b-2', 'border-purple-600');
                el.classList.add('text-gray-600');
            });
            
            // Mostrar conteúdo selecionado
            document.getElementById(`content-${tab}`).classList.remove('hidden');
            // Ativar botão selecionado
            const btn = document.getElementById(`tab-${tab}`);
            btn.classList.add('text-purple-600', 'border-b-2', 'border-purple-600');
            btn.classList.remove('text-gray-600');

            // Se abrir catálogo, carregar automaticamente
            if (tab === 'catalogo') {
                carregarCatalogo();
            }
        }

        // Buscar por ISBN
        async function buscarPorISBN() {
            const isbn = document.getElementById('isbn-search').value.trim();
            if (!isbn) {
                alert('Digite um ISBN!');
                return;
            }

            showLoading('search-results');
            try {
                const url = `${API_BASE}/openlibrary/buscar-isbn?isbn=${isbn}`;
                console.log('GET', url);
                const response = await axios.get(url);
                console.log('Resposta buscar-isbn:', response);
                if (response.data && response.data.success) {
                    displaySingleBook(response.data.data);
                } else {
                    const msg = response.data?.message || 'Livro não encontrado';
                    showError('search-results', msg);
                }
            } catch (error) {
                console.error('Erro em buscarPorISBN:', error);
                const resp = error.response;
                const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                showError('search-results', 'Erro ao buscar livro: ' + msg);
            }
        }

        // Buscar por Título
        async function buscarPorTitulo() {
            const titulo = document.getElementById('titulo-search').value.trim();
            if (!titulo) {
                alert('Digite um título!');
                return;
            }

            showLoading('search-results');
            try {
                const url = `${API_BASE}/openlibrary/buscar-titulo?titulo=${encodeURIComponent(titulo)}&limit=10`;
                console.log('GET', url);
                const response = await axios.get(url);
                console.log('Resposta buscar-titulo:', response);
                if (response.data && response.data.success && response.data.data.length > 0) {
                    displayMultipleBooks(response.data.data);
                } else {
                    const msg = response.data?.message || 'Nenhum livro encontrado';
                    showError('search-results', msg);
                }
            } catch (error) {
                console.error('Erro em buscarPorTitulo:', error);
                const resp = error.response;
                const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                showError('search-results', 'Erro ao buscar livros: ' + msg);
            }
        }

        // Buscar por Autor
        async function buscarPorAutor() {
            const autor = document.getElementById('autor-search').value.trim();
            if (!autor) {
                alert('Digite um autor!');
                return;
            }

            showLoading('search-results');
            try {
                const url = `${API_BASE}/openlibrary/buscar-autor?autor=${encodeURIComponent(autor)}&limit=10`;
                console.log('GET', url);
                const response = await axios.get(url);
                console.log('Resposta buscar-autor:', response);
                if (response.data && response.data.success && response.data.data.length > 0) {
                    displayMultipleBooks(response.data.data);
                } else {
                    const msg = response.data?.message || 'Nenhum livro encontrado';
                    showError('search-results', msg);
                }
            } catch (error) {
                console.error('Erro em buscarPorAutor:', error);
                const resp = error.response;
                const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                showError('search-results', 'Erro ao buscar livros: ' + msg);
            }
        }

        // Importar Livro
        async function importarLivro() {
            const isbn = document.getElementById('isbn-import').value.trim();
            if (!isbn) {
                alert('Digite um ISBN!');
                return;
            }

            showLoading('import-result');
            try {
                const response = await axios.post(`${API_BASE}/openlibrary/importar-isbn`, { isbn });
                if (response.data.success) {
                    document.getElementById('import-result').innerHTML = `
                        <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-6 mt-6">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                                <strong class="text-green-800 text-lg">Sucesso!</strong>
                            </div>
                            <p class="text-green-700 mb-4">${response.data.message}</p>
                            <div class="bg-white rounded-lg p-4 border border-green-200">
                                ${bookCard(response.data.data)}
                            </div>
                        </div>
                    `;
                    document.getElementById('isbn-import').value = '';
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.response?.data?.errors?.isbn?.[0] || error.message;
                showError('import-result', 'Erro: ' + errorMsg);
            }
        }

        // Carregar Catálogo
        async function carregarCatalogo() {
            showLoading('catalogo-results');
            try {
                const response = await axios.get(`${API_BASE}/livros`);
                const livros = response.data.data || response.data;
                
                if (livros && livros.length > 0) {
                    document.getElementById('catalogo-results').innerHTML = `
                        <div class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <p class="text-purple-800 font-semibold">
                                <i class="fas fa-book mr-2"></i>Total de livros no catálogo: ${livros.length}
                            </p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            ${livros.map(livro => bookCard(livro)).join('')}
                        </div>
                    `;
                } else {
                    document.getElementById('catalogo-results').innerHTML = `
                        <div class="text-center py-16">
                            <i class="fas fa-book-open text-gray-300 text-6xl mb-4"></i>
                            <h4 class="text-xl font-semibold text-gray-600 mb-2">Catálogo vazio</h4>
                            <p class="text-gray-500">Nenhum livro no catálogo. Importe alguns livros primeiro!</p>
                        </div>
                    `;
                }
            } catch (error) {
                showError('catalogo-results', 'Erro ao carregar catálogo.');
            }
        }

        // Display Single Book
        function displaySingleBook(book) {
            document.getElementById('search-results').innerHTML = `
                <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8">
                    ${bookDetailCard(book)}
                </div>
            `;
        }

        // Display Multiple Books
        function displayMultipleBooks(books) {
            document.getElementById('search-results').innerHTML = `
                <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <p class="text-purple-800 font-semibold">
                        <i class="fas fa-search mr-2"></i>Resultados encontrados: ${books.length}
                    </p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    ${books.map(book => bookCard(book)).join('')}
                </div>
            `;
        }

        // Book Card (Resumido)
        function bookCard(book) {
            const imgUrl = book.imagem || 'https://via.placeholder.com/200x300?text=Sem+Capa';
            const estadoBadge = book.estado === 'disponivel' 
                ? '<span class="inline-block bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded-full"><i class="fas fa-check-circle mr-1"></i>Disponível</span>'
                : '<span class="inline-block bg-red-100 text-red-800 text-xs font-semibold px-2 py-1 rounded-full"><i class="fas fa-times-circle mr-1"></i>Emprestado</span>';
            
            // Se o livro já está no catálogo (tem ID), não mostra botão de adicionar
            // Se não tem ISBN, mostra mensagem que não pode ser adicionado
            let adicionarBtn = '';
            if (!book.id) {
                if (book.isbn) {
                    adicionarBtn = `<button onclick='adicionarAoCatalogo(${JSON.stringify(book).replace(/'/g, "&apos;")})' 
                               class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg px-4 py-2 hover:from-green-700 hover:to-emerald-700 font-semibold transition transform hover:scale-105 mt-3">
                            <i class="fas fa-plus-circle mr-2"></i>Adicionar ao Catálogo
                        </button>`;
                } else {
                    adicionarBtn = `<div class="mt-3 p-2 bg-gray-100 text-gray-600 text-xs rounded-lg text-center">
                            <i class="fas fa-info-circle mr-1"></i>Sem ISBN - Não pode ser adicionado
                        </div>`;
                }
            }
            
            return `
                <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden group border border-gray-200">
                    <div class="relative overflow-hidden">
                        <img src="${imgUrl}" alt="${book.titulo}" 
                             class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300" 
                             onerror="this.src='https://via.placeholder.com/200x300?text=Sem+Capa'">
                        ${book.estado ? `<div class="absolute top-3 right-3">${estadoBadge}</div>` : ''}
                    </div>
                    <div class="p-4">
                        <h4 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">${book.titulo}</h4>
                        <p class="text-gray-600 text-sm mb-2 flex items-center">
                            <i class="fas fa-user mr-2 text-purple-600"></i>${book.autor || 'Autor desconhecido'}
                        </p>
                        ${book.isbn ? `<p class="text-xs text-gray-500 mb-1"><i class="fas fa-barcode mr-1"></i>ISBN: ${book.isbn}</p>` : '<p class="text-xs text-gray-400 mb-1"><i class="fas fa-barcode mr-1"></i>ISBN: Não disponível</p>'}
                        ${book.ano_publicacao ? `<p class="text-xs text-gray-500 mb-2"><i class="fas fa-calendar mr-1"></i>${book.ano_publicacao}</p>` : ''}
                        ${book.categoria ? `<span class="inline-block bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-1 rounded-full mt-2">${book.categoria}</span>` : ''}
                        ${adicionarBtn}
                    </div>
                </div>
            `;
        }

        // Book Detail Card
        function bookDetailCard(book) {
            const imgUrl = book.imagem || 'https://via.placeholder.com/300x450?text=Sem+Capa';
            return `
                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="flex-shrink-0">
                        <img src="${imgUrl}" alt="${book.titulo}" 
                             class="w-full lg:w-80 h-auto object-cover rounded-xl shadow-lg" 
                             onerror="this.src='https://via.placeholder.com/300x450?text=Sem+Capa'">
                    </div>
                    <div class="flex-1">
                        <h3 class="text-4xl font-bold text-gray-900 mb-3">${book.titulo}</h3>
                        <p class="text-xl text-gray-700 mb-6 flex items-center">
                            <i class="fas fa-user text-purple-600 mr-2"></i>${book.autor || 'Autor desconhecido'}
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            ${book.isbn ? `
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-barcode text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">ISBN</p>
                                        <p class="font-semibold text-gray-900">${book.isbn}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${book.editora ? `
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-building text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Editora</p>
                                        <p class="font-semibold text-gray-900">${book.editora}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${book.ano_publicacao ? `
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-calendar text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Ano de Publicação</p>
                                        <p class="font-semibold text-gray-900">${book.ano_publicacao}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${book.categoria ? `
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-tag text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Categoria</p>
                                        <p class="font-semibold text-gray-900">${book.categoria}</p>
                                    </div>
                                </div>
                            ` : ''}
                            ${book.numero_paginas ? `
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <i class="fas fa-file-alt text-purple-600 mr-3"></i>
                                    <div>
                                        <p class="text-xs text-gray-500">Páginas</p>
                                        <p class="font-semibold text-gray-900">${book.numero_paginas}</p>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        
                        ${book.descricao ? `
                            <div class="bg-purple-50 rounded-xl p-6 mb-6 border border-purple-200">
                                <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-align-left text-purple-600 mr-2"></i>Descrição
                                </h4>
                                <p class="text-gray-700 leading-relaxed">${book.descricao}</p>
                            </div>
                        ` : ''}
                        
                        ${book.isbn ? `
                            <button onclick="importarLivroRapido('${book.isbn}')" 
                                    class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg px-8 py-4 hover:from-green-700 hover:to-emerald-700 font-bold text-lg transition transform hover:scale-105">
                                <i class="fas fa-download mr-2"></i>Adicionar ao Catálogo
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        // Importar Livro Rápido
        async function importarLivroRapido(isbn) {
            if (!confirm('Deseja adicionar este livro ao catálogo?')) return;
            
            try {
                const response = await axios.post(`${API_BASE}/openlibrary/importar-isbn`, { isbn });
                if (response.data.success) {
                    alert('✅ Livro adicionado com sucesso!');
                    // Se estiver na aba catálogo, recarregar
                    if (!document.getElementById('content-catalogo').classList.contains('hidden')) {
                        carregarCatalogo();
                    }
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.response?.data?.errors?.isbn?.[0] || error.message;
                alert('❌ Erro: ' + errorMsg);
            }
        }

        // Adicionar Livro ao Catálogo
        async function adicionarAoCatalogo(book) {
            if (!book.isbn) {
                alert('❌ Este livro não possui ISBN e não pode ser adicionado ao catálogo.');
                return;
            }

            if (!confirm(`Adicionar "${book.titulo}" ao catálogo?`)) {
                return;
            }

            try {
                const response = await axios.post(`${API_BASE}/openlibrary/importar-isbn`, { 
                    isbn: book.isbn 
                });
                
                if (response.data.success) {
                    alert('✅ ' + response.data.message);
                    // Recarregar a busca atual ou mostrar feedback
                    if (document.getElementById('content-catalogo').classList.contains('hidden') === false) {
                        carregarCatalogo();
                    }
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.response?.data?.errors?.isbn?.[0] || error.message;
                alert('❌ Erro ao adicionar livro: ' + errorMsg);
            }
        }

        // Show Loading
        function showLoading(elementId) {
            document.getElementById(elementId).innerHTML = `
                <div class="text-center py-16">
                    <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-purple-200 border-t-purple-600 mb-4"></div>
                    <p class="text-gray-600 font-semibold">Carregando...</p>
                </div>
            `;
        }

        // Show Error
        function showError(elementId, message) {
            document.getElementById(elementId).innerHTML = `
                <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-exclamation-circle text-red-500 text-2xl mr-3"></i>
                        <strong class="text-red-800 text-lg">Erro!</strong>
                    </div>
                    <p class="text-red-700">${message}</p>
                </div>
            `;
        }
    </script>
</x-app-layout>
                    </button>
                </div>
                
                <div id="catalogo-results"></div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/api/v1';

        // Controle de Tabs
        function showTab(tab) {
            // Esconder todos os conteúdos
            document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
            // Remover estilo ativo de todos os botões
            document.querySelectorAll('[id^="tab-"]').forEach(el => {
                el.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                el.classList.add('text-gray-600');
            });
            
            // Mostrar conteúdo selecionado
            document.getElementById(`content-${tab}`).classList.remove('hidden');
            // Ativar botão selecionado
            const btn = document.getElementById(`tab-${tab}`);
            btn.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
            btn.classList.remove('text-gray-600');

            // Se abrir catálogo, carregar automaticamente
            if (tab === 'catalogo') {
                carregarCatalogo();
            }
        }

        // Buscar por ISBN
        async function buscarPorISBN() {
            const isbn = document.getElementById('isbn-search').value.trim();
            if (!isbn) {
                alert('Digite um ISBN!');
                return;
            }

            showLoading('search-results');
            try {
                    const url = `${API_BASE}/openlibrary/buscar-isbn?isbn=${isbn}`;
                    console.log('GET', url);
                    const response = await axios.get(url);
                    console.log('Resposta buscar-isbn:', response);
                    if (response.data && response.data.success) {
                        displaySingleBook(response.data.data);
                    } else {
                        const msg = response.data?.message || 'Livro não encontrado';
                        showError('search-results', msg);
                    }
            } catch (error) {
                    console.error('Erro em buscarPorISBN:', error);
                    const resp = error.response;
                    const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                    showError('search-results', 'Erro ao buscar livro: ' + msg);
            }
        }

        // Buscar por Título
        async function buscarPorTitulo() {
            const titulo = document.getElementById('titulo-search').value.trim();
            if (!titulo) {
                alert('Digite um título!');
                return;
            }

            showLoading('search-results');
            try {
                const url = `${API_BASE}/openlibrary/buscar-titulo?titulo=${encodeURIComponent(titulo)}&limit=10`;
                console.log('GET', url);
                const response = await axios.get(url);
                console.log('Resposta buscar-titulo:', response);
                if (response.data && response.data.success && response.data.data.length > 0) {
                    displayMultipleBooks(response.data.data);
                } else {
                    const msg = response.data?.message || 'Nenhum livro encontrado';
                    showError('search-results', msg);
                }
            } catch (error) {
                console.error('Erro em buscarPorTitulo:', error);
                const resp = error.response;
                const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                showError('search-results', 'Erro ao buscar livros: ' + msg);
            }
        }

        // Buscar por Autor
        async function buscarPorAutor() {
            const autor = document.getElementById('autor-search').value.trim();
            if (!autor) {
                alert('Digite um autor!');
                return;
            }

            showLoading('search-results');
            try {
                const url = `${API_BASE}/openlibrary/buscar-autor?autor=${encodeURIComponent(autor)}&limit=10`;
                console.log('GET', url);
                const response = await axios.get(url);
                console.log('Resposta buscar-autor:', response);
                if (response.data && response.data.success && response.data.data.length > 0) {
                    displayMultipleBooks(response.data.data);
                } else {
                    const msg = response.data?.message || 'Nenhum livro encontrado';
                    showError('search-results', msg);
                }
            } catch (error) {
                console.error('Erro em buscarPorAutor:', error);
                const resp = error.response;
                const msg = resp?.data?.message || (resp?.data?.errors ? JSON.stringify(resp.data.errors) : error.message);
                showError('search-results', 'Erro ao buscar livros: ' + msg);
            }
        }

        // Importar Livro
        async function importarLivro() {
            const isbn = document.getElementById('isbn-import').value.trim();
            if (!isbn) {
                alert('Digite um ISBN!');
                return;
            }

            showLoading('import-result');
            try {
                const response = await axios.post(`${API_BASE}/openlibrary/importar-isbn`, { isbn });
                if (response.data.success) {
                    document.getElementById('import-result').innerHTML = `
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            <strong>✅ Sucesso!</strong> ${response.data.message}
                            <div class="mt-4 bg-white p-4 rounded">
                                ${bookCard(response.data.data)}
                            </div>
                        </div>
                    `;
                    document.getElementById('isbn-import').value = '';
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.response?.data?.errors?.isbn?.[0] || error.message;
                showError('import-result', 'Erro: ' + errorMsg);
            }
        }

        // Carregar Catálogo
        async function carregarCatalogo() {
            showLoading('catalogo-results');
            try {
                const response = await axios.get(`${API_BASE}/livros`);
                const livros = response.data.data || response.data;
                
                if (livros && livros.length > 0) {
                    document.getElementById('catalogo-results').innerHTML = `
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            ${livros.map(livro => bookCard(livro)).join('')}
                        </div>
                    `;
                } else {
                    showError('catalogo-results', 'Nenhum livro no catálogo. Importe alguns livros primeiro!');
                }
            } catch (error) {
                showError('catalogo-results', 'Erro ao carregar catálogo. Certifique-se de criar a rota /api/v1/livros');
            }
        }

        // Display Single Book
        function displaySingleBook(book) {
            document.getElementById('search-results').innerHTML = `
                <div class="bg-white rounded-lg shadow-lg p-6">
                    ${bookDetailCard(book)}
                </div>
            `;
        }

        // Display Multiple Books
        function displayMultipleBooks(books) {
            document.getElementById('search-results').innerHTML = `
                <h3 class="font-bold text-xl mb-4">Resultados encontrados: ${books.length}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    ${books.map(book => bookCard(book)).join('')}
                </div>
            `;
        }

        // Book Card (Resumido)
        function bookCard(book) {
            const imgUrl = book.imagem || 'https://via.placeholder.com/200x300?text=Sem+Capa';
            return `
                <div class="border rounded-lg p-4 hover:shadow-lg transition">
                    <img src="${imgUrl}" alt="${book.titulo}" class="w-full h-64 object-cover rounded mb-3" onerror="this.src='https://via.placeholder.com/200x300?text=Sem+Capa'">
                    <h4 class="font-bold text-lg mb-1">${book.titulo}</h4>
                    <p class="text-gray-600 mb-1">📝 ${book.autor || 'Autor desconhecido'}</p>
                    ${book.isbn ? `<p class="text-sm text-gray-500">ISBN: ${book.isbn}</p>` : ''}
                    ${book.ano_publicacao ? `<p class="text-sm text-gray-500">📅 ${book.ano_publicacao}</p>` : ''}
                    ${book.categoria ? `<span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mt-2">${book.categoria}</span>` : ''}
                    ${book.estado ? `<span class="inline-block ${book.estado === 'disponivel' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} text-xs px-2 py-1 rounded mt-2 ml-1">${book.estado}</span>` : ''}
                </div>
            `;
        }

        // Book Detail Card
        function bookDetailCard(book) {
            const imgUrl = book.imagem || 'https://via.placeholder.com/200x300?text=Sem+Capa';
            return `
                <div class="flex flex-col md:flex-row gap-6">
                    <img src="${imgUrl}" alt="${book.titulo}" class="w-full md:w-64 h-auto object-cover rounded" onerror="this.src='https://via.placeholder.com/200x300?text=Sem+Capa'">
                    <div class="flex-1">
                        <h3 class="text-3xl font-bold mb-2">${book.titulo}</h3>
                        <p class="text-xl text-gray-700 mb-4">👤 ${book.autor || 'Autor desconhecido'}</p>
                        
                        <div class="space-y-2 mb-4">
                            ${book.isbn ? `<p><strong>ISBN:</strong> ${book.isbn}</p>` : ''}
                            ${book.editora ? `<p><strong>Editora:</strong> ${book.editora}</p>` : ''}
                            ${book.ano_publicacao ? `<p><strong>Ano:</strong> ${book.ano_publicacao}</p>` : ''}
                            ${book.categoria ? `<p><strong>Categoria:</strong> ${book.categoria}</p>` : ''}
                            ${book.numero_paginas ? `<p><strong>Páginas:</strong> ${book.numero_paginas}</p>` : ''}
                        </div>
                        
                        ${book.descricao ? `<div class="mt-4"><strong>Descrição:</strong><p class="text-gray-700 mt-2">${book.descricao}</p></div>` : ''}
                        
                        ${book.isbn ? `
                            <button onclick="importarLivroRapido('${book.isbn}')" class="mt-4 bg-green-500 text-white rounded px-6 py-2 hover:bg-green-600">
                                💾 Adicionar ao Catálogo
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        // Importar Livro Rápido
        async function importarLivroRapido(isbn) {
            if (!confirm('Deseja adicionar este livro ao catálogo?')) return;
            
            try {
                const response = await axios.post(`${API_BASE}/openlibrary/importar-isbn`, { isbn });
                if (response.data.success) {
                    alert('✅ Livro adicionado com sucesso!');
                }
            } catch (error) {
                const errorMsg = error.response?.data?.message || error.response?.data?.errors?.isbn?.[0] || error.message;
                alert('❌ Erro: ' + errorMsg);
            }
        }

        // Show Loading
        function showLoading(elementId) {
            document.getElementById(elementId).innerHTML = `
                <div class="text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-500"></div>
                    <p class="mt-2 text-gray-600">Carregando...</p>
                </div>
            `;
        }

        // Show Error
        function showError(elementId, message) {
            document.getElementById(elementId).innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>❌ Erro!</strong> ${message}
                </div>
            `;
        }
    </script>
</body>
</html>
