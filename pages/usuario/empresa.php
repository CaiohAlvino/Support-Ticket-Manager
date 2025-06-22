<?php include("../template/topo.php"); ?>
<?php
$nome = isset($_GET["nome"]) ? $_GET["nome"] : NULL;
$pagina = isset($_GET["pagina"]) ? $_GET["pagina"] : 1;
$id = isset($_GET["id"]) ? (int)$_GET["id"] : NULL;

$indexRegistros = $classEmpresaUsuario->index([
    "usuario_id" => $id,
    "pagina" => $pagina,
    "limite" => 15
]);
$registros = $indexRegistros["resultados"];

$paginacao = $indexRegistros["paginacao"];

$empresas = $classEmpresa->listarEmpresas();
$usuario = $classUsuario->pegarPorId($id);
?>
<header class="sessao">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
            <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 my-1">
            <h1 class="titulo">Empresas do usuario: <?php echo $usuario->nome ?></h1>
        </div>
    </div>
</header>

<div class="sessao">
    <form id="empresa-usuario-cadastrar" data-action="usuario/cadastrar-empresa.php">
        <input type="hidden" name="usuario_id" id="usuario_id" value="<?php echo $id; ?>">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                <label for="empresa_id" class="form-label">Adicionar empresas <span class="campo-obrigatorio text-danger">*</span></label>
                <select name="empresa_id" id="empresa_id" class="form-select select2 input-validar-select campo-obrigatorio">
                    <option value="">Selecione uma empresa</option>
                    <?php foreach ($empresas as $empresa): ?>
                        <option value="<?php echo $empresa->id; ?>"><?php echo $empresa->nome; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                <button type="submit" class="btn btn-confirmar botao-noty-ativo">
                    <i class="bi-check-lg"></i> Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<div class="sessao">
    <?php if (count($registros) == 0): ?>
        <div class="alert alerta-sem-cadastrar text-center py-4" role="alert">
            <div class="mb-3">
                <i class="bi bi-people fs-1"></i>
            </div>
            <h5 class="fw-bold">Nenhuma empresa encontrada</h5>
            <p class="mb-0">Confira se os dados estão corretos ou cadastre uma nova empresa.</p>
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
                            <tr class="<?php echo isset($focu_empresa_id) && $registro->id == $focu_empresa_id ? "table-active" : ""; ?>">
                                <td>
                                    <?php if (isset($registro->situacao) && $registro->situacao == 1): ?>
                                        <span class="badge text-bg-success">Ativo</span>
                                    <?php else: ?>
                                        <span class="badge text-bg-danger">Inativo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo isset($registro->empresa_nome) ? $registro->empresa_nome : "--"; ?></td>
                                <td class="nao-mostrar-impressao">
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
                            <?php $data_action_excluir = "usuario/excluir-empresa.php"; ?>
                            <?php include("../components/excluir.php"); ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
</div>

<?php $linkPaginacaoUrlParametros = "&limite=" . $paginacao["limite"] . "&nome=$nome"; ?>

<?php
        $limite = 2;
        $inicio = ((($pagina - $limite) > 1) ? $pagina - $limite : 1);
        $fim = ((($pagina + $limite) < $paginacao["total_paginas"]) ? $pagina + $limite : $paginacao["total_paginas"]);
?>

<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-3">
        <?php if ($paginacao["pagina"] > 1): ?>
            <li class="page-item">
                <a class="me-2 btn btn-outline-dark" href="?pagina=<?php echo $paginacao["pagina"] - 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">
                    Anterior
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = $inicio; $i <= $fim; $i++) : ?>
            <?php if ($i == $paginacao["pagina"]): ?>
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

        <?php if ($paginacao["pagina"] < $paginacao["total_paginas"]): ?>
            <li class="ms-2 page-item">
                <a class="btn btn-outline-dark" href="?pagina=<?php echo $paginacao["pagina"] + 1; ?><?php echo $linkPaginacaoUrlParametros; ?>">
                    Próxima
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php endif; ?>

<?php include("../template/rodape.php"); ?>
