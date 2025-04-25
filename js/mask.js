$(document).ready(function () {
  // Função para aplicar máscara de data
  function maskDate(input) {
    $(input).on("input", function () {
      let value = $(this).val().replace(/\D/g, "");
      if (value.length > 8) value = value.substr(0, 8);

      if (value.length >= 4) {
        value = value.replace(/^(\d{2})(\d{2})/, "$1/$2/");
      } else if (value.length >= 2) {
        value = value.replace(/^(\d{2})/, "$1/");
      }

      $(this).val(value);
    });
  }

  // Função para aplicar máscara de hora
  function maskTime(input) {
    $(input).on("input", function () {
      let value = $(this).val().replace(/\D/g, "");
      if (value.length > 4) value = value.substr(0, 4);

      if (value.length >= 2) {
        value = value.replace(/^(\d{2})(\d{2})?/, "$1:$2");
      }

      $(this).val(value);
    });
  }

  // Função para aplicar máscara de telefone
  function maskPhone(input) {
    $(input).on("input", function () {
      let value = $(this).val().replace(/\D/g, "");
      if (value.length > 11) value = value.substr(0, 11);

      if (value.length > 6) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, "($1) $2-$3");
      } else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,5})/, "($1) $2");
      } else if (value.length > 0) {
        value = value.replace(/^(\d*)/, "($1");
      }

      $(this).val(value);
    });
  }

  // Aplicar máscaras aos campos
  $('[data-mask="date"]').each(function () {
    maskDate(this);
  });

  $('[data-mask="time"]').each(function () {
    maskTime(this);
  });

  $('[data-mask="phone"]').each(function () {
    maskPhone(this);
  });
});
