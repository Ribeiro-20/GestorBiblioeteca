<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-tasks text-purple-600 mr-3"></i>
            {{ __('Gestão de Empréstimos') }}
        </h2>
        <p class="text-gray-600 mt-1">Gerenciar empréstimos, devoluções e controle de prazos</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl shadow-md border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button onclick="showTab('novo')" id="tab-novo" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-purple-600 border-b-2 border-purple-600 whitespace-nowrap">
                        <i class="fas fa-plus-circle mr-2"></i>Novo Empréstimo
                    </button>
                    <button onclick="showTab('ativos')" id="tab-ativos" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-book-reader mr-2"></i>Ativos
                    </button>
                    <button onclick="showTab('atrasados')" id="tab-atrasados" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Atrasados
                    </button>
                    <button onclick="showTab('historico')" id="tab-historico" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-history mr-2"></i>Histórico
                    </button>
                </div>
            </div>

            <!-- Tab Content Container -->
            <div class="bg-white rounded-b-2xl shadow-md">
                
                <!-- Tab: Novo Empréstimo -->
                <div id="content-novo" class="p-8">
                    <h3 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-plus-circle text-purple-600 mr-3"></i>
                        Registrar Novo Empréstimo
                    </h3>
                    
                    <form id="form-emprestimo" class="max-w-3xl mx-auto">
                        <div class="space-y-6">
                            <!-- Seleção de Livro -->
                            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                                <label class="block font-bold text-gray-900 mb-3 flex items-center text-lg">
                                    <i class="fas fa-book text-blue-600 mr-2"></i>Livro:
                                </label>
                                <select id="livro-select" 
                                        class="w-full border-2 border-blue-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-lg" 
                                        required>
                                    <option value="">Carregando livros...</option>
                                </select>
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>Apenas livros disponíveis são mostrados
                                </p>
                            </div>

                            <!-- Seleção de Usuário -->
                            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                                <label class="block font-bold text-gray-900 mb-3 flex items-center text-lg">
                                    <i class="fas fa-user text-green-600 mr-2"></i>Leitor:
                                </label>
                                <select id="usuario-select" 
                                        class="w-full border-2 border-green-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-transparent text-lg" 
                                        required>
                                    <option value="">Carregando leitores...</option>
                                </select>
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>Selecione o leitor que vai levar o livro
                                </p>
                            </div>

                            <!-- Prazo -->
                            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                                <label class="block font-bold text-gray-900 mb-3 flex items-center text-lg">
                                    <i class="fas fa-calendar-alt text-purple-600 mr-2"></i>Prazo de Devolução:
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="number" 
                                           id="dias-emprestimo" 
                                           value="14" 
                                           min="1" 
                                           max="30" 
                                           class="flex-1 border-2 border-purple-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-transparent text-lg">
                                    <span class="text-gray-700 font-semibold">dias</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-2">
                                    <i class="fas fa-info-circle mr-1"></i>Prazo padrão: 14 dias (ajuste conforme necessário)
                                </p>
                            </div>

                            <!-- Botão Submit -->
                            <button type="submit" 
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl px-6 py-4 hover:from-green-700 hover:to-emerald-700 font-bold text-lg transition transform hover:scale-[1.02] shadow-lg">
                                <i class="fas fa-check-circle mr-2"></i>Realizar Empréstimo
                            </button>
                        </div>
                    </form>

                    <!-- Resultado -->
                    <div id="resultado-emprestimo" class="mt-6"></div>
                </div>

                <!-- Tab: Empréstimos Ativos -->
                <div id="content-ativos" class="p-8 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-book-reader text-green-600 mr-3"></i>
                            Empréstimos Ativos
                        </h3>
                        <button onclick="carregarAtivos()" 
                                class="bg-green-600 text-white rounded-lg px-6 py-3 hover:bg-green-700 font-semibold transition">
                            <i class="fas fa-sync-alt mr-2"></i>Atualizar
                        </button>
                    </div>
                    
                    <div id="lista-ativos"></div>
                </div>

                <!-- Tab: Empréstimos Atrasados -->
                <div id="content-atrasados" class="p-8 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-red-600 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-3"></i>
                            Empréstimos Atrasados
                        </h3>
                        <button onclick="carregarAtrasados()" 
                                class="bg-red-600 text-white rounded-lg px-6 py-3 hover:bg-red-700 font-semibold transition">
                            <i class="fas fa-sync-alt mr-2"></i>Atualizar
                        </button>
                    </div>
                    
                    <div id="lista-atrasados"></div>
                </div>

                <!-- Tab: Histórico -->
                <div id="content-historico" class="p-8 hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-history text-purple-600 mr-3"></i>
                            Histórico Completo
                        </h3>
                        <button onclick="carregarHistorico()" 
                                class="bg-purple-600 text-white rounded-lg px-6 py-3 hover:bg-purple-700 font-semibold transition">
                            <i class="fas fa-sync-alt mr-2"></i>Atualizar
                        </button>
                    </div>
                    
                    <div id="lista-historico"></div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const API_BASE = '/ajax';
        
        // Configurar Axios para enviar credenciais (sessão + CSRF)
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrf) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf;
        }

        document.addEventListener('DOMContentLoaded', function() {
            console.log('🔧 Iniciando carregamento de dados...');
            carregarLivros();
            carregarUsuarios();
            document.getElementById('form-emprestimo').addEventListener('submit', realizarEmprestimo);
        });

        // Controle de Tabs
        function showTab(tab) {
            document.querySelectorAll('[id^="content-"]').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-button').forEach(el => {
                el.classList.remove('text-purple-600', 'border-b-2', 'border-purple-600');
                el.classList.add('text-gray-600');
            });
            
            document.getElementById(`content-${tab}`).classList.remove('hidden');
            const btn = document.getElementById(`tab-${tab}`);
            btn.classList.add('text-purple-600', 'border-b-2', 'border-purple-600');
            btn.classList.remove('text-gray-600');

            if (tab === 'ativos') carregarAtivos();
            if (tab === 'atrasados') carregarAtrasados();
            if (tab === 'historico') carregarHistorico();
        }

        // Carregar Livros Disponíveis
        async function carregarLivros() {
            const select = document.getElementById('livro-select');
            try {
                console.log('📚 Carregando livros de:', `${API_BASE}/livros`);
                const response = await axios.get(`${API_BASE}/livros`);
                console.log('✅ Resposta livros:', response.data);
                const livros = response.data.data;
                
                if (!livros || livros.length === 0) {
                    select.innerHTML = '<option value="">Nenhum livro cadastrado</option>';
                    console.warn('⚠️ Nenhum livro encontrado');
                    return;
                }
                
                const disponiveis = livros.filter(l => l.estado === 'disponivel');
                console.log(`📗 Livros disponíveis: ${disponiveis.length} de ${livros.length}`);
                
                if (disponiveis.length === 0) {
                    select.innerHTML = '<option value="">Nenhum livro disponível no momento</option>';
                    return;
                }
                
                select.innerHTML = '<option value="">Selecione um livro...</option>' + 
                    disponiveis.map(livro => 
                        `<option value="${livro.id}">${livro.titulo} - ${livro.autor || 'Autor desconhecido'}</option>`
                    ).join('');
                console.log('✅ Livros carregados com sucesso!');
            } catch (error) {
                console.error('❌ Erro ao carregar livros:', error);
                console.error('Detalhes:', error.response);
                select.innerHTML = '<option value="">❌ Erro ao carregar livros</option>';
                
                // Mostrar alerta visual
                if (error.response?.status === 403) {
                    alert('⚠️ ERRO: Você não tem permissão para acessar esta funcionalidade. Entre como Admin ou Bibliotecário.');
                } else {
                    alert('❌ Erro ao carregar livros: ' + (error.response?.data?.message || error.message));
                }
            }
        }

        // Carregar Usuários (Leitores)
        async function carregarUsuarios() {
            const select = document.getElementById('usuario-select');
            try {
                console.log('👥 Carregando usuários de:', `${API_BASE}/usuarios`);
                const response = await axios.get(`${API_BASE}/usuarios`);
                console.log('✅ Resposta usuários:', response.data);
                const usuarios = response.data.data;
                
                if (!usuarios || usuarios.length === 0) {
                    select.innerHTML = '<option value="">Nenhum leitor cadastrado</option>';
                    console.warn('⚠️ Nenhum leitor encontrado');
                    return;
                }
                
                select.innerHTML = '<option value="">Selecione um leitor...</option>' + 
                    usuarios.map(u => 
                        `<option value="${u.id}">${u.nome} ${u.numero_cartao ? '('+u.numero_cartao+')' : '('+u.email+')'}</option>`
                    ).join('');
                console.log(`✅ ${usuarios.length} leitores carregados com sucesso!`);
            } catch (error) {
                console.error('❌ Erro ao carregar usuários:', error);
                console.error('Detalhes:', error.response);
                select.innerHTML = '<option value="">❌ Erro ao carregar leitores</option>';
                
                // Mostrar alerta visual
                if (error.response?.status === 403) {
                    alert('⚠️ ERRO: Você não tem permissão para acessar esta funcionalidade. Entre como Admin ou Bibliotecário.');
                } else {
                    alert('❌ Erro ao carregar leitores: ' + (error.response?.data?.message || error.message));
                }
            }
        }

        // Realizar Empréstimo
        async function realizarEmprestimo(e) {
            e.preventDefault();
            
            const livroId = document.getElementById('livro-select').value;
            const userId = document.getElementById('usuario-select').value;
            const dias = document.getElementById('dias-emprestimo').value;
            
            if (!livroId || !userId) {
                showMessage('resultado-emprestimo', 'Por favor, selecione um livro e um leitor!', 'error');
                return;
            }
            
            showLoading('resultado-emprestimo');
            
            try {
                const response = await axios.post(`${API_BASE}/emprestimos`, {
                    livro_id: livroId,
                    user_id: userId,
                    dias_emprestimo: dias
                });
                
                if (response.data.success) {
                    showMessage('resultado-emprestimo', response.data.message, 'success');
                    
                    // Resetar formulário
                    document.getElementById('form-emprestimo').reset();
                    document.getElementById('dias-emprestimo').value = 14;
                    
                    // Recarregar livros disponíveis
                    carregarLivros();
                }
            } catch (error) {
                const msg = error.response?.data?.message || error.message;
                showMessage('resultado-emprestimo', 'Erro: ' + msg, 'error');
            }
        }

        // Carregar Empréstimos Ativos
        async function carregarAtivos() {
            showLoading('lista-ativos');
            try {
                const response = await axios.get(`${API_BASE}/emprestimos/lista/ativos`);
                const emprestimos = response.data.data;
                
                if (emprestimos.length === 0) {
                    document.getElementById('lista-ativos').innerHTML = `
                        <div class="text-center py-16">
                            <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                            <h4 class="text-xl font-semibold text-gray-600 mb-2">Nenhum empréstimo ativo</h4>
                            <p class="text-gray-500">Todos os livros foram devolvidos</p>
                        </div>
                    `;
                    return;
                }
                
                document.getElementById('lista-ativos').innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Empréstimo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Limite</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${emprestimos.map(emp => renderEmprestimoRow(emp)).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (error) {
                showError('lista-ativos', 'Erro ao carregar empréstimos ativos');
            }
        }

        // Carregar Empréstimos Atrasados
        async function carregarAtrasados() {
            showLoading('lista-atrasados');
            try {
                const response = await axios.get(`${API_BASE}/emprestimos/lista/atrasados`);
                const emprestimos = response.data.data;
                
                if (emprestimos.length === 0) {
                    document.getElementById('lista-atrasados').innerHTML = `
                        <div class="text-center py-16">
                            <i class="fas fa-check-circle text-green-400 text-6xl mb-4"></i>
                            <h4 class="text-xl font-semibold text-green-600 mb-2">Nenhum atraso!</h4>
                            <p class="text-gray-500">Todos os empréstimos estão dentro do prazo</p>
                        </div>
                    `;
                    return;
                }
                
                document.getElementById('lista-atrasados').innerHTML = `
                    <div class="mb-4 p-4 bg-red-50 rounded-lg border border-red-200">
                        <p class="text-red-800 font-semibold">
                            <i class="fas fa-exclamation-triangle mr-2"></i>Total de empréstimos atrasados: ${emprestimos.length}
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-red-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Limite</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dias Atraso</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${emprestimos.map(emp => `
                                    <tr class="hover:bg-red-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                                            <div class="text-sm text-gray-500">${emp.livro.autor || 'Autor desconhecido'}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${emp.user.nome}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatarData(emp.data_limite)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                ${emp.dias_atraso || calcularDiasAtraso(emp.data_limite)} dias
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button onclick="devolverLivro(${emp.id})" 
                                                    class="text-green-600 hover:text-green-900 font-medium">
                                                <i class="fas fa-undo mr-1"></i>Devolver
                                            </button>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (error) {
                showError('lista-atrasados', 'Erro ao carregar empréstimos atrasados');
            }
        }

        // Carregar Histórico
        async function carregarHistorico() {
            showLoading('lista-historico');
            try {
                const response = await axios.get(`${API_BASE}/emprestimos`);
                const emprestimos = response.data.data;
                
                if (emprestimos.length === 0) {
                    document.getElementById('lista-historico').innerHTML = `
                        <div class="text-center py-16">
                            <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
                            <h4 class="text-xl font-semibold text-gray-600 mb-2">Histórico vazio</h4>
                            <p class="text-gray-500">Nenhum empréstimo registrado</p>
                        </div>
                    `;
                    return;
                }
                
                document.getElementById('lista-historico').innerHTML = `
                    <div class="mb-4 p-4 bg-purple-50 rounded-lg border border-purple-200">
                        <p class="text-purple-800 font-semibold">
                            <i class="fas fa-list mr-2"></i>Total de empréstimos: ${emprestimos.length}
                        </p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leitor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Empréstimo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Devolução</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${emprestimos.map(emp => `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${emp.user.nome}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatarData(emp.data_emprestimo)}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${emp.data_devolucao ? formatarData(emp.data_devolucao) : '-'}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            ${renderEstadoBadge(emp.estado)}
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (error) {
                showError('lista-historico', 'Erro ao carregar histórico');
            }
        }

        // Renderizar linha de empréstimo
        function renderEmprestimoRow(emp) {
            const atrasado = emp.atrasado || (new Date(emp.data_limite) < new Date() && emp.estado === 'ativo');
            const statusClass = atrasado ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800';
            const statusText = atrasado ? `Atrasado (${emp.dias_atraso || calcularDiasAtraso(emp.data_limite)} dias)` : 'No prazo';
            
            return `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                        <div class="text-sm text-gray-500">${emp.livro.autor || 'Autor desconhecido'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${emp.user.nome}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatarData(emp.data_emprestimo)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatarData(emp.data_limite)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="devolverLivro(${emp.id})" 
                                class="text-green-600 hover:text-green-900 font-medium">
                            <i class="fas fa-undo mr-1"></i>Devolver
                        </button>
                    </td>
                </tr>
            `;
        }

        // Devolver Livro
        async function devolverLivro(emprestimoId) {
            if (!confirm('Confirma a devolução deste livro?')) return;
            
            try {
                const response = await axios.post(`${API_BASE}/emprestimos/${emprestimoId}/devolver`);
                if (response.data.success) {
                    alert('✅ Livro devolvido com sucesso!');
                    carregarAtivos();
                    carregarAtrasados();
                }
            } catch (error) {
                const msg = error.response?.data?.message || error.message;
                alert('❌ Erro: ' + msg);
            }
        }

        // Renderizar Badge de Estado
        function renderEstadoBadge(estado) {
            const badges = {
                'ativo': '<span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Ativo</span>',
                'devolvido': '<span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Devolvido</span>',
                'atrasado': '<span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Atrasado</span>'
            };
            return badges[estado] || `<span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">${estado}</span>`;
        }

        // Formatar Data
        function formatarData(data) {
            if (!data) return '-';
            const d = new Date(data);
            return d.toLocaleDateString('pt-BR');
        }

        // Calcular Dias de Atraso
        function calcularDiasAtraso(dataLimite) {
            const hoje = new Date();
            const limite = new Date(dataLimite);
            const diffTime = Math.abs(hoje - limite);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return hoje > limite ? diffDays : 0;
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

        // Show Message
        function showMessage(elementId, message, type) {
            const colors = {
                'success': 'green',
                'error': 'red',
                'info': 'blue'
            };
            const color = colors[type] || 'blue';
            const icon = type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle';
            
            document.getElementById(elementId).innerHTML = `
                <div class="bg-${color}-50 border-l-4 border-${color}-500 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fas fa-${icon} text-${color}-500 text-2xl mr-3"></i>
                        <p class="text-${color}-700 font-semibold">${message}</p>
                    </div>
                </div>
            `;
        }
    </script>
</x-app-layout>