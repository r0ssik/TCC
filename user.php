<?php
    session_start();
    include_once('config.php');
    if (!isset($_SESSION['email'])) {
        header('HTTP/1.0 403 Forbidden');
        header("Location: login.php");
        exit;
    }

    $logado = $_SESSION['email'];
    // Busca os dados do usuÃ¡rio logado
    $sql = "SELECT * FROM usuarios WHERE email = '$logado'";
    $result = $conexao->query($sql);
    $row = $result->fetch_assoc(); // Retorna apenas um resultado

    // Verifica o nÃ­vel de permissÃ£o do usuÃ¡rio
    if ($row['permissao'] == 1) {
        // Consulta permitida apenas para usuÃ¡rios com permissÃ£o 1 (admin)
        $data = "";
        $result = $conexao->query($sql);
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
            include 'style/user.css';  
                
        ?>
        input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus,
textarea:-webkit-autofill,
textarea:-webkit-autofill:hover
textarea:-webkit-autofill:focus,
select:-webkit-autofill,
select:-webkit-autofill:hover,
select:-webkit-autofill:focus {
  border: px solid white;
  -webkit-text-fill-color: #909090 !important;
  -webkit-box-shadow: 0 0 0px 0px #000 inset;
  transition: background-color 5000s ease-in-out 0s;
}
        </style>
    </head>
    <body>
        <nav class="navbar">
            <div class="container-fluid">
                <div class="navleft">
                    <?php
                            echo "<a class='navbar-brand' href=''> Seus Eventos</a>";

                    ?>
                </div>
                <div class="navright">
                    </button>
                    <button class="add" onclick="openPopup()">+</button>
                    <div class="circle-image">
                    <?php if (!empty($row['image'])) : ?>
                        <img src="<?php echo $row['image']; ?>" class="user-img">
                    <?php else : ?>
                        <i class="fa-solid fa-user fa-1x"></i>
                    <?php endif; ?>
                    </div>
                    <a href="sair.php" class="btnSair">Sair</a>
                    
                </div>
            </div>
            </div>
        </nav>
    <br>

    <?php 
if (isset($_POST['sub'])) {
    if ($row && isset($row['id'])) {
        $idUsuarioLogado = $row['id'];
        $nome = $_POST['nome'];
        $data = $_POST['data'];
        $descricao = $_POST['descricao'];

        $sql = "SELECT * FROM eventos WHERE nome = '$nome'";
        $result = mysqli_query($conexao, $sql);

        if (mysqli_num_rows($result) > 0) {
        } else {
            $sql_insert = "INSERT INTO eventos (nome, data, descricao, fk_id_usuario) VALUES ('$nome', '$data', '$descricao', '$idUsuarioLogado')";
            mysqli_query($conexao, $sql_insert);
        }
    }
}

$idUsuarioLogado = $row['id']; // ObtÃ©m o ID do usuÃ¡rio logado
$sql_select = "SELECT * FROM eventos WHERE fk_id_usuario = '$idUsuarioLogado'";
$result_select = mysqli_query($conexao, $sql_select);
?>
    <style>
        .popup-overlay {
            display: none;
        }
    </style>
<div class="container-event">
    <div class="events-header">
        <div class="events-header-left">
            <i class="fa-solid fa-list fa-1x"></i> Ordenar por...
        </div>
        <div class="events-header-right">
        <?php
        echo "<span><span class='txtlogado' id='mensagem'></span><br></span>";
        echo "<script>";
        echo "var now = new Date();";
        echo "var hora = now.getHours();";
        echo "var mensagemElement = document.getElementById('mensagem');";
        echo "if (hora >= 5 && hora < 12) {";
        echo "    mensagemElement.textContent = 'Bom dia, " . $row['nome'] . "!';";
        echo "} else if (hora >= 12 && hora < 18) {";
        echo "    mensagemElement.textContent = 'Boa tarde, " . $row['nome'] . "!';";
        echo "} else {";
        echo "    mensagemElement.textContent = 'Boa noite, " . $row['nome'] . "!';";
        echo "}";
        echo "</script>";
    ?>
        </div>
    </div>
<?php

    function randomOrangeColor() {
        $hue = rand(15, 35); // Define o tom de laranja
        $saturation = rand(80, 100); // Define a saturação (mais vibrante)
        $lightness = rand(50, 70); // Define a luminosidade
        return "hsl($hue, $saturation%, $lightness%)";
    }

    function verificaProduto($idDia, $conexao) {
        // Verifica se há pelo menos um produto cadastrado no evento
        $sql = "SELECT COUNT(*) as count FROM produtos WHERE fk_id_dia = '$idDia'";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $count = $row['count'];
    
        // Define a URL com base na condição
        if ($count > 0) {
            return "sistema.php";
        } else {
            return "cadastroprodutos.php";
        }
    }

    while ($user_data = mysqli_fetch_assoc($result_select)) {
        $originalDate = $user_data["data"];
        $newDate = date("d/m/Y", strtotime($originalDate));
        echo "<div class='content-event' onclick=\"window.location.href='cadastroprodutos.php?id_dia=".$user_data['id_dia']."';\" style='cursor: pointer;'>";
        echo "<span style='border-radius: 10px 10px 0 0;  background-color: ". randomOrangeColor() ."'><div class='section'><b>".$user_data['nome']."</b></div>";
        echo "<div class='section2'><svg class='date-svg' width='17' height='20' fill='none' xmlns='http://www.w3.org/2000/svg'><path d='M5.667 1.417v2.125m5.666-2.125v2.125M2.48 6.439h12.042m.354-.418v6.02c0 2.126-1.063 3.542-3.542 3.542H5.667c-2.48 0-3.542-1.416-3.542-3.541V6.02c0-2.125 1.063-3.542 3.542-3.542h5.666c2.48 0 3.542 1.417 3.542 3.542Z' stroke='#fff' stroke-width='1.4' stroke-miterlimit='10' stroke-linecap='round' stroke-linejoin='round'/><path d='M11.117 9.704h.007m-.007 2.125h.007M8.497 9.704h.007m-.007 2.125h.007M5.875 9.704h.007m-.007 2.125h.007' stroke='#fff' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>".$newDate."</div></span>";
        echo "<div class='section3'><p class='descricao'>".$user_data['descricao']."</p></div>";
        echo "<div class='section4'> <a class='btnEdit' title='Editar'  onclick=\"window.location.href='cadastroprodutos.php?id_dia=".$user_data['id_dia']."';\" style='cursor: pointer;'>
        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' class='bi bi-pencil-square' viewBox='0 0 16 16'>
          <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
          <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
        </svg>
      </a>
      <a class='btnDelete' href='excluirevento.php?id_dia=".$user_data['id_dia']."' title='Deletar'>
                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                    <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                </svg>
            </a> </div>";
        echo "</div>";
    }
    ?>
</div>
    <div id="popup" class="popup-overlay">
        <div class="popup-box">
                    <form method="POST" enctype="multipart/form-data">
                        <table>
                            <tr>
                                <h5 class="title-formitens">Nome do evento:</h5>
                                <div class="input-formitens-div">
                                    <input type="text" name="nome" maxlength="28" required class="input-formitens" placeholder="Exemplo: festa da Padroeira">
                                </div>
                            </tr>
                            <tr>
                                <br>
                            </tr>
                            <tr>
                                <h5 class="title-formitens">Data do evento</h5>
                                <div class="input-formitens-div">
                                    <input class="input-formitens-date" type="date" name="data" required>
                                </div>
                            </tr>
                            <tr>
                                <br>
                            </tr>
                            <tr>
                                <h5 class="title-formitens">Descrição do evento</h5>
                                <div class="input-formitens-div">
                                    <input class="input-formitens" type="text" name="descricao" maxlength="190" placeholder="(opcional)">
                                </div>
                            </tr>
                            <tr>
                                <br>
                            </tr>
                            <tr>
                            <button name="sub" class="submit">Próxima</button>
                            </tr>
                        </table>
                    </form>
                    <span class="popup-close" onclick="closePopup()"><i class="fa-solid fa-x"></i></span>
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
    </script>
 


    </body>

    </html>