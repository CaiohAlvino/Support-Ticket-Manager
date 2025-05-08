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

<?php require("../../config/Database.php"); ?>

<body class="layout-fixed">

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
                <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span class="user-name ms-2">Usuário</span>
                    <!-- <i class="bi bi-chevron-down ms-1"></i> -->
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
            <a href="../suporte/index.php" class="menu-item">
                <i class="bi bi-list-ul"></i>
                <span class="menu-text">Todos os Tickets</span>
            </a>
            <a href="../suporte/meus-tickets.php" class="menu-item">
                <i class="bi bi-ticket-fill"></i>
                <span class="menu-text">Meus Tickets</span>
            </a>
            <a href="../empresa/index.php" class="menu-item">
                <i class="bi bi-building"></i>
                <span class="menu-text">Empresas</span>
            </a>
            <a href="../servico/index.php" class="menu-item">
                <i class="bi bi-award-fill"></i>
                <span class="menu-text">Serviço</span>
            </a>
            <a href="../cliente/index.php" class="menu-item">
                <i class="bi bi-people"></i>
                <span class="menu-text">Clientes</span>
            </a>
            <a href="../fornecedor/index.php" class="menu-item">
                <i class="bi bi-shop"></i>
                <span class="menu-text">Fornecedores</span>
            </a>
            <a href="../usuario/index.php" class="menu-item">
                <i class="bi bi-person"></i>
                <span class="menu-text">Usuários</span>
            </a>
            <a href="../grupo/index.php" class="menu-item">
                <i class="bi bi-shield-lock"></i>
                <span class="menu-text">Grupo</span>
            </a>
        </div>
    </nav>

    <!-- Overlay para menu mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="container-fluid">