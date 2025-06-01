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
        } else if (
            typeof window.validadorNome !== "undefined" &&
            !window.validadorNome.validar(assunto)
        ) {
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
                    NotyE.exception({ error: true, xhr });
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
                NotyE.exception({ error: true, xhr });
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
                NotyE.exception({ error: true, xhr });
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
            $("#mensagem").after(
                "<span class='error-message text-danger'>A mensagem é obrigatória.</span>",
            );
            return;
        }

        $.ajax({
            url: `../../controller/${action}`,
            type: "POST",
            dataType: "json",
            data: $(this).serialize() + "&status=AGUARDANDO_SUPORTE",
            success: function (response) {
                NotyE.exception({ response, reload: true });
            },
            error: function (xhr, status, error) {
                NotyE.exception({ error: true, xhr });
            },
        });
    });
});
