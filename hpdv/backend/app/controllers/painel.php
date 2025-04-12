<?php
include "/../database/db_connection.php";

$sqlQuantidade = "SELECT COUNT(*) AS QUANTIDADE FROM `tb_clientes`";
$consultaQuantidade = mysqli_query($conexao, $sqlQuantidade);
$dadosQuantidade = mysqli_fetch_array($consultaQuantidade);
$quantidadeCliente = $dadosQuantidade['QUANTIDADE'];

$sqlTotal = "SELECT SUM( `subtotal`) AS VALOR FROM `tb_vendas`";
$consultaTotal = mysqli_query($conexao,$sqlTotal);
$dadosTotal = mysqli_fetch_array($consultaTotal);

$totalVenda =  number_format($dadosTotal['VALOR'],2,'.','');


$sqlQuantidadeVenda = "SELECT COUNT(*) AS QUANTIDADEVENDA FROM `tb_vendas`";
$consultaQuantidadeVenda = mysqli_query($conexao,$sqlQuantidadeVenda);
$dadosQuantidadeVenda = mysqli_fetch_array($consultaQuantidadeVenda);
$quantidadeVenda = $dadosQuantidadeVenda['QUANTIDADEVENDA'];

?>