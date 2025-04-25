<?php require("../template/topo.php");
?><div class="dashboard-container">
    <!-- Cabeçalho do Dashboard -->
    <div class="dashboard-header">
        <div class="header-title">
            <h1>Dashboard</h1>
            <p class="text-muted">Bem-vindo ao seu painel de gerenciamento de tickets</p>
        </div>
        <div class="header-actions"><button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoTicketModal"><i class="bi bi-plus-lg"></i>Novo Ticket </button></div>
    </div>
    <!-- Cards de Estatísticas -->
    <div class="stats-container">
        <div class="row g-4">
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-primary"><i class="bi bi-ticket-perforated"></i></div>
                    <div class="stats-info">
                        <h3>127</h3>
                        <p>Total de Tickets</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-warning"><i class="bi bi-clock"></i></div>
                    <div class="stats-info">
                        <h3>45</h3>
                        <p>Em Andamento</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-success"><i class="bi bi-check-circle"></i></div>
                    <div class="stats-info">
                        <h3>82</h3>
                        <p>Resolvidos</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stats-card">
                    <div class="stats-icon bg-danger"><i class="bi bi-exclamation-circle"></i></div>
                    <div class="stats-info">
                        <h3>12</h3>
                        <p>Críticos</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Seção Principal -->
    <div class="main-content">
        <div class="row g-4">
            <!-- Lista de Tickets Recentes -->
            <div class="col-lg-8">
                <div class="content-card">
                    <div class="card-header">
                        <h2>Tickets Recentes</h2>
                        <div class="header-actions">
                            <div class="input-group"><input type="text" class="form-control" placeholder="Buscar tickets..."><button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button></div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Assunto</th>
                                    <th>Status</th>
                                    <th>Prioridade</th>
                                    <th>Data</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#1234</td>
                                    <td>Problema com login</td>
                                    <td><span class="badge bg-warning">Em Andamento</span></td>
                                    <td><span class="badge bg-danger">Alta</span></td>
                                    <td>23/03/2024</td>
                                    <td><button class="btn btn-sm btn-light"><i class="bi bi-eye"></i></button><button class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></button></td>
                                </tr>
                                <!-- Mais linhas de exemplo... -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Sidebar com Informações Adicionais -->
            <div class="col-lg-4">
                <!-- Categorias Mais Comuns -->
                <div class="content-card mb-4">
                    <h2>Categorias</h2>
                    <div class="category-list">
                        <div class="category-item">
                            <div class="category-info"><span>Suporte Técnico</span><span class="badge bg-primary">45</span></div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 45%"></div>
                            </div>
                        </div>
                        <div class="category-item">
                            <div class="category-info"><span>Bugs</span><span class="badge bg-primary">32</span></div>
                            <div class="progress">
                                <div class="progress-bar" style="width: 32%"></div>
                            </div>
                        </div>
                        <!-- Mais categorias... -->
                    </div>
                </div>
                <!-- Atividades Recentes -->
                <div class="content-card">
                    <h2>Atividades Recentes</h2>
                    <div class="activity-timeline">
                        <div class="activity-item">
                            <div class="activity-icon bg-success"><i class="bi bi-check-lg"></i></div>
                            <div class="activity-content">
                                <p>Ticket #1234 foi resolvido</p><small class="text-muted">Há 2 horas</small>
                            </div>
                        </div>
                        <!-- Mais atividades... -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal de Novo Ticket -->
<div class="modal fade" id="novoTicketModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Criar Novo Ticket</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="novoTicketForm">
                    <div class="mb-3"><label class="form-label">Assunto</label><input type="text" class="form-control" required></div>
                    <div class="mb-3"><label class="form-label">Descrição</label><textarea class="form-control" rows="4" required></textarea></div>
                    <div class="mb-3"><label class="form-label">Prioridade</label><select class="form-select">
                            <option value="baixa">Baixa</option>
                            <option value="media">Média</option>
                            <option value="alta">Alta</option>
                        </select></div>
                </form>
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button><button type="submit" form="novoTicketForm" class="btn btn-primary">Criar Ticket</button></div>
        </div>
    </div>
</div><?php require("../template/rodape.php");
        ?>