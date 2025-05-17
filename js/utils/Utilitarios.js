// -------------------------- → UTILITÁRIOS ← -------------------------- //

class Utilitarios {

    /**
     * Formata um valor numérico para exibição monetária
     * @param {Number|String} valor Valor a ser formatado
     * @returns {String} Valor formatado
     */
    static formatarValor(valor) {
        const num = parseFloat(valor);
        return isNaN(num) ? "0,00" : num.toLocaleString("pt-BR", {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    /**
     * Formata uma data para exibição no formato dd/mm/yyyy
     * @param {Date} data Objeto de data a ser formatado
     * @returns {String} Data formatada
     */
    static formatarData(data) {
        try {
            if (!data) {
                return "__/__/____";
            }
            return String(data).split("-").reverse().join("/");
        } catch (e) {
            console.error("Erro de formatação de data:", e);
            return "__/__/____";
        }
    }

    /**
     * Formata uma data (com ou sem hora) para o formato dd/mm/yyyy ou dd/mm/yyyy HH:mm.
     * Aceita Date, timestamp, string yyyy-mm-dd, yyyy-mm-ddTHH:mm:ss, dd/mm/yyyy, dd/mm/yyyy HH:mm.
     * Usa moment.js se disponível.
     * @param {Date|number|string} data - Data a ser formatada.
     * @param {boolean} [temHora=false] - Se true, inclui a hora no resultado.
     * @returns {string} Data formatada.
     */
    static formatarDataComHora(data, temHora = false) {
        try {
            if (!data) {
                return "__/__/____";
            }
            if (typeof moment === "function") {
                const m = moment(data);
                return m.isValid() ? m.format(temHora ? "DD/MM/YYYY HH:mm" : "DD/MM/YYYY") : "__/__/____";
            }
            let d = typeof data === "number" ? new Date(data) :
                typeof data === "string" && /^\d{4}-\d{2}-\d{2}/.test(data) ? new Date(data) : data;
            if (d instanceof Date && !isNaN(d)) {
                const pad = n => String(n).padStart(2, "0");
                const res = `${pad(d.getDate())}/${pad(d.getMonth() + 1)}/${d.getFullYear()}`;
                return temHora ? `${res} ${pad(d.getHours())}:${pad(d.getMinutes())}` : res;
            }
            if (typeof data === "string" && /^\d{2}\/\d{2}\/\d{4}/.test(data)) {
                return temHora ? (data.length > 10 ? data : data + " 00:00") : data.slice(0, 10);
            }
            return "__/__/____";
        } catch (e) {
            console.error("Erro de formatação de data:", e);
            return "__/__/____";
        }
    }

    // Caso ocorra a necessidade de utilizar ja existe issa parada aqui
    static salvarAbaAtivaConfig() {
        const abaAtiva = document.querySelector('.config-nav .nav-link.active');
        if (abaAtiva && abaAtiva.id) {
            localStorage.setItem('abaConfiguracaoAtiva', abaAtiva.id);
        }
    }

    static restaurarAbaAtivaConfig() {
        const abaAtiva = localStorage.getItem('abaConfiguracaoAtiva');
        if (abaAtiva) {
            const $aba = document.querySelector('.config-nav .nav-link#' + abaAtiva);
            if ($aba && typeof $($aba).tab === 'function') {
                $($aba).tab('show');
            }
        }
    }
}