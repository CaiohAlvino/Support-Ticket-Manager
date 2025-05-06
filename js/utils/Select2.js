/**
 * Classe para gerenciar configurações do Select2
 * Responsável por padronizar e configurar elementos Select2 na aplicação
 */
class Select2 {
    /**
     * Inicializa todos os elementos com classe 'select2' automaticamente
     * Método principal que deve ser chamado no carregamento da página
     */
    static inicializarTodos() {
        $(function () {
            $('.select2').each(function () {
                const $select = $(this);
                const isModal = $select.closest('.modal').length > 0;

                if (isModal) {
                    const $modalBody = $select.closest('.modal-body');

                    // Verifica se os elementos têm IDs válidos
                    const selectId = $select.attr('id');
                    const modalBodyId = $modalBody.attr('id');

                    if (selectId && modalBodyId) {
                        Select2.configurarEmModal('#' + selectId, '#' + modalBodyId);
                    } else {
                        // Fallback para quando não há IDs - usar o próprio elemento
                        $select.select2({
                            dropdownParent: $modalBody,
                            width: '100%',
                            dropdownCssClass: 'select2-dropdown-modal',
                            language: 'pt-BR',
                            placeholder: "Selecione..."
                        });
                    }
                } else {
                    // Configuração padrão para selects fora de modais
                    Select2.configurarPadrao($select);
                }
            });
        });
    }

    /**
 * Configuração padrão que será aplicada a todos os selects
 * @param {jQuery|string} seletor - Seletor jQuery ou elemento jQuery
 * @param {object} opcoesAdicionais - Opções adicionais para personalização
 */
    static configurarPadrao($elemento, opcoesAdicionais = {}) {
        if (typeof $elemento === 'string') {
            $elemento = $($elemento);
        }

        const configPadrao = {
            width: '100%',
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado!";
                },
                searching: function () {
                    return "Pesquisando...";
                },
                inputTooShort: function () {
                    return "Digite pelo menos 1 caractere!";
                }
            },
            placeholder: "Selecione...",
            escapeMarkup: function (markup) {
                return markup;
            },
            templateResult: function (data) {
                if (data.loading) {
                    return data.text;
                }

                return data.text;
            },
            templateSelection: function (data) {
                return data.text;
            }
        };

        // Mescla as configurações padrão com as opções adicionais
        const configFinal = { ...configPadrao, ...opcoesAdicionais };

        $elemento.select2(configFinal);
    }

    /**
     * Configura um Select2 para funcionar corretamente dentro de modais
     * @param {string} selectorId - O seletor do elemento (ex: '#especializacao')
     * @param {string} parentId - O seletor do elemento pai (ex: '#step3')
     * @param {object} options - Opções adicionais para personalização
     */
    static configurarEmModal(selectorId, parentId, options = {}) {
        const $elemento = $(selectorId);
        const $parent = $(parentId);

        // Verifica se os elementos existem
        if ($elemento.length === 0 || $parent.length === 0) {
            console.warn('Select2: Elemento não encontrado', { selectorId, parentId });
            return;
        }

        // Configurações padrão para modais
        const configPadrao = {
            dropdownParent: $parent,
            width: '100%',
            dropdownCssClass: 'select2-dropdown-modal',
            language: {
                noResults: function () {
                    return "Nenhum resultado encontrado!";
                },
                searching: function () {
                    return "Pesquisando...";
                },
                inputTooShort: function () {
                    return "Digite pelo menos 1 caractere!";
                }
            },
            placeholder: "Selecione...",
            escapeMarkup: function (markup) {
                return markup;
            }
        };

        // Mescla as configurações padrão com as opções personalizadas
        const configFinal = { ...configPadrao, ...options };

        // Inicializa o Select2 com as configurações
        $elemento.select2(configFinal);

        // Garante o foco correto ao abrir o dropdown
        $elemento.on('select2:open', function () {
            const campo = document.querySelector('.select2-search__field');
            if (campo) {
                campo.focus();
            }
        });
    }

    /**
     * Destrói e reinicializa um Select2 existente
     * Útil quando precisa atualizar um Select2 já inicializado
     * @param {string} selectorId - O seletor do elemento
     * @param {object} options - Opções para reinicialização
     */
    static reinicializar(selectorId, options = {}) {
        const $elemento = $(selectorId);

        if ($elemento.length === 0) {
            console.warn('Select2: Elemento não encontrado para reinicializar', selectorId);
            return;
        }

        $elemento.select2('destroy');
        $elemento.select2(options);
    }

    /**
     * Adiciona opções dinamicamente a um Select2 existente
     * @param {string} selectorId - O seletor do elemento
     * @param {Array} dados - Array de objetos {id, text} com as novas opções
     * @param {boolean} limpar - Se deve limpar as opções existentes
     */
    static adicionarOpcoes(selectorId, dados, limpar = false) {
        const $elemento = $(selectorId);

        if ($elemento.length === 0) {
            console.warn('Select2: Elemento não encontrado para adicionar opções', selectorId);
            return;
        }

        if (limpar) {
            $elemento.empty();
        }

        if (Array.isArray(dados)) {
            dados.forEach(item => {
                if (item && item.hasOwnProperty('id') && item.hasOwnProperty('text')) {
                    const novaOpcao = new Option(item.text, item.id, false, false);
                    $elemento.append(novaOpcao);
                }
            });
        } else {
            console.warn('Select2: Dados inválidos para adicionar opções', dados);
        }
    }
}

// Inicialização para selects específicos
// Inicialização para selects específicos
$(document).ready(function () {
    // Inicializar todos os selects com classe select2
    Select2.inicializarTodos();

    // Configurações específicas para selects em modais
    $(".modal-cadastrar-com-select2").on("shown.bs.modal", function () {
        // Correção: Usando o seletor correto e passando o objeto jQuery diretamente
        const $modal = $(this);
        const $modalBody = $modal.find('.modal-body');

        // Configurar selects dentro deste modal específico
        $modal.find(".produto_id").each(function () {
            Select2.configurarPadrao($(this), { dropdownParent: $modalBody });
        });

        $modal.find(".usuario_id").each(function () {
            Select2.configurarPadrao($(this), { dropdownParent: $modalBody });
        });
    });

    $(".modal-cadastrar-com-select2").on("hidden.bs.modal", function () {
        $(".produto_id").select2({ placeholder: "Selecione..." });
        $(".usuario_id").select2({ placeholder: "Selecione..." });
    });

    // Inicialização de selects específicos
    $(".usuario_id, .cliente_id, .categoria_id, .tipo_procedimento_id, .produto_id, .procedimento_id, .perfil_id, .sala_id").each(function () {
        Select2.configurarPadrao($(this));
    });

    // Tratamento especial para o campo de especialização no modal de onboarding
    $("#onboardingModal").on("shown.bs.modal", function () {
        Select2.configurarEmModal('#especialidade_id', '#step3');
    });

    // Foco no campo de busca ao clicar no select2
    $(".select2-selection").on("click", function (e) {
        if ($(".select2-search__field")[0]) {
            $(".select2-search__field")[0].focus();
        }
    });

    // Capturar evento de seleção para ações personalizadas
    $(".select2").on("select2:select", function (e) {
        let { id } = e.params.data;
        // Aqui você pode adicionar ações específicas para quando um item for selecionado
    });
});