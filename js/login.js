$(document).ready(function () {
    $("#login").on("submit", function (e) {
        e.preventDefault();
        var email = $("#input-usuario-email").val().trim();
        var senha = $("#senha").val();
        var btn = $(this).find("button[type=submit]");

        if (!email || !senha) {
            NotyE.warning({ response: { message: "Preencha e-mail e senha." } });
            return;
        }

        btn.prop("disabled", true).text("Entrando...");

        $.ajax({
            url: $(this).attr("data-action") || "controller/login/logar.php",
            type: "POST",
            data: { email: email, senha: senha },
            dataType: "json",
            success: function (response) {
                if (response.status === "success" && response.usuario && response.usuario.token) {
                    document.cookie = "token=" + response.usuario.token + "; path=/";
                    NotyE.exception({ response, replace: `pages/home/index.php` });
                } else {
                    NotyE.exception({ response });
                }
            },
            error: function (xhr, status, error) {
                if (xhr.status !== 401) {
                    NotyE.exception({ error: true, xhr });
                }
            },
            complete: function () {
                btn.prop("disabled", false).text("Entrar no Sistema");
            },
        });
    });

    $(".password-toggle").on("click", function () {
        var input = $("#senha");
        var type = input.attr("type") === "password" ? "text" : "password";
        input.attr("type", type);
        $(this).find("i").toggleClass("bi-eye bi-eye-slash");
    });
});
