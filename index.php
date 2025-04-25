<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Tickets</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="stylesheet" href="libs/bootstrap/style.css">
    <link rel="stylesheet" href="libs/bootstrap/icons/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/interface/login.css">
</head>

<body class="body-login">
    <div class="login-container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login-illustration">
                    <div class="text-center">
                        <img src="./assets/logo-clinora.png" width="250px" heigth="auto" alt="Logo Clinora" class="img-fluid">
                    </div>
                    <h2 class="welcome-text">Software para clínicas e consultórios</h2>
                    <p class="welcome-subtext">Simplifique processos, otimize tempo e melhore atendimentos.</p>
                </div>
            </div>
            <div class="col-lg-6 sessao login-form">
                <div class="text-center">
                    <h2 class="login-title">Acesso ao Sistema</h2>
                </div>
                <form id="autenticar" data-action="login/autenticar.php">
                    <div>
                        <label for="email" class="form-label">E-mail</label>
                        <input id="email" type="email" name="email" class="form-control"
                            placeholder="Digite seu e-mail profissional">
                    </div>
                    <button type="submit" class="btn btn-login my-3">Entrar no Sistema</button>
                    <div class="text-center">
                        <a href="esqueciminhasenha.php" class="esqueceu-senha">Esqueceu a senha?</a>
                    </div>
                    <div class="text-center mt-4 create-account">
                        <p>Não tem uma conta? <a href="cadastro-sistema.php" class="create-account-link">Criar Grátis</a></p>
                    </div>
                </form>

                <form id="login" data-action="login/logar.php">
                    <input type="hidden" name="empresa_id">
                    <input type="hidden" name="email">
                    <div>
                        <label class="form-label">E-mail</label>
                        <input type="email" id="input-usuario-email" class="form-control" disabled
                            placeholder="Digite seu e-mail profissional">
                    </div>
                    <div id="login-empresas"></div>
                    <div>
                        <label for="senha" class="form-label">Senha</label>
                        <div class="password-field">
                            <input id="senha" type="password" name="senha" class="form-control"
                                placeholder="Digite sua senha">
                            <button type="button" class="password-toggle">
                                <i class="fa-regular fa-eye me-2"></i>
                            </button>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login my-3">Entrar no Sistema</button>
                    <div class="text-center">
                        <a href="esqueciminhasenha.php" class="esqueceu-senha">Esqueceu a senha?</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="./libs/jquery/script.js"></script>
    <script src="./libs/bootstrap/script.js"></script>
    <script src="./js/login.js"></script>
</body>

</html>