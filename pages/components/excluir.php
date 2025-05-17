<div class="modal fade" id="excluir-<?php echo $registroExcluir->id; ?>" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 450px;">
        <div class="modal-content" style="border-radius: 12px;">
            <div class="modal-body p-4">
                <h4 class="mb-3" style="font-weight: 600;">Confirmação de Exclusão</h4>
                <p class="mb-4">Deseja realmente excluir esse item?</p>
                <div class="d-flex justify-content-between mt-4">
                    <button type="button" class="btn btn-outline-secondary px-4 py-2 botao-noty-voltar" data-bs-dismiss="modal" style="border-radius: 8px; width: 48%;"> Cancelar
                    </button>
                    <button
                        data-id="<?php echo $registroExcluir->id; ?>"
                        data-action="<?php echo $data_action_excluir; ?>"
                        type="button"
                        class="btn btn-danger botao-excluir-executar px-4 py-2 botao-noty-ativo"
                        style="border-radius: 8px; width: 48%;">
                        <i class="bi bi-trash"></i> Excluir
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>