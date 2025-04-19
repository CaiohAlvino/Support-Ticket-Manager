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

// Buscar tickets do usuário
$stmt = $db->prepare("
    SELECT s.*, serv.nome as servico_nome
    FROM suporte s
    INNER JOIN servico serv ON s.servico_id = serv.id
    WHERE s.cliente_id = :cliente_id AND s.situacao = 1
    ORDER BY s.cadastrado DESC
");
$stmt->execute([':cliente_id' => $_SESSION['usuario']->id]);
$tickets = $stmt->fetchAll(PDO::FETCH_OBJ);

require_once '../../templates/header.php';
?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Meus Tickets</h2>
        <a href="novo.php" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Novo Ticket
        </a>
    </div>

    <?php if (isset($_GET['sucesso'])): ?>
        <div class="alert alert-success">
            Ticket criado com sucesso!
        </div>
    <?php endif; ?>

    <?php if (count($tickets) > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Serviço</th>
                        <th>Assunto</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tickets as $ticket): ?>
                        <tr>
                            <td><?php echo $ticket->id; ?></td>
                            <td><?php echo htmlspecialchars($ticket->servico_nome); ?></td>
                            <td><?php echo htmlspecialchars($ticket->assunto); ?></td>
                            <td>
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
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($ticket->cadastrado)); ?></td>
                            <td>
                                <a href="detalhes.php?id=<?php echo $ticket->id; ?>"
                                    class="btn btn-sm btn-info"
                                    data-bs-toggle="tooltip"
                                    title="Ver Detalhes">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">
            Você ainda não possui nenhum ticket. Clique em "Novo Ticket" para criar um.
        </div>
    <?php endif; ?>
</div>

<?php require_once '../../templates/footer.php'; ?>