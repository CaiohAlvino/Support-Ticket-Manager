<?php include("../template/topo.php"); ?>

<div class="container-fluid">
    <div class="sessao">
        <div class="row">
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                <a href="index.php" class="btn btn-voltar botao-noty-voltar">
                    <i class="bi-chevron-left me-2"></i>
                    Voltar
                </a>
            </div>
            <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
                <h1 class="titulo">Solicitar Suporte</h1>
            </div>
        </div>
    </div>
    <div class="sessao">
        <form id="suporte-cadastrar" data-action="suporte/cadastrar.php">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                    <label for="assunto">Assunto <span class="assunto-campo-obrigatorio text-danger">*</span></label>
                    <input type="text" class="form-control input-validar-nome campo-obrigatorio" id="assunto" name="assunto" maxlength="265">
                </div>
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                    <label for="mensagem">Mensagem<span class="mensagem-campo-obrigatorio text-danger">*</span></label>
                    <textarea class="form-control" name="mensagem" id="mensagem" maxlength="3000"></textarea>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 text-end">
                <button class="btn btn-confirmar botao-noty-ativo" type="submit">
                    <i class="bi-check-lg me-2"></i>
                    Solicitar Suporte
                </button>
            </div>
        </form>
    </div>
    <div class="border border-2 border-warning-subtle rounded p-3 bg-warning-subtle">
        <div class="row">
            <div class="col-xl-1 col-lg-1 col-md-8 col-sm-12 col-12 p-0 text-center">
                <i class="bi bi-patch-exclamation text-warning fs-1"></i>
            </div>
            <div class="col-xl-11 col-lg-11 col-md-8 col-sm-12 col-12 p-0">
                <div>
                    <p class="m-0 fw-bold">Instruções para Solicitação de Suporte</p>
                    <p class="">Antes de abrir um chamado de suporte, leia atentamente as orientações abaixo para garantir um atendimento mais rápido e eficaz:</p>
                </div>
                <div>
                    <p class="m-0 fw-bold">Solicite ajuda de forma eficiente:</p>
                    <ul>
                        <li>Verifique os tutoriais e FAQ antes de abrir um chamado</li>
                        <li>Descreva claramente o problema com prints de tela</li>
                        <li>Informe o módulo específico e os passos realizados</li>
                    </ul>
                </div>
                <div>
                    <p class="m-0 fw-bold">Prazos:</p>
                    <ul>
                        <li>Problemas críticos: 24h úteis</li>
                        <li>Questões gerais: 36h úteis</li>
                    </ul>
                </div>
                <div>
                    <p class="m-0 fw-bold">Estamos disponíveis em dias úteis, das 8h às 18h.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../template/rodape.php"); ?>