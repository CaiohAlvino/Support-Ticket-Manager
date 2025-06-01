<?php include '../template/topo.php'; ?>

<?php
$suporte_id = isset($_GET["suporte_id"]) ? $_GET["suporte_id"] : NULL;
$status = (isset($_GET["status"]) && $_GET["status"] !== "NULL") ? $_GET["status"] : NULL;
$assunto = isset($_GET["assunto"]) ? $_GET["assunto"] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;

$indexRegistros = $suporte->index([
    "dias" => 2,
    "quantidade" => 10,
    "status" => $status,
    "assunto" => $assunto,
    "pagina" => $pagina,
    "limite" => 15
]);

$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];

// Contagem de tickets por status para o gráfico
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

// Contagem de tickets por usuário para o gráfico
$userTicketCounts = [];
foreach ($registros as $registro) {
    if (is_object($registro) && isset($registro->usuario_nome)) {
        $nome = $registro->usuario_nome;
        if (!isset($userTicketCounts[$nome])) $userTicketCounts[$nome] = 0;
        $userTicketCounts[$nome]++;
    }
}
?>

<!-- Cabeçalho do Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Dashboard</h1><button class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#newTicketModal"><i class="bi bi-plus-lg me-2"></i>Novo Ticket </button>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary rounded-3"><i class="bi bi-ticket text-white fs-4"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-1">Total de Tickets</h6>
                        <h4 class="mb-0"><?php echo count($registros) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning rounded-3"><i
                            class="bi bi-hourglass-split text-white fs-4"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-1">Em Andamento</h6>
                        <h4 class="mb-0"><?php echo count(array_filter($registros, fn($r) => $r->status === "AGUARDANDO_SUPORTE")) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success rounded-3"><i class="bi bi-check-circle text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1">Resolvidos</h6>
                        <h4 class="mb-0"><?php echo count(array_filter($registros, fn($r) => $r->status === "FECHADO")) ?></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Conteúdo Principal -->
<div class="row mb-4">
    <!-- Lista de Tickets Recentes -->
    <div class="col-12  ">
        <div class="card border-0 shadow-sm">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Tickets em Aberto</h5>
                    <div class="d-flex gap-2">
                        <div class="input-group"><input type="text" class="form-control"
                                placeholder="Buscar tickets..."><button class="btn btn-outline-secondary"
                                type="button"><i class="bi bi-search"></i></button></div><button
                            class="btn btn-outline-secondary" type="button"><i class="bi bi-funnel"></i></button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0">#ID</th>
                                <th class="border-0">Situação</th>
                                <th class="border-0">Assunto</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Data</th>
                                <th class="border-0 text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($registros as $registro): ?>
                                <?php if (is_object($registro) && $registro->status === "ABERTO"): ?>
                                    <tr>
                                        <td class="">#<?php echo htmlspecialchars($registro->id) ?></td>
                                        <td>
                                            <?php if ($registro->situacao == 1): ?>
                                                <span class="badge text-bg-success">ativo</span>
                                            <?php else: ?>
                                                <span class="badge text-bg-secondary">inativo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($registro->assunto) ?></td>
                                        <td><span class="badge bg-<?php echo htmlspecialchars($registro->status) ?>"><?php echo htmlspecialchars($registro->status) ?></span></td>
                                        <td><?php echo date("d/m/Y (H:i)", strtotime($registro->alterado)); ?></td>
                                        <td class="text-end pe-4">
                                            <a href="../suporte/detalhes.php?id=<?php echo htmlspecialchars($registro->id) ?>" class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye">Ver</i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card distribution-card">
            <div class="card-header">
                <h5 class="card-title">Distribuição de Tickets (GERAL)</h5>
            </div>
            <div class="card-body">
                <div class="chart-row justify-content-center align-items-center" style="height:340px;display:flex;">
                    <div class="chart-col d-flex justify-content-center align-items-center w-100" style="height:100%;">
                        <canvas id="ticketStatusChart" style="max-width:340px;max-height:340px;width:100%;height:100%;"></canvas>
                    </div>
                    <div class="chart-col d-flex justify-content-center align-items-center w-100" style="height:100%;">
                        <canvas id="ticketCategoryChart" style="max-width:340px;max-height:340px;width:100%;height:100%;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Categorias Comuns -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Categorias Comuns</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <a href="#"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Problemas
                        de Acesso
                        <span class="badge bg-primary rounded-pill">24 </span>
                    </a>
                    <a href="#"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Bugs
                        <span class="badge bg-primary rounded-pill">18 </span></a><a href="#"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">Sugestões
                        <span class="badge bg-primary rounded-pill">12 </span></a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Novo Ticket -->
<div class="modal fade" id="newTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Novo Ticket</h5><button type="button" class="btn-close"
                    data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3"><label class="form-label">Assunto</label><input type="text" class="form-control"
                            required></div>
                    <div class="mb-3"><label class="form-label">Descrição</label><textarea class="form-control" rows="4"
                            required></textarea></div>
                    <div class="mb-3"><label class="form-label">Prioridade</label><select class="form-select">
                            <option value="baixa">Baixa</option>
                            <option value="media">Média</option>
                            <option value="alta">Alta</option>
                        </select></div>
                    <div class="mb-3"><label class="form-label">Categoria</label><select class="form-select">
                            <option value="acesso">Problemas de Acesso</option>
                            <option value="bug">Bugs</option>
                            <option value="sugestao">Sugestões</option>
                        </select></div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal">Cancelar</button><button type="button" class="btn btn-primary">Criar
                    Ticket</button></div>
        </div>
    </div>
</div>

<script>
    // Passando dados do PHP para o JS
    window.ticketStatusData = <?php echo json_encode($statusCounts); ?>;
    window.userTicketData = {
        labels: <?php echo json_encode(array_keys($userTicketCounts)); ?>,
        data: <?php echo json_encode(array_values($userTicketCounts)); ?>
    };
</script>
<script src="/js/dashboard.js"></script>
<?php include '../template/rodape.php'; ?>
