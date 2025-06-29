$(document).ready(function () {
    $(".botao-excluir-executar").on("click", function () {
        let { id } = $(this).data();

        let action = $(this).data("action");

        $.ajax({
            url: `../../controller/${action}`,
            type: "POST",
            dataType: "json",
            data: { id },
            success: function (response) {
                NotyE.exception({ response, reload: true });
            },
            error: function (xhr, status, error) {
                if (xhr.status !== 401) {
                    NotyE.exception({ error: true, xhr });
                }
            }
        });
    });
});
