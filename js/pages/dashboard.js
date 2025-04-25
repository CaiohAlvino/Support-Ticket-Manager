$(document).ready(function () {
  // Inicializa os tooltips do Bootstrap
  const tooltipTriggerList = document.querySelectorAll(
    '[data-bs-toggle="tooltip"]'
  );
  const tooltipList = [...tooltipTriggerList].map(
    (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
  );

  // Inicializa o select2 para os selects do modal
  $(".select2").select2({
    theme: "bootstrap-5",
  });

  // Gráfico de tickets por status
  const ticketStatusChart = new Chart(
    document.getElementById("ticketStatusChart"),
    {
      type: "doughnut",
      data: {
        labels: ["Em Aberto", "Em Andamento", "Resolvidos", "Fechados"],
        datasets: [
          {
            data: [12, 19, 3, 5],
            backgroundColor: ["#FF6384", "#36A2EB", "#4BC0C0", "#FF9F40"],
          },
        ],
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    }
  );

  // Gráfico de tickets por categoria
  const ticketCategoryChart = new Chart(
    document.getElementById("ticketCategoryChart"),
    {
      type: "bar",
      data: {
        labels: ["Bug", "Feature", "Suporte", "Dúvida"],
        datasets: [
          {
            label: "Tickets por Categoria",
            data: [65, 59, 80, 81],
            backgroundColor: "#93C572",
          },
        ],
      },
      options: {
        responsive: true,
        scales: {
          y: {
            beginAtZero: true,
          },
        },
      },
    }
  );

  // Manipulação do modal de novo ticket
  $("#newTicketModal").on("shown.bs.modal", function () {
    $("#ticketSubject").trigger("focus");
  });

  // Função para criar novo ticket
  $("#newTicketForm").on("submit", function (e) {
    e.preventDefault();

    // Aqui você implementará a lógica de criação do ticket
    // usando os dados do formulário
    const formData = new FormData(this);

    // Exemplo de uso do Axios para enviar os dados
    axios
      .post("/api/tickets/create", formData)
      .then(function (response) {
        new Noty({
          type: "success",
          text: "Ticket criado com sucesso!",
          timeout: 3000,
        }).show();

        $("#newTicketModal").modal("hide");
        // Recarregar dados do dashboard
      })
      .catch(function (error) {
        new Noty({
          type: "error",
          text: "Erro ao criar ticket. Tente novamente.",
          timeout: 3000,
        }).show();
      });
  });
});
