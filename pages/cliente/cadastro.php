<?php include("../templates/topo.php"); ?>

<div class="container-fluid">
    <div class="sessao">
        <div class="row">
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
            </div>
            <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
                <h1 class="titulo">Cadastrar fornecedor</h1>
            </div>
        </div>
    </div>
    <div class="sessao">
        <form id="fornecedor-cadastrar" data-action="fornecedor/cadastrar.php">
            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 mb-4">
                <label for="tipo-fornecedor">Tipo de Cadastro</label>
                <select name="tipo" id="tipo-fornecedor" class="form-control">
                    <option value="CNPJ">Pessoa Jurídica</option>
                    <option value="CPF">Pessoa Física</option>
                </select>
            </div>

            <!-- Campo Nome Fantasia (visível apenas para PF) -->
            <div id="sessao-pf-fornecedor" class="campo-pf-fornecedor">
                <h2 class="titulo">Dados da Empresa</h2>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="nome_fantasia_fornecedor" class="form-label">
                            Nome da Empresa
                            <span class="formulario-campo-obrigatorio">*</span>
                        </label>
                        <input type="text" class="form-control input-validar-nome-empresa campo-obrigatorio" id="nome_fantasia_fornecedor" name="nome_fantasia" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="telefone_fornecedor" class="form-label">
                            Telefone
                        </label>
                        <input type="text" class="form-control mascara-telefone" id="telefone_fornecedor" name="telefone" value="">
                    </div>
                </div>
            </div>

            <!-- Campos específicos para Pessoa Jurídica (CNPJ) -->
            <div id="sessao-cnpj-fornecedor" class="campo-pj-fornecedor">
                <h2 class="titulo">Dados da Empresa</h2>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="nome_fantasia_pj_fornecedor" class="form-label">
                            Nome Fantasia
                            <span class="formulario-campo-obrigatorio">*</span>
                        </label>
                        <input type="text" class="form-control input-validar-nome-fantasia campo-obrigatorio" id="nome_fantasia_pj_fornecedor" name="nome_fantasia" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="razao_social_fornecedor" class="form-label">
                            Razão Social
                            <span class="formulario-campo-obrigatorio">*</span>
                        </label>
                        <input type="text" class="form-control input-validar-razao-social campo-obrigatorio" id="razao_social_fornecedor" name="razao_social" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="documento_fornecedor" class="form-label">
                            CNPJ
                            <span class="formulario-campo-obrigatorio">*</span>
                        </label>
                        <input type="text" class="form-control mascara-cnpj input-validar-cnpj campo-obrigatorio" id="documento_fornecedor" name="documento" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="telefone_pj_fornecedor" class="form-label">
                            Telefone
                        </label>
                        <input type="text" class="form-control mascara-telefone" id="telefone_pj_fornecedor" name="telefone" value="">
                    </div>
                </div>
            </div>

            <!-- Dados de Identificação (compartilhados, mas com rótulos dinâmicos) -->
            <div class="dados-identificacao">
                <h2 class="titulo dados-titulo-fornecedor mt-4">teste</h2>
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="responsavel_fornecedor" class="form-label dados-label-nome-fornecedor"></label>
                        <input type="text" class="form-control input-validar-responsavel campo-obrigatorio" id="responsavel_fornecedor" name="responsavel" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="responsavel_documento_fornecedor" class="form-label dados-label-documento-fornecedor"></label>
                        <input type="text" class="form-control input-validar-cpf campo-obrigatorio mascara-cpf" id="responsavel_documento_fornecedor" name="responsavel_documento" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="responsavel_whatsapp_fornecedor" class="form-label dados-label-whatsapp-fornecedor"></label>
                        <input type="text" class="form-control input-validar-whatsapp mascara-whatsapp" id="responsavel_whatsapp_fornecedor" name="responsavel_whatsapp" value="">
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label for="responsavel_email_fornecedor" class="form-label dados-label-email-fornecedor"></label>
                        <input type="email" class="form-control input-validar-email" id="responsavel_email_fornecedor" name="responsavel_email" value="">
                    </div>
                </div>
            </div>

            <!-- Campos comuns para ambos os tipos -->
            <h2 class="titulo mt-4">Endereço</h2>
            <div class="row">
                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="cep_fornecedor" class="form-label">CEP</label>
                    <input type="text" class="form-control mascara-cep input-validar-cep" id="cep_fornecedor" name="cep" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="logradouro_fornecedor" class="form-label">Endereço</label>
                    <input type="text" class="form-control campo-logradouro" id="logradouro_fornecedor" name="logradouro" value="">
                </div>

                <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                    <label for="numero_fornecedor" class="form-label">Número</label>
                    <input type="text" class="form-control campo-numero" id="numero_fornecedor" name="numero" value="">
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="bairro_fornecedor" class="form-label">Bairro</label>
                    <input type="text" class="form-control campo-bairro" id="bairro_fornecedor" name="bairro" value="">
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="cidade_fornecedor" class="form-label">Cidade</label>
                    <input type="text" class="form-control campo-cidade" id="cidade_fornecedor" name="cidade" value="">
                </div>

                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                    <label for="estado_fornecedor" class="form-label">Estado</label>
                    <select class="select2 form-select campo-estado" id="estado_fornecedor" name="estado">
                        <option value="">Selecione...</option>
                        <?php foreach (Estado::todos() as $sigla): ?>
                            <option value="<?php echo Estado::getNome($sigla); ?>"><?php echo Estado::getNome($sigla); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                    <button type="submit" class="btn btn-confirmar botao-noty-ativo">
                        <i class="bi-check-lg"></i> Cadastrar fornecedor
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include("../templates/rodape.php"); ?>