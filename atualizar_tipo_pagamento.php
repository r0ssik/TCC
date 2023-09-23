<?php
include_once('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDia = $_POST['id_dia'];
    $idUsuario = $_POST['id_usuario'];
    $tipoPagamento = $_POST['tipo_pagamento'];

    $sql_update_tipo_pagamento = "UPDATE pedidos SET tipo_de_pagamento = '$tipoPagamento', status = 'fechado' WHERE fk_id_evento = '$idDia' AND fk_id_usuario = '$idUsuario' AND status = 'aberto'";
    if (mysqli_query($conexao, $sql_update_tipo_pagamento)) {
        echo "Tipo de pagamento e status do pedido atualizados com sucesso";
        echo "<script>window.location.reload();</script>"; 
    } else {
        echo "Erro ao atualizar o tipo de pagamento e status do pedido: " . mysqli_error($conexao);
    }
}

if (mysqli_query($conexao, $sql_update_tipo_pagamento)) {
  echo "Tipo de pagamento e status do pedido atualizados com sucesso";

  if ($tipoPagamento == "fechado") {
      $sql_create_new_order = "INSERT INTO pedidos (status, tipo_de_pagamento, fk_id_evento, fk_id_usuario) VALUES ('aberto', 'tipo_pagamento', '$idDia', '$idUsuarioLogado')";
      if (mysqli_query($conexao, $sql_create_new_order)) {
          echo "Novo pedido criado com sucesso com ID: " . mysqli_insert_id($conexao);
          echo '<script>window.location.reload();</script>'; 
      } else {
          echo "Erro ao criar novo pedido: " . mysqli_error($conexao);
      }
  }
} else {
  echo "Erro ao atualizar o tipo de pagamento e status do pedido: " . mysqli_error($conexao);
}
// ...



?>
