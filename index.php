<?php
require_once 'init.php';

// Se já estiver logado, redireciona para a página principal
if (isset($_SESSION['token']) && JWT::verificar($db)) {
    header('Location: ' . BASE_URL . '/pages/dashboard.php');
    exit;
}

// Processar formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $erros = [];

    if (empty($email) || empty($senha)) {
        $erros[] = 'Por favor, preencha todos os campos';
    } else {
        $stmt = $db->prepare("
            SELECT u.*, g.nome as grupo_nome, eu.empresa_id 
            FROM usuario u 
            INNER JOIN grupo g ON u.grupo_id = g.id
            LEFT JOIN empresa_usuario eu ON u.id = eu.usuario_id
            WHERE u.email = :email
        ");

        $stmt->execute([':email' => $email]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Gerar token JWT
            $token = JWT::criar([
                'id' => $usuario['id'],
                'nome' => $usuario['nome'],
                'email' => $usuario['email'],
                'grupo' => $usuario['grupo_nome'],
                'empresa_id' => $usuario['empresa_id']
            ]);

            $_SESSION['token'] = $token;
            $_SESSION['usuario'] = (object) $usuario;
            header('Location: ' . BASE_URL . '/pages/dashboard.php');
            exit;
        } else {
            $erros[] = 'Email ou senha inválidos';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema de Suporte</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bi bi-headset display-1 text-primary"></i>
                            <h2 class="mt-3">Sistema de Suporte</h2>
                            <p class="text-muted">Faça login para continuar</p>
                        </div>

                        <?php if (!empty($erros)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($erros as $erro): ?>
                                        <li><?php echo $erro; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <?php
                        if (isset($_SESSION['mensagem'])) {
                            $tipo = isset($_SESSION['mensagem_tipo']) ? $_SESSION['mensagem_tipo'] : 'info';
                            echo "<div class='alert alert-{$tipo}'>{$_SESSION['mensagem']}</div>";
                            unset($_SESSION['mensagem'], $_SESSION['mensagem_tipo']);
                        }
                        ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Entrar</button>
                                <a href="pages/registro.php" class="btn btn-outline-secondary">Criar Nova Conta</a>
                            </div>

                            <div class="text-center mt-3">
                                <a href="pages/recuperar-senha.php" class="text-decoration-none">Esqueceu sua senha?</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>