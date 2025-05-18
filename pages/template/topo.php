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
    <link rel="stylesheet" href="../../css/components/cards.css">
    <link rel="stylesheet" href="../../css/components/centraliza.css">
    <link rel="stylesheet" href="../../css/components/excluir.css">
    <link rel="stylesheet" href="../../css/components/hover.css">
    <link rel="stylesheet" href="../../css/components/layout.css">
    <link rel="stylesheet" href="../../css/components/sessao.css">
    <link rel="stylesheet" href="../../css/pages/cliente.css">
    <link rel="stylesheet" href="../../css/pages/dashboard.css">
    <link rel="stylesheet" href="../../css/pages/empresa.css">
    <link rel="stylesheet" href="../../css/pages/grupo.css">
    <link rel="stylesheet" href="../../css/pages/home.css">
    <link rel="stylesheet" href="../../css/pages/login.css">
    <link rel="stylesheet" href="../../css/pages/servico.css">
    <link rel="stylesheet" href="../../css/pages/suporte.css">
    <link rel="stylesheet" href="../../css/pages/tickets.css">
    <link rel="stylesheet" href="../../css/pages/usuario.css">
    <link rel="stylesheet" href="../../css/theme/variables.css">
</head>
<?php require("../../config/Database.php");
require("../../config/Cliente.php");
require("../../config/Validador.php");
require("../../config/Suporte.php");
require("../../config/SuporteMensagem.php");
require("../../config/Estado.php");
$db = new Database();
$cliente = new Cliente($db->getConnection());
$suporte = new Suporte($db->getConnection());
$suporteMensagem = new SuporteMensagem($db->getConnection());
$validacao = new Validador($db->getConnection());
?>

<body class="layout-fixed">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="d-flex align-items-center gap-3"><button class="menu-toggle d-lg-none btn btn-link p-0 me-2" id="menuToggle" aria-label="Toggle Menu"><i class="bi bi-list fs-2"></i></button><a href="../home/index.php" class="logo d-flex align-items-center text-decoration-none"><i class="bi bi-ticket-perforated fs-3 me-2"></i><span class="brand-text fw-bold fs-4">Support</span></a></div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown"><button class="dropdown-toggle btn btn-light d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle fs-4"></i><?php session_start();

                                                                                                                                                                                                                                            if (isset($_SESSION['usuario_nome'])) {
                                                                                                                                                                                                                                                echo '<span class="user-name">' . htmlspecialchars($_SESSION['usuario_nome']) . '</span>';
                                                                                                                                                                                                                                            } else {
                                                                                                                                                                                                                                                echo '<span class="user-name">Usuário</span>';
                                                                                                                                                                                                                                            }

                                                                                                                                                                                                                                            ?></button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="../usuario/perfil.php"><i class="bi bi-person me-2"></i>Meu Perfil</a></li>
                        <li><a class="dropdown-item" href="../configuracoes/index.php"><i class="bi bi-gear me-2"></i>Configurações</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item text-danger" href="../login/sair.php"><i class="bi bi-box-arrow-right me-2"></i>Sair</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-nav">
            <div class="sidebar-section mb-2"><small class="text-muted text-uppercase ms-2">Tickets</small><a href="../home/index.php" class="sidebar-link menu-item"><i class="bi bi-grid-1x2-fill"></i>Dashboard</a><a href="../suporte/index.php" class="sidebar-link menu-item"><i class="bi bi-list-ul"></i>Todos os Tickets</a><a href="../suporte/meus-tickets.php" class="sidebar-link menu-item"><i class="bi bi-ticket-fill"></i>Meus Tickets</a></div>
            <div class="sidebar-section mb-2"><small class="text-muted text-uppercase ms-2">Cadastros</small><a href="../empresa/index.php" class="sidebar-link menu-item"><i class="bi bi-building"></i>Empresas</a><a href="../servico/index.php" class="sidebar-link menu-item"><i class="bi bi-award-fill"></i>Serviço</a><a href="../cliente/index.php" class="sidebar-link menu-item"><i class="bi bi-people"></i>Clientes</a></div>
            <div class="sidebar-section"><small class="text-muted text-uppercase ms-2">Configurações</small><a href="../usuario/index.php" class="sidebar-link menu-item"><i class="bi bi-person"></i>Usuários</a><a href="../grupo/index.php" class="sidebar-link menu-item"><i class="bi bi-shield-lock"></i>Grupo</a></div>
        </div>
    </nav>
    <!-- Overlay para menu mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <!-- Main Content -->
    <main class="main-content" id="mainContent">