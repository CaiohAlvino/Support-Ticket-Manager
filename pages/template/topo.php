< !DOCTYPE html>
    <html lang="pt-BR">

    </html>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Sistema de Tickets - Gerenciamento de Suporte</title>
        <meta name="robots" content="noindex, nofollow">
        <link rel="icon" href="../../assets/favicon.webp" type="image/x-icon">
        <link rel="stylesheet" href="../../libs/bootstrap/style.css">
        <link rel="stylesheet" href="../../libs/bootstrap/icons/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../../libs/select2/style.css">
        <link rel="stylesheet" href="../../libs/noty/style.css">
        <link rel="stylesheet" href="../../css/main.css">
        <link rel="stylesheet" href="../../css/components/layout.css">
        <link rel="stylesheet" href="../../css/components/cards.css">
        <link rel="stylesheet" href="../../css/pages/dashboard.css">
    </head>

    <body><?php require("../../config/Database.php");
            ?><header class="header">
            <div class="header-left"><button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu"><i class="bi bi-list"></i></button><img src="../../assets/logo.png" class="img-fluid" alt="Logo Sistema" width="110" heigth="auto"></div>
            <div class="header-right">
                <div class="dropdown"><button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                        aria-expanded="false"><i class="bi bi-person-circle"></i><span class="user-name"></span></button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="../usuario/perfil.php"><i class="bi bi-person"></i>Meu Perfil </a></li>
                        <li><a class="dropdown-item" href="../configuracoes/index.php"><i class="bi bi-gear"></i>Configurações </a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><button class="dropdown-item text-danger" id="botao-sair" data-action="login/sair.php"><i class="bi bi-box-arrow-right"></i>Sair </button></li>
                    </ul>
                </div>
            </div>
        </header>
        <!-- Sidebar -->
        <nav class="sidebar" id="sidebar">
            <div class="menu-list"><a href="../home/index.php" class="menu-item"><i class="bi bi-grid-1x2-fill"></i><span class="menu-text">Dashboard</span></a><a href="../tickets/meus-tickets.php" class="menu-item"><i class="bi bi-ticket-perforated"></i><span class="menu-text">Meus Tickets</span></a><a href="../tickets/todos-tickets.php" class="menu-item"><i class="bi bi-list-ul"></i><span class="menu-text">Todos os Tickets</span></a><a href="../categorias/index.php" class="menu-item"><i class="bi bi-tag"></i><span class="menu-text">Categorias</span></a><a href="../relatorios/index.php" class="menu-item"><i class="bi bi-graph-up"></i><span class="menu-text">Relatórios</span></a></div>
        </nav>
        < !-- Overlay para menu mobile -->
            <div class="sidebar-overlay" id="sidebarOverlay"></div>
            <main class="main-content" id="mainContent">