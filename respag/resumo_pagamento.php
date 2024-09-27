<?php
// ------------------------------------------------------------------------------------------------
// Configurações principais
// ------------------------------------------------------------------------------------------------
$host = "localhost"; // Host do banco de dados MySQL
$usuario = "root"; // Usuário do banco de dados MySQL
$senha = "vertrigo"; // Senha do banco de dados MySQL
$db = "mkradius"; // Nome do banco de dados MySQL

$apiUrl = 'https://{{baseURL}}/message/sendText/{{instance}}'; // URL da API Evolution v2
$apiToken = 'seu-token-aqui'; // Token de autenticação da API

// ------------------------------------------------------------------------------------------------
// Conexão com o banco de dados
// ------------------------------------------------------------------------------------------------
$mysqli = new mysqli($host, $usuario, $senha, $db);
if ($mysqli->connect_errno) {
    echo "Falha na conexão: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit();
}

// ------------------------------------------------------------------------------------------------
// Formulário de entrada da data
// ------------------------------------------------------------------------------------------------
?>
<form name="pagamentos" method="post">
    <label for="datapag">Selecione a data para consultar os pagamentos:</label>
    <input type="date" name="datapag" id="datapag" required>
    <button type="submit">Consultar pagamentos</button>
</form>

<?php
// ------------------------------------------------------------------------------------------------
// Processa o formulário de consulta de pagamentos
// ------------------------------------------------------------------------------------------------
if (isset($_POST["datapag"])) {
    $datapag = $_POST["datapag"];
    
    // Consulta SQL para obter os pagamentos na data especificada
    $query = "SELECT datavenc, valorpag, coletor, formapag, login FROM sis_lanc WHERE datapag = '$datapag'";
    $result = mysqli_query($mysqli, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $detalhes = ""; // Inicializa a variável detalhes
        echo "<h2><strong>Resumo dos pagamentos do dia " . date('d/m/Y', strtotime($datapag)) . "</strong></h2>";
        $total = 0;
        
        // Loop pelos resultados e exibe os detalhes
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<p>{$row['login']} - R$ {$row['valorpag']} - {$row['formapag']} - " . date('d/m/Y', strtotime($row['datavenc'])) . " - {$row['coletor']}</p>";
            $detalhes .= "{$row['login']} - R$ {$row['valorpag']} - {$row['formapag']} - " . date('d/m/Y', strtotime($row['datavenc'])) . " - {$row['coletor']}\n";
            $total += $row['valorpag'];
        }
        
        // Exibe o total dos pagamentos
        echo "<p><strong>----------------------------------------------------------------</strong></p>";
        echo "<p><strong>TOTAL R$ $total</strong></p>";
        
        // Formulário para enviar via WhatsApp
        echo "<form method='post' action='enviar_whatsapp.php'>";
        echo "<input type='hidden' name='resumo' value='Resumo dos pagamentos do dia " . date('d/m/Y', strtotime($datapag)) . "'>";
        echo "<input type='hidden' name='detalhes' value='$detalhes'>";
        echo "<button type='submit'>Enviar via WhatsApp</button>";
        echo "</form>";
    } else {
        echo "<p><strong>Nenhum pagamento encontrado para a data " . date('d/m/Y', strtotime($datapag)) . ".</strong></p>";
    }
}
?>