<?php include("../template/topo.php"); ?>
<?php

$id = isset($_GET["id"]) ? $_GET["id"] : NULL;

$suporte_registro = $classSuporte->pegarPorId($id);
$mensagem_registro = $classSuporteMensagem->pegarPorSuporteId($id);
?>

<div class="suporte-mensagem-detalhe mt-2">

    <?php if (!$suporte_registro): ?>
        <div class="error-container text-center py-5">
            <div class="error-icon mb-4">
                <i class="bi-exclamation-circle display-1 text-danger"></i>
            </div>
            <h2 class="error-title mb-3 text-muted">Suporte não encontrado!</h2>
            <p class="error-message text-muted mb-4">O suporte que você está procurando não existe ou foi removido.</p>
            <a href="index.php" class="btn btn-voltar botao-noty-voltar">
                <i class="bi-arrow-left me-2"></i>
                Voltar para a lista de Suporte
            </a>
        </div>
    <?php else: ?>
        <div class="sessao">
            <div class="row">
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-12 col-12">
                    <a href="#" onclick="history.back(); return false;" class="btn btn-voltar botao-noty-voltar">
                        <i class="bi-chevron-left me-2"></i>
                        Voltar
                    </a>
                </div>
                <div class="col-xl-10 col-lg-10 col-md-8 col-sm-12 col-12">
                    <h1 class="titulo">Detalhes do Suporte</h1>
                </div>
            </div>
        </div>
        <div class="sessao">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="subtitulo mb-0">Assunto: <?php echo isset($suporte_registro->assunto) ? htmlspecialchars($suporte_registro->assunto) : '-' ?></h2>
                <p class="fw-bold my-1">
                    <?php if (isset($suporte_registro->status) && $suporte_registro->status == "ABERTO"): ?>
                        <span class="badge text-bg-success">ABERTO</span>
                    <?php elseif (isset($suporte_registro->status) && $suporte_registro->status == "AGUARDANDO_SUPORTE"): ?>
                        <span class="badge text-bg-warning">AGUARDANDO SUPORTE</span>
                    <?php elseif (isset($suporte_registro->status) && $suporte_registro->status == "RESPONDIDO"): ?>
                        <span class="badge text-bg-info">RESPONDIDO</span>
                    <?php else: ?>
                        <span class="badge text-bg-danger">FECHADO</span>
                    <?php endif; ?>
                </p>
            </div>
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <p class="my-1">
                        <?php if (isset($suporte_registro->status) && $suporte_registro->status == "FECHADO"): ?>
                            <span class="text-muted d-block">Fechado:</span>
                            <span class="fw-medium"><?php echo !empty($suporte_registro->alterado) ? date("d/m/Y (H:i)", strtotime($suporte_registro->alterado)) : '-' ?></span>
                        <?php else: ?>
                            <span class="text-muted d-block">Criado:</span>
                            <span class="fw-medium"><?php echo !empty($suporte_registro->cadastrado) ? date("d/m/Y (H:i)", strtotime($suporte_registro->cadastrado)) : '-' ?></span>
                        <?php endif ?>
                    </p>
                </div>
                <div class="col-md-3 col-sm-6">
                    <span class="text-muted d-block">ID do Ticket:</span>
                    <span class="fw-medium">#<?php echo isset($suporte_registro->id) ? htmlspecialchars($suporte_registro->id) : '-' ?></span>
                </div>

                <div class="col-md-3 col-sm-6">
                    <span class="text-muted d-block">Empresa</span>
                    <span class="fw-medium"><?php echo isset($suporte_registro->empresa_nome) ? htmlspecialchars($suporte_registro->empresa_nome) : '-' ?></span>
                </div>

                <div class="col-md-3 col-sm-6">
                    <span class="text-muted d-block">Serviço</span>
                    <span class="fw-medium"><?php echo isset($suporte_registro->servico_nome) ? htmlspecialchars($suporte_registro->servico_nome) : '-' ?></span>
                </div>

                <div class="col-md-12 mt-3">
                    <span class="meta-label text-muted d-block mb-2">Mensagem:</span>
                    <?php if (isset($mensagem_registro[0])): ?>
                        <?php echo htmlspecialchars($mensagem_registro[0]->mensagem) ?>
                    <?php else: ?>
                        <span class="text-muted">Nenhuma mensagem cadastrada.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="sessao">
            <h2 class="subtitulo mb-0">Histórico de Interação</h2>
            <?php
            $historico_mensagens = (is_array($mensagem_registro) && count($mensagem_registro) > 1) ? array_slice($mensagem_registro, 1) : [];
            foreach ($historico_mensagens as $mensagens):
            ?>
                <div class=" p-3 border border-1 rounded mt-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <?php if ($mensagens->proprietario == "CLIENTE"): ?>
                            <span class="usuario-nome-cliente fw-bold">
                                <?php
                                echo $suporte_registro->cliente_nome_fantasia ? htmlspecialchars($suporte_registro->cliente_nome_fantasia) : ($suporte_registro->cliente_responsavel_nome ? htmlspecialchars($suporte_registro->cliente_responsavel_nome) : 'Cliente');
                                ?>
                            </span>
                        <?php else: ?>
                            <span class="usuario-nome-suporte fw-bold">
                                <?php
                                echo $suporte_registro->usuario_nome ? htmlspecialchars($suporte_registro->usuario_nome) : 'Suporte';
                                ?>
                            </span>
                        <?php endif; ?>
                        <span class="text-muted small"><?php echo date('d/m/Y H:i:s', strtotime($mensagens->cadastrado)); ?></span>
                    </div>
                    <div class="">
                        <?php echo htmlspecialchars($mensagens->mensagem) ?>
                    </div>
                </div>
            <?php endforeach ?>
            <div class="text-end mt-3" <?php echo $suporte_registro->status == "FECHADO" ? '' : 'style="display:none;"'; ?>>
                <form id="suporte-reabrir" data-action="suporte-mensagem/cadastrar.php">
                    <input type="hidden" name="id" value="<?php echo isset($suporte_registro->id) ? htmlspecialchars($suporte_registro->id) : '' ?>">
                    <?php if ($_SESSION["usuario_grupo"] != 2): ?>
                        <input type="hidden" name="usuario_id" value="<?php echo $_SESSION["usuario_id"] ?>">
                    <?php else: ?>
                        <input type="hidden" name="usuario_id" value="">
                    <?php endif ?>
                    <input type="hidden" name="cliente_id" value="<?php echo isset($suporte_registro->cliente_id) ? htmlspecialchars($suporte_registro->cliente_id) : '' ?>">
                    <input type="hidden" name="empresa_id" value="<?php echo isset($suporte_registro->empresa_id) ? htmlspecialchars($suporte_registro->empresa_id) : '' ?>">
                    <input type="hidden" name="mensagem">
                    <button class="btn btn-confirmar reabrir botao-noty-ativo" type="submit">
                        <i class="bi-envelope-open me-2"></i>
                        Reabrir Suporte
                    </button>
                </form>
            </div>
        </div>
        <div class="sessao" <?php echo $suporte_registro->status != "FECHADO" ? '' : 'style="display:none;"'; ?>>
            <form id="suporte-mensagem-detalhe" data-action="suporte-mensagem/cadastrar.php">
                <input type="hidden" name="id" value="<?php echo isset($suporte_registro->id) ? htmlspecialchars($suporte_registro->id) : '' ?>">
                <?php if ($_SESSION["usuario_grupo"] != 2): ?>
                    <input type="hidden" name="usuario_id" value="<?php echo $_SESSION["usuario_id"] ?>">
                <?php else: ?>
                    <input type="hidden" name="usuario_id" value="">
                <?php endif ?>
                <input type="hidden" name="cliente_id" value="<?php echo isset($suporte_registro->cliente_id) ? htmlspecialchars($suporte_registro->cliente_id) : '' ?>">
                <input type="hidden" name="empresa_id" value="<?php echo isset($suporte_registro->empresa_id) ? htmlspecialchars($suporte_registro->empresa_id) : '' ?>">
                <h6>Responder ao Ticket</h6>
                <div class="row">
                    <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 mb-3">
                        <label for="mensagem">Mensagem<span class="mensagem-campo-obrigatorio text-danger">*</span></label>
                        <textarea class="form-control" name="mensagem" id="mensagem"></textarea>
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-excluir botao-noty-voltar" type="submit">
                        <i class="bi-ban me-2"></i>
                        Fechar Ticket
                    </button>
                    <button class="btn btn-confirmar botao-noty-ativo" type="submit">
                        <i class="bi-pen me-2"></i>
                        Enviar Mensagem
                    </button>
                </div>
            </form>
        </div>
    <?php endif ?>
    <div class="modal fade" id="confirmarFecharTicket" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Confirmação de Fechamento</h1>
                    <button type="button" class="btn-close botao-noty-voltar" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja fechar este ticket?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary botao-noty-voltar" data-bs-dismiss="modal">
                        Cancelar
                    </button>
                    <button type="button" class="btn btn-danger botao-noty-ativo" id="confirmar-fechar-ticket">
                        Confirmar Fechamento
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include("../template/rodape.php"); ?>
