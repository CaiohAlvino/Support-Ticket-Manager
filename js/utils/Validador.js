/**
 *  Validador base para os outros validadores
 */
class Validador {
    constructor() {
        this.campoObrigatorio = "campo-obrigatorio";
        this.inicializar();
    }

    /**
     *  Inicializa o validador com eventos em tempo real
     */
    inicializar() {
        // Método a ser sobrescrito pelas classes filhas
    }

    /**
     *  Valida um valor
     *  @param {*} valor - Valor a ser validado
     *  @returns {Boolean} - Verdadeiro se o valor for válido
     */
    validar(valor) {
        // Método a ser sobrescrito pelas classes filhas
        return true;
    }
}

// -------------------------- → FeedbackVisual ← -------------------------- //
/**
 * Classe dedicada a mostrar avisos em tempo real para o usuário final
 */
class FeedbackVisual {
    /**
     *  Mostra uma mensagem de erro para um campo
     *  @param {Element|String} campo - O campo que apresentou erro
     *  @param {String} mensagem - A mensagem de erro a ser exibida
     *  @param {Object} opcoesPersonalizadas - Opções adicionais de estilo
     */
    static mostrarErro(campo, mensagem, opcoesPersonalizadas = {}) {
        const $campo = $(campo);
        const opcoesPadrao = {
            classeAdicional: "error-message",
            tempoExibicao: null,
        };

        const opcoes = { ...opcoesPadrao, ...opcoesPersonalizadas };

        $campo.removeClass("is-valid").addClass("is-invalid");

        $campo.siblings(".invalid-feedback").remove();

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.siblings(".invalid-feedback").remove();
        }

        const elementoErro = $(`<div class="invalid-feedback ${opcoes.classeAdicional}">${mensagem}</div>`);

        if ($campo.hasClass("select2") && $inputGroup.length > 0) {
            $campo.next(".select2-container").addClass("is-invalid");
            $inputGroup.after(elementoErro);
            $inputGroup.addClass("is-invalid");
        } else if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").addClass("is-invalid");
            $campo.next(".select2-container").after(elementoErro);
        } else {
            $campo.after(elementoErro);
        }

        if (opcoes.tempoExibicao) {
            setTimeout(() => {
                elementoErro.remove();
                $campo.removeClass("is-invalid");
                if ($inputGroup.length > 0) {
                    $inputGroup.removeClass("is-invalid");
                }
                if ($campo.hasClass("select2")) {
                    $campo.next(".select2-container").removeClass("is-invalid");
                }
            }, opcoes.tempoExibicao);
        }
    }

    /**
     *  Mostra feedback de sucesso para um campo
     *  @param {Element|String} campo - O campo que foi validado com sucesso
     *  @param {Object} opcoesPersonalizadas - Opções adicionais de estilo
     */
    static mostrarSucesso(campo, opcoesPersonalizadas = {}) {
        const $campo = $(campo);
        const opcoesPadrao = {
            classeAdicional: "text-success",
            tempoExibicao: null,
        };

        const opcoes = { ...opcoesPadrao, ...opcoesPersonalizadas };

        $campo.removeClass("is-invalid").addClass("is-valid");

        $campo.siblings(".invalid-feedback").remove();

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.siblings(".invalid-feedback").remove();
            $inputGroup.removeClass("is-invalid");
        }

        if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").removeClass("is-invalid").addClass("is-valid");
        }

        if (opcoes.tempoExibicao) {
            setTimeout(() => {
                $campo.removeClass("is-valid");
                if ($campo.hasClass("select2")) {
                    $campo.next(".select2-container").removeClass("is-valid");
                }
            }, opcoes.tempoExibicao);
        }
    }

    /**
     *  Mostra uma mensagem de aviso para um campo (não impede o envio do formulário)
     *  @param {Element|String} campo - O campo que apresentou o aviso
     *  @param {String} mensagem - A mensagem de aviso a ser exibida
     *  @param {Object} opcoesPersonalizadas - Opções adicionais de estilo
     */
    static mostrarAviso(campo, mensagem, opcoesPersonalizadas = {}) {
        const $campo = $(campo);
        const opcoesPadrao = {
            classeAdicional: "warning-message",
            tempoExibicao: null,
        };

        const opcoes = { ...opcoesPadrao, ...opcoesPersonalizadas };

        $campo.removeClass("is-invalid is-valid").addClass("has-warning");

        $campo.siblings(".invalid-feedback, .mensagem-alerta").remove();

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.siblings(".invalid-feedback, .mensagem-alerta").remove();
            $inputGroup.removeClass("is-invalid");
        }

        const elementoAviso = $(
            `<div class="alert mensagem-alerta ${opcoes.classeAdicional}"><i class="bi bi-exclamation-triangle mx-2"></i>${mensagem}</div>`,
        );

        if ($campo.hasClass("select2") && $inputGroup.length > 0) {
            $campo.next(".select2-container").addClass("has-warning");
            $inputGroup.after(elementoAviso);
        } else if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").addClass("has-warning");
            $campo.next(".select2-container").after(elementoAviso);
        } else {
            $campo.after(elementoAviso);
        }

        if (opcoes.tempoExibicao) {
            setTimeout(() => {
                elementoAviso.remove();
                $campo.removeClass("has-warning");
                if ($campo.hasClass("select2")) {
                    $campo.next(".select2-container").removeClass("has-warning");
                }
            }, opcoes.tempoExibicao);
        }
    }

    /**
     *  Mostra feedback de carregamento para um campo que está consultando API
     *  @param {Element|String} campo - O campo principal que está realizando a consulta
     *  @param {Array|String} camposRelacionados - Campos relacionados para desabilitar durante a consulta
     *  @param {String} mensagem - Mensagem de carregamento
     *  @param {Object} opcoesPersonalizadas - Opções adicionais
     *  @returns {Object} - Objeto com método concluir() para finalizar o carregamento
     */
    static mostrarCarregamento(
        campo,
        camposRelacionados = [],
        mensagem = "Carregando...",
        opcoesPersonalizadas = {},
    ) {
        const $campo = $(campo);
        const opcoesPadrao = {
            classeAdicional: "loading-feedback",
            tempoExibicao: null,
        };

        const opcoes = { ...opcoesPadrao, ...opcoesPersonalizadas };

        $campo.data("estado-original", {
            disabled: $campo.prop("disabled"),
            valor: $campo.val(),
        });

        $campo.prop("disabled", true);

        let $camposRelacionados = [];
        if (camposRelacionados) {
            $camposRelacionados = Array.isArray(camposRelacionados)
                ? camposRelacionados.map(c => $(c))
                : [$(camposRelacionados)];

            $camposRelacionados.forEach($campoRel => {
                $campoRel.data("estado-original", {
                    disabled: $campoRel.prop("disabled"),
                    valor: $campoRel.val(),
                });
                $campoRel.prop("disabled", true);
            });
        }

        $campo.siblings(".invalid-feedback, .valid-feedback, .loading-feedback").remove();

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.siblings(".invalid-feedback, .valid-feedback, .loading-feedback").remove();
            $inputGroup.removeClass("is-invalid");
        }

        $campo.removeClass("is-invalid is-valid").addClass("is-loading");

        if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").removeClass("is-invalid is-valid").addClass("is-loading");
        }

        const $feedbackCarregamento = $(
            `<div class="loading-feedback ${opcoes.classeAdicional}">
                <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                <span>${mensagem}</span>
            </div>`,
        );

        if ($inputGroup.length > 0) {
            $inputGroup.after($feedbackCarregamento);
        } else {
            $campo.after($feedbackCarregamento);
        }

        return {
            concluir: (sucesso = true, mensagemResultado = "") => {
                $campo.removeClass("is-loading is-invalid is-valid");
                $feedbackCarregamento.remove();

                if ($inputGroup.length > 0) {
                    $inputGroup.removeClass("is-invalid is-valid");
                }

                const estadoOriginal = $campo.data("estado-original");
                if (estadoOriginal) {
                    $campo.prop("disabled", estadoOriginal.disabled);
                    $campo.removeData("estado-original");
                }

                $camposRelacionados.forEach($campoRel => {
                    const estadoOriginalRel = $campoRel.data("estado-original");
                    if (estadoOriginalRel) {
                        $campoRel.prop("disabled", estadoOriginalRel.disabled);
                        $campoRel.removeData("estado-original");
                    }
                });

                if ($campo.hasClass("select2")) {
                    $campo.next(".select2-container").removeClass("is-loading is-invalid is-valid");
                }

                $campo.siblings(".invalid-feedback, .valid-feedback").remove();
                if ($inputGroup.length > 0) {
                    $inputGroup.siblings(".invalid-feedback, .valid-feedback").remove();
                }

                if (sucesso) {
                    if (mensagemResultado) {
                        FeedbackVisual.mostrarSucesso(campo, { mensagem: mensagemResultado });
                    } else {
                        FeedbackVisual.mostrarSucesso(campo);
                    }
                } else {
                    FeedbackVisual.mostrarErro(campo, mensagemResultado || "Erro na operação.");
                }
            },
        };
    }

    /**
     *  Habilita um campo e seus campos relacionados
     *  @param {Element|String} campo - O campo principal a ser habilitado
     *  @param {Array|String} camposRelacionados - Campos relacionados para habilitar
     */
    static habilitarCampo(campo, camposRelacionados = []) {
        const $campo = $(campo);

        $campo.prop("disabled", false);

        if (camposRelacionados.length > 0) {
            camposRelacionados.forEach(seletor => {
                $(seletor).prop("disabled", false);
            });
        }

        if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").removeClass("select2-container--disabled");
            if ($campo.hasClass("select2-hidden-accessible")) {
                $campo.select2("enable", true);
            }
        }

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.removeClass("is-invalid");
            $inputGroup.find("input, select").prop("disabled", false);
        }
    }

    /**
     *  Remove qualquer feedback visual do campo
     *  @param {Element|String} campo - O campo a ter o feedback removido
     */
    static limparFeedback(campo) {
        const $campo = $(campo);

        $campo.removeClass("is-invalid is-valid has-warning");
        $campo.siblings(".invalid-feedback, .mensagem-alerta, .error-message").remove();

        const $inputGroup = $campo.closest(".input-group");
        if ($inputGroup.length > 0) {
            $inputGroup.removeClass("is-invalid");
            $inputGroup.siblings(".invalid-feedback, .mensagem-alerta, .error-message").remove();
        }

        if ($campo.hasClass("select2")) {
            $campo.next(".select2-container").removeClass("is-invalid is-valid has-warning");
        }
    }

    /**
     *  Limpa o feedback de todos os campos do formulário
     *  @param {String} formSelector - Seletor do formulário
     *  @param {Boolean} tirarAvisos - Se deve remover também os avisos de alerta
     */
    static limparTodosFeedbacks(formSelector, tirarAvisos = false) {
        $(`${formSelector} .is-invalid`).removeClass("is-invalid");
        $(`${formSelector} .is-valid`).removeClass("is-valid");

        $(`${formSelector} .invalid-feedback`).remove();
        $(`${formSelector} .error-message`).remove();
        if (tirarAvisos) {
            $(`${formSelector} .has-warning`).removeClass("has-warning");
            $(`${formSelector} .mensagem-alerta`).remove();
        }

        // Tratamento especial para Select2
        $(`${formSelector} .select2`).each(function () {
            if (tirarAvisos) {
                $(this).next(".select2-container").removeClass("is-invalid is-valid has-warning");
            } else {
                $(this).next(".select2-container").removeClass("is-invalid is-valid");
            }
        });

        // Verificar e remover mensagens após input-groups
        $(`${formSelector} .input-group`).each(function () {
            $(this).removeClass("is-invalid");
            if (tirarAvisos) {
                $(this).siblings(".invalid-feedback, .error-message, .mensagem-alerta").remove();
            } else {
                $(this).siblings(".invalid-feedback, .error-message").remove();
            }
        });
    }
}

/**
 *  Validações de Documentos
 *  CPF, CNPJ, CEP e Número Endereço
 */
// -------------------------- → Validador CPF ← -------------------------- //
class ValidadorCPF extends Validador {
    inicializar() {
        $(".input-validar-cpf").on("input blur", event => {
            const campo = event.target;
            const cpf = $(campo).val().trim();

            if (cpf === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o CPF.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else {
                if (this.validar(cpf)) {
                    FeedbackVisual.mostrarSucesso(campo);
                } else {
                    FeedbackVisual.mostrarErro(campo, "CPF inválido. Por favor, verifique.");
                }
            }
        });

        $(".input-validar-cpf-nao-obrigatorio").on("input blur", event => {
            const campo = event.target;
            const cpf = $(campo).val().trim();

            if (cpf === "") {
                FeedbackVisual.limparFeedback(campo);
            } else {
                if (this.validar(cpf)) {
                    FeedbackVisual.mostrarSucesso(campo);
                } else {
                    FeedbackVisual.mostrarErro(campo, "CPF inválido. Por favor, verifique.");
                }
            }
        });
    }

    /**
     *  Valida um CPF
     *  @param {String} cpf - O CPF a ser validado
     *  @returns {Boolean} - Verdadeiro se o CPF for válido
     */
    validar(cpf) {
        if (!cpf) {
            return false;
        }

        if (cpf === "") {
            return false;
        }

        cpf = cpf.replace(/[^\d]+/g, "");

        if (cpf === "" || cpf.length !== 11) {
            return false;
        }
        if (/^(\d)\1+$/.test(cpf)) {
            return false;
        }

        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }

        let resto = soma % 11;
        let dv1 = resto < 2 ? 0 : 11 - resto;

        if (parseInt(cpf.charAt(9)) !== dv1) {
            return false;
        }

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }

        resto = soma % 11;
        let dv2 = resto < 2 ? 0 : 11 - resto;

        return parseInt(cpf.charAt(10)) === dv2;
    }
}

// -------------------------- → Validador CNPJ ← -------------------------- //
class ValidadorCNPJ extends Validador {
    inicializar() {
        $(".input-validar-cnpj").on("input blur", event => {
            const campo = event.target;
            const cnpj = $(campo).val().trim();

            if (cnpj === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o CNPJ.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
                return;
            }

            if (!this.validar(cnpj)) {
                FeedbackVisual.mostrarErro(campo, "CNPJ inválido. Por favor, verifique.");
            } else {
                FeedbackVisual.mostrarSucesso(campo);
            }
        });
    }

    validar(cnpj) {
        if (!cnpj) {
            return false;
        }

        if (cnpj === "") {
            return false;
        }

        cnpj = cnpj.replace(/[^\d]+/g, "");

        if (cnpj === "" || cnpj.length !== 14) {
            return false;
        }

        if (/^(\d)\1+$/.test(cnpj)) {
            return false;
        }

        let tamanho = cnpj.length - 2;
        let numeros = cnpj.substring(0, tamanho);
        let digitos = cnpj.substring(tamanho);
        let soma = 0;
        let pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado !== parseInt(digitos.charAt(0))) {
            return false;
        }

        tamanho = tamanho + 1;
        numeros = cnpj.substring(0, tamanho);
        soma = 0;
        pos = tamanho - 7;

        for (let i = tamanho; i >= 1; i--) {
            soma += numeros.charAt(tamanho - i) * pos--;
            if (pos < 2) pos = 9;
        }

        resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
        if (resultado !== parseInt(digitos.charAt(1))) {
            return false;
        }

        return true;
    }
}

// -------------------------- → Validador CEP ← -------------------------- //
class ValidadorCEP extends Validador {
    inicializar() {
        $(".input-validar-cep").on("input blur", event => {
            const campo = event.target;
            const cep = $(campo).val().trim().replace(/\D/g, "");

            if (cep === "") {
                FeedbackVisual.limparFeedback(campo);
                return;
            }

            if (this.validar(cep)) {
                this.consultarCEP(cep, campo);
            } else {
                FeedbackVisual.mostrarErro(campo, "CEP inválido.");
            }
        });
    }

    /**
     *  Valida um CEP
     *  @param {String} cep - O CEP a ser validado
     *  @returns {Boolean} - Verdadeiro se o CEP for válido
     */
    validar(cep) {
        cep = cep.replace(/\D/g, "");
        return cep.length === 8;
    }

    /**
     *  Consulta um CEP na API ViaCEP
     *  @param {String} cep - O CEP a ser consultado
     *  @param {Element} campo - O campo que contém o CEP
     */
    // Modifique o método consultarCEP na classe ValidadorCEP
    consultarCEP(cep, campo) {
        // Selecionar os campos relacionados que devem ser desabilitados durante a consulta
        const camposRelacionados = [
            ".campo-logradouro",
            ".campo-bairro",
            ".campo-cidade",
            ".campo-estado",
            ".campo-numero",
        ];

        // Iniciar o feedback de carregamento
        const carregamento = FeedbackVisual.mostrarCarregamento(
            campo,
            camposRelacionados,
            "Consultando CEP...",
        );

        $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function (data) {
            if (!data.erro) {
                // Preencher os campos relacionados
                $(".campo-logradouro").val(data.logradouro);
                $(".campo-bairro").val(data.bairro);
                $(".campo-cidade").val(data.localidade);

                // Tratar o campo de estado corretamente
                const $estadoSelect = $(".campo-estado");

                // Mapa direto de UF para nome do estado
                const estados = {
                    AC: "Acre",
                    AL: "Alagoas",
                    AP: "Amapá",
                    AM: "Amazonas",
                    BA: "Bahia",
                    CE: "Ceará",
                    DF: "Distrito Federal",
                    ES: "Espírito Santo",
                    GO: "Goiás",
                    MA: "Maranhão",
                    MT: "Mato Grosso",
                    MS: "Mato Grosso do Sul",
                    MG: "Minas Gerais",
                    PA: "Pará",
                    PB: "Paraíba",
                    PR: "Paraná",
                    PE: "Pernambuco",
                    PI: "Piauí",
                    RJ: "Rio de Janeiro",
                    RN: "Rio Grande do Norte",
                    RS: "Rio Grande do Sul",
                    RO: "Rondônia",
                    RR: "Roraima",
                    SC: "Santa Catarina",
                    SP: "São Paulo",
                    SE: "Sergipe",
                    TO: "Tocantins",
                };

                $estadoSelect.val(estados[data.uf]);
                if ($estadoSelect.hasClass("select2")) {
                    $estadoSelect.trigger("change.select2");
                }

                // Garantir que todos os campos estejam habilitados para edição
                $(campo).prop("disabled", false);
                camposRelacionados.forEach(seletor => {
                    $(seletor).prop("disabled", false);
                });

                // Focar no campo número
                $(".campo-numero").focus();

                // Finalizar carregamento com sucesso
                carregamento.concluir(true, "CEP localizado com sucesso!");
            } else {
                // IMPORTANTE: Garantir que TODOS os campos sejam habilitados mesmo em caso de erro
                $(campo).prop("disabled", false);
                camposRelacionados.forEach(seletor => {
                    $(seletor).prop("disabled", false);
                });

                // Usar o método habilitarCampo para garantir que tudo funcione
                FeedbackVisual.habilitarCampo(campo, camposRelacionados);

                // Finalizar carregamento com erro
                carregamento.concluir(false, "CEP não encontrado.");
            }
        }).fail(function () {
            // IMPORTANTE: Garantir que TODOS os campos sejam habilitados mesmo em caso de erro de conexão
            $(campo).prop("disabled", false);
            camposRelacionados.forEach(seletor => {
                $(seletor).prop("disabled", false);
            });

            // Usar o método habilitarCampo para garantir que tudo funcione
            FeedbackVisual.habilitarCampo(campo, camposRelacionados);

            // Finalizar carregamento com erro de conexão
            carregamento.concluir(false, "Erro ao consultar CEP. Verifique sua conexão.");
        });
    }
}

// -------------------------- → Validador Número Endereço ← -------------------------- //
class ValidadorNumeroEndereco extends Validador {
    inicializar() {
        $(".input-validar-numero-endereco").on("input blur", event => {
            const campo = event.target;
            const valor = $(campo).val().trim();

            if (valor === "") {
                FeedbackVisual.limparFeedback(campo);
                return;
            }

            if (!this.validar(valor)) {
                FeedbackVisual.mostrarErro(campo, "O número deve começar com dígitos.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    /**
     *  Valida um número de endereço
     *  @param {String} valor - O valor a ser validado
     *  @returns {Boolean} - Verdadeiro se o valor for válido
     */
    validar(valor) {
        if (!valor) {
            return false;
        }

        if (valor === "") {
            return false;
        }

        return /^\d+.*$/.test(valor);
    }
}

/**
 *  Validações de Valores
 *  Nome, Data Nascimento, Data, Horário e Valor
 */
// -------------------------- → Validador Nome ← -------------------------- //
class ValidadorNome extends Validador {
    inicializar() {
        $(".input-validar-nome").on("input blur", event => {
            const campo = event.target;
            const nome = $(campo).val().trim();
            // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
            // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'-]+$/;

            if (nome === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o Nome.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
                return;
            }

            if (nome.length > 255) {
                FeedbackVisual.mostrarErro(campo, "O nome deve ser menor que 255 caracteres.");
            } else if (nome.length < 3) {
                FeedbackVisual.mostrarErro(campo, "O nome deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    /**
     *  Valida um nome
     *  @param {String} nome - O nome a ser validado
     *  @returns {Boolean} - Verdadeiro se o nome for válido
     */
    validar(nome) {
        if (!nome || nome === "" || nome.length < 3 || nome.length > 255) {
            return false;
        }

        // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
        // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'-]+$/;
        //if (!regexNome.test(nome)) {
        //return false;
        //}

        return true;
    }
}

// -------------------------- → Validador Data Nascimento ← -------------------------- //
class ValidadorDataNascimento extends Validador {
    inicializar() {
        $(".input-validar-data-nascimento").on("input blur", event => {
            const campo = event.target;
            const data = $(campo).val().trim();

            if (data === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Data de nascimento é obrigatória.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
                return;
            }

            if (!this.validar(data)) {
                FeedbackVisual.mostrarErro(campo, "Data de nascimento inválida.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    /**
     *  Valida uma data de nascimento
     *  @param {String} data - A data a ser validada (formato YYYY-MM-DD)
     *  @returns {Boolean} - Verdadeiro se a data for válida
     */
    validar(data) {
        if (!data) {
            return false;
        }

        if (data === "") {
            return false;
        }

        const hoje = new Date();
        const dataNascimento = new Date(data);

        if (isNaN(dataNascimento.getTime())) {
            return false;
        }

        if (dataNascimento > hoje) {
            return false;
        }

        const idadeMaxima = new Date();
        idadeMaxima.setFullYear(hoje.getFullYear() - 120);
        if (dataNascimento < idadeMaxima) {
            return false;
        }

        return true;
    }
}

// -------------------------- → Validador Data ← -------------------------- //
class ValidadorData extends Validador {
    inicializar() {
        $(".input-validar-data").on("input blur", event => {
            const campo = event.target;
            const data = $(campo).val().trim();

            // Limpa qualquer feedback anterior
            FeedbackVisual.limparFeedback(campo);

            // Verifica se é campo obrigatório e está vazio
            if (data === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe a Data.");
                }
                return;
            }

            // Verifica se é data antiga (apenas se existir data)
            if ($(campo).hasClass("validar-data-antiga")) {
                const hoje = moment().startOf("day");
                const dataDigitada = moment(data);

                if (dataDigitada.isValid() && dataDigitada.isBefore(hoje)) {
                    FeedbackVisual.mostrarAviso(campo, "A data informada é anterior à data atual.");
                }
            }
        });
    }

    validar(data) {
        if (!data || data.trim() === "") {
            return false;
        }

        return moment(data).isValid();
    }
}

// -------------------------- → Validador Horário ← -------------------------- //
class ValidadorHorario extends Validador {
    inicializar() {
        $(".input-validar-horario").on("input blur", event => {
            const campo = event.target;
            const horario = $(campo).val().trim();

            if (horario === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informar o Horário.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(horario) {
        if (!horario) {
            return false;
        }

        return horario.trim() !== "";
    }
}

// -------------------------- → Validador Valor ← -------------------------- //
class ValidadorValor extends Validador {
    inicializar() {
        $(".input-validar-valor").on("input blur", event => {
            const campo = event.target;
            const valor = $(campo).val().trim();

            if (valor === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informar o Valor.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(valor) {
        if (!valor) {
            return false;
        }

        return valor.trim() !== "";
    }
}

/**
 *  Validações de Número de Celular e WhatsApp
 *  Celular e WhatsApp
 */
// -------------------------- → Validador WhatsApp ← -------------------------- //
class ValidadorWhatsapp extends Validador {
    constructor() {
        super();

        this.configuracoesPorPais = {
            "+55": {
                nome: "Brasil",
                minDigitos: 11,
                maxDigitos: 11,
            },
            "+1": {
                nome: "Estados Unidos",
                minDigitos: 10,
                maxDigitos: 10,
            },
            "+351": { nome: "Portugal", minDigitos: 9, maxDigitos: 9 },
            "+44": { nome: "Reino Unido", minDigitos: 10, maxDigitos: 10 },
        };
    }

    inicializar() {
        $(".input-validar-whatsapp").each((index, campo) => {
            const $campo = $(campo);
            const $grupoInput = $campo.closest(".input-group");
            const $selectPais = $grupoInput.find('select[name="pais"]');

            if ($selectPais.length > 0) {
                this.inicializarCampoInternacional($campo, $selectPais);
            } else {
                this.inicializarCampoPadrao($campo);
            }
        });
    }

    inicializarCampoInternacional($campo, $selectPais) {
        $campo.on("input blur", () => {
            const whatsapp = $campo.val().trim();
            const codigoPais = $selectPais.val();

            if (whatsapp === "") {
                FeedbackVisual.limparFeedback($campo[0]);
                return;
            }

            if (!this.validarPorPais(whatsapp, codigoPais)) {
                const mensagem = this.obterMensagemErro(codigoPais);
                FeedbackVisual.mostrarErro($campo[0], mensagem);
            } else {
                FeedbackVisual.limparFeedback($campo[0]);
            }
        });

        $selectPais.on("change", () => {
            $campo.val("");
            FeedbackVisual.limparFeedback($campo[0]);
        });
    }

    inicializarCampoPadrao($campo) {
        $campo.on("input blur", () => {
            const whatsapp = $campo.val().trim();

            if (whatsapp === "") {
                if ($campo.hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro($campo[0], "Por favor, informe o número de WhatsApp");
                } else {
                    FeedbackVisual.limparFeedback($campo[0]);
                }
                return;
            }

            if (!this.validar(whatsapp)) {
                FeedbackVisual.mostrarErro($campo[0], "Número de WhatsApp inválido.");
            } else {
                FeedbackVisual.limparFeedback($campo[0]);
            }
        });
    }

    validar(whatsapp) {
        const numeroLimpo = whatsapp.replace(/[^\d]+/g, "");
        return this.validarPorPais(numeroLimpo, "+55");
    }

    validarPorPais(whatsapp, codigoPais) {
        const numeroLimpo =
            typeof whatsapp === "string" ? whatsapp.replace(/[^\d]+/g, "") : whatsapp.toString();

        const config = this.configuracoesPorPais[codigoPais] || {
            minDigitos: 8,
            maxDigitos: 15,
            nome: "Outro País",
        };

        const dentroDoLimite =
            numeroLimpo.length >= config.minDigitos && numeroLimpo.length <= config.maxDigitos;

        if (config.formatoRegex && dentroDoLimite) {
            return config.formatoRegex.test(numeroLimpo);
        }

        return dentroDoLimite;
    }

    obterMensagemErro(codigoPais) {
        const config = this.configuracoesPorPais[codigoPais];

        if (config) {
            if (config.minDigitos === config.maxDigitos) {
                return `Número deve ter exatamente ${config.minDigitos} dígitos para ${config.nome}.`;
            } else {
                return `Número deve ter entre ${config.minDigitos} e ${config.maxDigitos} dígitos para ${config.nome}.`;
            }
        }

        return `Número de WhatsApp inválido para ${codigoPais}.`;
    }
}

/**
 *  Validações de Campos de Login
 *  E-mail e Senha
 */
// -------------------------- → Validador Email ← -------------------------- //
class ValidadorEmail extends Validador {
    inicializar() {
        $(".input-validar-email").on("input blur", event => {
            const campo = event.target;
            const email = $(campo).val().trim();

            if (email === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o E-mail.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (email.length > 255) {
                FeedbackVisual.mostrarErro(campo, "E-mail deve ser menor que 255 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    /**
     *  Valida um email
     *  @param {String} email - O email a ser validado
     *  @returns {Boolean} - Verdadeiro se o email for válido
     */
    validar(email) {
        if (!email) {
            return false;
        }

        if (email === "") {
            return false;
        }

        let regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return regex.test(email);
    }
}

// -------------------------- → Validador Senha ← -------------------------- //
class ValidadorSenha extends Validador {
    inicializar() {
        $(".input-validar-senha").on("input blur", event => {
            const campo = event.target;
            const senha = $(campo).val().trim();

            if (senha === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe a Senha.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (senha.length < 3) {
                FeedbackVisual.mostrarErro(campo, "Senha deve ser maior que 3 caracteres.");
            } else if (senha.length > 255) {
                FeedbackVisual.mostrarErro(campo, "Senha deve ser menor que 255 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });

        $(".input-validar-senha-login").on("input blur", event => {
            const campo = event.target;
            const senha = $(campo).val().trim();

            if (senha === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe a Senha.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    /**
     *  Valida um senha
     *  @param {String} senha - O senha a ser validado
     *  @returns {Boolean} - Verdadeiro se o senha for válido
     */
    validar(senha, validarMinimoCaracteres = true) {
        if (!senha) {
            return false;
        }

        if (senha === "") {
            return false;
        }

        if (validarMinimoCaracteres) {
            return senha.trim().length >= 3;
        } else {
            return true;
        }
    }
}

/**
 *  Validações das Informações de Empresas
 *  Razão Social, Nome Fantasia, Nome Empresa e Responsável
 */
// -------------------------- → Validador Razão Social ← -------------------------- //
class ValidadorRazaoSocial extends Validador {
    inicializar() {
        $(".input-validar-razao-social").on("input blur", event => {
            const campo = event.target;
            const razaoSocial = $(campo).val().trim();
            // const regexRazaoSocial = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()-]+$/;

            if (razaoSocial === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Razão Social é obrigatória.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (razaoSocial.length > 255) {
                FeedbackVisual.mostrarErro(campo, "Razão Social deve ser menor que 255 caracteres.");
            } else if (razaoSocial.length < 3) {
                FeedbackVisual.mostrarErro(campo, "Razão Social deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(razaoSocial) {
        if (!razaoSocial) {
            return false;
        }

        if (razaoSocial === "") {
            return false;
        }

        razaoSocial = razaoSocial.trim();

        if (razaoSocial.length < 3) {
            return false;
        }

        if (razaoSocial.length > 255) {
            return false;
        }

        // const regexRazaoSocial = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()-]+$/;
        // if (!regexRazaoSocial.test(razaoSocial)) {
        //     return false;
        // }

        return true;
    }
}

// -------------------------- → Validador Nome Fantasia ← -------------------------- //
class ValidadorNomeFantasia extends Validador {
    inicializar() {
        $(".input-validar-nome-fantasia").on("input blur", event => {
            const campo = event.target;
            const nomeFantasia = $(campo).val().trim();
            // const regexNomeFantasia = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()!@#$%*+-]+$/;

            if (nomeFantasia === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o Nome Fantasia.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (nomeFantasia.length > 255) {
                FeedbackVisual.mostrarErro(campo, "Nome Fantasia deve ser menor que 255 caracteres.");
            } else if (nomeFantasia.length < 3) {
                FeedbackVisual.mostrarErro(campo, "Nome Fantasia deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(nomeFantasia) {
        if (!nomeFantasia) {
            return false;
        }

        if (nomeFantasia === "") {
            return false;
        }

        nomeFantasia = nomeFantasia.trim();

        if (nomeFantasia.length < 3) {
            return false;
        }

        if (nomeFantasia.length > 255) {
            return false;
        }

        // const regexNomeFantasia = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()!@#$%*+-]+$/;
        // if (!regexNomeFantasia.test(nomeFantasia)) {
        //     return false;
        // }

        return true;
    }
}

// -------------------------- → Validador Nome Empresa ← -------------------------- //
class ValidadorNomeEmpresa extends Validador {
    inicializar() {
        $(".input-validar-nome-empresa").on("input blur", event => {
            const campo = event.target;
            const nomeEmpresa = $(campo).val().trim();
            // const regexNomeEmpresa = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()!@#$%*+-]+$/;

            if (nomeEmpresa === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o Nome da Empresa.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (nomeEmpresa.length > 255) {
                FeedbackVisual.mostrarErro(campo, "Nome da Empresa deve ser menor que 255 caracteres.");
            } else if (nomeEmpresa.length < 3) {
                FeedbackVisual.mostrarErro(campo, "Nome da Empresa deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(nomeEmpresa) {
        if (!nomeEmpresa) {
            return false;
        }

        nomeEmpresa = nomeEmpresa.trim();

        if (nomeEmpresa.length < 3) {
            return false;
        }

        if (nomeEmpresa.length > 255) {
            return false;
        }

        // const regexNomeEmpresa = /^[A-Za-zÀ-ÖØ-öø-ÿ0-9\s'.&()!@#$%*+-]+$/;
        // if (!regexNomeEmpresa.test(nomeEmpresa)) {
        //     return false;
        // }

        return true;
    }
}

// -------------------------- → Validador Responsável ← -------------------------- //
class ValidadorResponsavel extends Validador {
    inicializar() {
        $(".input-validar-responsavel").on("input blur", event => {
            const campo = event.target;
            const responsavel = $(campo).val().trim();
            // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;

            if (responsavel === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe o Nome do responsável.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (responsavel.length > 255) {
                FeedbackVisual.mostrarErro(campo, "Nome do responsável deve ser menor que 255 caracteres.");
            } else if (responsavel.length < 3) {
                FeedbackVisual.mostrarErro(campo, "Nome do responsável deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(responsavel) {
        if (
            typeof window.validadorNome !== "undefined" &&
            typeof window.validadorNome.validar === "function"
        ) {
            return window.validadorNome.validar(responsavel);
        }

        if (!responsavel) {
            return false;
        }

        responsavel = responsavel.trim();

        if (responsavel.length < 3) {
            return false;
        }

        if (responsavel.length > 255) {
            return false;
        }

        // const regexNome = /^[A-Za-zÀ-ÖØ-öø-ÿ\s'-]+$/;
        return true;
    }
}

/**
 *  Validações de Campos Diversos
 *  Select Vazio, Select Selecionado, Radio, TextArea, CheckBox e Input
 */
// -------------------------- → Validador Select Vazio ← -------------------------- //
class ValidadorSelect extends Validador {
    inicializar() {
        $(".input-validar-select").each((index, elemento) => {
            const $select = $(elemento);
            let primeiraInteracao = true;

            // Para compatibilidade com Select2
            if ($select.hasClass("select2")) {
                // Eventos de seleção
                $select.on("select2:select", () => {
                    FeedbackVisual.limparFeedback($select[0]);
                });

                // Ao abrir o dropdown - não validar neste momento
                $select.on("select2:opening", () => {
                    primeiraInteracao = false;
                });

                // Só validar quando o dropdown for fechado E não for a primeira interação
                $select.on("select2:close", () => {
                    if (!primeiraInteracao) {
                        setTimeout(() => this.verificarSelect($select), 100);
                    }
                });

                // Remover qualquer mensagem de erro ao abrir o dropdown
                $select.on("select2:opening", () => {
                    FeedbackVisual.limparFeedback($select[0]);
                });
            } else {
                // Para selects normais
                $select.on("change", () => {
                    const valor = $select.val();
                    if (valor && valor !== "" && valor !== "CADASTRE") {
                        FeedbackVisual.limparFeedback($select[0]);
                    } else {
                        this.verificarSelect($select);
                    }
                });

                $select.on("focus", () => {
                    primeiraInteracao = false;
                });

                $select.on("blur", () => {
                    if (!primeiraInteracao) {
                        this.verificarSelect($select);
                    }
                });
            }
        });
    }

    verificarSelect($select) {
        const valor = $select.val();
        const campo = $select[0];

        // Se estiver aberto, não validar
        if ($select.hasClass("select2") && $select.data("select2") && $select.data("select2").isOpen()) {
            return;
        }

        if (!valor || valor === "") {
            if ($select.hasClass(this.campoObrigatorio)) {
                FeedbackVisual.mostrarErro(campo, "Por favor, selecione uma opção.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        } else if (valor === "CADASTRE") {
            FeedbackVisual.mostrarErro(campo, "Cadastre uma opção antes de continuar.");
            return;
        } else {
            FeedbackVisual.limparFeedback(campo);
        }
    }

    validar(select) {
        if (!select) return false;
        if (select === "CADASTRE") return false;
        return select.toString().trim() !== "";
    }
}

// -------------------------- → Validador Select Selecionado ← -------------------------- //
class ValidadorSelectSelecionado extends Validador {
    inicializar() {
        $(".input-validar-select-selecionado").each((index, elemento) => {
            const $select = $(elemento);
            let primeiraInteracao = true;

            // Para compatibilidade com Select2
            if ($select.hasClass("select2")) {
                // Eventos de seleção
                $select.on("select2:select", () => {
                    this.verificarSelectSelecionado($select);
                });

                // Ao abrir o dropdown - não validar neste momento
                $select.on("select2:opening", () => {
                    primeiraInteracao = false;
                });

                // Validar quando o dropdown for fechado E não for a primeira interação
                $select.on("select2:close", () => {
                    if (!primeiraInteracao) {
                        setTimeout(() => this.verificarSelectSelecionado($select), 100);
                    }
                });

                // Limpar mensagem de erro ao abrir o dropdown
                $select.on("select2:opening", () => {
                    FeedbackVisual.limparFeedback($select[0]);
                });
            } else {
                // Para selects normais
                $select.on("change", () => {
                    const valor = $select.val();
                    if (!valor || valor === "") {
                        FeedbackVisual.limparFeedback($select[0]);
                    } else {
                        this.verificarSelectSelecionado($select);
                    }
                });

                $select.on("focus", () => {
                    primeiraInteracao = false;
                });

                $select.on("blur", () => {
                    if (!primeiraInteracao) {
                        this.verificarSelectSelecionado($select);
                    }
                });
            }
        });
    }

    verificarSelectSelecionado($select) {
        const valor = $select.val();
        const campo = $select[0];

        // Se estiver aberto, não validar
        if ($select.hasClass("select2") && $select.data("select2") && $select.data("select2").isOpen()) {
            return;
        }

        if (valor && valor !== "" && valor !== "NULL") {
            if ($select.hasClass(this.campoObrigatorio)) {
                FeedbackVisual.mostrarErro(campo, "Adicione o item antes de continuar.");
            } else {
                FeedbackVisual.mostrarErro(campo, "Adicione o item antes de continuar.");
            }
        } else {
            FeedbackVisual.limparFeedback(campo);
        }
    }

    validar(select) {
        if (!select) return true;
        if (select === "CADASTRE") return false;
        return select.toString().trim() === "";
    }
}

// -------------------------- → Validador Radio ← -------------------------- //
class ValidadorRadio extends Validador {
    inicializar() {
        $(".input-validar-radio").each((index, radioGroup) => {
            const $radioGroup = $(radioGroup);
            const name = $radioGroup.data("name");

            if (!name) return;

            $(`input[name="${name}"]`).on("change blur", () => {
                const selecionado = $(`input[name="${name}"]:checked`).length > 0;

                if (!selecionado && $radioGroup.hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro($radioGroup, "Por favor, selecione uma opção.");
                } else {
                    FeedbackVisual.limparFeedback($radioGroup);
                }
            });
        });
    }

    validar(name) {
        if (!name) return false;

        return $(`input[name="${name}"]:checked`).length > 0;
    }
}

// -------------------------- → Validador TextArea ← -------------------------- //
class ValidadorTextArea extends Validador {
    inicializar() {
        $(".input-validar-textarea").on("input blur", event => {
            const campo = event.target;
            const textArea = $(campo).val().trim();

            if (textArea === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe alguma informação.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (textArea.length > 3000) {
                FeedbackVisual.mostrarErro(campo, "O campo deve ser menor de 3000 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(textArea) {
        if (!textArea) {
            return false;
        }

        textArea = textArea.trim();

        if (textArea.length > 3000) {
            return false;
        }

        return true;
    }
}

// -------------------------- → Validador Checkbox ← -------------------------- //
class ValidadorCheckbox extends Validador {
    inicializar() {
        // Monitorar alterações nos grupos de checkbox quando necessário
        $(".input-validar-checkbox").each((index, checkboxGroup) => {
            const $checkboxGroup = $(checkboxGroup);
            const groupName = $checkboxGroup.data("group");

            if (!groupName) return;

            $(`input[name="${groupName}"]`).on("change", () => {
                this.verificarGrupoCheckbox($checkboxGroup, groupName);
            });

            // Também verificar ao perder o foco do último checkbox do grupo
            $(`input[name="${groupName}"]:last`).on("blur", () => {
                this.verificarGrupoCheckbox($checkboxGroup, groupName);
            });
        });

        // Para os validadores que usam o campo hidden
        $(".checkbox-validator").each((index, validator) => {
            const $validator = $(validator);
            const groupName = $validator.data("group");

            if (!groupName) return;

            $(`input[name="${groupName}"]`).on("change", () => {
                const isValid = this.validar(groupName);

                if (isValid) {
                    $validator.siblings(".error-message").remove();
                }
            });
        });
    }

    verificarGrupoCheckbox($checkboxGroup, groupName) {
        const isValid = this.validar(groupName);

        if (!isValid && $checkboxGroup.hasClass(this.campoObrigatorio)) {
            // Se o grupo já tem um container personalizado para mensagem de erro
            if ($checkboxGroup.find(".feedback-container").length > 0) {
                const $feedbackContainer = $checkboxGroup.find(".feedback-container");
                $feedbackContainer.html(
                    '<div class="invalid-feedback error-message d-block">Selecione pelo menos uma opção.</div>',
                );
            } else {
                // Se ainda não tem erro
                if ($checkboxGroup.find(".error-message").length === 0) {
                    $checkboxGroup.append(
                        '<div class="invalid-feedback error-message d-block">Selecione pelo menos uma opção.</div>',
                    );
                }
            }
        } else {
            $checkboxGroup.find(".error-message").remove();
        }
    }

    /**
     * Valida se pelo menos um checkbox em um grupo está marcado
     * @param {String} groupName - Nome do grupo de checkboxes (com [] para arrays)
     * @returns {Boolean} - Verdadeiro se pelo menos um checkbox estiver marcado
     */
    validar(groupName) {
        if (!groupName) return false;

        return $(`input[name="${groupName}"]:checked`).length > 0;
    }

    /**
     * Valida todos os grupos de checkboxes em um formulário
     * @param {String} formSelector - Seletor do formulário
     * @returns {Boolean} - Verdadeiro se todos os grupos obrigatórios tiverem pelo menos um checkbox marcado
     */
    validarTodosGrupos(formSelector = "form") {
        let todosValidos = true;

        // Validar grupos com classe específica
        $(`${formSelector} .input-validar-checkbox.campo-obrigatorio`).each((index, group) => {
            const $group = $(group);
            const groupName = $group.data("group");

            if (!groupName) return true; // continuar

            if (!this.validar(groupName)) {
                this.verificarGrupoCheckbox($group, groupName);
                todosValidos = false;
            }
        });

        return todosValidos;
    }
}

// -------------------------- → Validador Input ← -------------------------- //
class ValidadorInput extends Validador {
    inicializar() {
        $(".input-validar-input").on("input blur", event => {
            const campo = event.target;
            const palavra = $(campo).val().trim();

            if (palavra === "") {
                if ($(campo).hasClass(this.campoObrigatorio)) {
                    FeedbackVisual.mostrarErro(campo, "Por favor, informe alguma informação.");
                } else {
                    FeedbackVisual.limparFeedback(campo);
                }
            } else if (palavra.length > 255) {
                FeedbackVisual.mostrarErro(campo, "O campo deve ser menor que 255 caracteres.");
            } else if (palavra.length < 3) {
                FeedbackVisual.mostrarErro(campo, "O campo deve ter pelo menos 3 caracteres.");
            } else {
                FeedbackVisual.limparFeedback(campo);
            }
        });
    }

    validar(palavra) {
        if (!palavra) {
            return false;
        }

        palavra = palavra.trim();

        if (palavra.length > 255) {
            return false;
        }

        if (palavra.length < 3) {
            return false;
        }

        return true;
    }
}

/**
 *  Criação dos Validadores
 *  Cria os validadores
 */
// -------------------------- → Cria Validadores ← -------------------------- //
$(function () {
    function criarValidadores() {
        // Validações de documentos
        window.validadorCPF = new ValidadorCPF();
        window.validadorCNPJ = new ValidadorCNPJ();
        window.validadorCEP = new ValidadorCEP();
        window.validadorNumeroEndereco = new ValidadorNumeroEndereco();

        // Validações de Valores
        window.validadorNome = new ValidadorNome();
        window.validadorDataNascimento = new ValidadorDataNascimento();
        window.validadorData = new ValidadorData();
        window.validadorHorario = new ValidadorHorario();
        window.validadorValor = new ValidadorValor();

        // Validações de Número de Celular e WhatsApp
        window.validadorWhatsapp = new ValidadorWhatsapp();

        // Validações de Campos de Login
        window.validadorEmail = new ValidadorEmail();
        window.validadorSenha = new ValidadorSenha();

        // Validações das Informações de empresas
        window.validadorRazaoSocial = new ValidadorRazaoSocial();
        window.validadorNomeFantasia = new ValidadorNomeFantasia();
        window.validadorNomeEmpresa = new ValidadorNomeEmpresa();
        window.validadorResponsavel = new ValidadorResponsavel();

        // Validações de Campos Diversos
        window.validadorSelect = new ValidadorSelect();
        window.validadorSelectSelecionado = new ValidadorSelectSelecionado();
        window.validadorRadio = new ValidadorRadio();
        window.validadorTextArea = new ValidadorTextArea();
        window.validadorCheckbox = new ValidadorCheckbox();
        window.validadorInput = new ValidadorInput();
    }

    criarValidadores();

    Object.keys(window).forEach(key => {
        if (key.startsWith("validador") && window[key] === null) {
            console.warn(`Validador ${key} não pode ser inicializado.`);
        }
    });

    /**
     * Reinicializa todos os validadores para elementos criados dinamicamente
     * Pode ser chamada de qualquer lugar do projeto
     */
    function reinicializarValidadores() {
        try {
            // Validações de documentos
            if (typeof window.validadorCPF !== "undefined") {
                window.validadorCPF.inicializar();
            }
            if (typeof window.validadorCNPJ !== "undefined") {
                window.validadorCNPJ.inicializar();
            }
            if (typeof window.validadorCEP !== "undefined") {
                window.validadorCEP.inicializar();
            }
            if (typeof window.validadorNumeroEndereco !== "undefined") {
                window.validadorNumeroEndereco.inicializar();
            }

            // Validações de Valores
            if (typeof window.validadorNome !== "undefined") {
                window.validadorNome.inicializar();
            }
            if (typeof window.validadorDataNascimento !== "undefined") {
                window.validadorDataNascimento.inicializar();
            }
            if (typeof window.validadorData !== "undefined") {
                window.validadorData.inicializar();
            }
            if (typeof window.validadorHorario !== "undefined") {
                window.validadorHorario.inicializar();
            }
            if (typeof window.validadorValor !== "undefined") {
                window.validadorValor.inicializar();
            }

            // Validações de Número de Celular e WhatsApp
            if (typeof window.validadorWhatsapp !== "undefined") {
                window.validadorWhatsapp.inicializar();
            }

            // Validações de Campos de Login
            if (typeof window.validadorEmail !== "undefined") {
                window.validadorEmail.inicializar();
            }
            if (typeof window.validadorSenha !== "undefined") {
                window.validadorSenha.inicializar();
            }

            // Validações das Informações de empresas
            if (typeof window.validadorRazaoSocial !== "undefined") {
                window.validadorRazaoSocial.inicializar();
            }
            if (typeof window.validadorNomeFantasia !== "undefined") {
                window.validadorNomeFantasia.inicializar();
            }
            if (typeof window.validadorNomeEmpresa !== "undefined") {
                window.validadorNomeEmpresa.inicializar();
            }
            if (typeof window.validadorResponsavel !== "undefined") {
                window.validadorResponsavel.inicializar();
            }

            // Validações de Campos Diversos
            if (typeof window.validadorSelect !== "undefined") {
                window.validadorSelect.inicializar();
            }
            if (typeof window.validadorSelectSelecionado !== "undefined") {
                window.validadorSelectSelecionado.inicializar();
            }
            if (typeof window.validadorRadio !== "undefined") {
                window.validadorRadio.inicializar();
            }
            if (typeof window.validadorTextArea !== "undefined") {
                window.validadorTextArea.inicializar();
            }
            if (typeof window.validadorCheckbox !== "undefined") {
                window.validadorCheckbox.inicializar();
            }
            if (typeof window.validadorInput !== "undefined") {
                window.validadorInput.inicializar();
            }
        } catch (error) {
            console.warn("Erro ao reinicializar validadores:", error);
        }
    }

    // Adicionar no final do $(function () { ... }) no validador.js:
    window.reinicializarValidadores = reinicializarValidadores;

    // Não inicializa mais os gerenciadores de formulário aqui
    // Isso será feito no cliente.js

    // $("#observacao").before('<span class="caracteres-contador small text-muted">3000 caracteres restantes</span>');

    // $("#observacao").on("input", function () {
    //     let maxLength = 3000;
    //     let currentLength = $(this).val().length;
    //     let remaining = maxLength - currentLength;

    //     $(".caracteres-contador").text(remaining + " caracteres restantes");

    //     if (remaining < 100) {
    //         $(".caracteres-contador").removeClass("text-muted").addClass("text-danger");
    //     } else {
    //         $(".caracteres-contador").removeClass("text-danger").addClass("text-muted");
    //     }
    // });
});
