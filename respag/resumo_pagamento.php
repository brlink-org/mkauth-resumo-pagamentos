<?php
// ------------------------------------------------------------------------------------------------
// Configurações principais
// ------------------------------------------------------------------------------------------------
$host = "localhost"; // Host do banco de dados MySQL
$usuario = "root"; // Usuário do banco de dados MySQL
$senha = "vertrigo"; // Senha do banco de dados MySQL
$db = "mkradius"; // Nome do banco de dados MySQL

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
    // Escapa a data para evitar problemas de injeção e garantir o formato correto
    $datapag = mysqli_real_escape_string($mysqli, $_POST["datapag"]);

    // Adiciona o operador LIKE para pegar qualquer registro com a data especificada, ignorando o horário
    $query = "SELECT datavenc, valorpag, coletor, formapag, login FROM sis_lanc WHERE datapag LIKE '$datapag%' ORDER BY formapag"; 
    $result = mysqli_query($mysqli, $query);

    if (!$result) {
        echo "Erro na consulta: " . mysqli_error($mysqli); // Exibe o erro da consulta se ocorrer
        exit();
    }

    if (mysqli_num_rows($result) > 0) {
        // Monta o cabeçalho do resumo
        $mensagem = "==================================\n";
        $mensagem .= "*Resumo dos pagamentos do dia " . date('d/m/Y', strtotime($datapag)) . "*\n";
        $mensagem .= "==================================\n\n";

        // Exibe o cabeçalho no HTML com negrito
        echo "<p><strong>==================================</strong></p>";
        echo "<h2><strong>Resumo dos pagamentos do dia " . date('d/m/Y', strtotime($datapag)) . "</strong></h2>";
        echo "<p><strong>==================================</strong></p>";

        // Inicializa variáveis para agrupamento e totalização
        $formas_pagamento = [];
        $totais = [];

        // Loop pelos resultados e organiza por forma de pagamento
        while ($row = mysqli_fetch_assoc($result)) {
            // Trocar "status.paid" por "bolemix"
            if ($row['formapag'] == "status.paid") {
                $row['formapag'] = "bolemix";
            }

            // Agrupa os pagamentos por forma de pagamento
            $formas_pagamento[$row['formapag']][] = $row;

            // Calcula os totais por forma de pagamento
            if (!isset($totais[$row['formapag']])) {
                $totais[$row['formapag']] = 0;
            }
            $totais[$row['formapag']] += $row['valorpag'];
        }

        // Exibe os detalhes agrupados por forma de pagamento
        foreach ($formas_pagamento as $forma => $pagamentos) {
            // Adiciona o subtotal e os pagamentos
            $subtotal = number_format($totais[$forma], 2, ',', '.');
            $mensagem .= strtoupper($forma) . ": Subtotal R$ $subtotal\n\n";

            // Exibe no HTML
            echo "<h3><strong>" . strtoupper($forma) . ": Subtotal R$ $subtotal</strong></h3>";

            // Detalhes dos pagamentos
            foreach ($pagamentos as $row) {
                $mensagem .= "{$row['login']} - R$ " . number_format($row['valorpag'], 2, ',', '.') . " - " . date('d/m/Y', strtotime($row['datavenc'])) . " - {$row['coletor']}\n";
                
                // Exibe no HTML
                echo "<p>{$row['login']} - R$ " . number_format($row['valorpag'], 2, ',', '.') . " - " . date('d/m/Y', strtotime($row['datavenc'])) . " - {$row['coletor']}</p>";
            }

            $mensagem .= "==================================\n\n";
            echo "<p><strong>==================================</strong></p>"; // Exibição no HTML
        }

        // Adiciona o total geral ao final
        $total_geral = number_format(array_sum($totais), 2, ',', '.');
        $mensagem .= "*TOTAL GERAL R$ $total_geral*\n";
        $mensagem .= "==================================\n";

        // Exibe no HTML
        echo "<h3><strong>TOTAL GERAL R$ $total_geral</strong></h3>";
        echo "<p><strong>==================================</strong></p>";

        // Envia o formulário com a mensagem
        echo "<form method='post' action='enviar_whatsapp.php'>";
        echo "<input type='hidden' name='resumo' value='" . htmlentities($mensagem, ENT_QUOTES, 'UTF-8') . "'>";
        echo "<button type='submit'>Enviar via WhatsApp</button>";
        echo "</form>";

    } else {
        echo "<p><strong>Nenhum pagamento encontrado para a data " . date('d/m/Y', strtotime($datapag)) . ".</strong></p>";
    }
}
?>