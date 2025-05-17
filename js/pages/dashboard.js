$(document).ready(function () {
  // Inicializa tooltips do Bootstrap (compatível com ambos)
  $('[data-bs-toggle="tooltip"]').tooltip();

  // Inicializa Select2 nos selects
  $(".select2").select2({
    theme: "bootstrap-5",
    width: "100%",
  });

  // Gráfico de Status dos Tickets
  const statusCtx = document.getElementById("ticketStatusChart")?.getContext("2d");
  let statusChart;
  if (statusCtx) {
    statusChart = new Chart(statusCtx, {
      type: "doughnut",
      data: {
        labels: ["Em Aberto", "Em Andamento", "Resolvidos", "Fechados"],
        datasets: [
          {
            data: [12, 19, 8, 5],
            backgroundColor: [
              "#FF6B6B", // Vermelho para Em Aberto
              "#4ECDC4", // Azul turquesa para Em Andamento
              "#93C572", // Verde pistache para Resolvidos
              "#708238", // Verde oliva para Fechados
            ],
            borderWidth: 0,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: "bottom",
          },
        },
      },
    });
  }

  // Gráfico de Categorias
  const categoryCtx = document.getElementById("ticketCategoryChart")?.getContext("2d");
  let categoryChart;
  if (categoryCtx) {
    categoryChart = new Chart(categoryCtx, {
      type: "bar",
      data: {
        labels: ["Bug", "Suporte", "Feature", "Dúvida", "Outros"],
        datasets: [
          {
            label: "Tickets por Categoria",
            data: [15, 25, 10, 18, 5],
            backgroundColor: "#708238",
            borderRadius: 5,
          },
        ],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          },
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              stepSize: 5,
            },
          },
        },
      },
    });
  }

  // Modal de Novo Ticket
  const newTicketModal = document.getElementById("newTicketModal");
  if (newTicketModal) {
    newTicketModal.addEventListener("shown.bs.modal", function () {
      $("#ticketSubject").focus();
    });
  }

  // Manipulação do formulário de novo ticket
  $("#newTicketForm").on("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Desabilita o botão de submit
    const submitBtn = $(this).find('button[type="submit"]');
    const originalText = submitBtn.text();
    submitBtn
      .prop("disabled", true)
      .html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Enviando...'
      );

    axios
      .post("api/tickets/create", formData)
      .then(function (response) {
        // Fecha o modal
        $("#newTicketModal").modal("hide");

        // Limpa o formulário
        $("#newTicketForm")[0].reset();

        // Notifica o usuário
        new Noty({
          type: "success",
          text: "Ticket criado com sucesso!",
          timeout: 3000,
        }).show();

        // Atualiza os dados da dashboard
        if (typeof updateDashboardData === "function") updateDashboardData();
      })
      .catch(function (error) {
        new Noty({
          type: "error",
          text:
            error.response?.data?.message ||
            "Erro ao criar ticket. Tente novamente.",
          timeout: 3000,
        }).show();
      })
      .finally(function () {
        // Reativa o botão de submit
        submitBtn.prop("disabled", false).text(originalText);
      });
  });

  // Função para atualizar os dados da dashboard
  function updateDashboardData() {
    axios
      .get("api/dashboard/stats")
      .then(function (response) {
        const data = response.data;

        // Atualiza os números das estatísticas
        $("#totalTickets").text(data.total);
        $("#ongoingTickets").text(data.ongoing);
        $("#resolvedTickets").text(data.resolved);
        $("#criticalTickets").text(data.critical);

        // Atualiza os gráficos
        statusChart.data.datasets[0].data = data.statusDistribution;
        statusChart.update();

        categoryChart.data.datasets[0].data = data.categoryDistribution;
        categoryChart.update();

        // Atualiza a timeline de atividades
        updateActivityTimeline(data.recentActivities);
      })
      .catch(function (error) {
        console.error("Erro ao atualizar dados:", error);
      });
  }

  // Função para atualizar a timeline de atividades
  function updateActivityTimeline(activities) {
    const timeline = $(".timeline");
    timeline.empty();

    activities.forEach((activity) => {
      const timeAgo = moment(activity.timestamp).fromNow();
      const item = `
                <li class="timeline-item">
                    <div class="d-flex flex-column">
                        <p class="mb-1">${activity.description}</p>
                        <small class="text-muted">${timeAgo}</small>
                    </div>
                </li>
            `;
      timeline.append(item);
    });
  }

  // Atualiza os dados inicialmente
  updateDashboardData();

  // Atualiza os dados a cada 5 minutos
  setInterval(updateDashboardData, 300000);
});
