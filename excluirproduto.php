<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['email'])) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: login.php");
    exit;
}

$idProduto = isset($_GET['id_produto']) ? $_GET['id_produto'] : null;

if (!is_null($idProduto)) {
    $sql_delete = "DELETE FROM produtos WHERE id_produto = '$idProduto'";
    if ($conexao->query($sql_delete) === TRUE) {
        header("Location: ".$_SERVER['HTTP_REFERER']); // Redireciona para a página anterior
        exit;
    } else {
        echo "Erro ao excluir produto: " . $conexao->error;
    }
}
?>
