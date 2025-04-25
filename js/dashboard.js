document.addEventListener("DOMContentLoaded", function () {
  // Inicialização do dashboard
  initDashboard();
});

function initDashboard() {
  // Inicializa os event listeners
  initEventListeners();

  // Carrega os dados iniciais
  loadDashboardData();
}

function initEventListeners() {
  // Listener para o formulário de novo ticket
  const novoTicketForm = document.getElementById("novoTicketForm");
  if (novoTicketForm) {
    novoTicketForm.addEventListener("submit", handleNovoTicket);
  }

  // Listener para busca de tickets
  const searchInput = document.querySelector('.input-group input[type="text"]');
  if (searchInput) {
    searchInput.addEventListener("input", debounce(handleSearch, 500));
  }

  // Listeners para botões de ação
  document.querySelectorAll(".btn-light").forEach((btn) => {
    btn.addEventListener("click", handleTicketAction);
  });
}

function loadDashboardData() {
  // Carrega estatísticas
  loadStats();

  // Carrega tickets recentes
  loadRecentTickets();

  // Carrega categorias
  loadCategories();

  // Carrega atividades
  loadActivities();
}

function loadStats() {
  // Simulação de chamada API
  axios
    .get("api/dashboard/stats")
    .then((response) => {
      updateStatsCards(response.data);
    })
    .catch((error) => {
      NotyE.error({
        response: {
          message: "Erro ao carregar tickets recentes",
        },
      }).show();
    });
}

function loadRecentTickets() {
  // Simulação de chamada API
  axios
    .get("api/tickets/recent")
    .then((response) => {
      updateTicketsTable(response.data);
    })
    .catch((error) => {
      new Noty({
        type: "error",
        text: "Erro ao carregar tickets recentes",
      }).show();
    });
}

function handleNovoTicket(event) {
  event.preventDefault();

  const formData = new FormData(event.target);

  axios
    .post("api/tickets/create", formData)
    .then((response) => {
      new Noty({
        type: "success",
        text: "Ticket criado com sucesso!",
      }).show();

      // Fecha o modal
      const modal = bootstrap.Modal.getInstance(
        document.getElementById("novoTicketModal")
      );
      modal.hide();

      // Recarrega os dados
      loadDashboardData();
    })
    .catch((error) => {
      new Noty({
        type: "error",
        text: "Erro ao criar ticket",
      }).show();
    });
}

function handleSearch(event) {
  const searchTerm = event.target.value;

  axios
    .get(`api/tickets/search?q=${searchTerm}`)
    .then((response) => {
      updateTicketsTable(response.data);
    })
    .catch((error) => {
      console.error("Erro na busca:", error);
    });
}

function handleTicketAction(event) {
  const button = event.currentTarget;
  const action = button.querySelector("i").classList.contains("bi-eye")
    ? "view"
    : "edit";
  const ticketId = button.closest("tr").dataset.id;

  if (action === "view") {
    window.location.href = `../tickets/visualizar.php?id=${ticketId}`;
  } else {
    window.location.href = `../tickets/editar.php?id=${ticketId}`;
  }
}

// Função auxiliar para debounce
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Funções auxiliares para atualização da UI
function updateStatsCards(data) {
  // Atualiza os números nos cards de estatísticas
  Object.entries(data).forEach(([key, value]) => {
    const element = document.querySelector(`[data-stat="${key}"]`);
    if (element) {
      element.textContent = value;
    }
  });
}

function updateTicketsTable(tickets) {
  const tbody = document.querySelector(".table tbody");
  if (!tbody) return;

  tbody.innerHTML = tickets
    .map(
      (ticket) => `
        <tr data-id="${ticket.id}">
            <td>#${ticket.id}</td>
            <td>${ticket.assunto}</td>
            <td><span class="badge bg-${getStatusColor(ticket.status)}">${
        ticket.status
      }</span></td>
            <td><span class="badge bg-${getPriorityColor(ticket.prioridade)}">${
        ticket.prioridade
      }</span></td>
            <td>${formatDate(ticket.data)}</td>
            <td>
                <button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button>
            </td>
        </tr>
    `
    )
    .join("");

  // Reativa os event listeners
  document.querySelectorAll(".btn-light").forEach((btn) => {
    btn.addEventListener("click", handleTicketAction);
  });
}

// Funções auxiliares de formatação
function getStatusColor(status) {
  const colors = {
    Pendente: "warning",
    "Em Andamento": "info",
    Resolvido: "success",
    Fechado: "secondary",
  };
  return colors[status] || "primary";
}

function getPriorityColor(priority) {
  const colors = {
    Baixa: "success",
    Média: "warning",
    Alta: "danger",
  };
  return colors[priority] || "primary";
}

function formatDate(date) {
  return moment(date).format("DD/MM/YYYY HH:mm");
}
