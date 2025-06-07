<?php include '../template/topo.php'; ?>

<?php
$suporte_id = isset($_GET["suporte_id"]) ? $_GET["suporte_id"] : NULL;
$status = (isset($_GET["status"]) && $_GET["status"] !== "NULL") ? $_GET["status"] : NULL;
$assunto = isset($_GET["assunto"]) ? $_GET["assunto"] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
$dias = isset($_GET["dias"]) ? (int)$_GET["dias"] : null;

$usuario_id = $_SESSION["usuario_grupo"] == 2 ? $_SESSION["usuario_id"] : NULL;

$indexRegistros = $classSuporte->index([
    "dias" => $dias,
    "quantidade" => 10,
    "status" => $status,
    "assunto" => $assunto,
    "pagina" => $pagina,
    "limite" => 15
], $usuario_id);

$registros = $indexRegistros["resultados"];
$paginacao = $indexRegistros["paginacao"];

// Contagem de tickets por status
$statusCounts = [
    'ABERTO' => 0,
    'AGUARDANDO_SUPORTE' => 0,
    'FECHADO' => 0,
    'CRITICO' => 0
];
foreach ($registros as $registro) {
    if (is_object($registro) && isset($statusCounts[$registro->status])) {
        $statusCounts[$registro->status]++;
    }
}

// Contagem de tickets por usuário
$userTicketCounts = [];
foreach ($registros as $registro) {
    if (is_object($registro) && isset($registro->usuario_nome) && !empty($registro->usuario_nome)) {
        $nome = $registro->usuario_nome;
        if (!isset($userTicketCounts[$nome])) $userTicketCounts[$nome] = 0;
        $userTicketCounts[$nome]++;
    }
}

// Contagem de tickets por serviço
$servicoCounts = [];
foreach ($registros as $registro) {
    if (is_object($registro) && isset($registro->servico_nome) && !empty($registro->servico_nome)) {
        $servico = $registro->servico_nome;
        if (!isset($servicoCounts[$servico])) $servicoCounts[$servico] = 0;
        $servicoCounts[$servico]++;
    }
}
arsort($servicoCounts);

// Função para obter classe CSS do status
function getStatusClass($status)
{
    switch ($status) {
        case 'ABERTO':
            return 'success';
        case 'AGUARDANDO_SUPORTE':
            return 'warning';
        case 'FECHADO':
            return 'secondary';
        case 'CRITICO':
            return 'danger';
        default:
            return 'light';
    }
}

// Filtrar tickets em aberto para a tabela
$ticketsAbertos = array_filter($registros, fn($r) => in_array($r->status, ['ABERTO', 'AGUARDANDO_SUPORTE']));
?>

<!-- Cabeçalho do Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Dashboard de Suporte</h1>
    <a href="../suporte/cadastro.php" class="btn btn-primary">
        <i class="bi bi-plus-lg me-2"></i>
        Novo Ticket
    </a>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary rounded-3 p-3">
                        <i class="bi bi-ticket text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 text-muted">Total de Tickets</h6>
                        <h4 class="mb-0"><?php echo count($registros) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success rounded-3 p-3">
                        <i class="bi bi-check-circle text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 text-muted">Abertos</h6>
                        <h4 class="mb-0"><?php echo $statusCounts['ABERTO'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning rounded-3 p-3">
                        <i class="bi bi-hourglass-split text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 text-muted">Aguardando</h6>
                        <h4 class="mb-0"><?php echo $statusCounts['AGUARDANDO_SUPORTE'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-secondary rounded-3 p-3">
                        <i class="bi bi-check2-square text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1 text-muted">Resolvidos</h6>
                        <h4 class="mb-0"><?php echo $statusCounts['FECHADO'] ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="row mb-4">
    <!-- Lista de Tickets Ativos -->
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tickets em Andamento</h5>
                    <div class="d-flex gap-2">
                        <form method="GET" class="d-flex gap-2">
                            <div class="input-group">
                                <input type="text" name="assunto" class="form-control"
                                    placeholder="Buscar por assunto..."
                                    value="<?php echo htmlspecialchars($assunto ?? '') ?>">
                                <button class="btn btn-outline-secondary" type="submit">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                            <select name="status" class="form-select" style="width: auto;">
                                <option value="">Todos Status</option>
                                <option value="ABERTO" <?php echo $status === 'ABERTO' ? 'selected' : '' ?>>Aberto</option>
                                <option value="AGUARDANDO_SUPORTE" <?php echo $status === 'AGUARDANDO_SUPORTE' ? 'selected' : '' ?>>Aguardando</option>
                                <option value="FECHADO" <?php echo $status === 'FECHADO' ? 'selected' : '' ?>>Fechado</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (empty($ticketsAbertos)): ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-3 text-muted mb-3"></i>
                        <h5 class="text-muted">Nenhum ticket em andamento</h5>
                        <p class="text-muted">Todos os tickets foram resolvidos!</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 fw-semibold">#ID</th>
                                    <th class="border-0 fw-semibold">Cliente</th>
                                    <th class="border-0 fw-semibold">Assunto</th>
                                    <th class="border-0 fw-semibold">Serviço</th>
                                    <th class="border-0 fw-semibold">Status</th>
                                    <th class="border-0 fw-semibold">Data</th>
                                    <th class="border-0 text-end pe-4 fw-semibold">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ticketsAbertos as $registro): ?>
                                    <?php if (is_object($registro)): ?>
                                        <tr>
                                            <td class="fw-medium">#<?php echo htmlspecialchars($registro->id) ?></td>
                                            <td><?php echo htmlspecialchars($registro->cliente_nome ?? 'N/A') ?></td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;">
                                                    <?php echo htmlspecialchars($registro->assunto) ?>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($registro->servico_nome ?? 'N/A') ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo getStatusClass($registro->status) ?>">
                                                    <?php echo str_replace('_', ' ', $registro->status) ?>
                                                </span>
                                            </td>
                                            <td class="text-muted small">
                                                <?php echo date("d/m/Y H:i", strtotime($registro->alterado)); ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="../suporte/detalhes.php?id=<?php echo htmlspecialchars($registro->id) ?>"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-eye me-1"></i>Ver
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <!-- Gráficos -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Distribuição de Tickets</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6 class="text-muted mb-3">Por Status</h6>
                            <canvas id="ticketStatusChart" style="max-width:250px;max-height:250px;"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="text-center">
                            <h6 class="text-muted mb-3">Por Responsável</h6>
                            <canvas id="ticketUserChart" style="max-width:250px;max-height:250px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Serviços Mais Solicitados -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Serviços Mais Solicitados</h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($servicoCounts)): ?>
                    <div class="p-3 text-center text-muted">
                        <i class="bi bi-pie-chart display-6 mb-2"></i>
                        <p class="mb-0">Nenhum dado disponível</p>
                    </div>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php
                        $count = 0;
                        foreach ($servicoCounts as $servico => $quantidade):
                            if ($count >= 3) break; // Limitar a 3 itens
                            $count++;
                        ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0"><?php echo htmlspecialchars($servico) ?></h6>
                                    <small class="text-muted">
                                        <?php echo number_format(($quantidade / count($registros)) * 100, 1) ?>% do total
                                    </small>
                                </div>
                                <span class="badge bg-primary rounded-pill">
                                    <?php echo $quantidade ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Resumo Rápido -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="card-title mb-0">Resumo Rápido</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-1 text-primary"><?php echo count($userTicketCounts) ?></div>
                            <div class="small text-muted">Responsáveis</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="text-center">
                            <div class="h4 mb-1 text-success"><?php echo count($servicoCounts) ?></div>
                            <div class="small text-muted">Serviços</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Dados para os gráficos
    window.ticketStatusData = <?php echo json_encode($statusCounts); ?>;
    window.userTicketData = {
        labels: <?php echo json_encode(array_keys($userTicketCounts)); ?>,
        data: <?php echo json_encode(array_values($userTicketCounts)); ?>
    };
</script>
<?php include '../template/rodape.php'; ?>
