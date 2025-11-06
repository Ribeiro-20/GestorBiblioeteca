<x-app-layout>
    <x-slot name="header">
        <h2 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-book-reader text-purple-600 mr-3"></i>
            {{ __('Meus Empréstimos') }}
        </h2>
        <p class="text-gray-600 mt-1">Gerencie seus empréstimos ativos e consulte o histórico</p>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Mensagens -->
            <div id="mensagem" class="hidden mb-6"></div>

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl shadow-md border-b border-gray-200">
                <div class="flex overflow-x-auto">
                    <button onclick="mostrarAba('ativos')" id="tab-ativos" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-purple-600 border-b-2 border-purple-600 whitespace-nowrap">
                        <i class="fas fa-book-open mr-2"></i>Empréstimos Ativos
                    </button>
                    <button onclick="mostrarAba('historico')" id="tab-historico" 
                            class="tab-button flex-1 px-6 py-4 font-semibold text-gray-600 hover:text-purple-600 whitespace-nowrap">
                        <i class="fas fa-history mr-2"></i>Histórico
                    </button>
                </div>
            </div>

            <!-- Tab Content Container -->
            <div class="bg-white rounded-b-2xl shadow-md">
                
                <!-- Aba Empréstimos Ativos -->
                <div id="aba-ativos" class="p-8">
                    <div id="loading-ativos" class="text-center py-16">
                        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-purple-200 border-t-purple-600 mb-4"></div>
                        <p class="text-gray-600 font-semibold">Carregando seus empréstimos...</p>
                    </div>
                    <div id="lista-ativos" class="hidden"></div>
                    <div id="vazio-ativos" class="hidden text-center py-16">
                        <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">Nenhum empréstimo ativo</h4>
                        <p class="text-gray-500">Você não possui livros emprestados no momento</p>
                        <a href="{{ route('biblioteca') }}" 
                           class="inline-block mt-6 bg-gradient-to-r from-purple-600 to-indigo-600 text-white rounded-lg px-6 py-3 hover:from-purple-700 hover:to-indigo-700 font-semibold transition">
                            <i class="fas fa-search mr-2"></i>Buscar Livros
                        </a>
                    </div>
                </div>

                <!-- Aba Histórico -->
                <div id="aba-historico" class="p-8 hidden">
                    <div id="loading-historico" class="text-center py-16">
                        <div class="inline-block animate-spin rounded-full h-16 w-16 border-4 border-purple-200 border-t-purple-600 mb-4"></div>
                        <p class="text-gray-600 font-semibold">Carregando histórico...</p>
                    </div>
                    <div id="lista-historico" class="hidden"></div>
                    <div id="vazio-historico" class="hidden text-center py-16">
                        <i class="fas fa-history text-gray-300 text-6xl mb-4"></i>
                        <h4 class="text-xl font-semibold text-gray-600 mb-2">Histórico vazio</h4>
                        <p class="text-gray-500">Você ainda não realizou nenhum empréstimo</p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal de Confirmação de Devolução -->
    <div id="modal-devolucao" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center">
        <div class="relative p-8 border w-full max-w-md shadow-2xl rounded-2xl bg-white mx-4">
            <div class="text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-undo text-green-600 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">Confirmar Devolução</h3>
                <p class="text-gray-600 mb-6" id="modal-mensagem">
                    Confirma que já devolveste este livro à biblioteca?
                </p>
                <div class="flex justify-center space-x-4">
                    <button onclick="fecharModal()" 
                            class="px-6 py-3 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 font-semibold transition">
                        <i class="fas fa-times mr-2"></i>Cancelar
                    </button>
                    <button onclick="confirmarDevolucao()" 
                            id="btn-confirmar-devolucao"
                            class="px-6 py-3 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 font-semibold transition">
                        <i class="fas fa-check mr-2"></i>Sim, Devolver
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = '/ajax';
        let emprestimoParaDevolucao = null;
        let userId = null;

        // Configurar axios para enviar credenciais e CSRF
        axios.defaults.withCredentials = true;
        axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken.content;
        }

        // Carregar dados ao iniciar
        document.addEventListener('DOMContentLoaded', function() {
            carregarMeusEmprestimos();
        });

        function mostrarAba(aba) {
            // Atualizar tabs
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('text-purple-600', 'border-b-2', 'border-purple-600');
                btn.classList.add('text-gray-600');
            });
            
            const tabAtiva = document.getElementById(`tab-${aba}`);
            tabAtiva.classList.add('text-purple-600', 'border-b-2', 'border-purple-600');
            tabAtiva.classList.remove('text-gray-600');

            // Mostrar conteúdo
            document.querySelectorAll('[id^="aba-"]').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(`aba-${aba}`).classList.remove('hidden');
        }

        async function carregarMeusEmprestimos() {
            try {
                const response = await axios.get(`${API_BASE}/emprestimos/meus`);
                
                if (response.data.success) {
                    userId = response.data.user_id;
                    const emprestimos = response.data.data;
                    
                    // Separar ativos e histórico
                    const ativos = emprestimos.filter(e => e.estado === 'ativo');
                    const historico = emprestimos.filter(e => e.estado !== 'ativo');
                    
                    renderizarEmprestimosAtivos(ativos);
                    renderizarHistorico(historico);
                } else {
                    mostrarMensagem('Erro ao carregar empréstimos.', 'error');
                }
            } catch (error) {
                console.error('Erro ao carregar empréstimos:', error);
                mostrarMensagem('Erro ao carregar empréstimos: ' + (error.response?.data?.message || error.message), 'error');
                document.getElementById('loading-ativos').classList.add('hidden');
                document.getElementById('vazio-ativos').classList.remove('hidden');
            }
        }

        function renderizarEmprestimosAtivos(emprestimos) {
            const container = document.getElementById('lista-ativos');
            const loading = document.getElementById('loading-ativos');
            const vazio = document.getElementById('vazio-ativos');

            loading.classList.add('hidden');

            if (emprestimos.length === 0) {
                vazio.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            
            // Verificar se há atrasados
            const atrasados = emprestimos.filter(e => e.atrasado || (new Date(e.data_limite) < new Date()));
            const alertaAtrasados = atrasados.length > 0 ? `
                <div class="mb-6 p-4 bg-red-50 rounded-lg border-l-4 border-red-500">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl mr-3"></i>
                        <div>
                            <h4 class="text-red-800 font-bold">Atenção!</h4>
                            <p class="text-red-700">Você tem ${atrasados.length} empréstimo(s) atrasado(s). Por favor, devolva o(s) livro(s) o quanto antes.</p>
                        </div>
                    </div>
                </div>
            ` : '';

            container.innerHTML = `
                ${alertaAtrasados}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    ${emprestimos.map(emp => {
                        const atrasado = emp.atrasado || (new Date(emp.data_limite) < new Date() && emp.estado === 'ativo');
                        const diasAtraso = emp.dias_atraso || calcularDiasAtraso(emp.data_limite);
                        const imgUrl = emp.livro.imagem || 'https://via.placeholder.com/200x300?text=Sem+Capa';
                        
                        return `
                            <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border ${atrasado ? 'border-red-300' : 'border-gray-200'}">
                                ${atrasado ? `
                                    <div class="bg-red-500 text-white px-4 py-2 text-center font-bold">
                                        <i class="fas fa-exclamation-triangle mr-2"></i>ATRASADO ${diasAtraso} DIA(S)
                                    </div>
                                ` : ''}
                                <div class="relative">
                                    <img src="${imgUrl}" alt="${emp.livro.titulo}" 
                                         class="w-full h-64 object-cover" 
                                         onerror="this.src='https://via.placeholder.com/200x300?text=Sem+Capa'">
                                </div>
                                <div class="p-4">
                                    <h4 class="font-bold text-gray-900 text-lg mb-2 line-clamp-2">${emp.livro.titulo}</h4>
                                    <p class="text-gray-600 text-sm mb-4 flex items-center">
                                        <i class="fas fa-user mr-2 text-purple-600"></i>${emp.livro.autor || 'Autor desconhecido'}
                                    </p>
                                    
                                    <div class="space-y-2 mb-4">
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-calendar-plus text-gray-400 mr-2"></i>
                                            <span class="text-gray-600">Empréstimo: ${formatarData(emp.data_emprestimo)}</span>
                                        </div>
                                        <div class="flex items-center text-sm">
                                            <i class="fas fa-calendar-check ${atrasado ? 'text-red-500' : 'text-green-500'} mr-2"></i>
                                            <span class="${atrasado ? 'text-red-600 font-semibold' : 'text-gray-600'}">
                                                Devolução: ${formatarData(emp.data_limite)}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <button onclick="devolverLivro(${emp.id}, '${emp.livro.titulo.replace(/'/g, "&apos;")}')" 
                                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg px-4 py-3 hover:from-green-700 hover:to-emerald-700 font-semibold transition transform hover:scale-105">
                                        <i class="fas fa-undo mr-2"></i>Devolver Livro
                                    </button>
                                </div>
                            </div>
                        `;
                    }).join('')}
                </div>
            `;
        }

        function renderizarHistorico(emprestimos) {
            const container = document.getElementById('lista-historico');
            const loading = document.getElementById('loading-historico');
            const vazio = document.getElementById('vazio-historico');

            loading.classList.add('hidden');

            if (emprestimos.length === 0) {
                vazio.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            container.innerHTML = `
                <div class="mb-6 p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <p class="text-purple-800 font-semibold">
                        <i class="fas fa-history mr-2"></i>Total de empréstimos concluídos: ${emprestimos.length}
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Empréstimo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Devolução</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${emprestimos.map(emp => `
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            ${emp.livro.imagem ? `
                                                <img src="${emp.livro.imagem}" alt="${emp.livro.titulo}" 
                                                     class="w-12 h-16 object-cover rounded mr-3"
                                                     onerror="this.style.display='none'">
                                            ` : ''}
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                                                <div class="text-sm text-gray-500">${emp.livro.autor || 'Autor desconhecido'}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${formatarData(emp.data_emprestimo)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${emp.data_devolucao ? formatarData(emp.data_devolucao) : '-'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full 
                                            ${emp.estado === 'devolvido' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                            <i class="fas ${emp.estado === 'devolvido' ? 'fa-check-circle' : 'fa-circle'} mr-1"></i>
                                            ${emp.estado}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function devolverLivro(emprestimoId, tituloLivro) {
            emprestimoParaDevolucao = emprestimoId;
            document.getElementById('modal-mensagem').textContent = 
                `Confirma que já devolveste o livro "${tituloLivro}" à biblioteca?`;
            document.getElementById('modal-devolucao').classList.remove('hidden');
        }

        async function confirmarDevolucao() {
            if (!emprestimoParaDevolucao) return;

            const btnConfirmar = document.getElementById('btn-confirmar-devolucao');
            const textoOriginal = btnConfirmar.innerHTML;
            btnConfirmar.disabled = true;
            btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';

            try {
                const response = await axios.post(`${API_BASE}/emprestimos/${emprestimoParaDevolucao}/devolver`);
                
                if (response.data.success) {
                    mostrarMensagem('✅ ' + response.data.message, 'success');
                    fecharModal();
                    // Recarregar empréstimos
                    setTimeout(() => carregarMeusEmprestimos(), 500);
                } else {
                    mostrarMensagem('❌ Erro ao processar devolução.', 'error');
                }
            } catch (error) {
                console.error('Erro ao devolver:', error);
                mostrarMensagem('❌ Erro: ' + (error.response?.data?.message || error.message), 'error');
            } finally {
                btnConfirmar.disabled = false;
                btnConfirmar.innerHTML = textoOriginal;
            }
        }

        function fecharModal() {
            document.getElementById('modal-devolucao').classList.add('hidden');
            emprestimoParaDevolucao = null;
        }

        function mostrarMensagem(texto, tipo) {
            const mensagem = document.getElementById('mensagem');
            
            const icon = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
            const bgColor = tipo === 'success' ? 'bg-green-50 border-green-500' : 'bg-red-50 border-red-500';
            const textColor = tipo === 'success' ? 'text-green-800' : 'text-red-800';
            const iconColor = tipo === 'success' ? 'text-green-500' : 'text-red-500';
            
            mensagem.innerHTML = `
                <div class="${bgColor} border-l-4 rounded-lg p-6">
                    <div class="flex items-center">
                        <i class="fas ${icon} ${iconColor} text-2xl mr-3"></i>
                        <p class="${textColor} font-semibold">${texto}</p>
                    </div>
                </div>
            `;
            mensagem.classList.remove('hidden');
            
            setTimeout(() => {
                mensagem.classList.add('hidden');
            }, 5000);
        }

        function formatarData(data) {
            if (!data) return '-';
            const d = new Date(data);
            return d.toLocaleDateString('pt-BR');
        }

        function calcularDiasAtraso(dataLimite) {
            const hoje = new Date();
            const limite = new Date(dataLimite);
            const diffTime = Math.abs(hoje - limite);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return hoje > limite ? diffDays : 0;
        }
    </script>
</x-app-layout>
                
                if (response.data.success) {
                    userId = response.data.user_id;
                    const emprestimos = response.data.data;
                    
                    // Separar ativos e histórico
                    const ativos = emprestimos.filter(e => e.estado === 'ativo');
                    const historico = emprestimos.filter(e => e.estado !== 'ativo');
                    
                    renderizarEmprestimosAtivos(ativos);
                    renderizarHistorico(historico);
                } else {
                    mostrarMensagem('Erro ao carregar empréstimos.', 'error');
                }
            } catch (error) {
                console.error('Erro ao carregar empréstimos:', error);
                mostrarMensagem('Erro ao carregar empréstimos: ' + (error.response?.data?.message || error.message), 'error');
                document.getElementById('loading-ativos').classList.add('hidden');
                document.getElementById('vazio-ativos').classList.remove('hidden');
            }
        }

        function renderizarEmprestimosAtivos(emprestimos) {
            const container = document.getElementById('lista-ativos');
            const loading = document.getElementById('loading-ativos');
            const vazio = document.getElementById('vazio-ativos');

            loading.classList.add('hidden');

            if (emprestimos.length === 0) {
                vazio.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            container.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Empréstimo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Limite</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${emprestimos.map(emp => {
                                const atrasado = emp.atrasado || (new Date(emp.data_limite) < new Date() && emp.estado === 'ativo');
                                const statusClass = atrasado ? 'text-red-600 font-semibold' : 'text-green-600';
                                const statusText = atrasado ? `Atrasado (${emp.dias_atraso || calcularDiasAtraso(emp.data_limite)} dias)` : 'No prazo';
                                
                                return `
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                                            <div class="text-sm text-gray-500">${emp.livro.autor || 'Autor desconhecido'}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${formatarData(emp.data_emprestimo)}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            ${formatarData(emp.data_limite)}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="${statusClass} text-sm">${statusText}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <button onclick="devolverLivro(${emp.id}, '${emp.livro.titulo}')" 
                                                    class="text-green-600 hover:text-green-900 font-medium">
                                                Devolver Livro
                                            </button>
                                        </td>
                                    </tr>
                                `;
                            }).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function renderizarHistorico(emprestimos) {
            const container = document.getElementById('lista-historico');
            const loading = document.getElementById('loading-historico');
            const vazio = document.getElementById('vazio-historico');

            loading.classList.add('hidden');

            if (emprestimos.length === 0) {
                vazio.classList.remove('hidden');
                return;
            }

            container.classList.remove('hidden');
            container.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Livro</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Empréstimo</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Devolução</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${emprestimos.map(emp => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">${emp.livro.titulo}</div>
                                        <div class="text-sm text-gray-500">${emp.livro.autor || 'Autor desconhecido'}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${formatarData(emp.data_emprestimo)}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ${emp.data_devolucao ? formatarData(emp.data_devolucao) : '-'}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            ${emp.estado === 'devolvido' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                            ${emp.estado}
                                        </span>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function carregarHistorico() {
            // Já carregado na função inicial, apenas garantir que renderize
            if (document.getElementById('lista-historico').innerHTML === '') {
                await carregarMeusEmprestimos();
            }
        }

        function devolverLivro(emprestimoId, tituloLivro) {
            emprestimoParaDevolucao = emprestimoId;
            document.getElementById('modal-mensagem').textContent = 
                `Confirma que já devolveste o livro "${tituloLivro}" à biblioteca?`;
            document.getElementById('modal-devolucao').classList.remove('hidden');
        }

        async function confirmarDevolucao() {
            if (!emprestimoParaDevolucao) return;

            const btnConfirmar = document.getElementById('btn-confirmar-devolucao');
            btnConfirmar.disabled = true;
            btnConfirmar.textContent = 'Processando...';

            try {
                const response = await axios.post(`${API_BASE}/emprestimos/${emprestimoParaDevolucao}/devolver`);
                
                if (response.data.success) {
                    mostrarMensagem('Livro devolvido com sucesso!', 'success');
                    fecharModal();
                    // Recarregar empréstimos
                    setTimeout(() => carregarMeusEmprestimos(), 500);
                } else {
                    mostrarMensagem('Erro ao processar devolução.', 'error');
                }
            } catch (error) {
                console.error('Erro ao devolver:', error);
                mostrarMensagem('Erro ao processar devolução: ' + (error.response?.data?.message || error.message), 'error');
            } finally {
                btnConfirmar.disabled = false;
                btnConfirmar.textContent = 'Sim, Devolver';
            }
        }

        function fecharModal() {
            document.getElementById('modal-devolucao').classList.add('hidden');
            emprestimoParaDevolucao = null;
        }

        function mostrarMensagem(texto, tipo) {
            const mensagem = document.getElementById('mensagem');
            mensagem.textContent = texto;
            mensagem.className = `mb-4 p-4 rounded-md ${
                tipo === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
            }`;
            mensagem.classList.remove('hidden');
            
            setTimeout(() => {
                mensagem.classList.add('hidden');
            }, 5000);
        }

        function formatarData(data) {
            if (!data) return '-';
            const d = new Date(data);
            return d.toLocaleDateString('pt-BR');
        }

        function calcularDiasAtraso(dataLimite) {
            const hoje = new Date();
            const limite = new Date(dataLimite);
            const diffTime = Math.abs(hoje - limite);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return hoje > limite ? diffDays : 0;
        }
    </script>
</body>
</html>
