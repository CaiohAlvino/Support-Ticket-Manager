<?php
require_once '../../templates/header.php';

// Buscar serviços disponíveis
$stmt = $db->prepare("
    SELECT s.* 
    FROM servico s
    JOIN empresa_servico es ON s.id = es.servico_id
    WHERE es.empresa_id = :empresa_id AND s.situacao = 1
");
$stmt->execute([':empresa_id' => $_SESSION['usuario']->empresa_id]);
$servicos = $stmt->fetchAll(PDO::FETCH_OBJ);

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servico_id = filter_input(INPUT_POST, 'servico_id', FILTER_VALIDATE_INT);
    $assunto = filter_input(INPUT_POST, 'assunto', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'mensagem', FILTER_SANITIZE_STRING);

    if ($servico_id && $assunto && $mensagem) {
        try {
            $db->beginTransaction();

            // Inserir ticket
            $stmt = $db->prepare("
                INSERT INTO suporte (empresa_id, servico_id, cliente_id, assunto, status) 
                VALUES (:empresa_id, :servico_id, :cliente_id, :assunto, :status)
            ");

            $stmt->execute([
                ':empresa_id' => $_SESSION['usuario']->empresa_id,
                ':servico_id' => $servico_id,
                ':cliente_id' => $_SESSION['usuario']->id,
                ':assunto' => $assunto,
                ':status' => STATUS_ABERTO
            ]);

            $suporte_id = $db->lastInsertId();

            // Inserir primeira mensagem
            $stmt = $db->prepare("
                INSERT INTO suporte_mensagem (suporte_id, mensagem, proprietario) 
                VALUES (:suporte_id, :mensagem, :proprietario)
            ");

            $stmt->execute([
                ':suporte_id' => $suporte_id,
                ':mensagem' => $mensagem,
                ':proprietario' => $_SESSION['usuario']->nome
            ]);

            $db->commit();
            redirecionarComMensagem('index.php', 'Ticket criado com sucesso!');
        } catch (Exception $e) {
            $db->rollback();
            $erro = 'Erro ao criar ticket. Por favor, tente novamente.';
        }
    } else {
        $erro = 'Por favor, preencha todos os campos obrigatórios.';
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title mb-0">Novo Ticket de Suporte</h2>
                </div>
                <div class="card-body">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-danger"><?php echo $erro; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" id="suporte-cadastrar">
                        <div class="mb-3">
                            <label for="servico_id" class="form-label">Serviço <span class="campo-obrigatorio">*</span></label>
                            <select class="form-select" id="servico_id" name="servico_id" required>
                                <option value="">Selecione um serviço</option>
                                <?php foreach ($servicos as $servico): ?>
                                    <option value="<?php echo $servico->id; ?>">
                                        <?php echo $servico->nome; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="assunto" class="form-label">Assunto <span class="campo-obrigatorio">*</span></label>
                            <input type="text" class="form-control" id="assunto" name="assunto" required>
                        </div>

                        <div class="mb-3">
                            <label for="mensagem" class="form-label">Mensagem <span class="campo-obrigatorio">*</span></label>
                            <textarea class="form-control" id="mensagem" name="mensagem" rows="5" required></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Voltar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-lg"></i> Criar Ticket
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../templates/footer.php'; ?>