<?php
require_once '../../init.php';

// Verificar se o usuário está logado
if (!isset($_SESSION['token']) || !JWT::verificar($db)) {
    header('Location: ../../index.php');
    exit;
}

// Verificar se o usuário é um cliente
if ($_SESSION['usuario']->grupo_id != 2) { // 2 = ID do grupo Usuário
    header('Location: ../../pages/dashboard.php');
    exit;
}

$erros = [];

// Buscar serviços disponíveis para a empresa do usuário
$stmt = $db->prepare("
    SELECT s.* 
    FROM servico s
    INNER JOIN empresa_servico es ON s.id = es.servico_id
    WHERE es.empresa_id = :empresa_id AND s.situacao = 1
    ORDER BY s.nome
");
$stmt->execute([':empresa_id' => $_SESSION['usuario']->empresa_id]);
$servicos = $stmt->fetchAll(PDO::FETCH_OBJ);

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servico_id = filter_input(INPUT_POST, 'servico_id', FILTER_VALIDATE_INT);
    $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

    // Validações
    if (empty($servico_id)) {
        $erros[] = 'Selecione um serviço';
    }

    if (empty($assunto)) {
        $erros[] = 'O assunto é obrigatório';
    }

    if (empty($mensagem)) {
        $erros[] = 'A mensagem é obrigatória';
    }

    if (empty($erros)) {
        try {
            $db->beginTransaction();

            // Inserir ticket
            $stmt = $db->prepare("
                INSERT INTO suporte (
                    empresa_id, servico_id, cliente_id, assunto, status
                ) VALUES (
                    :empresa_id, :servico_id, :cliente_id, :assunto, 'ABERTO'
                )
            ");

            $stmt->execute([
                ':empresa_id' => $_SESSION['usuario']->empresa_id,
                ':servico_id' => $servico_id,
                ':cliente_id' => $_SESSION['usuario']->id,
                ':assunto' => $assunto
            ]);

            $suporte_id = $db->lastInsertId();

            // Inserir primeira mensagem
            $stmt = $db->prepare("
                INSERT INTO suporte_mensagem (
                    suporte_id, mensagem, proprietario
                ) VALUES (
                    :suporte_id, :mensagem, 'CLIENTE'
                )
            ");

            $stmt->execute([
                ':suporte_id' => $suporte_id,
                ':mensagem' => $mensagem
            ]);

            $db->commit();
            header('Location: index.php?sucesso=1');
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $erros[] = 'Erro ao criar ticket. Por favor, tente novamente.';
        }
    }
}

require_once '../../templates/header.php';
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Novo Ticket</h2>
                </div>
                <div class="card-body">
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
                            <label for="servico_id" class="form-label">Serviço</label>
                            <select class="form-select" id="servico_id" name="servico_id" required>
                                <option value="">Selecione um serviço</option>
                                <?php foreach ($servicos as $servico): ?>
                                    <option value="<?php echo $servico->id; ?>">
                                        <?php echo htmlspecialchars($servico->nome); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto</label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>

                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem</label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Enviar Ticket</button>
                            <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>