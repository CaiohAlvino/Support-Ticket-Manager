<?php include("../template/topo.php"); ?>

<div class="sessao">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
            <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
        </div>
        <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
            <h1 class="titulo">Cadastrar Serviço</h1>
        </div>
    </div>
</div>

<div class="sessao">
    <form id="servico-cadastrar" data-action="servico/cadastrar.php">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                <label for="nome" class="form-label">Nome do Serviço <span class="campo-obrigatorio text-danger">*</span></label>
                <input type="text" class="form-control input-validar-nome campo-obrigatorio" id="nome" name="nome">
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                <button type="submit" class="btn btn-confirmar botao-noty-ativo">
                    <i class="bi-check-lg"></i> Cadastrar serviço
                </button>
            </div>
        </div>
    </form>
</div>

<?php include("../template/rodape.php"); ?>
