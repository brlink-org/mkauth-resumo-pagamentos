<?php
// ------------------------------------------------------------------------------------------------
// Configurações da API WhatsApp
// ------------------------------------------------------------------------------------------------
$apiUrl = 'https://{{baseURL}}/message/sendText/{{instance}}'; // URL da API Evolution v2
$apiToken = 'seu-token-aqui'; // Token de autenticação da API
$celular = "numero_destino"; // Número de celular no formato internacional (com código do país)

// ------------------------------------------------------------------------------------------------
// Envio da mensagem via API
// ------------------------------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resumo = $_POST["resumo"]; // Agora, a mensagem já está formatada

    // Prepara os dados para envio via Evolution API v2
    $data = array(
        "number" => "$celular", // Número de celular no formato internacional
        "text" => "$resumo"     // Conteúdo da mensagem
    );

    $jsonData = json_encode($data);

    // Inicializa o cURL para enviar a requisição
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'apikey: ' . $apiToken
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Executa a requisição e verifica erros
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Erro ao chamar a API: ' . curl_error($ch);
    } else {
        echo 'Mensagem enviada com sucesso!';
    }

    curl_close($ch);

    // Redireciona de volta para a página principal
    header("Location: index.php");
    exit();
}
?>