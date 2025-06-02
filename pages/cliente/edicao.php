<?php include("../template/topo.php"); ?>

<?php
$id = isset($_GET["id"]) ? $_GET["id"] : NULL;
$aba = isset($_GET["aba"]) ? $_GET["aba"] : "dados";

$registro = $cliente->pegarPorId($id);
?>

<div class="cliente-edicao mt-2">

    <?php if (!$registro): ?>
        <div class="error-container text-center py-5">
            <div class="error-icon mb-4">
                <i class="bi-exclamation-circle display-1 text-danger"></i>
            </div>
            <h2 class="error-title mb-3 text-muted">Cliente não encontrado!</h2>
            <p class="error-message text-muted mb-4">O Cliente que você está procurando não existe ou foi removido.</p>
            <a href="index.php" class="btn btn-voltar botao-noty-voltar">
                <i class="bi-arrow-left me-2"></i>
                Voltar para a lista de Clientes
            </a>
        </div>
    <?php else: ?>
        <div class="sessao">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                    <a href="index.php" class="btn btn-voltar botao-noty-voltar">
                        <i class="bi-chevron-left me-2"></i>
                        Voltar
                    </a>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
                    <h1 class="titulo">Editar Cliente</h1>
                </div>
            </div>
        </div>
        <div class="sessao">
            <form id="cliente-edicao" data-action="cliente/editar.php">
                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 mb-4">
                    <label for="tipo-cliente">Tipo de Cadastro</label>
                    <select name="tipo" id="tipo-cliente" class="form-control">
                        <option value="CNPJ" <?php echo $registro->tipo == "CNPJ" ? "selected" : ""; ?>>Pessoa Jurídica</option>
                        <option value="CPF" <?php echo $registro->tipo == "CPF" ? "selected" : ""; ?>>Pessoa Física</option>
                    </select>
                </div>

                <input type="hidden" name="id" value="<?php echo $registro->id; ?>">

                <!-- Campo Nome Fantasia (visível apenas para PF) -->
                <div id="sessao-pf-cliente" class="campo-pf-cliente" <?php echo $registro->tipo != "CPF" ? 'style="display:none;"' : ''; ?>>
                    <h2 class="titulo">Dados da Empresa</h2>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="nome_fantasia_cliente" class="form-label">
                                Nome da Empresa
                                <span class="formulario-campo-obrigatorio">*</span>
                            </label>
                            <input type="text" class="form-control input-validar-nome-empresa campo-obrigatorio" id="nome_fantasia_cliente" name="nome_fantasia" value="<?php echo $registro->nome_fantasia; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="telefone_cliente" class="form-label">
                                Telefone
                            </label>
                            <input type="text" class="form-control mascara-telefone" id="telefone_cliente" name="telefone" value="<?php echo $registro->telefone; ?>">
                        </div>
                    </div>
                </div>

                <!-- Campos específicos para Pessoa Jurídica (CNPJ) -->
                <div id="sessao-cnpj-cliente" class="campo-pj-cliente" <?php echo $registro->tipo != "CNPJ" ? 'style="display:none;"' : ''; ?>>
                    <h2 class="titulo">Dados da Empresa</h2>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="nome_fantasia_pj_cliente" class="form-label">
                                Nome Fantasia
                                <span class="formulario-campo-obrigatorio">*</span>
                            </label>
                            <input type="text" class="form-control input-validar-nome-fantasia campo-obrigatorio" id="nome_fantasia_pj_cliente" name="nome_fantasia" value="<?php echo $registro->nome_fantasia; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="razao_social_cliente" class="form-label">
                                Razão Social
                                <span class="formulario-campo-obrigatorio">*</span>
                            </label>
                            <input type="text" class="form-control input-validar-razao-social campo-obrigatorio" id="razao_social_cliente" name="razao_social" value="<?php echo $registro->razao_social; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="documento_cliente" class="form-label">
                                CNPJ
                                <span class="formulario-campo-obrigatorio">*</span>
                            </label>
                            <input type="text" class="form-control mascara-cnpj input-validar-cnpj campo-obrigatorio" id="documento_cliente" name="documento" value="<?php echo $registro->documento; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="telefone_pj_cliente" class="form-label">
                                Telefone
                            </label>
                            <input type="text" class="form-control mascara-telefone" id="telefone_pj_cliente" name="telefone" value="<?php echo $registro->telefone; ?>">
                        </div>
                    </div>
                </div>

                <!-- Dados de Identificação (compartilhados, mas com rótulos dinâmicos) -->
                <div class="dados-identificacao">
                    <h2 class="titulo dados-titulo-cliente mt-4">
                        <?php if ($registro->tipo == "CPF"): ?>
                            Dados Pessoais
                        <?php else: ?>
                            Dados do Responsável
                        <?php endif; ?>
                    </h2>
                    <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="responsavel_cliente" class="form-label dados-label-nome-cliente"></label>
                            <input type="text" class="form-control input-validar-responsavel campo-obrigatorio" id="responsavel_cliente" name="responsavel_nome" value="<?php echo $registro->responsavel_nome; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="responsavel_documento_cliente" class="form-label dados-label-documento-cliente"></label>
                            <input type="text" class="form-control input-validar-cpf campo-obrigatorio mascara-cpf" id="responsavel_documento_cliente" name="responsavel_documento" value="<?php echo $registro->responsavel_documento; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="responsavel_telefone_cliente" class="form-label dados-label-whatsapp-cliente"></label>
                            <input type="text" class="form-control input-validar-whatsapp campo-obrigatorio mascara-whatsapp" id="responsavel_telefone_cliente" name="responsavel_telefone" value="<?php echo $registro->responsavel_telefone; ?>">
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                            <label for="responsavel_email_cliente" class="form-label dados-label-email-cliente"></label>
                            <input type="email" class="form-control input-validar-email" id="responsavel_email_cliente" name="responsavel_email" value="<?php echo $registro->responsavel_email; ?>">
                        </div>
                    </div>
                </div>

                <!-- Campos comuns para ambos os tipos -->
                <h2 class="titulo mt-4">Endereço</h2>
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <label for="cep_cliente" class="form-label">CEP</label>
                        <input type="text" class="form-control mascara-cep input-validar-cep" id="cep_cliente" name="cep" value="<?php echo $registro->cep; ?>">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="endereco_cliente" class="form-label">Endereço</label>
                        <input type="text" class="form-control campo-endereco" id="endereco_cliente" name="endereco" value="<?php echo $registro->endereco; ?>">
                    </div>

                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                        <label for="numero_cliente" class="form-label">Número</label>
                        <input type="text" class="form-control campo-numero" id="numero_cliente" name="numero" value="<?php echo $registro->numero; ?>">
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <label for="bairro_cliente" class="form-label">Bairro</label>
                        <input type="text" class="form-control campo-bairro" id="bairro_cliente" name="bairro" value="<?php echo $registro->bairro; ?>">
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <label for="cidade_cliente" class="form-label">Cidade</label>
                        <input type="text" class="form-control campo-cidade" id="cidade_cliente" name="cidade" value="<?php echo $registro->cidade; ?>">
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                        <label for="estado_cliente" class="form-label">Estado</label>
                        <select class="select2 form-select campo-estado" id="estado_cliente" name="estado">
                            <option value="">Selecione...</option>
                            <?php foreach (Estado::todos() as $sigla): ?>
                                <option <?php echo $registro->estado == Estado::getNome($sigla) ? "selected" : ""; ?> value="<?php echo Estado::getNome($sigla); ?>"><?php echo Estado::getNome($sigla); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                        <button type="submit" class="btn btn-primary botao-noty-ativo">
                            <i class="bi-check-lg"></i> Atualzar cliente
                        </button>
                    </div>
                </div>
            </form>
        </div>
    <?php endif ?>
</div>

<?php include("../template/rodape.php"); ?>
