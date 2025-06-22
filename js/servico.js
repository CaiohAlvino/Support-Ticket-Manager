$("#servico-cadastrar").submit(function (event) {
    event.preventDefault();

    $(".campo-obrigatorio").trigger("blur");
    FeedbackVisual.limparTodosFeedbacks("#servico-cadastrar");

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
                NotyE.exception({ error: true, xhr });
            },
        });
    }
});

$("#servico-editar").submit(function (event) {
    event.preventDefault();

    $(".campo-obrigatorio").trigger("blur");
    FeedbackVisual.limparTodosFeedbacks("#servico-editar");

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
                NotyE.exception({ error: true, xhr });
            },
        });
    }
});
