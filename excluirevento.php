<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['email'])) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: login.php");
    exit;
}

$idDia = isset($_GET['id_dia']) ? $_GET['id_dia'] : null;

if (!is_null($idDia)) {
    $sql_delete = "DELETE FROM eventos WHERE id_dia = '$idDia'";
    if ($conexao->query($sql_delete) === TRUE) {
        header("Location: ".$_SERVER['HTTP_REFERER']); // Redireciona para a página anterior
        exit;
    } else {
        echo "Erro ao excluir produto: " . $conexao->error;
    }
}
?>
