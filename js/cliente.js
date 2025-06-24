$(document).ready(function () {
    const dadosOriginaisCliente = {
        nome_fantasia: $("#nome_fantasia_cliente").val() || $("#nome_fantasia_pj_cliente").val(),
        razao_social: $("#razao_social_cliente").val(),
        documento: $("#documento_cliente").val(),
        telefone: $("#telefone_pj_cliente").val(),
        responsavel: $("#responsavel_cliente").val(),
        responsavel_whats: $("#responsavel_telefone_cliente").val(),
        responsavel_documento: $("#responsavel_documento_cliente").val(),
        responsavel_email: $("#responsavel_email_cliente").val(),
    };

    function atualizarTipoFormularioCliente(tipo) {
        if (tipo === "CNPJ") {
            FeedbackVisual.limparTodosFeedbacks("#sessao-cnpj-cliente");
            $(".campo-pf-cliente").hide();
            $(".campo-pj-cliente").show();

            // Sincroniza os valores entre os campos nome_fantasia
            if ($("#nome_fantasia_pj_cliente").val()) {
                $("#nome_fantasia_cliente").val($("#nome_fantasia_pj_cliente").val());
            }

            if ($("#telefone_cliente").val()) {
                $("#telefone_pj_cliente").val($("#telefone_cliente").val());
            }

            $(".dados-titulo-cliente").html("Dados do Responsável");
            $(".dados-label-nome-cliente").html("Nome do Responsável <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-documento-cliente").html(
                "CPF do Responsável <span class='formulario-campo-obrigatorio'>*</span>",
            );
            $(".dados-label-whatsapp-cliente").html("Telefone do Responsável");
            $(".dados-label-email-cliente").html(
                "E-mail do Responsável <span class='formulario-campo-obrigatorio'>*</span>",
            );

            if (dadosOriginaisCliente.razao_social) {
                $("#razao_social").val(dadosOriginaisCliente.razao_social);
            }
            if (dadosOriginaisCliente.nome_fantasia) {
                $("#nome_fantasia_pj").val(dadosOriginaisCliente.nome_fantasia);
            }
            if (dadosOriginaisCliente.documento && dadosOriginaisCliente.documento.length > 11) {
                $("#documento").val(dadosOriginaisCliente.documento);
            }
        } else {
            FeedbackVisual.limparTodosFeedbacks("#sessao-pf-cliente");
            $(".campo-pj-cliente").hide();
            $(".campo-pf-cliente").show();

            // Sincroniza os valores entre os campos nome_fantasia
            if ($("#nome_fantasia_cliente").val()) {
                $("#nome_fantasia_pj_cliente").val($("#nome_fantasia_cliente").val());
            }

            if ($("#telefone_pj_cliente").val()) {
                $("#telefone_cliente").val($("#telefone_pj_cliente").val());
            }

            $(".dados-titulo-cliente").html("Dados Pessoais");
            $(".dados-label-nome-cliente").html("Nome Completo <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-documento-cliente").html("CPF <span class='formulario-campo-obrigatorio'>*</span>");
            $(".dados-label-whatsapp-cliente").html("Telefone");
            $(".dados-label-email-cliente").html("E-mail <span class='formulario-campo-obrigatorio'>*</span>");
        }
    }

    // Sincroniza os valores dos campos nome_fantasia
    $("#nome_fantasia_cliente, #nome_fantasia_pj_cliente").on("input", function () {
        const valor = $(this).val();
        if ($(this).attr("id") === "nome_fantasia_cliente") {
            $("#nome_fantasia_pj_cliente").val(valor);
        } else {
            $("#nome_fantasia_cliente").val(valor);
        }
    });

    $("#telefone_cliente, #telefone_pj_cliente").on("input", function () {
        const valor = $(this).val();
        if ($(this).attr("id") === "telefone_cliente") {
            $("#telefone_pj_cliente").val(valor);
        } else {
            $("#telefone_cliente").val(valor);
        }
    });

    // Inicializa o tipo de formulário
    atualizarTipoFormularioCliente($("#tipo-cliente").val());

    $("#tipo-cliente").change(function () {
        const tipo = $(this).val();
        atualizarTipoFormularioCliente(tipo);
    });

    $("#cliente-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#cliente-cadastrar");

        let isValid = true;
        let action = $(this).data("action");
        const tipo = $("#tipo-cliente").val();

        // Validações específicas para CNPJ
        if (tipo === "CNPJ") {
            let razaoSocial = $("#razao_social_cliente").val().trim();
            if (razaoSocial === "") {
                FeedbackVisual.mostrarErro($("#razao_social_cliente"), "Por favor, informe a Razão Social.");
                isValid = false;
            } else if (
                typeof window.validadorRazaoSocial !== "undefined" &&
                !window.validadorRazaoSocial.validar(razaoSocial)
            ) {
                isValid = false;
            }

            let nomeFantasia = $("#nome_fantasia_pj_cliente").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_pj_cliente"), "Por favor, preencha o Nome Fantasia.");
                isValid = false;
            } else if (
                typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)
            ) {
                isValid = false;
            }

            let documento = $("#documento_cliente").val().trim();
            if (documento === "") {
                FeedbackVisual.mostrarErro($("#documento_cliente"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorCNPJ !== "undefined" && !window.validadorCNPJ.validar(documento)) {
                FeedbackVisual.mostrarErro($("#documento_cliente"), "CNPJ inválido. Por favor, verifique.");
                isValid = false;
            }
        } else {
            // Validações para CPF
            let nomeFantasia = $("#nome_fantasia_cliente").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_cliente"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (
                typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)
            ) {
                isValid = false;
            }
        }

        // Validações de responsável
        let responsavel = $("#responsavel_cliente").val().trim();
        if (responsavel === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o Nome do responsável." : "Por favor, informe seu Nome.",
            );
            isValid = false;
        } else if (typeof window.validadorResponsavel !== "undefined" && !window.validadorResponsavel.validar(responsavel)) {
            isValid = false;
        }

        let responsavelDocumento = $("#responsavel_documento_cliente").val().trim();
        if (responsavelDocumento === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_documento_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o CPF do responsável." : "Por favor, informe seu CPF.",
            );
            isValid = false;
        } else if (typeof window.validadorCPF !== "undefined" && !window.validadorCPF.validar(responsavelDocumento)) {
            isValid = false;
        }

        let responsavelEmail = $("#responsavel_email_cliente").val().trim();
        if (responsavelEmail === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_email_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o E-mail do responsável." : "Por favor, informe seu E-mail.",
            );
            isValid = false;
        } else if (typeof window.validadorEmail !== "undefined" && !window.validadorEmail.validar(responsavelEmail)) {
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `../../controller/${action}`,
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function (response) {
                    NotyE.exception({ response });
                },
                error: function (xhr, status, error) {
                    NotyE.exception({ error: true, xhr });
                },
            });
        }
    });

    $("#cliente-edicao").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#cliente-edicao");

        let isValid = true;
        let action = $(this).data("action");
        const tipo = $("#tipo-cliente").val();

        // Validações específicas para CNPJ (similar ao cadastro)
        if (tipo === "CNPJ") {
            let razaoSocial = $("#razao_social_cliente").val().trim();
            if (razaoSocial === "") {
                FeedbackVisual.mostrarErro($("#razao_social_cliente"), "Por favor, informe a Razão Social.");
                isValid = false;
            } else if (
                typeof window.validadorRazaoSocial !== "undefined" &&
                !window.validadorRazaoSocial.validar(razaoSocial)
            ) {
                isValid = false;
            }

            let nomeFantasia = $("#nome_fantasia_pj_cliente").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_pj_cliente"), "Por favor, preencha o Nome Fantasia.");
                isValid = false;
            } else if (
                typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)
            ) {
                isValid = false;
            }

            let documento = $("#documento_cliente").val().trim();
            if (documento === "") {
                FeedbackVisual.mostrarErro($("#documento_cliente"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (typeof window.validadorCNPJ !== "undefined" && !window.validadorCNPJ.validar(documento)) {
                isValid = false;
            }
        } else {
            let nomeFantasia = $("#nome_fantasia_cliente").val().trim();
            if (nomeFantasia === "") {
                FeedbackVisual.mostrarErro($("#nome_fantasia_cliente"), "Por favor, informe o CNPJ.");
                isValid = false;
            } else if (
                typeof window.validadorNomeFantasia !== "undefined" &&
                !window.validadorNomeFantasia.validar(nomeFantasia)
            ) {
                isValid = false;
            }
        }

        // Validações de responsável
        let responsavel = $("#responsavel_cliente").val().trim();
        if (responsavel === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o Nome do responsável." : "Por favor, informe seu Nome.",
            );
            isValid = false;
        } else if (typeof window.validadorResponsavel !== "undefined" && !window.validadorResponsavel.validar(responsavel)) {
            isValid = false;
        }

        let responsavelDocumento = $("#responsavel_documento_cliente").val().trim();
        if (responsavelDocumento === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_documento_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o CPF do responsável." : "Por favor, informe seu CPF.",
            );
            isValid = false;
        } else if (typeof window.validadorCPF !== "undefined" && !window.validadorCPF.validar(responsavelDocumento)) {
            isValid = false;
        }

        let responsavelEmail = $("#responsavel_email_cliente").val().trim();
        if (responsavelEmail === "") {
            FeedbackVisual.mostrarErro(
                $("#responsavel_email_cliente"),
                tipo === "CNPJ" ? "Por favor, informe o E-mail do responsável." : "Por favor, informe seu E-mail.",
            );
            isValid = false;
        } else if (typeof window.validadorEmail !== "undefined" && !window.validadorEmail.validar(responsavelEmail)) {
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `../../controller/${action}`,
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function (response) {
                    NotyE.exception({ response, reload: true });
                },
                error: function (xhr, status, error) {
                    NotyE.exception({ error: true, xhr });
                },
            });
        } else {
            $(".campo-obrigatorio").trigger("blur");
        }
    });

    $("#empresa-cliente-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#empresa-cliente-cadastrar");
        let isValid = true;
        let action = $(this).data("action");

        let empresa_id = $("#empresa_id").val().trim();
        if (empresa_id === "") {
            FeedbackVisual.mostrarErro($("#empresa_id"), "Por favor, informe o Serviço.");
            isValid = false;
        } else if (typeof window.validadorSelect !== "undefined" && !window.validadorSelect.validar(empresa_id)) {
            isValid = false;
        }

        if (isValid) {
            $.ajax({
                url: `../../controller/${action}`,
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),
                success: function (response) {
                    NotyE.exception({ response, reload: true });
                },
                error: function (xhr, status, error) {
                    NotyE.exception({ error: true, xhr });
                },
            });
        }
    });
});
