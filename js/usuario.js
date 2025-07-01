$(document).ready(function () {
    $("#usuario-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#usuario-cadastrar");

        let isValid = true;
        let action = $(this).data("action");

        let grupo_id = $("#grupo_id").val().trim();
        if (grupo_id === "") {
            FeedbackVisual.mostrarErro($("#grupo_id"), "Por favor, informe o Grupo do Usuário.");
            isValid = false;
        } else if (typeof window.validadorSelect !== "undefined" && !window.validadorSelect.validar(grupo_id)) {
            isValid = false;
        }

        let nome = $("#nome").val().trim();
        if (nome === "") {
            FeedbackVisual.mostrarErro($("#nome"), "Por favor, informe o Nome do Usuário.");
            isValid = false;
        } else if (typeof window.validadorNome !== "undefined" && !window.validadorNome.validar(nome)) {
            isValid = false;
        }

        let email = $("#email").val().trim();
        if (email === "") {
            FeedbackVisual.mostrarErro($("#email"), "Por favor, informe o Email do Usuário.");
            isValid = false;
        } else if (typeof window.validadorEmail !== "undefined" && !window.validadorEmail.validar(email)) {
            isValid = false;
        }

        let senha = $("#senha").val().trim();
        if (senha === "") {
            FeedbackVisual.mostrarErro($("#senha"), "Por favor, informe a Senha do Usuário.");
            isValid = false;
        } else if (typeof window.validadorSenha !== "undefined" && !window.validadorSenha.validar(senha)) {
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

    $("#usuario-editar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#usuario-editar");

        let isValid = true;
        let action = $(this).data("action");

        let grupo_id = $("#grupo_id").val().trim();
        if (grupo_id === "") {
            FeedbackVisual.mostrarErro($("#grupo_id"), "Por favor, informe o Grupo do Usuário.");
            isValid = false;
        } else if (typeof window.validadorSelect !== "undefined" && !window.validadorSelect.validar(grupo_id)) {
            isValid = false;
        }

        let nome = $("#nome").val().trim();
        if (nome === "") {
            FeedbackVisual.mostrarErro($("#nome"), "Por favor, informe o Nome do Usuário.");
            isValid = false;
        } else if (typeof window.validadorNome !== "undefined" && !window.validadorNome.validar(nome)) {
            isValid = false;
        }

        let email = $("#email").val().trim();
        if (email === "") {
            FeedbackVisual.mostrarErro($("#email"), "Por favor, informe o Email do Usuário.");
            isValid = false;
        } else if (typeof window.validadorEmail !== "undefined" && !window.validadorEmail.validar(email)) {
            isValid = false;
        }

        let senha = $("#senha").val().trim();
        if (senha === "") {
            FeedbackVisual.mostrarErro($("#senha"), "Por favor, informe uma nova Senha para o Usuário.");
            isValid = false;
        } else if (typeof window.validadorSenha !== "undefined" && !window.validadorSenha.validar(senha)) {
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

    $("#empresa-usuario-cadastrar").submit(function (event) {
        event.preventDefault();

        $(".campo-obrigatorio").trigger("blur");
        FeedbackVisual.limparTodosFeedbacks("#empresa-usuario-cadastrar");
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
                    if (xhr.status !== 401) {
                        NotyE.exception({ error: true, xhr });
                    }
                },
            });
        }
    });
});
