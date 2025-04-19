$(document).ready(function () {
  // Manipulação do formulário de suporte
  $("#suporte-cadastrar").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);

    $.ajax({
      url: $(this).data("action"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let data = JSON.parse(response);

        if (data.status === "success") {
          alert(data.message);
          window.location.href = "index.php";
        } else {
          alert(data.message);
        }
      },
      error: function () {
        alert("Erro ao processar a solicitação");
      },
    });
  });

  // Manipulação do formulário de resposta
  $("#suporte-mensagem-detalhe").on("submit", function (e) {
    e.preventDefault();

    let formData = new FormData(this);
    let isFecharTicket = $(e.originalEvent.submitter).hasClass("btn-excluir");

    if (isFecharTicket) {
      $("#confirmarFecharTicket").modal("show");
      return;
    }

    enviarMensagem(formData);
  });

  // Confirmação para fechar ticket
  $("#confirmar-fechar-ticket").on("click", function () {
    let form = $("#suporte-mensagem-detalhe");
    let formData = new FormData(form[0]);
    formData.append("status", "FECHADO");

    enviarMensagem(formData);
    $("#confirmarFecharTicket").modal("hide");
  });

  // Função para enviar mensagem
  function enviarMensagem(formData) {
    $.ajax({
      url: $("#suporte-mensagem-detalhe").data("action"),
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        let data = JSON.parse(response);

        if (data.status === "success") {
          window.location.reload();
        } else {
          alert(data.message);
        }
      },
      error: function () {
        alert("Erro ao processar a solicitação");
      },
    });
  }

  // Validação de campos obrigatórios
  $(".campo-obrigatorio").on("blur", function () {
    let campo = $(this);
    if (!campo.val()) {
      campo.addClass("is-invalid");
    } else {
      campo.removeClass("is-invalid");
    }
  });

  // Auto-resize para textareas
  $("textarea").on("input", function () {
    this.style.height = "auto";
    this.style.height = this.scrollHeight + "px";
  });

  // Filtros da tabela
  $("#status").on("change", function () {
    $(this).closest("form").submit();
  });

  // Tooltips
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );
});
