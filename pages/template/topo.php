<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - Gerenciamento de Suporte</title>
    <meta name="robots" content="noindex, nofollow">
    <!-- Bootstrap e ícones -->
    <link rel="stylesheet" href="../../libs/bootstrap/style.css">
    <link rel="stylesheet" href="../../libs/bootstrap/icons/font/bootstrap-icons.css">
    <!-- CSS principal centralizado -->
    <link rel="stylesheet" href="../../css/main.css">
    <!-- Bibliotecas extras -->
    <link rel="stylesheet" href="../../libs/select2/style.css">
    <link rel="stylesheet" href="../../libs/noty/style.css">
</head>
<?php
require("../../config/Database.php");
require("../../config/Validador.php");
require("../../config/Estado.php");

require("../../config/Cliente.php");
require("../../config/Suporte.php");
require("../../config/SuporteMensagem.php");
require("../../config/Empresa.php");
require("../../config/Grupo.php");
require("../../config/Servico.php");
require("../../config/Usuario.php");
require("../../config/EmpresaCliente.php");
require("../../config/EmpresaServico.php");
require("../../config/EmpresaUsuario.php");
require("../../config/JWT.php");

$db = new Database();
$validador = new Validador();
$estado = new Estado();

$classCliente = new Cliente($db->getConnection());
$classSuporte = new Suporte($db->getConnection());
$classSuporteMensagem = new SuporteMensagem($db->getConnection());
$classEmpresa = new Empresa($db->getConnection());
$classGrupo = new Grupo($db->getConnection());
$classServico = new Servico($db->getConnection());
$classUsuario = new Usuario($db->getConnection());
$classEmpresaCliente = new EmpresaCliente($db->getConnection());
$classEmpresaServico = new EmpresaServico($db->getConnection());
$classEmpresaUsuario = new EmpresaUsuario($db->getConnection());
?>

<body class="layout-fixed">
    <!-- Header -->
    <header class="header nao-mostrar-impressao">
        <div class="header-content">
            <div class="d-flex align-items-center gap-3">
                <button class="menu-toggle btn btn-link p-0 me-2" id="menuToggle" aria-label="Menu lateral" title="Menu lateral"><i class="bi bi-list fs-2"></i></button>
                <a href="../home/index.php" class="logo d-flex align-items-center text-decoration-none"><i class="bi bi-ticket-perforated fs-3 me-2"></i><span class="brand-text fw-bold fs-4">Support</span></a>
            </div>
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="dropdown-toggle btn btn-light d-flex align-items-center gap-2" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle fs-4"></i>
                        <?php

                        if (isset($_SESSION['usuario_nome'])) {
                            echo '<span class="user-name">' . htmlspecialchars($_SESSION['usuario_nome']) . '</span>';
                        } else {
                            echo '<span class="user-name">Usuário</span>';
                        }

                        ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <!-- <li>
                            <a class="dropdown-item" href="../usuario/perfil.php">
                                <i class="bi bi-person me-2"></i>
                                Meu Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="../configuracoes/index.php">
                                <i class="bi bi-gear me-2"></i>
                                Configurações
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li> -->
                        <li>
                            <a class="dropdown-item text-danger" href="../../controller/login/sair.php" id="logoutButton">
                                <i class="bi bi-box-arrow-right me-2"></i>
                                Sair
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>
    <!-- Sidebar -->
    <nav class="sidebar nao-mostrar-impressao" id="sidebar">
        <div class="sidebar-nav">
            <div class="sidebar-section mb-2">
                <small class="text-muted text-uppercase ms-2">
                    Tickets
                </small>
                <a href="../home/index.php" class="sidebar-link menu-item">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
                <?php if ($_SESSION["usuario_grupo"] != 2): ?>
                    <a href="../suporte/index.php" class="sidebar-link menu-item">
                        <i class="bi bi-list-ul"></i>
                        <span class="menu-text">Todos os Tickets</span>
                    </a>
                <?php endif; ?>
                <a href="../suporte/meus-tickets.php" class="sidebar-link menu-item">
                    <i class="bi bi-ticket-fill"></i>
                    <span class="menu-text">Meus Tickets</span>
                </a>
            </div>
            <?php if ($_SESSION["usuario_grupo"] != 2): ?>
                <div class="sidebar-section mb-2">
                    <small class="text-muted text-uppercase ms-2">Cadastros</small>
                    <a href="../empresa/index.php" class="sidebar-link menu-item">
                        <i class="bi bi-building"></i>
                        <span class="menu-text">Empresas</span>
                    </a>
                    <a href="../servico/index.php" class="sidebar-link menu-item">
                        <i class="bi bi-award-fill"></i>
                        <span class="menu-text">Serviço</span>
                    </a>
                    <a href="../cliente/index.php" class="sidebar-link menu-item">
                        <i class="bi bi-people"></i>
                        <span class="menu-text">Clientes</span>
                    </a>
                </div>
                <?php if ($_SESSION["usuario_grupo"] == 1): ?>
                    <div class="sidebar-section">
                        <small class="text-muted text-uppercase ms-2">Configurações</small>
                        <a href="../usuario/index.php" class="sidebar-link menu-item">
                            <i class="bi bi-person"></i>
                            <span class="menu-text">Usuários</span>
                        </a>
                        <a href="../grupo/index.php" class="sidebar-link menu-item">
                            <i class="bi bi-shield-lock"></i>
                            <span class="menu-text">Grupo</span>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </nav>
    <!-- Main Content -->
    <main class="main-content" id="mainContent">
        <div class="cabecalho mostrar-impressao">
            <div class="cabecalho-impressao">
                <div class="cabecalho-impressao-logo">
                    <div class="cabecalho-impressao-info">
                        <div class="logo d-flex align-items-center text-decoration-none"><i class="bi bi-ticket-perforated fs-3 me-2"></i><span class="fw-bold fs-4">Support Ticket Manager</span></div>
                        <span class="text-muted">Gerenciamento de Suporte</span>
                    </div>
                </div>
                <div class="cabecalho-impressao-dados">
                    <div><strong>Usuário:</strong> <?php echo isset($_SESSION['usuario_nome']) ? htmlspecialchars($_SESSION['usuario_nome']) : 'Usuário'; ?></div>
                    <div><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i'); ?></div>
                </div>
            </div>
        </div>
        <!-- Conteúdo principal aqui -->
