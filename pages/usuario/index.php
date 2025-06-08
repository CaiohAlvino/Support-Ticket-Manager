<?php include("../template/topo.php"); ?>
<?php
$nome = isset($_GET['nome']) ? $_GET['nome'] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;

$indexRegistros = $classUsuario->index([
    "nome" => $nome,
    "pagina" => $pagina,
    "limite" => 15
]);
$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];
?>

<header class="sessao">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1">
            <h1 class="titulo">Usuário</h1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1 text-end">
            <a href="cadastro.php" class="btn btn-adicionar">
                <i class="bi-plus-lg" aria-hidden="true"></i> Novo Usuário
            </a>
        </div>
    </div>
</header>

<div class="sessao">
    <form method="get">
        <div class="row mb-3">
            <div class="col-xl-5 col-lg-7 col-md-7 col-sm-12 col-1">
                <label>nome</label>
                <div class="input-group">
                    <input type="text" class="form-control" name="nome" placeholder="Buscar pelo nome..." value="<?php echo $nome; ?>">
                    <button class="btn btn-buscar" type="submit"><i class="bi bi-search"></i></button>
                </div>
            </div>
        </div>
    </form>
    <?php if (count($registros) == 0): ?>
        <div class="alert alerta-sem-cadastrar text-center py-4" role="alert">
            <div class="mb-3">
                <i class="bi bi-people fs-1"></i>
            </div>
            <h5 class="fw-bold">Nenhum usuário encontrado</h5>
            <p class="mb-0">Confira se os dados estão corretos ou cadastre um novo usuário.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Nome</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro): ?>
                        <?php if (is_object($registro)): ?>
                            <tr class="<?php echo isset($focu_empresa_id) && $registro->id == $focu_empresa_id ? 'table-active' : ''; ?>">
                                <td>
                                    <?php if (isset($registro->situacao) && $registro->situacao == 1): ?>
                                        <span class="badge status-situacao">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge status-insituacao">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($registro->nome) ? $registro->nome : "--"; ?></td>
                                <td>
                                    <a href="edicao.php?id=<?php echo $registro->id; ?>" class="btn btn-sm btn-editar">
                                        <i class="bi-pencil-square"></i> Editar
                                    </a>

                                    <button
                                        data-bs-toggle="modal"
                                        data-bs-target="#excluir-<?php echo $registro->id; ?>"
                                        type="button"
                                        class="btn btn-sm btn-excluir">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </td>
                            </tr>

                            <?php $registroExcluir = $registro; ?>
                            <?php $data_action_excluir = "servico/excluir.php"; ?>
                            <?php include("../components/excluir.php"); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</div>

<?php $linkPaginacaoUrlParametros = "&limite=" . $paginacao['limite'] . "&nome=$nome"; ?>

<?php
        $limite = 2;
        $inicio = ((($pagina - $limite) > 1) ? $pagina - $limite : 1);
        $fim = ((($pagina + $limite) < $paginacao['total_paginas']) ? $pagina + $limite : $paginacao['total_paginas']);
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-3">
        <?php if ($paginacao['pagina'] > 1): ?>
            <li class="page-item">
                <a class="me-2 btn btn-outline-dark" href="?pagina=<?php echo $paginacao['pagina'] - 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">
                    Anterior
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = $inicio; $i <= $fim; $i++) : ?>
            <?php if ($i == $paginacao['pagina']): ?>
                <li class="page-item active">
                    <a class="ms-1 me-1 btn btn-dark">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item">
                    <a class="ms-1 me-1 btn btn-outline-dark" href="?pagina=<?php echo $i; ?><?php echo $linkPaginacaoUrlParametros; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ($paginacao['pagina'] < $paginacao['total_paginas']): ?>
            <li class="ms-2 page-item">
                <a class="btn btn-outline-dark" href="?pagina=<?php echo $paginacao['pagina'] + 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">
                    Próxima
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

<?php include("../template/rodape.php"); ?>
