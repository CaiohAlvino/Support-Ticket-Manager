<?php
include("../template/topo.php");
$id = isset($_GET['id']) ? $_GET['id'] : null;

$usuario = $classUsuario->pegarPorId($id);
?>

<div class="sessao">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
            <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
        </div>
        <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
            <h1 class="titulo">Editar Usuário</h1>
        </div>
    </div>
</div>

<div class="sessao">
    <form id="usuario-editar" data-action="usuario/editar.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($usuario->id); ?>">
        <div class="row">
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-4 col-12 mb-3">
                <label for="ativo" class="form-label">Situação <span class="campo-obrigatorio text-danger">*</span></label>
                <select name="ativo" id="ativo" class="form-select">
                    <option value="1" <?php echo $usuario->situacao == 1 ? 'selected' : ''; ?>>Ativo</option>
                    <option value="0" <?php echo $usuario->situacao == 0 ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>

            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 mb-3">
                <label for="grupo_id" class="form-label">Grupo <span class="campo-obrigatorio text-danger">*</span></label>
                <select class="form-select select2 input-validar-select campo-obrigatorio" id="grupo_id" name="grupo_id">
                    <option value="">Selecione um grupo</option>
                    <?php
                    $grupos = $classGrupo->listarGrupos();
                    foreach ($grupos as $grupo): ?>
                        <option value='<?php echo $grupo->id ?>' <?php echo $usuario->grupo_id == $grupo->id ? 'selected' : ''; ?>><?php echo $grupo->nome ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-12 mb-3">
                <label for="nome" class="form-label">Nome da Usuário <span class="campo-obrigatorio text-danger">*</span></label>
                <input type="text" class="form-control input-validar-nome campo-obrigatorio" id="nome" name="nome" value="<?php echo htmlspecialchars($usuario->nome); ?>" placeholder="Digite o nome do usuário">
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                <label for="email" class="form-label">E-mail <span class="campo-obrigatorio text-danger">*</span></label>
                <input type="email" class="form-control input-validar-email campo-obrigatorio" id="email" name="email" value="<?php echo htmlspecialchars($usuario->email); ?>" placeholder="Digite o e-mail do usuário">
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 mb-3">
                <label for="senha" class="form-label">Nova Senha <span class="campo-obrigatorio text-danger">*</span></label>
                <input type="password" class="form-control input-validar-senha campo-obrigatorio" id="senha" name="senha" placeholder="Digite a senha do usuário">
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
