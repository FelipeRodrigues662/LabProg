<?php

require_once '../classes/User.php';
require_once '../classes/Event.php';
// Verifique se os parâmetros user_id e event_id estão presentes na URL
if (!isset($_GET['user_id']) || !isset($_GET['event_id'])) {
    echo "Parâmetros inválidos.";
    exit;
}

// Obtenha os valores dos parâmetros
$user_id = $_GET['user_id'];
$event_id = $_GET['event_id'];

// Aqui você pode obter o usuário e o evento do banco de dados
$user = User::getById($user_id);
$event = Event::getById($event_id);

// Verifique se o usuário e o evento existem
if (!$user || !$event) {
    echo "Usuário ou evento não encontrado.";
    exit;
}

// Obtenha o nome do usuário e o título do evento
$user_name = $user->getName();
$event_title = $event->getTitle();

// Crie o conteúdo para o QR Code usando o nome do usuário e o título do evento
$qr_code_content = "User: $user_name, Event: $event_title";

// Inclua a biblioteca de geração de QR Code
require_once '../bibliotecas/phpqrcode/qrlib.php';

// Crie uma função para gerar o QR Code e fazer o download
function generateQRCode($content) {
    // Nome do arquivo para salvar o QR Code
    $filename = 'qrcode.png';

    // Gerar o QR Code e salvar no arquivo
    QRcode::png($content, $filename, QR_ECLEVEL_L, 10, 2);

    // Definir o cabeçalho para download
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // Enviar o arquivo para download
    readfile($filename);

    // Remover o arquivo temporário
    unlink($filename);
}

// Gerar o QR Code e fazer o download
generateQRCode($qr_code_content);
