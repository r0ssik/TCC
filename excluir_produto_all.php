<?php

    if(!empty($_GET['id_pedido']))
    {
        include_once('config.php');

        $id = $_GET['id_pedido'];

        $sqlSelect = "SELECT *  FROM pedidos_produtos WHERE id_pedido=$id";

        $result = $conexao->query($sqlSelect);

        if($result->num_rows > 0)
        {
            $sqlDelete = "DELETE FROM pedidos_produtos WHERE id_pedido=$id";
            $resultDelete = $conexao->query($sqlDelete);
        }
    }
    header('Location: carrinho.php');
   
?>