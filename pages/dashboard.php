<?php
require_once '../templates/header.php';

// Buscar estatísticas
$stmt = $db->prepare("
    SELECT 
        COUNT(*) as total,
        SUM(CASE WHEN status = 'ABERTO' THEN 1 ELSE 0 END) as abertos,
        SUM(CASE WHEN status = 'AGUARDANDO_SUPORTE' THEN 1 ELSE 0 END) as aguardando,
        SUM(CASE WHEN status = 'RESPONDIDO' THEN 1 ELSE 0 END) as respondidos,
        SUM(CASE WHEN status = 'FECHADO' THEN 1 ELSE 0 END) as fechados
    FROM suporte 
    WHERE empresa_id = :empresa_id
");
$stmt->execute([':empresa_id' => $_SESSION['usuario']->empresa_id]);
$estatisticas = $stmt->fetch(PDO::FETCH_OBJ);

// Buscar tickets recentes
$stmt = $db->prepare("
    SELECT s.*, u.nome as usuario_nome, serv.nome as servico_nome
    FROM suporte s
    JOIN usuario u ON s.cliente_id = u.id
    JOIN servico serv ON s.servico_id = serv.id
    WHERE s.empresa_id = :empresa_id
    ORDER BY s.cadastrado DESC
    LIMIT 5
");
$stmt->execute([':empresa_id' => $_SESSION['usuario']->empresa_id]);
$tickets_recentes = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<div class="container">
    <!-- Estatísticas -->
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="titulo">Dashboard</h2>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title">Total de Tickets</h5>
                    <p class="card-text display-6"><?php echo $estatisticas->total; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h5 class="card-title">Tickets Abertos</h5>
                    <p class="card-text display-6"><?php echo $estatisticas->abertos; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Aguardando Suporte</h5>
                    <p class="card-text display-6"><?php echo $estatisticas->aguardando; ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title">Tickets Fechados</h5>
                    <p class="card-text display-6"><?php echo $estatisticas->fechados; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Recentes -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Tickets Recentes</h5>
                    <a href="suporte/index.php" class="btn btn-primary btn-sm">Ver Todos</a>
                </div>
                <div class="card-body">
                    <?php if (count($tickets_recentes) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Assunto</th>
                                        <th>Serviço</th>
                                        <th>Cliente</th>
                                        <th>Status</th>
                                        <th>Data</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tickets_recentes as $ticket): ?>
                                        <tr>
                                            <td><?php echo $ticket->id; ?></td>
                                            <td><?php echo $ticket->assunto; ?></td>
                                            <td><?php echo $ticket->servico_nome; ?></td>
                                            <td><?php echo $ticket->usuario_nome; ?></td>
                                            <td><?php echo formatarStatusSuporte($ticket->status); ?></td>
                                            <td><?php echo formatarData($ticket->cadastrado); ?></td>
                                            <td>
                                                <a href="suporte/detalhes.php?id=<?php echo $ticket->id; ?>"
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
                        <p class="text-center text-muted">Nenhum ticket encontrado.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../templates/footer.php'; ?>