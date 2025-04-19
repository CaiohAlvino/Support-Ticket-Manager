<?php
require_once '../init.php';

// Se já estiver logado, redireciona para a página principal
if (isset($_SESSION['token']) && JWT::verificar($db)) {
    header('Location: dashboard.php');
    exit;
}

// Processar formulário de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $senha = $_POST['senha'];
    $confirmar_senha = $_POST['confirmar_senha'];

    $erros = [];

    // Validações
    if (empty($nome)) {
        $erros[] = 'O nome é obrigatório';
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = 'Email inválido';
    } else {
        // Verificar se email já existe
        $stmt = $db->prepare("SELECT id FROM usuario WHERE email = :email");
        $stmt->execute([':email' => $email]);
        if ($stmt->rowCount() > 0) {
            $erros[] = 'Este email já está cadastrado';
        }
    }

    if (strlen($senha) < 6) {
        $erros[] = 'A senha deve ter no mínimo 6 caracteres';
    }

    if ($senha !== $confirmar_senha) {
        $erros[] = 'As senhas não conferem';
    }

    // Se não houver erros, criar o usuário
    if (empty($erros)) {
        try {
            $db->beginTransaction();

            // Inserir usuário como cliente (grupo_id = 2)
            $stmt = $db->prepare("
                INSERT INTO usuario (grupo_id, nome, email, senha) 
                VALUES (2, :nome, :email, :senha)
            ");

            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);

            $stmt->execute([
                ':nome' => $nome,
                ':email' => $email,
                ':senha' => $senha_hash
            ]);

            $usuario_id = $db->lastInsertId();

            // Vincular usuário à empresa padrão
            $stmt = $db->prepare("
                INSERT INTO empresa_usuario (usuario_id, empresa_id) 
                VALUES (:usuario_id, :empresa_id)
            ");

            $stmt->execute([
                ':usuario_id' => $usuario_id,
                ':empresa_id' => 1 // Empresa padrão
            ]);

            $db->commit();
            header('Location: ../index.php?sucesso=1');
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $erros[] = 'Erro ao criar usuário. Por favor, tente novamente.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Sistema de Suporte</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-plus display-1 text-primary"></i>
                            <h2 class="mt-3">Criar Conta</h2>
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

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo</label>
                                <input type="text" class="form-control" id="nome" name="nome"
                                    value="<?php echo isset($nome) ? htmlspecialchars($nome) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label">Senha</label>
                                <input type="password" class="form-control" id="senha" name="senha"
                                    minlength="6" required>
                                <div class="form-text">A senha deve ter no mínimo 6 caracteres</div>
                            </div>

                            <div class="mb-3">
                                <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
                                <input type="password" class="form-control" id="confirmar_senha"
                                    name="confirmar_senha" required>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Criar Conta</button>
                                <a href="../index.php" class="btn btn-outline-secondary">Voltar para Login</a>
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