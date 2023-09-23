<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['email'])) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: login.php");
    exit;
}
$idDiaSelecionado = isset($_GET['id_dia']) ? $_GET['id_dia'] : null;

$logado = $_SESSION['email'];   

$sql = "SELECT * FROM usuarios WHERE email = '$logado'";
$result = $conexao->query($sql);
$row = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    $idDia = $_GET['id_dia'];
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    $preco_gasto = str_replace(',', '.', $_POST['preco_gasto']); // Converte vírgula para ponto
    $preco_cobrado = str_replace(',', '.', $_POST['preco_cobrado']); // Converte vírgula para ponto
    $fk_id_usuario = $row['id'];

    $sql_eventos = "SELECT * FROM eventos WHERE id_dia = '$idDia'";
    $result_eventos = $conexao->query($sql_eventos);

    if ($result_eventos->num_rows > 0) {
        $row_eventos = $result_eventos->fetch_assoc();
        $fk_id_dia = $row_eventos['id_dia'];

        $sql = "INSERT INTO produtos (nome, categoria, preco_gasto, preco_cobrado, fk_id_dia, fk_id_usuario)
                VALUES ('$nome', '$categoria', '$preco_gasto', '$preco_cobrado', '$fk_id_dia', '$fk_id_usuario')";

        if ($conexao->query($sql) === TRUE) {
            header("Location: ".$_SERVER['PHP_SELF']."?id_dia=".$idDia); 
            exit;
        } else {
            echo "Error: " . $conexao->error;
        }
    }
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
    <link rel="shortcut icon" type="image/x-icon" href="img/icon.ico">
    <script src="https://kit.fontawesome.com/4ab7ce30c3.js" crossorigin="anonymous"></script>
    <style>
        <?php 
            include 'style/cadastroprodutos.css';
        ?>
    </style>
    <script>
        function redirectToCaixa() {
            // pega o valor do ID do dia da tabela eventos
            var idDia = "<?php echo $idDiaSelecionado; ?>";
            // taca para a página caixa.php com o ID do dia no URL
            window.location.href = "caixa.php?id_dia=" + idDia;
        }
    </script>
</head>
<body>

    <nav class="navbar">
        <div class="container-fluid">
            <div class="navleft">
                <?php
                    echo "<a class='navbar-brand' href=''>Cadastro de Produtos</a>";
                ?>
            </div>
            <div class="navright">
                <a href="sair.php" class="btnSair">Sair</a>
            </div>
        </div>
    </nav>
    <img src="img/logoCatalogXsemTexto.svg">
    <div class="container-co">
            <div class="cadastro">
                <div class="title-cadastro">
                    <p><b>Insira os dados do produto:</b></p>
                </div>
                <div class="main-cadastro">
                    <form action="" method="POST" style="width: 100%;">
                    <div class="inputbox">
                    <p>Nome do produto:</p>
                    <input type="text" name="nome" class="inputProduto" required style="width: 100%; background-color: transparent; border: none;" placeholder="Exemplo: Refrigerante">
                </div>
                <a href="user.php" class="voltar">< Voltar</a>
                <br>
                <p for="comida">Categoria do produto:</p>
                <div class="checkbox-container">
                    <input type="checkbox" id="comida" name="categoria" value="comida" onclick="handleCheckbox(this)">
                    <label for="comida">Comida</label>
                    <input type="checkbox" id="bebida" name="categoria" value="bebida" onclick="handleCheckbox(this)">
                    <label for="bebida">Bebida</label>
                    <input type="checkbox" id="doce" name="categoria" value="doce" onclick="handleCheckbox(this)">
                    <label for="doce">Doce</label>
                    <input type="checkbox" id="outro" name="categoria" value="outro" onclick="handleCheckbox(this)">
                    <label for="outro">Outro</label>
                </div>
                <script>
                    function handleCheckbox(checkbox) {
                    var checkboxes = document.getElementsByName("categoria");
                    for (var i = 0; i < checkboxes.length; i++) {
                        if (checkboxes[i] !== checkbox) {
                        checkboxes[i].checked = false;
                        }
                        }
                    }
                </script>
                        <br>
                        <div class="inputbox-pgpc">
                            <p>Preço gasto na produção:</p>
                            <div class="inputbox-row">
                                <span>R$</span>
                                <input type="text" name="preco_gasto" class="inputCusto" required>
                            </div>
                        </div>
                        <br>
                        <div class="inputbox-pgpc">
                            <p>Preço cobrado no produto:</p>
                            <div class="inputbox-row">
                                <span>R$</span>
                                <input type="text" name="preco_cobrado" class="inputCobrado" required>
                            </div>
                        </div>
                        <br>
                        <div class="submitbox">
                            <input class="inputSubmit" type="submit" name="submit" value="Salvar" title="adicionar o produto">
                            <button class="inputSubmit" onclick="redirectToCaixa()" title="Ir para a página do caixa!"> Tudo Pronto!</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="overflow">
            <?php
            $idUsuarioLogado = $row['id'];
            $idDia = $_GET['id_dia'];
            
            $sql_select = "SELECT * FROM produtos WHERE fk_id_dia = '$idDia' AND fk_id_usuario = '$idUsuarioLogado'";
            $result_select = mysqli_query($conexao, $sql_select);
            
            if ($result_select) {
                while ($user_data = mysqli_fetch_assoc($result_select)) {
                    $categoria = $user_data['categoria'];
                    $iconClass = '';
                    if ($categoria == 'comida') {
                        $iconClass = 'fa-utensils';
                    } elseif ($categoria == 'bebida') {
                        $iconClass = 'fa-glass-martini-alt';
                    } elseif ($categoria == 'doce') {
                        $iconClass = 'fa-cookie-bite';
                    } elseif ($categoria == 'outro') {
                        $iconClass = 'fa-ticket';
                    }
            
                    echo "
                    <div class='item'>
                        <div class='item-categoria'>
                            <i class='categoria-icon fa-solid $iconClass fa-2x'></i>
                        </div>
                        <div class='item-nome'>
                            ".$user_data['nome']."
                        </div>
                        <div class='item-preco'>
                            ".formatPrice($user_data['preco_cobrado'])."
                        </div>
                        <div class='item-delete'>
                            <a class='btn-delete' href='excluirproduto.php?id_produto=".$user_data['id_produto']."' title='Deletar'>
                                <b>X</b>
                            </a>
                        </div>
                    </div>
                    ";
                }
            } else {
                echo "Erro na consulta SQL: " . mysqli_error($conexao);
            }
                ?>
    </div>
    </body>