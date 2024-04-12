<?php
// INCLUE FUNCOES DE ADDONS -----------------------------------------------------------------------
include('addons.class.php');
?>
<!DOCTYPE html>
<html lang="pt-BR" class="has-navbar-fixed-top">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta charset="iso-8859-1">
<title>MK - AUTH :: Pagamentos</title>

<link href="../../estilos/mk-auth.css" rel="stylesheet" type="text/css" />
<link href="../../estilos/font-awesome.css" rel="stylesheet" type="text/css" />

<script src="../../scripts/jquery.js"></script>
<script src="../../scripts/mk-auth.js"></script>

</head>
<body>

<?php include('../../topo.php'); ?>

<!-- Formulário para entrada da data -->
<form name="pagamentos" method="post">
  <input type="date" name="datapag" id="datapag" required>
  <button type="submit">Consultar pagamentos</button>
</form>

<?php
if(isset($_POST["datapag"])){
  $datapag = $_POST["datapag"];
  
  // Conexão com o banco de dados do Mk-Auth
  $host = "localhost";
  $usuario = "root";
  $senha = "vertrigo";
  $db = "mkradius";
  $mysqli = new mysqli($host, $usuario, $senha, $db);
  if($mysqli->connect_errno) {
    echo "Falha na conexao: (".$mysqli->connect_errno.") ".$mysqli->connect_error;
  } else {
    $con = mysqli_connect("$host","$usuario","$senha");
    mysqli_select_db($con,"$db");
  
    // Consulta SQL para obter os pagamentos na data especificada
    $query = "SELECT datavenc, valorpag, coletor, formapag, login FROM sis_lanc WHERE datapag = '$datapag'";
    $result = mysqli_query($con, $query);
    
    // Se houver resultados, exiba o resumo
    if(mysqli_num_rows($result) > 0) {
      echo "<h2><strong>Resumo dos pagamentos do dia " . date('d/m/Y', strtotime($datapag)) . "</strong></h2>";
      $total = 0;
      while($row = mysqli_fetch_assoc($result)) {
        // Formatação da data de pagamento
        $datapag_formatted = date('d/m/Y', strtotime($row['datapag']));
        $datavenc_formatted = date('d/m/Y', strtotime($row['datavenc']));
        
        // Exiba os detalhes de cada pagamento
        echo "<p>{$row['login']} - R$ {$row['valorpag']} - {$row['formapag']} - $datavenc_formatted - {$row['coletor']}</p>";
        $total += $row['valorpag'];
      }
      // Exiba o total dos pagamentos em negrito
      echo "<p>----------------------------------------------------------------</p>";
      echo "<p><strong>TOTAL - R$ $total</strong></p>";
    } else {
      echo "<p>Nenhum pagamento encontrado para a data $datapag.</p>";
    }
  }
}
?>

<?php include('../../baixo.php'); ?>

<script src="../../menu.js.hhvm"></script>

</body>
</html>
