<?php function formatarData($data, $formato = 'd/m/Y H:i')
{
    return date($formato, strtotime($data));
}

function sanitizarString($string)
{
    return htmlspecialchars(strip_tags($string));
}

function validarEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function gerarSenhaAleatoria($tamanho = 8)
{
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()';
    $senha = '';

    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }

    return $senha;
}

function formatarStatusSuporte($status)
{
    switch ($status) {
        case STATUS_ABERTO:
            return '<span class="badge text-bg-success">ABERTO</span>';
        case STATUS_AGUARDANDO_SUPORTE:
            return '<span class="badge text-bg-warning">AGUARDANDO SUPORTE</span>';
        case STATUS_RESPONDIDO:
            return '<span class="badge text-bg-info">RESPONDIDO</span>';
        case STATUS_FECHADO:
            return '<span class="badge text-bg-danger">FECHADO</span>';
        default:
            return '<span class="badge text-bg-secondary">DESCONHECIDO</span>';
    }
}

/**
 * Valida se o usuário tem permissão de administrador
 * @return bool
 */
function validarPermissao($permissao)
{
    if (!isset($_SESSION['usuario'])) {
        return false;
    }

    switch ($permissao) {
        case 'admin':
            return $_SESSION['usuario']->grupo_id == 1; // 1 = ID do grupo Administrador
        case 'cliente':
            return $_SESSION['usuario']->grupo_id == 2; // 2 = ID do grupo Usuário
        default:
            return false;
    }
}

function redirecionarComMensagem($url, $mensagem, $tipo = 'success')
{
    $_SESSION['flash_message'] = [
        'mensagem' => $mensagem,
        'tipo' => $tipo
    ];
    header("Location: " . $url);
    exit;
}

function mostrarMensagem()
{
    if (isset($_SESSION['flash_message'])) {
        $mensagem = $_SESSION['flash_message']['mensagem'];
        $tipo = $_SESSION['flash_message']['tipo'];
        unset($_SESSION['flash_message']);

        return "<div class='alert alert-{$tipo} alert-dismissible fade show' role='alert'>
 {
            $mensagem
        }

        <button type='button'class='btn-close'data-bs-dismiss='alert'aria-label='Close'></button></div>";
    }

    return '';
}

function gerarLog($acao, $descricao, $usuario_id = null)
{
    global $db;

    if (!$usuario_id && isset($_SESSION['usuario'])) {
        $usuario_id = $_SESSION['usuario']->id;
    }

    $stmt = $db->prepare("INSERT INTO logs (acao, descricao, usuario_id, ip) VALUES (:acao, :descricao, :usuario_id, :ip)");

    return $stmt->execute([
        ':acao' => $acao,
        ':descricao' => $descricao,
        ':usuario_id' => $usuario_id,
        ':ip' => $_SERVER['REMOTE_ADDR']
    ]);
}
