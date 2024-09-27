<?php
// INCLUI FUNÇÕES DE ADDONS -----------------------------------------------------------------------
// Este script inclui o arquivo 'addons.class.php', que contém funções ou classes auxiliares.
include('addons.class.php');
?>
<!DOCTYPE html>
<html lang="pt-BR" class="has-navbar-fixed-top"> <!-- Define o idioma como português BR -->
<head>
    <!-- Define visualização para dispositivos móveis -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Define o charset como ISO-8859-1 -->
    <meta charset="iso-8859-1">
    <!-- Define o título da página, usando o nome do addon no Manifest -->
    <title>MK - AUTH :: <?php echo $Manifest->{'name'}; ?></title> 

    <!-- Inclui arquivos de estilo -->
    <link href="../../estilos/mk-auth.css" rel="stylesheet" type="text/css" /> 
    <link href="../../estilos/font-awesome.css" rel="stylesheet" type="text/css" /> 

    <!-- Inclui bibliotecas JavaScript -->
    <script src="../../scripts/jquery.js"></script> 
    <script src="../../scripts/mk-auth.js"></script> 
</head>
<body>
    <!-- Inclui o cabeçalho e o conteúdo principal -->
    <?php include('../../topo.php'); ?> <!-- Inclui o cabeçalho -->
    <?php include('resumo_pagamento.php'); ?> <!-- Inclui o arquivo principal de resumo -->
    <?php include('../../baixo.php'); ?> <!-- Inclui o rodapé -->

    <!-- Inclui scripts relacionados ao menu -->
    <script src="../../menu.js.hhvm"></script> 
</body>
</html>