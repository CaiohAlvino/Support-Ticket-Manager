document.addEventListener("DOMContentLoaded", function () {
    const body = document.body;
    const sidebar = document.getElementById("sidebar");
    let sidebarOverlay = document.getElementById("sidebarOverlay");
    const menuToggle = document.getElementById("menuToggle");

    // Cria overlay se não existir
    if (!sidebarOverlay && sidebar) {
        sidebarOverlay = document.createElement("div");
        sidebarOverlay.id = "sidebarOverlay";
        sidebarOverlay.className = "sidebar-overlay";
        sidebar.parentNode.insertBefore(sidebarOverlay, sidebar.nextSibling);
    }

    if (sidebar && menuToggle) {
        // Estado inicial da sidebar (baseado no localStorage)
        const sidebarCollapsed = localStorage.getItem("sidebarCollapsed") === "true";
        if (sidebarCollapsed && window.innerWidth >= 992) {
            body.classList.add("sidebar-collapsed");
        }

        function openMobileSidebar() {
            sidebar.classList.add("sidebar-open");
            if (sidebarOverlay) sidebarOverlay.classList.add("active");
            body.classList.add("sidebar-open");
        }
        function closeMobileSidebar() {
            sidebar.classList.remove("sidebar-open");
            if (sidebarOverlay) sidebarOverlay.classList.remove("active");
            body.classList.remove("sidebar-open");
        }
        function toggleDesktopSidebar() {
            body.classList.toggle("sidebar-collapsed");
            localStorage.setItem("sidebarCollapsed", body.classList.contains("sidebar-collapsed"));
            window.dispatchEvent(new Event("resize"));
        }

        menuToggle.addEventListener("click", function (e) {
            e.preventDefault();
            if (window.innerWidth < 992) {
                if (sidebar.classList.contains("sidebar-open")) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            } else {
                toggleDesktopSidebar();
            }
        });
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener("click", closeMobileSidebar);
        }

        // Fecha a sidebar ao clicar em um link (apenas em mobile)
        document.querySelectorAll(".sidebar .menu-item").forEach(item => {
            item.addEventListener("click", () => {
                if (window.innerWidth < 992 && sidebar.classList.contains("sidebar-open")) {
                    closeMobileSidebar();
                }
            });
        });

        // Marca o item ativo no menu
        const currentPath = window.location.pathname;
        document.querySelectorAll(".sidebar .menu-item").forEach(item => {
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
            resizeTimer = setTimeout(() => {
                if (window.innerWidth >= 992) {
                    closeMobileSidebar();
                    // Restaura estado collapsed do desktop
                    if (localStorage.getItem("sidebarCollapsed") === "true") {
                        body.classList.add("sidebar-collapsed");
                    } else {
                        body.classList.remove("sidebar-collapsed");
                    }
                } else {
                    // Sempre fecha a sidebar no mobile ao redimensionar
                    closeMobileSidebar();
                }
            }, 250);
        });
    }
});
