<?php
session_start();
include_once('config.php');
if (!isset($_SESSION['email'])) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: login.php");
    exit;
}

$userEmail = $_SESSION['email'];
$sql_user_id = "SELECT id FROM usuarios WHERE email = '$userEmail'";
$result_user_id = mysqli_query($conexao, $sql_user_id);
$user_id_row = mysqli_fetch_assoc($result_user_id);
$idUsuarioLogado = $user_id_row['id'];

$idDia = isset($_GET['id_dia']) ? $_GET['id_dia'] : null;

$idDiaSelecionado = isset($_GET['id_dia']) ? $_GET['id_dia'] : null;
$logado = $_SESSION['email'];
// Busca os dados do usuário logado
$sql = "SELECT * FROM usuarios WHERE email = '$logado'";
$result = $conexao->query($sql);
$row = $result->fetch_assoc(); // Retorna apenas um resultado

// Verifica o nível de permissão do usuário
if ($row['permissao'] == 1) {
    // Consulta permitida apenas para usuários com permissão 1 (admin)
    $data = "";
    $result = $conexao->query($sql);
}

// Função para formatar o preço no padrão de moeda
function formatPrice($price) {
    return 'R$ ' . number_format($price, 2, ',', '.');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Catalogx</title>
    <script src="https://kit.fontawesome.com/4ab7ce30c3.js" crossorigin="anonymous"></script>
    <link rel="shortcut icon" type="image/x-icon" href="img/icon.ico">
    <style>
        <?php if ($row['permissao'] == 0) {
            include 'style/caixa.css';
        } ?>
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <a class='navbar-brand' href=''>SISTEMA CAIXA</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex">
            <a href="sair.php" class="btnSair">Sair</a>
        </div>
    </div>
</nav>

<section>
    <?php
    $idUsuarioLogado = $row['id'];
    $idDia = isset($_GET['id_dia']) ? $_GET['id_dia'] : null;

    $sql_select = "SELECT * FROM produtos WHERE fk_id_dia = '$idDia' AND fk_id_usuario = '$idUsuarioLogado'";
    $result_select = mysqli_query($conexao, $sql_select);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];
    
        // Verificar se o pedido já existe para este usuário e evento
        $sql_check_order = "SELECT id_pedido FROM pedidos WHERE fk_id_evento = '$idDia' AND fk_id_usuario = '$idUsuarioLogado' AND status = 'aberto'";
        $result_check_order = mysqli_query($conexao, $sql_check_order);
    
        if (mysqli_num_rows($result_check_order) == 0) {
            // O pedido não existe, então crie um novo pedido
            $sql_insert_order = "INSERT INTO pedidos (status, tipo_de_pagamento, fk_id_evento, fk_id_usuario) VALUES ('aberto', 'tipo_pagamento', '$idDia', '$idUsuarioLogado')";
            mysqli_query($conexao, $sql_insert_order);
            $idPedido = mysqli_insert_id($conexao); // Obtém o ID do novo pedido
        } else {
            // O pedido já existe, obtenha seu ID
            $order_row = mysqli_fetch_assoc($result_check_order);
            $idPedido = $order_row['id_pedido'];
        }
    
        // Agora, associe o produto ao pedido na tabela 'pedidos_produtos'
        $sql_insert_product = "INSERT INTO pedidos_produtos (id_pedido, id_produto) VALUES ('$idPedido', (SELECT id_produto FROM produtos WHERE nome = '$productName' AND fk_id_dia = '$idDia' AND fk_id_usuario = '$idUsuarioLogado' LIMIT 1))";
        if (mysqli_query($conexao, $sql_insert_product)) {
            // Produto associado ao pedido com sucesso
            echo "";
        } else {
            echo "Erro ao associar o produto ao pedido: " . mysqli_error($conexao);
        }
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cancel_order'])) {
        // Cancelar o pedido
        $sql_cancel_order = "DELETE FROM pedidos WHERE fk_id_evento = '$idDia' AND fk_id_usuario = '$idUsuarioLogado' AND status = 'aberto'";
        if (mysqli_query($conexao, $sql_cancel_order)) {
            echo "";
        } else {
            echo "Erro ao cancelar o pedido: " . mysqli_error($conexao);
        }
    }
    

    if ($result_select) {
        echo '
        <div class="carrinho col-lg-3 col-md-12">
            <img src="img/logoCatalogXTextoEmbaixo.png" style="height:5vw; margin-top: 1.5vh;">
            <div class="items">
            <div class="items-c-header">
            <span class="items-c-header-left">Pedido: #00001</span>
            <form method="post">
                <button type="submit" name="cancel_order" class="items-c-header-right">Cancelar Pedido</button>
            </form>
        </div>
                <div class="items-c-main">
                    <iframe src="carrinho.php" frameborder="0" width="100%" height="100%"></iframe>
                </div>
            </div>
            <div class="resultados">
                <div class="ct-total">
                    <div class="ct-left" style="font-size: 1.7rem;"><b>Total:</b></div>
                    <div class="ct-right" style="font-size:1.7rem; color:#ff8f1c;"><b>R$00,00</b></div>
                </div>
                <div class="ct-pago">
                    <div class="ct-left" style="font-size:1.2rem;">Pago:</div>
                    <div class="ct-right">R$<input type="text" style="background-color:transparent;border:none;border-bottom:solid;width:80px;border-color:white;color:white;"></div>
                </div>
                <div class="ct-troco">
                    <div class="ct-left" style="font-size:1.2rem;">Troco</div>
                    <div class="ct-right" style="font-size:1.2rem;">R$00,00</div>
                </div>
                <div class="ct-button">
                    <div class="btn-confirm" onclick="openPopup()"><b>Confirmar</b></div>
                </div>
            </div>
        </div>
        <div class="main col-lg-9 col-md-12">
            <div class="container-items">';

        while ($user_data = mysqli_fetch_assoc($result_select)) {
            $categoria = $user_data['categoria'];
            $iconClass = '';
            $colorBox = '';

            if ($categoria == 'comida') {
                $iconClass = 'fa-utensils';
                $colorbox = '#ff8f1c';
            } elseif ($categoria == 'bebida') {
                $iconClass = 'fa-glass-martini-alt';
                $colorbox = '#00A3FF';
            } elseif ($categoria == 'doce') {
                $iconClass = 'fa-cookie-bite';
                $colorbox = '#B94892';
            } elseif ($categoria == 'outro') {
                $iconClass = 'fa-ticket';
                $colorbox = '#4AB948';
            }

            echo '
            <div class="itemcx col-lg-2 col-md-6 col-sm-12 mx-4 mb-3"   >
                <div class="item-categoriacx">
                <form method="post" action="">
                <input type="hidden" name="product_name" value="' . $user_data['nome'] . '">
                <input type="hidden" name="product_price" value="' . $user_data['preco_cobrado'] . '">
                <button type="submit" name="add_to_cart" class="btn-btn-primary">+</button>
            </form>
                    <i class="categoria-icon fa-solid ' . $iconClass . ' fa-2x"></i>
                </div>';

            if (isset($user_data['nome'])) {
                echo '<div class="item-nomecx">' . $user_data['nome'] . '</div>';
            }

            echo '
                <div class="item-corcx">
                
                    <div class="color-box" style="background-color: ' . $colorbox . ';"></div>
                </div>';

            if (isset($user_data['preco_cobrado'])) {
                echo '<div class="item-precocx"><b>' . formatPrice($user_data['preco_cobrado']) . '</b></div>';
            }

            echo '
                <div>

                </div>
            </div>';
        }

        echo '
            </div>
            <a href="dashboard.php" target="_blank">
                <div class="btn-dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="dash_vector" width="22" height="22" fill="white" class="bi bi-bar-chart-line-fill" viewBox="0 0 16 16">
                        <path d="M11 2a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12h.5a.5.5 0 0 1 0 1H.5a.5.5 0 0 1 0-1H1v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h1V7a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7h1V2z"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</section>';} ?>
<div id="popup" class="popup-pagamento">
    <div class="header-popup">
        Qual é o tipo de pagamento?
        <div class="close-popup" onclick="closePopup()">
            <i class="fa-solid fa-x"></i>
        </div>
    </div>
    <div class="main-popup">
    <div class="tipo-pag" id="tipo-pix" data-tipo="pix">
    <i class="fa-brands fa-pix fa-2x"></i>
</div>
<div class="tipo-pag" id="tipo-cartao" data-tipo="cartao">
    <i class="fa-solid fa-credit-card fa-2x"></i>
</div>
<div class="tipo-pag" id="tipo-dinheiro" data-tipo="dinheiro">
    <i class="fa-solid fa-money-bills fa-2x"></i>
</div>

    </div>
</div>

<script>
    function openPopup() {
        document.getElementById("popup").style.display = "flex";
    }
    
    function closePopup() {
        document.getElementById("popup").style.display = "none";
    }


    document.querySelectorAll('.tipo-pag').forEach(function(element) {
        element.addEventListener('click', function() {
            var tipoPagamento = element.getAttribute('data-tipo');
            updateTipoPagamento(tipoPagamento);
            location.reload();
            closePopup();
        });
    });

    function updateTipoPagamento(tipoPagamento) {
        var idDia = "<?php echo $idDiaSelecionado; ?>"; 
        var idUsuarioLogado = "<?php echo $idUsuarioLogado; ?>"; 


        var xhr = new XMLHttpRequest();
        xhr.open("POST", "atualizar_tipo_pagamento.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                console.log("Tipo de pagamento atualizado: " + tipoPagamento);
            }
        };
        xhr.send("id_dia=" + idDia + "&id_usuario=" + idUsuarioLogado + "&tipo_pagamento=" + tipoPagamento);
    }
</script>

</body>
</html>