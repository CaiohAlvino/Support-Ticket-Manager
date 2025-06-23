<?php include("../template/topo.php"); ?>

<div class="sessao">
    <div class="row">
        <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
            <a href="index.php" class="btn btn-voltar botao-noty-voltar"><i class="bi-chevron-left me-2"></i> Voltar</a>
        </div>
        <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
            <h1 class="titulo">Cadastrar Cliente</h1>
        </div>
    </div>
</div>
<div class="sessao">
    <form id="cliente-cadastrar" data-action="cliente/cadastrar.php">
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12 mb-4">
            <label for="tipo-cliente">Tipo de Cadastro</label>
            <select name="tipo" id="tipo-cliente" class="form-control">
                <option value="CNPJ">Pessoa Jurídica</option>
                <option value="CPF">Pessoa Física</option>
            </select>
        </div>

        <!-- Campo Nome Fantasia (visível apenas para PF) -->
        <div id="sessao-pf-cliente" class="campo-pf-cliente">
            <h2 class="titulo">Dados da Empresa</h2>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="nome_fantasia_cliente" class="form-label">
                        Nome da Empresa
                        <span class="formulario-campo-obrigatorio">*</span>
                    </label>
                    <input type="text" class="form-control input-validar-nome-empresa campo-obrigatorio" id="nome_fantasia_cliente" name="nome_fantasia" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="telefone_cliente" class="form-label">
                        Telefone
                    </label>
                    <input type="text" class="form-control mascara-telefone" id="telefone_cliente" name="telefone" value="">
                </div>
            </div>
        </div>

        <!-- Campos específicos para Pessoa Jurídica (CNPJ) -->
        <div id="sessao-cnpj-cliente" class="campo-pj-cliente">
            <h2 class="titulo">Dados da Empresa</h2>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="nome_fantasia_pj_cliente" class="form-label">
                        Nome Fantasia
                        <span class="formulario-campo-obrigatorio">*</span>
                    </label>
                    <input type="text" class="form-control input-validar-nome-fantasia campo-obrigatorio" id="nome_fantasia_pj_cliente" name="nome_fantasia" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="razao_social_cliente" class="form-label">
                        Razão Social
                        <span class="formulario-campo-obrigatorio">*</span>
                    </label>
                    <input type="text" class="form-control input-validar-razao-social campo-obrigatorio" id="razao_social_cliente" name="razao_social" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="documento_cliente" class="form-label">
                        CNPJ
                        <span class="formulario-campo-obrigatorio">*</span>
                    </label>
                    <input type="text" class="form-control mascara-cnpj input-validar-cnpj campo-obrigatorio" id="documento_cliente" name="documento" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="telefone_pj_cliente" class="form-label">
                        Telefone
                    </label>
                    <input type="text" class="form-control mascara-telefone" id="telefone_pj_cliente" name="telefone" value="">
                </div>
            </div>
        </div>

        <!-- Dados de Identificação (compartilhados, mas com rótulos dinâmicos) -->
        <div class="dados-identificacao">
            <h2 class="titulo dados-titulo-cliente mt-4">Título</h2>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="responsavel_cliente" class="form-label dados-label-nome-cliente"></label>
                    <input type="text" class="form-control input-validar-responsavel campo-obrigatorio" id="responsavel_cliente" name="responsavel_nome" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="responsavel_documento_cliente" class="form-label dados-label-documento-cliente"></label>
                    <input type="text" class="form-control input-validar-cpf campo-obrigatorio mascara-cpf" id="responsavel_documento_cliente" name="responsavel_documento" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="responsavel_telefone_cliente" class="form-label dados-label-whatsapp-cliente"></label>
                    <input type="text" class="form-control input-validar-whatsapp mascara-whatsapp" id="responsavel_telefone_cliente" name="responsavel_telefone" value="">
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <label for="responsavel_email_cliente" class="form-label dados-label-email-cliente"></label>
                    <input type="email" class="form-control input-validar-email campo-obrigatorio" id="responsavel_email_cliente" name="responsavel_email" value="">
                </div>
            </div>
        </div>

        <!-- Campos comuns para ambos os tipos -->
        <h2 class="titulo mt-4">Endereço</h2>
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <label for="cep_cliente" class="form-label">CEP</label>
                <input type="text" class="form-control mascara-cep input-validar-cep" id="cep_cliente" name="cep" value="">
            </div>

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                <label for="endereco_cliente" class="form-label">Endereço</label>
                <input type="text" class="form-control campo-logradouro" id="endereco_cliente" name="endereco" value="">
            </div>

            <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-12">
                <label for="numero_cliente" class="form-label">Número</label>
                <input type="text" class="form-control campo-numero" id="numero_cliente" name="numero" value="">
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <label for="bairro_cliente" class="form-label">Bairro</label>
                <input type="text" class="form-control campo-bairro" id="bairro_cliente" name="bairro" value="">
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <label for="cidade_cliente" class="form-label">Cidade</label>
                <input type="text" class="form-control campo-cidade" id="cidade_cliente" name="cidade" value="">
            </div>

            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12">
                <label for="estado_cliente" class="form-label">Estado</label>
                <select class="select2 form-select campo-estado" id="estado_cliente" name="estado">
                    <option value="">Selecione...</option>
                    <?php foreach (Estado::todos() as $sigla): ?>
                        <option value="<?php echo Estado::getNome($sigla); ?>"><?php echo Estado::getNome($sigla); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mt-3 text-end">
                <button type="submit" class="btn btn-confirmar botao-noty-ativo">
                    <i class="bi-check-lg"></i> Cadastrar cliente
                </button>
            </div>
        </div>
    </form>
</div>

<?php include("../template/rodape.php"); ?>
