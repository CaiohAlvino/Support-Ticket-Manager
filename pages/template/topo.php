<!DOCTYPE html>
<html lang="pt-BR">

</html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinora - Software para clínicas e consultórios</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="../../assets/favicon.webp" type="image/x-icon">
    <link rel="stylesheet" href="../../libs/bootstrap/style.css">
    <link rel="stylesheet" href="../../libs/bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../../libs/select2/style.css">
    <link rel="stylesheet" href="../../libs/noty/style.css">
    <link rel="stylesheet" href="../../css/main.css">
</head>

<body>
    <?php require("../../config/Database.php"); ?>

    <header class="header">
        <div class="header-left">
            <button class="menu-toggle" id="menuToggle" aria-label="Toggle Menu">
                <i class="bi bi-list"></i>
            </button>
            <img src="../../assets/logo-clinora.png" class="img-fluid" alt="logo clinora" width="110" heigth="auto">
        </div>
        <div class=" header-right">
            <div class="modulos-icon-container">
                <a href="../modulos/index.php" class="modulos-icon" title="Módulos Adicionais">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </a>
            </div>
            <div class="dropdown">
                <button class="dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                    <span class="user-name"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item"
                            href="../usuario/edicao.php?id=<?php echo $_SESSION["usuario"]->id; ?>">
                            <i class="bi bi-person"></i> Meu Perfil
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../empresa/detalhes.php">
                            <i class="bi bi-building"></i> Dados Cadastrais
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../configuracao/index.php">
                            <i class="bi bi-gear"></i> Configurações
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="../historico-pagamento/index.php">
                            <i class="bi bi-list-check"></i> Histórico de pagamentos
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <button class="dropdown-item text-danger" id="botao-sair" data-action="login/sair.php">
                            <i class="bi bi-box-arrow-right"></i> Sair
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="menu-list">
            <a href="../dashboard/index.php"
                class="menu-item">
                <i class="bi bi-grid-1x2-fill"></i>
                <span class="menu-text">Dashboard</span>
            </a>
            <a href="../calendario/index.php"
                class="menu-item <?php echo $ultimaPasta == 'calendario' ? 'active' : '' ?>">
                <i class="bi bi-calendar-week"></i>
                <span class=" menu-text">Agenda</span>
            </a>
            <a href="../cliente/index.php" class="menu-item">
                <i class="bi bi-people"></i>
                <span class="menu-text">Clientes</span>
            </a>
            <a href="../formulario/index.php" class="menu-item">
                <i class="bi bi-list-check"></i>
                <span class="menu-text">Formulários</span>
            </a>

            <a href="../fornecedor/index.php" class="menu-item">
                <i class="bi bi-shop"></i>
                <span class="menu-text">Fornecedor</span>
            </a>

            <a href="../recebimento/index.php" class="menu-item">
                <i class="bi bi-receipt"></i>
                <span class=" menu-text">Recebimento</span>
            </a>

            <a href="../profissional/index.php" class="menu-item">
                <i class="bi bi-people"></i>
                <span class="menu-text">Profissionais</span>
            </a>

            <div class="menu-item has-submenu"
                id="menuUsuarios">
                <div class="menu-header">
                    <i class="bi bi-headset"></i>
                    <span class="menu-text">Suporte</span>
                    <i class="bi bi-chevron-down submenu-icon"></i>
                </div>
                <div class="submenu">
                    <a class="submenu-item" href="../suporte/index.php">
                        <i class="bi bi-headset"></i>
                        Solicitar suporte
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Overlay para menu mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content" id="mainContent">

        <div class="container-fluid mb-3">
            <div class="alert alerta-sem-cadastrar text-center text-center" role="alert">
                Bem vindo ao Clinora. Seu período de avaliação termina em X dias.
                <a href="#" class="btn btn-sm btn-primary ms-2">Contrate agora</a>
            </div>
        </div>