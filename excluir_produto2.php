<?php
session_start();
include_once('config.php');

if (isset($_GET['id_pedido']) && isset($_GET['id_produto'])) {
    $id_pedido = $_GET['id_pedido'];
    $id_produto = $_GET['id_produto'];

    // Exclua o produto do carrinho (tabela pedidos_produtos)
    $delete_produto_sql = "DELETE FROM pedidos_produtos WHERE id_pedido = $id_pedido AND id_produto = $id_produto";
    $conexao->query($delete_produto_sql);

    // Redirecione de volta para o carrinho
    header("Location: carrinho.php");
    exit();
} else {
    echo "Parâmetros inválidos.";
}
?>
