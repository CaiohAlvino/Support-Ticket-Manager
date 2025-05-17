$(document).ready(function () {
    const dadosOriginaisFornecedor = {
        nome_fantasia: $("#nome_fantasia_fornecedor").val() || $("#nome_fantasia_pj_fornecedor").val(),
        razao_social: $("#razao_social_fornecedor").val(),
        documento: $("#documento_fornecedor").val(),
        telefone: $("#telefone_pj_fornecedor").val(),
        responsavel: $("#responsavel_fornecedor").val(),
        responsavel_whats: $("#responsavel_whatsapp_fornecedor").val(),
        responsavel_documento: $("#responsavel_documento_fornecedor").val(),
        responsavel_email: $("#responsavel_email_fornecedor").val(),
    };

    function atualizarTipoFormularioFornecedor(tipo) {
        if (tipo === "CNPJ") {
            FeedbackVisual.limparTodosFeedbacks("#sessao-cnpj-fornecedor");
            $(".campo-pf-fornecedor").hide();
            $(".campo-pj-fornecedor").show();

            // Sincroniza os valores entre os campos nome_fantasia
            if ($("#nome_fantasia_pj_fornecedor").val()) {
                $("#nome_fantasia_fornecedor").val($("#nome_fantasia_pj_fornecedor").val());
            }

            if ($("#telefone_fornecedor").val()) {
                $("#telefone_pj_fornecedor").val($("#telefone_fornecedor").val());
            }

            $(".dados-titulo-fornecedor").html("Dados do Responsável");
            $(".dados-label-nome-fornecedor").html("Nome do Responsável <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-documento-fornecedor").html("CPF do Responsável <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-whatsapp-fornecedor").html("WhatsApp do Responsável");
            $(".dados-label-email-fornecedor").html("E-mail do Responsável");

            if (dadosOriginaisFornecedor.razao_social) {
                $("#razao_social").val(dadosOriginaisFornecedor.razao_social);
            }
            if (dadosOriginaisFornecedor.nome_fantasia) {
                $("#nome_fantasia_pj").val(dadosOriginaisFornecedor.nome_fantasia);
            }
            if (dadosOriginaisFornecedor.documento && dadosOriginaisFornecedor.documento.length > 11) {
                $("#documento").val(dadosOriginaisFornecedor.documento);
            }
        } else {
            FeedbackVisual.limparTodosFeedbacks("#sessao-pf-fornecedor");
            $(".campo-pj-fornecedor").hide();
            $(".campo-pf-fornecedor").show();

            // Sincroniza os valores entre os campos nome_fantasia
            if ($("#nome_fantasia_fornecedor").val()) {
                $("#nome_fantasia_pj_fornecedor").val($("#nome_fantasia_fornecedor").val());
            }

            if ($("#telefone_pj_fornecedor").val()) {
                $("#telefone_fornecedor").val($("#telefone_pj_fornecedor").val());
            }

            $(".dados-titulo-fornecedor").html("Dados Pessoais");
            $(".dados-label-nome-fornecedor").html("Nome Completo <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-documento-fornecedor").html("CPF <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-whatsapp-fornecedor").html("WhatsApp");
            $(".dados-label-email-fornecedor").html("E-mail");
        }
    }

    // Sincroniza os valores dos campos nome_fantasia
    $("#nome_fantasia_fornecedor, #nome_fantasia_pj_fornecedor").on("input", function () {
        const valor = $(this).val();
        if ($(this).attr("id") === "nome_fantasia_fornecedor") {
            $("#nome_fantasia_pj_fornecedor").val(valor);
        } else {
            $("#nome_fantasia_fornecedor").val(valor);
        }
    });

    $("#telefone_fornecedor, #telefone_pj_fornecedor").on("input", function () {
        const valor = $(this).val();
        if ($(this).attr("id") === "telefone_fornecedor") {
            $("#telefone_pj_fornecedor").val(valor);
        } else {
            $("#telefone_fornecedor").val(valor);
        }
    });

    // Inicializa o tipo de formulário
    atualizarTipoFormularioFornecedor($("#tipo-fornecedor").val());

    $("#tipo-fornecedor").change(function () {
        const tipo = $(this).val();
        atualizarTipoFormularioFornecedor(tipo);
    });

    $("#fornecedor-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#fornecedor-cadastrar");

        let isValid = true;
        let action = $(this).data("action");
        const tipo = $("#tipo-fornecedor").val();

        // Validações específicas para CNPJ
        if (tipo === "CNPJ") {
            let razaoSocial = $("#razao_social_fornecedor").val().trim();
            if (razaoSocial === "") {
                FeedbackVisual.mostrarErro($("#razao_social_fornecedor"), "Por favor, informe a Razão Social.");
                isValid = false;
            } else if (typeof window.validadorRazaoSocial !== "undefined" &&
                !window.validadorRazaoSocial.validar(razaoSocial)) {
                isValid = false;
            }

            let nomeFantasia = $("#nome_fantasia_pj_fornecedor").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_pj_fornecedor"), "Por favor, preencha o Nome Fantasia.");
                isValid = false;
            } else if (typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)) {
                isValid = false;
            }

            let documento = $("#documento_fornecedor").val().trim();
            if (documento === "") {
                FeedbackVisual.mostrarErro($("#documento_fornecedor"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorCNPJ !== "undefined" && !window.validadorCNPJ.validar(documento)) {
                FeedbackVisual.mostrarErro($("#documento_fornecedor"), "CNPJ inválido. Por favor, verifique.");
                isValid = false;
            }

        } else {
            // Validações para CPF
            let nomeFantasia = $("#nome_fantasia_fornecedor").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_fornecedor"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)) {
                isValid = false;
            }
        }

        // Validações de responsável
        let responsavel = $("#responsavel_fornecedor").val().trim();
        if (responsavel === "") {
            FeedbackVisual.mostrarErro($("#responsavel_fornecedor"), (tipo === "CNPJ" ? "Por favor, informe o Nome do responsável." : "Por favor, informe seu Nome."));
            isValid = false;
        } else if (typeof window.validadorResponsavel !== "undefined" &&
            !window.validadorResponsavel.validar(responsavel)) {
            isValid = false;
        }

        let responsavelDocumento = $("#responsavel_documento_fornecedor").val().trim();
        if (responsavelDocumento === "") {
            FeedbackVisual.mostrarErro($("#responsavel_documento_fornecedor"), (tipo === "CNPJ" ? "Por favor, informe o CPF do responsável." : "Por favor, informe seu CPF."));
            isValid = false;
        } else if (typeof window.validadorCPF !== "undefined" &&
            !window.validadorCPF.validar(responsavelDocumento)) {
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `../../executar/${action}`,
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function (response) {
                    NotyE.exception({ response });
                },
                error: function (xhr, status, error) {
                    NotyE.exception({ error: true, xhr });
                }
            });
        }
    });

    $("#fornecedor-edicao").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#fornecedor-edicao");

        let isValid = true;
        let action = $(this).data("action");
        const tipo = $("#tipo-fornecedor").val();

        // Validações específicas para CNPJ (similar ao cadastro)
        if (tipo === "CNPJ") {
            let razaoSocial = $("#razao_social_fornecedor").val().trim();
            if (razaoSocial === "") {
                FeedbackVisual.mostrarErro($("#razao_social_fornecedor"), "Por favor, informe a Razão Social.");
                isValid = false;
            } else if (typeof window.validadorRazaoSocial !== "undefined" &&
                !window.validadorRazaoSocial.validar(razaoSocial)) {
                isValid = false;
            }

            let nomeFantasia = $("#nome_fantasia_pj_fornecedor").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_pj_fornecedor"), "Por favor, preencha o Nome Fantasia.");
                isValid = false;
            } else if (typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)) {
                isValid = false;
            }

            let documento = $("#documento_fornecedor").val().trim();
            if (documento === "") {
                FeedbackVisual.mostrarErro($("#documento_fornecedor"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorCNPJ !== "undefined" &&
                !window.validadorCNPJ.validar(documento)) {
                isValid = false;
            }

        } else {
            let nomeFantasia = $("#nome_fantasia_fornecedor").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_fornecedor"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)) {
                isValid = false;
            }
        }

        // Validações de responsável
        let responsavel = $("#responsavel_fornecedor").val().trim();
        if (responsavel === "") {
            FeedbackVisual.mostrarErro($("#responsavel_fornecedor"), (tipo === "CNPJ" ? "Por favor, informe o Nome do responsável." : "Por favor, informe seu Nome."));
            isValid = false;
        } else if (typeof window.validadorResponsavel !== "undefined" &&
            !window.validadorResponsavel.validar(responsavel)) {
            isValid = false;
        }

        let responsavelDocumento = $("#responsavel_documento_fornecedor").val().trim();
        if (responsavelDocumento === "") {
            FeedbackVisual.mostrarErro($("#responsavel_documento_fornecedor"), (tipo === "CNPJ" ? "Por favor, informe o CPF do responsável." : "Por favor, informe seu CPF."));
            isValid = false;
        } else if (typeof window.validadorCPF !== "undefined" &&
            !window.validadorCPF.validar(responsavelDocumento)) {
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `../../executar/${action}`,
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function (response) {
                    NotyE.exception({ response, reload: true });
                },
                error: function (xhr, status, error) {
                    NotyE.exception({ error: true, xhr });
                }
            });
        } else {
            $(".campo-obrigatorio").trigger("blur");
        }
    });
});