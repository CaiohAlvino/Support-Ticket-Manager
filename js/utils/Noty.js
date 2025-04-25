class NotyE {

    static config({ type }) {
        Noty.overrideDefaults({
            type: type,  // Tipo de notiicação (success, error, warning e info)
            theme: 'aurora',  // Tema (personalisado ou os ja existentes)
            layout: "topRight",  // possição da notificação
            timeout: 1000,
            progressBar: true,  // Exibir barra de progresso
            closeWith: ['click'],  // Define como a notificação pode ser fechada
            maxVisible: 5,  // Número máximo de notificações visíveis
            visibilityControl: true, // Pausa o timeout quando a aba não está visível
            queueDelay: 500,  // Atraso antes de mostrar a próxima notificação da fila (ms)
            modal: false,  // Fundo modal (escurece o resto da página)
        });
    }

    static exception({ response, replace = `index.php`, error = false, xhr, type, targetBlank = false, reload = false, isNull = false }) {
        if (error) {
            this.config({ type: type });

            console.error("Erro AJAX:", xhr?.responseText, xhr);

            return this.error({
                response: {
                    status: "error",
                    message: "Erro ao processar a solicitação, Por favor, tente novamente."
                }
            });
        } else {
            if (response?.status === "success") {
                return this.processAjax({ response, replace, type, targetBlank, reload, isNull });
            } else {
                return this.error({ response, type });
            }
        }
    }

    static processAjax({ response, replace = `index.php`, type, targetBlank, reload, isNull }) {
        try {
            this.config({ type: type || "success" });

            new Noty({
                type: type ? type : response.status,
                text: response.message || "Operação realizada com sucesso!",
                callbacks: {
                    onShow: function () {
                        $(".botao-noty-ativo")
                            .prop("disabled", true)
                            .html('<i class="bi bi-hourglass-split me-2"></i> Processando...');

                        $(".botao-noty-voltar").addClass("disabled");
                    },
                    afterClose: function () {
                        if (isNull) {
                            null; // Faz nada
                        } else if (targetBlank) {
                            window.open(replace, "_self");
                        } else if (reload) {
                            location.reload();
                        } else {
                            location.replace(replace);
                        }
                    }
                }
            }).show();
        } catch (error) {
            console.error("Erro ao processar notificação:", error);
            return this.error({
                response: {
                    message: "Erro interno ao processar. Caso necessite contate o suporte."
                }
            });
        }
    }

    static success({ response, type, options = {} }) {
        try {
            this.config({ type: type || "success" })

            new Noty({
                type: type ? type : response.status,
                text: response.message || "Operação realizada com sucesso!",
                ...options
            }).show();
        } catch (error) {
            console.error("Erro ao processar notificação:", error)
            return this.error({
                response: {
                    message: "Erro interno ao processar. Caso necessite contate o suporte."
                }
            });
        }
    }

    static error({ response, type, options = {} }) {
        try {
            this.config({ type: type || "error" });

            new Noty({
                type: type ? type : response.status,
                text: response.message || "Erro ao realizar operação!",
                timeout: 3500,
                ...options
            }).show();
        } catch (error) {
            console.error("Erro ao processar notificação:", error);
            console.error("Erro interno ao processar. Caso necessite contate o suporte.");
        }
    }

    static warning({ response, type, options = {} }) {
        try {
            this.config({ type: type || "warning" });

            new Noty({
                type: type ? type : response.status,
                text: response.message || "Atenção com essa operação!",
                timeout: 3000,
                ...options
            }).show();
        } catch (error) {
            console.error("Erro ao processar notificação:", error);
            return this.error({
                response: {
                    message: "Erro interno ao processar. Caso necessite contate o suporte."
                }
            });
        }
    }

    static info({ response, type, options = {} }) {
        try {
            this.config({ type: type || "info" });

            new Noty({
                type: type ? type : response.status,
                text: response.message || "Informação da operação!",
                timeout: 5000,
                ...options
            }).show();
        } catch (error) {
            console.error("Erro ao processar notificação:", error);
            return this.error({
                response: {
                    message: "Erro interno ao processar. Caso necessite contate o suporte."
                }
            });
        }
    }
}
/**
$(document).ready(function () {
    // NotyE.exception({
    //     response: {
    //         status: "success",
    //     },
    //     type: "",
    //     reload: true,
    // });

    // Exemplos para testes:
    // Sucesso
    NotyE.success({
        response: {
            status: "success",
            message: "Mensagem de sucesso"
        },
        type: "",
    })
    // Erro
    NotyE.error({
        response: {
            status: "error",
            message: "Mensagem de erro"
        },
        type: "",
    })
    // Informativo
    NotyE.info({
        response: {
            status: "info",
            message: "Mensagem de info"
        },
        type: "",
        // Exemplo com options para adicionar mais configurações
        options: {
            callbacks: {
                afterClose: function () {
                    location.reload();
                }
            }
        }
    })
    // Aviso
    NotyE.warning({
        response: {
            status: "warning",
            message: "Mensagem de aviso"
        },
        type: "",
    })
});
*/