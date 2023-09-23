<?php
session_start();
include_once('config.php');

if (isset($_GET['increase_quantity']) && isset($_GET['id_pedido'])) {
    $id_pedido = $_GET['id_pedido'];

    $update_sql = "UPDATE pedidos SET quantidade = quantidade + 1 WHERE id_pedido = $id_pedido";
    $conexao->query($update_sql);
    header("Location: carrinho.php");
    exit();
}

function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        <?php
        $userEmail = $_SESSION['email'];
        $sql_user_info = "SELECT permissao FROM usuarios WHERE email = '$userEmail'";
        $result_user_info = mysqli_query($conexao, $sql_user_info);
        $user_info_row = mysqli_fetch_assoc($result_user_info);
        if ($user_info_row['permissao'] == 0) {
            include 'style/carrinho.css';
        }
        ?>
    </style>
    <script src="https://kit.fontawesome.com/4ab7ce30c3.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="item-c-qnt">
    <table>
        <?php
        $userEmail = $_SESSION['email'];
        $sql_user_id = "SELECT id FROM usuarios WHERE email = '$userEmail'";
        $result_user_id = mysqli_query($conexao, $sql_user_id);
        $user_id_row = mysqli_fetch_assoc($result_user_id);
        $idUsuarioLogado = $user_id_row['id'];

        $sql_pedido_aberto = "SELECT id_pedido FROM pedidos WHERE fk_id_usuario = '$idUsuarioLogado' AND status = 'aberto'";
        $result_pedido_aberto = mysqli_query($conexao, $sql_pedido_aberto);
        $sql_eventos_usuario = "SELECT id_dia FROM eventos WHERE fk_id_usuario = '$idUsuarioLogado'";
        $result_eventos_usuario = mysqli_query($conexao, $sql_eventos_usuario);
    
$result_pedido_aberto = mysqli_query($conexao, $sql_pedido_aberto);
        if ($result_pedido_aberto) {
            while ($row_pedido_aberto = mysqli_fetch_assoc($result_pedido_aberto)) {
                $id_pedido_aberto = $row_pedido_aberto['id_pedido'];
        

                $sql_evento_pedido = "SELECT fk_id_evento FROM pedidos WHERE id_pedido = '$id_pedido_aberto'";
                $result_evento_pedido = mysqli_query($conexao, $sql_evento_pedido);
                $evento_pedido_row = mysqli_fetch_assoc($result_evento_pedido);
                $id_evento_pedido = $evento_pedido_row['fk_id_evento'];
        
                $sql_evento_usuario = "SELECT id_dia FROM eventos WHERE fk_id_usuario = '$idUsuarioLogado' AND id_dia = '$id_evento_pedido'";
                $result_evento_usuario = mysqli_query($conexao, $sql_evento_usuario);

                
        
                if (mysqli_num_rows($result_evento_usuario) > 0) {
                    $sql = "SELECT pr.id_produto, pr.nome AS nome_produto, pr.preco_cobrado AS preco_produto, COUNT(pp.id_produto) AS quantidade FROM pedidos_produtos AS pp
                            JOIN produtos AS pr ON pp.id_produto = pr.id_produto
                            WHERE pp.id_pedido = '$id_pedido_aberto' 
                            GROUP BY pp.id_produto";
        
                    $result = $conexao->query($sql);
        
                } dsaddsa
              
                if($result->num_rows > 0){
                    $sql2 = "SELECT e.id_dia, pr.id_produto, pr.nome AS nome_produto, pr.preco_cobrado AS preco_produto, COUNT(pp.id_produto) AS quantidade FROM pedidos_produtos AS pp
                    JOIN produtos AS pr ON pp.id_produto = pr.id_produto
                    LEFT join eventos as e 
                    join pedidos as ped
                    WHERE pp.id_pedido = '$id_pedido_aberto' and e.id_dia = $sql_evento_pedido
                    GROUP BY pp.id_produto";

                     $result2 = $conexao->query($sql2);
                }
                if($result->num_rows > 0){
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='item-crr'>";
                        echo "<div class='quantidade'>" . $row['quantidade'] . "X</div>";
                        echo "<div class='titleCart'>" . $row["nome_produto"] . "</div>";
                        echo "<div class='preco'>" . formatPrice($row["preco_produto"]) . "</div>";
                        echo "<div class='acoes'>
                                <a class='btn-delete' href='excluir_produto2.php?id_pedido=" . $id_pedido_aberto . "&id_produto=" . $row['id_produto'] . "' title='Deletar'>
                                    <b>x</b>
                                </a>
                            </div>";
                        echo "</div>";
                    }
                } else {
                    // Se não houver produtos, exclua o pedido
                    $delete_pedido_sql = "DELETE FROM pedidos WHERE id_pedido = '$id_pedido_aberto'";
                    $conexao->query($delete_pedido_sql);
                }
                }
            }
        else {
            echo "Erro ao obter os pedidos abertos.";
        }

        $conexao->close();
        ?>
    </table>
</div>
</body>
</html>
