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

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: index.php');
    exit;
}

// Buscar ticket
$stmt = $db->prepare("
    SELECT s.*, serv.nome as servico_nome
    FROM suporte s
    INNER JOIN servico serv ON s.servico_id = serv.id
    WHERE s.id = :id AND s.cliente_id = :cliente_id AND s.situacao = 1
");
$stmt->execute([
    ':id' => $id,
    ':cliente_id' => $_SESSION['usuario']->id
]);
$ticket = $stmt->fetch(PDO::FETCH_OBJ);

if (!$ticket) {
    header('Location: index.php');
    exit;
}

// Buscar mensagens do ticket
$stmt = $db->prepare("
    SELECT *
    FROM suporte_mensagem
    WHERE suporte_id = :suporte_id
    ORDER BY cadastrado ASC
");
$stmt->execute([':suporte_id' => $id]);
$mensagens = $stmt->fetchAll(PDO::FETCH_OBJ);

// Processar nova mensagem
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $ticket->status !== 'FECHADO') {
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

    if (!empty($mensagem)) {
        try {
            $db->beginTransaction();

            // Inserir mensagem
            $stmt = $db->prepare("
                INSERT INTO suporte_mensagem (
                    suporte_id, mensagem, proprietario
                ) VALUES (
                    :suporte_id, :mensagem, 'CLIENTE'
                )
            ");

            $stmt->execute([
                ':suporte_id' => $id,
                ':mensagem' => $mensagem
            ]);

            // Atualizar status do ticket
            $stmt = $db->prepare("
                UPDATE suporte 
                SET status = 'AGUARDANDO_SUPORTE'
                WHERE id = :id
            ");
            $stmt->execute([':id' => $id]);

            $db->commit();
            header("Location: detalhes.php?id={$id}");
            exit;
        } catch (Exception $e) {
            $db->rollback();
            $erro = 'Erro ao enviar mensagem. Por favor, tente novamente.';
        }
    }
}

require_once '../../templates/header.php';
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Ticket #<?php echo $ticket->id; ?></h2>
                <a href="index.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Voltar
                </a>
            </div>

            <?php if (isset($erro)): ?>
                <div class="alert alert-danger">
                    <?php echo $erro; ?>
                </div>
            <?php endif; ?>

            <!-- Informações do Ticket -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Serviço:</strong> <?php echo htmlspecialchars($ticket->servico_nome); ?></p>
                            <p><strong>Assunto:</strong> <?php echo htmlspecialchars($ticket->assunto); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Status:</strong>
                                <?php
                                $status_class = '';
                                switch ($ticket->status) {
                                    case 'ABERTO':
                                        $status_class = 'bg-success';
                                        break;
                                    case 'AGUARDANDO_SUPORTE':
                                        $status_class = 'bg-warning';
                                        break;
                                    case 'RESPONDIDO':
                                        $status_class = 'bg-info';
                                        break;
                                    case 'FECHADO':
                                        $status_class = 'bg-secondary';
                                        break;
                                }
                                ?>
                                <span class="badge <?php echo $status_class; ?>">
                                    <?php echo $ticket->status; ?>
                                </span>
                            </p>
                            <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($ticket->cadastrado)); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagens -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Mensagens</h5>
                </div>
                <div class="card-body">
                    <?php foreach ($mensagens as $mensagem): ?>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>
                                    <?php echo $mensagem->proprietario === 'CLIENTE' ? 'Você' : 'Suporte'; ?>
                                </strong>
                                <small class="text-muted">
                                    <?php echo date('d/m/Y H:i', strtotime($mensagem->cadastrado)); ?>
                                </small>
                            </div>
                            <div class="p-3 rounded <?php echo $mensagem->proprietario === 'CLIENTE' ? 'bg-light' : 'bg-primary text-white'; ?>">
                                <?php echo nl2br(htmlspecialchars($mensagem->mensagem)); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Formulário de Resposta -->
            <?php if ($ticket->status !== 'FECHADO'): ?>
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Responder</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="mensagem" class="form-label">Mensagem</label>
                                <textarea class="form-control" id="mensagem" name="mensagem" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>