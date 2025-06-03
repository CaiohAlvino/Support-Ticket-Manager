<?php include("../template/topo.php"); ?>

<?php
$suporte_id = isset($_GET["suporte_id"]) ? $_GET["suporte_id"] : NULL;
$status = (isset($_GET["status"]) && $_GET["status"] !== "NULL") ? $_GET["status"] : NULL;
$assunto = isset($_GET["assunto"]) ? $_GET["assunto"] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;

$indexRegistros = $classSuporte->index([
    "status" => $status,
    "assunto" => $assunto,
    "pagina" => $pagina,
    "limite" => 15
], $_SESSION["usuario_id"]);

$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];
?>

<header class="sessao">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1">
            <h1 class="titulo">Meus Tickets</h1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 text-end my-1">
            <a href="cadastro.php" class="btn btn-adicionar">
                <i class="bi-plus-lg me-2" aria-hidden="true"></i>Novo Suporte
            </a>
        </div>

    </div>
</header>

<div class="sessao">
    <form method="get">
        <div class="row mb-3">
            <div class="col-xl-5 col-lg-7 col-md-7 col-sm-12 col-12">
                <label>Assunto</label>
                <input type="text" class="form-control" name="assunto" placeholder="Buscar pelo assunto..." value="<?php echo $assunto; ?>">
            </div>
            <div class="col-md-4">
                <label>Status</label>
                <div class="input-group">
                    <select class="form-select" name="status" id="status">
                        <option value="NULL" selected>Todos</option>
                        <option value="ABERTO" <?php echo $status == "ABERTO" ? "selected" : ""; ?>>ABERTO</option>
                        <option value="AGUARDANDO_SUPORTE" <?php echo $status == "AGUARDANDO_SUPORTE" ? "selected" : ""; ?>>AGUARDANDO SUPORTE</option>
                        <option value="RESPONDIDO" <?php echo $status == "RESPONDIDO" ? "selected" : ""; ?>>RESPONDIDO</option>
                        <option value="FECHADO" <?php echo $status == "FECHADO" ? "selected" : ""; ?>>FECHADO</option>
                    </select>
                    <button class="btn btn-buscar" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </form>
    <?php if (count($registros) == 0): ?>
        <div class="alert alerta-sem-cadastrar text-center py-4 mb-5" role="alert">
            <div class="mb-3">
                <i class="bi bi-headset fs-1"></i>
            </div>
            <h5 class="fw-bold">Nenhum ticket encontrado</h5>
            <p class="mb-0">Confira se os dados estão corretos ou cadastre uma novo.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Cliente</th>
                        <th>Assunto</th>
                        <th>Status</th>
                        <th>Ultima alteração</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro): ?>
                        <?php if (is_object($registro)): ?>
                            <tr class="<?php echo ($suporte_id == $registro->id) ? 'table-active' : ''; ?>">
                                <td>
                                    <?php if ($registro->situacao == 1): ?>
                                        <span class="badge text-bg-success">ativo</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-secondary">inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $registro->cliente_nome; ?></td>
                                <td><?php echo $registro->assunto; ?></td>
                                <td>
                                    <?php if ($registro->status == "ABERTO"): ?>
                                        <span class="badge text-bg-success">ABERTO</span>
                                    <?php elseif ($registro->status == "AGUARDANDO_SUPORTE"): ?>
                                        <span class="badge text-bg-warning">AGUARDANDO SUPORTE</span>
                                    <?php elseif ($registro->status == "RESPONDIDO"): ?>
                                        <span class="badge text-bg-info">RESPONDIDO</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-danger">FECHADO</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date("d/m/Y (H:i)", strtotime($registro->alterado)); ?></td>
                                <td>
                                    <a href="detalhes.php?id=<?php echo $registro->id; ?>" class="btn btn-sm btn-editar">
                                        <i class="bi-eye-fill me-2"></i>
                                        Ver
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

<?php $linkPaginacaoUrlParametros = "&limite=" . $paginacao['limite'] . "&assunto=$assunto&status=$status&suporte_id=$suporte_id"; ?>

<?php
$limite = 2;
$inicio = ((($pagina - $limite) > 1) ? $pagina - $limite : 1);
$fim = ((($pagina + $limite) < $paginacao['total_paginas']) ? $pagina + $limite : $paginacao['total_paginas']);
?>

<!-- <div class="border border-2 border-warning-subtle rounded p-3 bg-warning-subtle">
    <div class="row">
        <div class="col-xl-1 col-lg-1 col-md-8 col-sm-12 col-12 p-0 text-center">
            <i class="bi bi-patch-exclamation text-warning fs-1"></i>
        </div>
        <div class="col-xl-11 col-lg-11 col-md-8 col-sm-12 col-12 p-0">
            <div>
                <p class="m-0 fw-bold">Instruções para Solicitação de Suporte</p>
                <p class="">Antes de abrir um chamado de suporte, leia atentamente as orientações abaixo para garantir um atendimento mais rápido e eficaz:</p>
            </div>
            <div>
                <p class="m-0 fw-bold">Solicite ajuda de forma eficiente:</p>
                <ul>
                    <li>Verifique os tutoriais e FAQ antes de abrir um chamado</li>
                    <li>Descreva claramente o problema com prints de tela</li>
                    <li>Informe o módulo específico e os passos realizados</li>
                </ul>
            </div>
            <div>
                <p class="m-0 fw-bold">Prazos:</p>
                <ul>
                    <li>Problemas críticos: 24h úteis</li>
                    <li>Questões gerais: 36h úteis</li>
                </ul>
            </div>
            <div>
                <p class="m-0 fw-bold">Estamos disponíveis em dias úteis, das 8h às 18h.</p>
            </div>
        </div>
    </div>
</div> -->

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-3">
        <?php if ($paginacao['pagina'] > 1): ?>
            <li class="page-item">
                <a class="me-2 btn btn-outline-dark" href="?pagina=<?php echo $paginacao['pagina'] - 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">Anterior</a>
            </li>
        <?php endif; ?>

        <?php for ($i = $inicio; $i <= $fim; $i++) : ?>
            <?php if ($i == $paginacao['pagina']): ?>
                <li class="page-item active">
                    <a class="ms-1 me-1 btn btn-dark"><?php echo $i; ?></a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="ms-1 me-1 btn btn-outline-dark" href="?pagina=<?php echo $i; ?><?php echo $linkPaginacaoUrlParametros; ?>"><?php echo $i; ?></a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($paginacao['pagina'] < $paginacao['total_paginas']): ?>
            <li class="ms-2 page-item">
                <a class="btn btn-outline-dark" href="?pagina=<?php echo $paginacao['pagina'] + 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">Próxima</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<?php include("../template/rodape.php"); ?>
