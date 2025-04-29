<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - Gerenciamento de Suporte</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="../../libs/bootstrap/style.css">
    <link rel="stylesheet" href="../../libs/bootstrap/icons/font/bootstrap-icons.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="../../libs/select2/style.css">
    <!-- Noty -->
    <link rel="stylesheet" href="../../libs/noty/style.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../../css/main.css">
    <link rel="stylesheet" href="../../css/components/layout.css">
    <link rel="stylesheet" href="../../css/components/cards.css">
    <link rel="stylesheet" href="../../css/pages/dashboard.css">
</head>

<body class="layout-fixed">
    <?php require("../../config/Database.php"); ?>

    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                <i class="bi bi-list"></i>
            </button>
            <a href="../home/index.php" class="header-brand">
                <i class="bi bi-ticket-perforated"></i>
                <span class="brand-text">Support</span>
            </a>
        </div>
        <div class="header-right">
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span class="user-name ms-2">Usuário</span>
                    <i class="bi bi-chevron-down ms-1"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="../usuario/perfil.php">
                            <i class="bi bi-person"></i>
                            <span>Meu Perfil</span>
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../configuracoes/index.php">
                            <i class="bi bi-gear"></i>
                            <span>Configurações</span>
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <button class="dropdown-item text-danger" id="botao-sair" data-action="login/sair.php">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Sair</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="menu-list">
            <a href="../home/index.php" class="menu-item active">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="../tickets/meus-tickets.php" class="menu-item">
                <i class="bi bi-ticket-fill"></i>
                <span class="menu-text">Meus Tickets</span>
            </a>
            <a href="../tickets/todos-tickets.php" class="menu-item">
                <i class="bi bi-list-ul"></i>
                <span class="menu-text">Todos os Tickets</span>
            </a>
            <a href="../categorias/index.php" class="menu-item">
                <i class="bi bi-tag"></i>
                <span class="menu-text">Categorias</span>
            </a>
            <a href="../relatorios/index.php" class="menu-item">
                <i class="bi bi-graph-up"></i>
                <span class="menu-text">Relatórios</span>
            </a>
        </div>
    </nav>

    <!-- Overlay para menu mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="container-fluid">