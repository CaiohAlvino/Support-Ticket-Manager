<?php include("../template/topo.php"); ?>
<?php
$nome = isset($_GET['nome']) ? $_GET['nome'] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
$relatorio = isset($_GET['relatorio']) ? $_GET['relatorio'] : 0;

$limiteConsulta = ($relatorio == 1) ? null : 15;

$indexRegistros = $classGrupo->index([
    "nome" => $nome,
    "pagina" => $pagina,
    "limite" => $limiteConsulta
]);
$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];
?>

<header class="sessao">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1">
            <h1 class="titulo">Grupos</h1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1 text-end nao-mostrar-impressao">
            <a href="cadastro.php" class="btn btn-adicionar">
                <i class="bi-plus-lg" aria-hidden="true"></i> Novo Grupos
            </a>
            <button type="button" class="btn btn-imprimir botao-noty-ativo ms-2" id="btnRelatorio">
                <i class="bi bi-printer"></i> Gerar Relatório
            </button>
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
            <h5 class="fw-bold">Nenhum serviço encontrado</h5>
            <p class="mb-0">Confira se os dados estão corretos ou cadastre um novo serviço.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Situação</th>
                        <th>Nome</th>
                        <th class="nao-mostrar-impressao">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro): ?>
                        <?php if (is_object($registro)): ?>
                            <tr class="<?php echo isset($focu_empresa_id) && $registro->id == $focu_empresa_id ? 'table-active' : ''; ?>">
                                <td>
                                    <?php if (isset($registro->situacao) && $registro->situacao == 1): ?>
                                        <span class="badge text-bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($registro->nome) ? $registro->nome : "--"; ?></td>
                                <td class="nao-mostrar-impressao">
                                    <a href="edicao.php?id=<?php echo $registro->id; ?>" class="btn btn-sm btn-editar">
                                        <i class="bi-pencil-square"></i> Editar
                                    </a>

                                    <?php if ($registro->id != 1 && $registro->id != 2): ?>
                                        <button
                                            data-bs-toggle="modal"
                                            data-bs-target="#excluir-<?php echo $registro->id; ?>"
                                            type="button"
                                            class="btn btn-sm btn-excluir">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <?php $registroExcluir = $registro; ?>
                            <?php $data_action_excluir = "grupo/excluir.php"; ?>
                            <?php include("../components/excluir.php"); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $linkPaginacaoUrlParametros = "&limite=" . $paginacao['limite'] . "&nome=$nome"; ?>

<?php
$limiteNavegacao = 2;
$inicio = ((($pagina - $limiteNavegacao) > 1) ? $pagina - $limiteNavegacao : 1);
$fim = ((($pagina + $limiteNavegacao) < $paginacao['total_paginas']) ? $pagina + $limiteNavegacao : $paginacao['total_paginas']);
?>

<?php if ($limiteConsulta !== null): ?>
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

<script>
    document.getElementById('btnRelatorio').addEventListener('click', function() {
        const url = new URL(window.location.href);
        if (url.searchParams.get('relatorio') === '1') {
            window.print();
        } else {
            url.searchParams.set('relatorio', '1');
            window.location.href = url.toString();
        }
    });
    window.addEventListener('DOMContentLoaded', function() {
        const url = new URL(window.location.href);
        if (url.searchParams.get('relatorio') === '1') {
            setTimeout(() => window.print(), 300);
        }
    });
</script>
