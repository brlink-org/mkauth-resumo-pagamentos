<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dados da mensagem
    $resumo = $_POST["resumo"];
    $detalhes = $_POST["detalhes"];
    
    // Calcular o total
    $linhas = explode("\n", $detalhes);
    $total = 0;
    foreach($linhas as $linha) {
        $info = explode(" - ", $linha);
        if(count($info) > 1) {
            $valor = str_replace("R$ ", "", $info[1]);
            $total += floatval($valor);
        }
    }
    // Adicionar o total à mensagem
    $mensagem = "*$resumo*\n\n$detalhes\n\n*TOTAL R$ $total*";

    // URL DA EvolutionAPI
    $apiUrl = 'http://{{baseURL}}/message/sendText/{{instance}}'; // Substitua "{{baseURL}}" e "{{instance}}" pelas suas informações reais

    // Dados para enviar via WhatsApp
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

    // Converte para JSON
    $jsonData = json_encode($data);

    // Inicializa o cURL
    $ch = curl_init($apiUrl);

    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'apikey: digite-seu-token' // Substituir "digite-seu-token" pelo seu token real
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

    // Redireciona de volta para a página index.php
    header("Location: index.php");
    exit();
}
?>
