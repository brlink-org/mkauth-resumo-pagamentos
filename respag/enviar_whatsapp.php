<?php
// ------------------------------------------------------------------------------------------------
// Configurações da API WhatsApp
// ------------------------------------------------------------------------------------------------
$apiUrl = 'https://{{baseURL}}/message/sendText/{{instance}}'; // URL da API Evolution v2
$apiToken = 'seu-token-aqui'; // Token de autenticação da API

// ------------------------------------------------------------------------------------------------
// Envio da mensagem via API
// ------------------------------------------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resumo = $_POST["resumo"];
    $detalhes = $_POST["detalhes"];
    
    // Calcula o total
    $linhas = explode("\n", $detalhes);
    $total = 0;
    foreach ($linhas as $linha) {
        $info = explode(" - ", $linha);
        if (count($info) > 1) {
            $valor = str_replace("R$ ", "", $info[1]);
            $total += floatval($valor);
        }
    }
    
    // Prepara a mensagem para envio
    $mensagem = "*$resumo*\n\n$detalhes\n\n*TOTAL R$ $total*";
    $data = array(
        "number" => "numero_destino", // Substituir "numero_destino" pelo número de destino
        "options" => array(
            "delay" => 1200,
            "presence" => "composing",
            "linkPreview" => false
        ),
        "textMessage" => array(
            "text" => $mensagem
        )
    );
    
    $jsonData = json_encode($data);

    // Configura cURL para enviar a requisição
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'apikey: ' . $apiToken
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Executa a requisição
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