<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Tickets - Login</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="libs/bootstrap/style.css">
    <link rel="stylesheet" href="libs/bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/pages/login.css">
</head>

<body class="body-login">
    <div class="login-container">
        <div class="row">
            <div class="col-lg-6 login-illustration">
                <div class="text-center"><i class="bi bi-ticket-perforated"></i></div>
                <h2 class="welcome-text">Sistema de Gerenciamento de Tickets</h2>
                <p class="welcome-subtext">Gerencie seus tickets de suporte de forma eficiente e organizada. Acompanhe,
                    responda e resolva solicitações em um só lugar.</p>
            </div>
            <div class="col-lg-6 sessao login-form">
                <div class="text-center">
                    <h2 class="login-title">Acesso ao Sistema</h2>
                </div>
                <form id="login" data-action="login/logar.php"><input type="hidden" name="empresa_id"><input type="hidden" name="email">
                    <div class="mb-4"><label class="form-label">E-mail</label><input type="email" id="input-usuario-email" class="form-control"
                            placeholder="Digite seu e-mail profissional"></div>
                    <div id="login-empresas"></div>
                    <div class="mb-3"><label for="senha" class="form-label">Senha</label>
                        <div class="password-field"><input id="senha" type="password" name="senha" class="form-control"
                                placeholder="Digite sua senha"><button type="button" class="password-toggle"><i class="bi bi-eye"></i></button></div>
                    </div><button type="submit" class="btn btn-login">Entrar no Sistema</button>
                    <!-- <div class="text-center"><a href="esqueciminhasenha.php" class="esqueceu-senha">Esqueceu a senha?</a></div> -->
                </form>
            </div>
        </div>
    </div>
    <script src="./libs/jquery/script.js"></script>
    <script src="./libs/bootstrap/script.js"></script>
    <script src="./js/pages/login.js"></script>
</body>

</html>