$(document).ready(function () {
    $("#empresa-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#empresa-cadastrar");

        let isValid = true;
        let action = $(this).data("action");

        let nome = $("#nome").val().trim();
        if (nome === "") {
            FeedbackVisual.mostrarErro($("#nome"), "Por favor, informe o Nome do Serviço.");
            isValid = false;
        } else if (typeof window.validadorNome !== "undefined" && !window.validadorNome.validar(nome)) {
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
                    if (xhr.status !== 401) {
                        NotyE.exception({ error: true, xhr });
                    }
                },
            });
        }
    });

    $("#empresa-editar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#empresa-editar");

        let isValid = true;
        let action = $(this).data("action");

        let nome = $("#nome").val().trim();
        if (nome === "") {
            FeedbackVisual.mostrarErro($("#nome"), "Por favor, informe o Nome do Serviço.");
            isValid = false;
        } else if (typeof window.validadorNome !== "undefined" && !window.validadorNome.validar(nome)) {
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
                    if (xhr.status !== 401) {
                        NotyE.exception({ error: true, xhr });
                    }
                },
            });
        }
    });

    $("#empresa-servico-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#empresa-servico-cadastrar");
        let isValid = true;
        let action = $(this).data("action");

        let servico_id = $("#servico_id").val().trim();
        if (servico_id === "") {
            FeedbackVisual.mostrarErro($("#servico_id"), "Por favor, informe o Serviço.");
            isValid = false;
        } else if (typeof window.validadorSelect !== "undefined" && !window.validadorSelect.validar(servico_id)) {
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
                    if (xhr.status !== 401) {
                        NotyE.exception({ error: true, xhr });
                    }
                },
            });
        }
    });
});
