<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados da mensagem
    $resumo = $_POST["resumo"];
    $detalhes = $_POST["detalhes"];

    // URL DA EvolutionAPI
    $apiUrl = 'http://{{baseURL}}/message/sendText/{{instance}}'; // Substituir "{{baseURL}}" e "{{instance}}" pelas suas informações reais

    // Dados para enviar via WhatsApp
    $data = array(
        "number" => "numero_destino", // Substituir "numero_destino" pelo número de destino
        "options" => array(
            "delay" => 1200,
            "presence" => "composing",
            "linkPreview" => false
        ),
        "textMessage" => array(
            "text" => "$resumo\n\n$detalhes"
        )
    );

    // Converte para JSON
    $jsonData = json_encode($data);

    // Inicializa o cURL
    $ch = curl_init($apiUrl);

    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'apikey: digite-seu-token' // Substitua "digite-seu-token" pelo seu token real
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);

    // Executa o cURL
    $response = curl_exec($ch);

    // Verifica erros
    if (curl_errno($ch)) {
        echo 'Erro ao chamar a API: ' . curl_error($ch);
    } else {
        echo 'Mensagem enviada com sucesso!';
    }

    // Fecha o cURL
    curl_close($ch);
}
?>
