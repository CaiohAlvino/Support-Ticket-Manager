<?php include '../template/topo.php'; ?>

<!-- Cabeçalho do Dashboard -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="mb-0">Dashboard</h1><button class="btn btn-primary" data-bs-toggle="modal"
        data-bs-target="#newTicketModal"><i class="bi bi-plus-lg me-2"></i>Novo Ticket </button>
</div>

<!-- Cards de Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-primary rounded-3"><i class="bi bi-ticket text-white fs-4"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-1">Total de Tickets</h6>
                        <h4 class="mb-0">150</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-warning rounded-3"><i
                            class="bi bi-hourglass-split text-white fs-4"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-1">Em Andamento</h6>
                        <h4 class="mb-0">45</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-success rounded-3"><i class="bi bi-check-circle text-white fs-4"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="mb-1">Resolvidos</h6>
                        <h4 class="mb-0">95</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="stats-icon bg-danger rounded-3"><i
                            class="bi bi-exclamation-triangle text-white fs-4"></i></div>
                    <div class="ms-3">
                        <h6 class="mb-1">Críticos</h6>
                        <h4 class="mb-0">10</h4>
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
                    <h5 class="card-title mb-0">Tickets Recentes</h5>
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
                                <th class="border-0">Assunto</th>
                                <th class="border-0">Prioridade</th>
                                <th class="border-0">Status</th>
                                <th class="border-0">Data</th>
                                <th class="border-0 text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="">#1234</td>
                                <td>Problema com login</td>
                                <td><span class="badge bg-danger">Alta</span></td>
                                <td><span class="badge bg-warning">Em Andamento</span></td>
                                <td>10/03/2024</td>
                                <td class="text-end pe-4"><button class="btn btn-sm btn-outline-primary"><i
                                            class="bi bi-eye"></i></button><button
                                        class="btn btn-sm btn-outline-secondary"><i
                                            class="bi bi-pencil"></i></button></td>
                            </tr>
                            <!-- Mais linhas de exemplo aqui -->
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
                <h5 class="card-title">Distribuição de Tickets</h5>
            </div>
            <div class="card-body">
                <div class="chart-row">
                    <div class="chart-col"><canvas id="ticketStatusChart"></canvas></div>
                    <div class="chart-col"><canvas id="ticketCategoryChart"></canvas></div>
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

<?php include '../template/rodape.php'; ?>