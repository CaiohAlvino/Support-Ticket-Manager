document.addEventListener("DOMContentLoaded", function () {
  const body = document.body;
  const sidebar = document.getElementById("sidebar");
  const sidebarOverlay = document.getElementById("sidebarOverlay");
  const menuToggle = document.getElementById("menuToggle");
  const mainContent = document.getElementById("mainContent");

  // Estado inicial da sidebar (baseado no localStorage)
  const sidebarCollapsed = localStorage.getItem("sidebarCollapsed") === "true";

  if (sidebarCollapsed) {
    body.classList.add("sidebar-collapsed");
  }

  // Toggle da sidebar
  function toggleSidebar() {
    body.classList.toggle("sidebar-collapsed");

    // Salva o estado no localStorage
    localStorage.setItem(
      "sidebarCollapsed",
      body.classList.contains("sidebar-collapsed")
    );

    // Dispara evento de resize para ajustar gráficos ou outros elementos
    window.dispatchEvent(new Event("resize"));
  }

  // Toggle da sidebar em mobile
  function toggleMobileSidebar() {
    if (window.innerWidth < 992) {
      body.classList.toggle("sidebar-open");
      sidebarOverlay.classList.toggle("active");
    } else {
      toggleSidebar();
    }
  }

  // Event Listeners
  menuToggle.addEventListener("click", toggleMobileSidebar);
  sidebarOverlay.addEventListener("click", toggleMobileSidebar);

  // Fecha a sidebar ao clicar em um link (apenas em mobile)
  document.querySelectorAll(".sidebar .menu-item").forEach((item) => {
    item.addEventListener("click", () => {
      if (window.innerWidth < 992 && body.classList.contains("sidebar-open")) {
        toggleMobileSidebar();
      }
    });
  });

  // Marca o item ativo no menu
  const currentPath = window.location.pathname;

  document.querySelectorAll(".sidebar .menu-item").forEach((item) => {
    if (item.getAttribute("href") === currentPath) {
      item.classList.add("active");
    } else {
      item.classList.remove("active");
    }
  });

  // Ajusta a sidebar ao redimensionar a janela
  let resizeTimer;

  window.addEventListener("resize", () => {
    clearTimeout(resizeTimer);

    resizeTimer = setTimeout(
      () => {
        if (window.innerWidth >= 992) {
          body.classList.remove("sidebar-open");
          sidebarOverlay.classList.remove("active");
        }
      },

      250
    );
  });
});
