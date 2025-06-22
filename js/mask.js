$(document).ready(function () {
    // Só executa se o plugin jQuery Mask estiver disponível
    if (typeof $.fn.mask === "function") {
        // Só executa se houver pelo menos um campo de máscara na página
        if (
            $(
                ".mascara-cpf, .mascara-cnpj, .mascara-dinheiro, .mascara-whatsapp, .mascara-telefone, .mascara-cep, .mascara-quantidade",
            ).length > 0
        ) {
            // Mapeamento de códigos de discagem para códigos ISO
            const codigoParaISO = {
                "+1": "US",
                "+55": "BR",
                "+351": "PT",
                "+44": "GB",
            };

            /**
             *  Converte código de discagem para código ISO
             *  @param {string} codigoPais - Código de discagem
             *  @returns {string} - Código ISO do país
             */
            function converterParaISO(codigoPais) {
                return codigoParaISO[codigoPais] || "BR";
            }

            // Máscaras específicas por país com suporte a diferentes formatos
            const mascarasPorPais = {
                "+1": "(000) 000-0000", // EUA
                "+55": "(00) 0 0000-0000", // Brasil
                "+351": "000 000 000", // Portugal
                "+44": "(00) 0000 0000", // Reino Unido
            };

            // Placeholders por país para melhor experiência de usuário
            const placeholdersPorPais = {
                "+1": "(___)  ___-____", // EUA
                "+55": "(__)  _ ____-____", // Brasil
                "+351": "___ ___ ___", // Portugal
                "+44": "(__) ____ ____", // Reino Unido
            };

            // Máscaras padrão para outros campos
            $(".mascara-cpf").mask("000.000.000-00", {
                placeholder: "___.___.___-__",
                clearIfNotMatch: true,
            });

            $(".mascara-cnpj").mask("00.000.000/0000-00", {
                placeholder: "__.___.___/____-__",
                clearIfNotMatch: true,
            });
            $(".mascara-dinheiro").mask("00.000.000,00", { reverse: true, placeholder: "0,00" });
            $(".mascara-whatsapp").mask("(00) 0 0000-0000", { placeholder: "(__) _ ____-____" });
            $(".mascara-telefone").mask("(00) 0 0000-0000", { placeholder: "(__) _ ____-____" });
            $(".mascara-cep").mask("00000-000", { placeholder: "_____-___" });
            $(".mascara-quantidade").mask("000000", { reverse: true });

            const isEditPage = $("#cliente-editar").length > 0;

            /**
             * Aplica máscara de telefone com suporte a formatação do libphonenumber-js
             * @param {jQuery} selectPais - Elemento select do país
             * @param {jQuery} inputTelefone - Elemento input do telefone
             * @param {boolean} isChangeEvent - Indica se é um evento de mudança de país
             */
            function aplicarMascaraTelefone(selectPais, inputTelefone, isChangeEvent = false) {
                const codigoPais = $(selectPais).val();
                const codigoISO = converterParaISO(codigoPais);

                const valorAtual = $(inputTelefone).val();
                const valorApenasNumeros = valorAtual ? valorAtual.replace(/\D/g, "") : "";

                // Remove a máscara atual
                $(inputTelefone).unmask();

                // Lógica de preservação/limpeza do valor
                if (isChangeEvent) {
                    $(inputTelefone).val("");
                } else if (isEditPage && valorApenasNumeros) {
                    $(inputTelefone).val(valorApenasNumeros);
                }

                // Tenta formatar usando libphonenumber-js, se disponível
                let formatado = valorApenasNumeros;
                if (typeof TelefoneValidator !== "undefined" && valorApenasNumeros) {
                    const resultado = TelefoneValidator.validarNumero(
                        valorApenasNumeros,
                        codigoISO,
                    );
                    if (resultado.valido && resultado.formatado) {
                        formatado = resultado.formatado;
                    }
                }

                // Aplica máscara do país ou padrão brasileiro
                const mascara = mascarasPorPais[codigoPais] || "(00) 0 0000-0000";
                const placeholder = placeholdersPorPais[codigoPais] || "(__)  _ ____-____";

                $(inputTelefone).mask(mascara, {
                    placeholder: placeholder,
                    clearIfNotMatch: false,
                });

                // Define o valor formatado
                if (formatado) {
                    $(inputTelefone).val(formatado);
                }
            }

            /**
             * Inicializa as máscaras de telefone para campos com seleção de país
             */
            function inicializarMascarasTelefone() {
                $('select[name="pais"]').each(function () {
                    const selectPais = this;
                    const inputWhatsapp = $(this).closest(".input-group").find(".mascara-whatsapp");

                    if (inputWhatsapp.length) {
                        const valorOriginal = inputWhatsapp.val();

                        inputWhatsapp.data("original-value", valorOriginal);

                        aplicarMascaraTelefone(selectPais, inputWhatsapp, false);

                        $(selectPais).on("change", function () {
                            aplicarMascaraTelefone(selectPais, inputWhatsapp, true);
                        });

                        if (isEditPage && valorOriginal) {
                            setTimeout(function () {
                                if (!inputWhatsapp.val()) {
                                    const valorNumerico = valorOriginal.replace(/\D/g, "");
                                    inputWhatsapp.val(valorNumerico);
                                    inputWhatsapp.trigger("input"); // Reaplica a máscara
                                }
                            }, 100);
                        }
                    }
                });
            }

            // Inicializa as máscaras de telefone
            // inicializarMascarasTelefone();
        }
    }
});
