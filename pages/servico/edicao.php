<?php
include("../template/topo.php");
$id = isset($_GET['id']) ? $_GET['id'] : null;

$servico = $classServico->pegarPorId($id);
?>

<div class="sessao">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
            <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
        </div>
        <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
            <h1 class="titulo">Editar Serviço</h1>
        </div>
    </div>
</div>

<div class="sessao">
    <form id="servico-editar" data-action="servico/editar.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($servico->id); ?>">
        <div class="row">
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 col-12 mb-3">
                <label for="ativo" class="form-label">Situação <span class="campo-obrigatorio text-danger">*</span></label>
                <select name="ativo" id="ativo" class="form-select">
                    <option value="1" <?php echo $servico->situacao == 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo $servico->situacao == 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>

            <div class="col-xl-10 col-lg-10 col-md-10 col-sm-8 col-12 mb-3">
                <label for="nome" class="form-label">Nome do Serviço <span class="campo-obrigatorio text-danger">*</span></label>
                <input type="text" class="form-control input-validar-nome campo-obrigatorio" id="nome" name="nome" value="<?php echo htmlspecialchars($servico->nome); ?>" placeholder="Digite o nome do serviço" required>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                <button type="submit" class="btn btn-confirmar botao-noty-ativo">
                    <i class="bi-check-lg"></i> Salvar
                </button>
            </div>
        </div>
    </form>
</div>

<?php include("../template/rodape.php"); ?>
