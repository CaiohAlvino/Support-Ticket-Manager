$(document).ready(function () {
    $("#suporte-cadastrar").on("submit", function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#suporte-cadastrar");

        let isValid = true;
        let action = $(this).data("action");
        let mensagem = $("#mensagem").val().trim();

        let assunto = $("#assunto").val().trim();
        if (assunto === "") {
            FeedbackVisual.mostrarErro($("#assunto"), "Por favor, informe o Assunto.");
            isValid = false;
        } else if (typeof window.validadorNome !== "undefined" && !window.validadorNome.validar(assunto)) {
            isValid = false;
        }

        if (mensagem.length < 20) {
            isValid = false;
            $("#mensagem").after(
                "<span class='error-message' style='color: red;'>A mensagem deve ter no mínimo 20 caracteres para poder abrir um ticket.</span>",
            );
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
                    if (xhr.status !== 401) {
                        NotyE.exception({ error: true, xhr });
                    }
                },
            });
        }
    });

    $(document).on("click", ".reabrir", function (event) {
        event.preventDefault();

        let form = $("#suporte-reabrir");
        let ticketId = form.find("input[name='id']").val();
        let adminId = form.find("input[name='admin_id']").val();
        let action = form.data("action");

        let dadosForm = {
            id: ticketId,
            admin_id: adminId,
            status: "ABERTO",
        };

        $.ajax({
            url: `../../controller/${action}`,
            type: "POST",
            dataType: "json",
            data: dadosForm,
            success: function (response) {
                NotyE.exception({ response, reload: true });
            },
            error: function (xhr, status, error) {
                if (xhr.status !== 401) {
                    NotyE.exception({ error: true, xhr });
                }
            },
        });
    });

    $("#suporte-mensagem-detalhe .btn-excluir").on("click", function (event) {
        event.preventDefault();
        $("#confirmarFecharTicket").modal("show");
    });

    $("#confirmar-fechar-ticket").on("click", function () {
        let form = $("#suporte-mensagem-detalhe");
        let action = form.data("action");

        $("#confirmarFecharTicket").modal("hide");

        let dadosForm = form.serialize() + "&status=FECHADO";

        $.ajax({
            url: `../../controller/${action}`,
            type: "POST",
            dataType: "json",
            data: dadosForm,
            success: function (response) {
                NotyE.exception({ response, reload: true });
            },
            error: function (xhr, status, error) {
                if (xhr.status !== 401) {
                    NotyE.exception({ error: true, xhr });
                }
            },
        });
    });

    $("#suporte-mensagem-detalhe").on("submit", function (event) {
        event.preventDefault();

        let botaoClicado = $(document.activeElement);
        if (botaoClicado.hasClass("btn-excluir")) {
            return;
        }

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#suporte-mensagem-detalhe");

        let action = $(this).data("action");

        let mensagem = $("#mensagem").val().trim();
        if (mensagem === "") {
            $("#mensagem").after("<span class='error-message text-danger'>A mensagem é obrigatória.</span>");
            return;
        }

        $.ajax({
            url: `../../controller/${action}`,
            type: "POST",
            dataType: "json",
            data: $(this).serialize(),
            success: function (response) {
                NotyE.exception({ response, reload: true });
            },
            error: function (xhr, status, error) {
                if (xhr.status !== 401) {
                    NotyE.exception({ error: true, xhr });
                }
            },
        });
    });

    // Atualiza empresas ao selecionar cliente
    $("#cliente_id_suporte").on("change", function () {
        var clienteId = $(this).val();
        var $empresaSelect = $("#empresa_id_suporte");
        $empresaSelect.html('<option value="">Carregando...</option>');
        if (!clienteId) {
            $empresaSelect.html('<option value="">Selecione uma empresa</option>');
            return;
        }
        $.ajax({
            url: "../../controller/empresa/empresas-por-cliente.php",
            type: "GET",
            data: { cliente_id: clienteId },
            dataType: "json",
            success: function (response) {
                if (response.debug) {
                    console.log(response.debug);
                }
                var empresas = response.empresas || [];
                var options = '<option value="">Selecione uma empresa</option>';
                if (empresas.length > 0) {
                    empresas.forEach(function (empresa) {
                        options += '<option value="' + empresa.id + '">' + empresa.nome + "</option>";
                    });
                }
                $empresaSelect.html(options);
                $empresaSelect.trigger("change");
            },
            error: function () {
                $empresaSelect.html('<option value="">Erro ao carregar empresas</option>');
            },
        });
    }); // Se o campo cliente_id_suporte for hidden (usuário cliente), dispara o change para carregar as empresas automaticamente
    if ($("#cliente_id_suporte").is(":hidden") || $("#cliente_id_suporte").attr("type") === "hidden") {
        $("#cliente_id_suporte").trigger("change");
    }

    // Atualiza empresas ao selecionar cliente
    $("#empresa_id_suporte").on("change", function () {
        var empresaId = $(this).val();
        var $servicoSelect = $("#servico_id");
        $servicoSelect.html('<option value="">Carregando...</option>');
        if (!empresaId) {
            $servicoSelect.html('<option value="">Selecione um serviço</option>');
            return;
        }
        $.ajax({
            url: "../../controller/empresa/servicos-por-empresa.php",
            type: "GET",
            data: { empresa_id: empresaId },
            dataType: "json",
            success: function (response) {
                if (response.debug) {
                    console.log(response.debug);
                }
                var servicos = response.servicos || [];
                var options = '<option value="">Selecione um serviço</option>';
                if (servicos.length > 0) {
                    servicos.forEach(function (servico) {
                        options += '<option value="' + servico.id + '">' + servico.nome + "</option>";
                    });
                }
                $servicoSelect.html(options);
                $servicoSelect.trigger("change");
            },
            error: function () {
                $empresaSelect.html('<option value="">Erro ao carregar empresas</option>');
            },
        });
    });
});
