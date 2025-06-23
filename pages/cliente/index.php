<?php include("../template/topo.php"); ?>

<?php
$cliente_id = isset($_GET["cliente_id"]) ? $_GET["cliente_id"] : NULL;
$empresa_id = isset($_GET["empresa_id"]) ? $_GET["empresa_id"] : NULL;
$documento = isset($_GET["documento"]) ? $_GET["documento"] : NULL;
$nome_fantasia = isset($_GET["nome_fantasia"]) ? $_GET["nome_fantasia"] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
$relatorio = isset($_GET['relatorio']) ? $_GET['relatorio'] : 0;

$limiteConsulta = ($relatorio == 1) ? null : 15;

$indexRegistros = $classCliente->index([
    "cliente_id" => $cliente_id,
    "empresa_id" => $empresa_id,
    "documento" => $documento,
    "nome_fantasia" => $nome_fantasia,
    "pagina" => $pagina,
    "limite" => $limiteConsulta
]);

$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];

// Informações sobre empresas do usuário (apenas para usuários não-admin)
$empresasUsuario = [];
$totalEmpresasUsuario = 0;
$infoFiltroCliente = null;
if ($_SESSION["usuario_grupo"] != 1) {
    $empresasUsuario = $classEmpresa->listarEmpresas();
    $totalEmpresasUsuario = count($empresasUsuario);
    $infoFiltroCliente = $classCliente->getInfoFiltro();
}
?>

<header class="sessao">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1">
            <h1 class="titulo">Cliente
                <?php if ($_SESSION["usuario_grupo"] != 1 && $totalEmpresasUsuario > 0): ?>
                    <small class="text-muted fs-6">
                        (Filtrando por <?php echo $totalEmpresasUsuario; ?> empresa<?php echo $totalEmpresasUsuario > 1 ? 's' : ''; ?> associada<?php echo $totalEmpresasUsuario > 1 ? 's' : ''; ?>
                        <?php if ($infoFiltroCliente && isset($infoFiltroCliente['total_clientes_sem_empresa']) && $infoFiltroCliente['total_clientes_sem_empresa'] > 0): ?>
                            + <?php echo $infoFiltroCliente['total_clientes_sem_empresa']; ?> sem empresa)
                        <?php else: ?>
                            )
                        <?php endif; ?>
                    </small>
                <?php elseif ($_SESSION["usuario_grupo"] != 1): ?>
                    <small class="text-muted fs-6">
                        <?php if ($infoFiltroCliente && isset($infoFiltroCliente['total_clientes_sem_empresa']) && $infoFiltroCliente['total_clientes_sem_empresa'] > 0): ?>
                            (<?php echo $infoFiltroCliente['total_clientes_sem_empresa']; ?> cliente<?php echo $infoFiltroCliente['total_clientes_sem_empresa'] > 1 ? 's' : ''; ?> sem empresa)
                        <?php else: ?>
                            (Nenhuma empresa associada)
                        <?php endif; ?>
                    </small>
                <?php endif; ?>
            </h1>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1 text-end nao-mostrar-impressao">
            <a href="cadastro.php" class="btn btn-adicionar">
                <i class="bi-plus-lg" aria-hidden="true"></i> Novo Cliente
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
            <div class="col-xl-4 col-lg-5 col-md-5 col-sm-12 col-12">
                <label>Nome Fantasia</label>
                <input type="text" class="form-control" name="nome_fantasia" placeholder="Buscar cliente..." value="<?php echo $nome_fantasia; ?>">
            </div>
            <div class="col-xl-5 col-lg-7 col-md-7 col-sm-12 col-1">
                <label>CNPJ</label>
                <div class="input-group">
                    <input type="text" class="form-control mascara-cnpj" name="documento" placeholder="Buscar por CNPJ..." value="<?php echo $documento; ?>">
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
            <h5 class="fw-bold">Nenhum cliente encontrado</h5>
            <p class="mb-0">Confira se os dados estão corretos ou cadastre um novo cliente.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="nao-mostrar-impressao">Situação</th>
                        <th>Tipo Empresa</th>
                        <th>Nome Responsavel</th>
                        <th>Nome Fantasia</th>
                        <th>Razão Social</th>
                        <th>Documento</th>
                        <th class="nao-mostrar-impressao">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registros as $registro): ?>
                        <?php if (is_object($registro)): ?>
                            <tr class="<?php echo isset($focu_cliente_id) && $registro->id == $focu_cliente_id ? 'table-active' : ''; ?>">
                                <td class="nao-mostrar-impressao">
                                    <?php if (isset($registro->situacao) && $registro->situacao == 1): ?>
                                        <span class="badge text-bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (isset($registro->tipo) && $registro->tipo === "CNPJ"): ?>
                                        <span class="badge status-situacao">PJ</span>
                                    <?php else: ?>
                                        <span class="badge status-situacao">PF</span>
                                    <?php endif ?>

                                </td>
                                <td><?php echo isset($registro->responsavel_nome) ? $registro->responsavel_nome : "--"; ?></td>
                                <td><?php echo isset($registro->nome_fantasia) ? $registro->nome_fantasia : "--"; ?></td>
                                <td><?php echo isset($registro->razao_social) ? $registro->razao_social : "--"; ?></td>
                                <td><?php echo isset($registro->documento) && $registro->documento ? $registro->documento : (isset($registro->responsavel_documento) ? $registro->responsavel_documento : "--"); ?></td>
                                <td class="nao-mostrar-impressao">
                                    <a href="edicao.php?id=<?php echo $registro->id; ?>" class="btn btn-sm btn-editar">
                                        <i class="bi-pencil-square"></i> Editar
                                    </a>

                                    <?php if ($_SESSION["usuario_grupo"] == 1): ?>
                                        <button
                                            data-bs-toggle="modal"
                                            data-bs-target="#excluir-<?php echo $registro->id; ?>"
                                            type="button"
                                            class="btn btn-sm btn-excluir">
                                            <i class="bi bi-trash"></i> Excluir
                                        </button>
                                    <?php endif; ?>

                                    <a href="empresa.php?id=<?php echo $registro->id; ?>" class="btn btn-sm btn-empresa">
                                        <i class="bi-building"></i> Empresas
                                    </a>
                                </td>
                            </tr>

                            <?php $registroExcluir = $registro; ?>
                            <?php $data_action_excluir = "cliente/excluir.php"; ?>
                            <?php include("../components/excluir.php"); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php $linkPaginacaoUrlParametros = "&limite=" . $paginacao['limite'] . "&documento=$documento&nome_fantasia=$nome_fantasia&cliente_id=$cliente_id"; ?>

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
