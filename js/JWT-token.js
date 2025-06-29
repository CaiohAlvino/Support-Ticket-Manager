$(document).ready(function () {

    $.ajaxSetup({
        beforeSend: function (xhr, settings) {
            const token = getCookie("token");
            if (token) {
                xhr.setRequestHeader("Authorization", `Bearer ${token}`);
            }
        }
    });

    function getCookie(name) {
        try {
            const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
            return match ? match[2] : null;
        } catch (error) {
            return null;
        }
    }
});
$(document).ajaxError(function(event, jqxhr) {
    if (jqxhr.status === 401) {
        // Limpa o cookie do token (opcional)
        document.cookie = "token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        NotyE.error({
            response: {
                message: "Sessão expirada. Faça login novamente."
            },
            options: {
                callbacks: {
                    afterClose: function () {
                        location.replace("../../index.php");
                    }
                }
            }
        })
    }
});
