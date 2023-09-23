<?php
if (!empty($_GET['id_pedido'])) {
    include_once('config.php');

    $id = $_GET['id_pedido'];

    $sqlSelect = "SELECT * FROM pedidos WHERE id_pedido = $id";
    $resultSelect = $conexao->query($sqlSelect);

    if ($resultSelect->num_rows > 0) {
        $row = $resultSelect->fetch_assoc();
        $quantidade = $row['quantidade'];

        // Se a quantidade for maior que 1, atualiza a quantidade -1
        if ($quantidade > 1) {
            $newQuantidade = $quantidade - 1;
            $sqlUpdate = "UPDATE pedidos SET quantidade = $newQuantidade WHERE id_pedido = $id";
            $resultUpdate = $conexao->query($sqlUpdate);
        } else {
            // Se a quantidade for 1, exclui o item
            $sqlDelete = "DELETE FROM pedidos WHERE id_pedido = $id";
            $resultDelete = $conexao->query($sqlDelete);
        }
    }
}

header('Location: carrinho.php');
?>
