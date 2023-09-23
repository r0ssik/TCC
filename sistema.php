<?php
session_start();
include_once('config.php');
if(!isset($_SESSION['email'])){
    header('HTTP/1.0 403 Forbidden');
    header("Location: login.php");
    exit;
}



$logado = $_SESSION['email'];
// Busca os dados do usuário logado
$sql = "SELECT * FROM usuarios WHERE email = '$logado'";
$result = $conexao->query($sql);
$row = $result->fetch_assoc(); // Retorna apenas um resultado
// Verifica o nível de permissão do usuário
if ($row['permissao'] == 1) {
    // Consulta permitida apenas para usuários com permissão 1 (admin)
    $data = "";
    if (!empty($_GET['search'])) {
        $data = $_GET['search'];
        $sql = "SELECT * FROM usuarios WHERE id LIKE '%$data%' OR email LIKE '%$data%' ORDER BY id DESC";
    } else {
        $sql = "SELECT * FROM usuarios ORDER BY id DESC";
    }
     }
    $result = $conexao->query($sql);
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
        <?php if ($row['permissao'] == 1) {
            include 'style/sistemadmin.css';  }
             ?>
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container-fluid">
            <?php
                if ($row['permissao'] == 1) {
                    echo "<a class='navbar-brand' href=''>SISTEMA ADMIM</a>";
                }

            ?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="d-flex">
            <a href="reg.php" class="btnInserir">Inserir Novo usuário</a>
            <a href="sair.php" class="btnSair">Sair</a>
        </div>
        </div>
    </nav>
    <?php
    if ($row['permissao'] == 1) {
        echo "<br><h1>Bem vindo <u>$logado</u></h1><br> ";
    }
    ?>
    
    <?php
if ($row['permissao'] == 1) {
    echo '
        <div class="box-search">
            <input type="search" class="barrapesquisar" placeholder="Pesquisar" id="pesquisar">
            <button onclick="searchData()" class="btnSearch">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" class="bi bi-search" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </button>
        </div>
        <script>
            var search = document.getElementById(\'pesquisar\');

            search.addEventListener("keydown", function(event) {
                if (event.key === "Enter") 
                {
                    searchData();
                }
            });

            function searchData()
            {
                window.location = \'sistema.php?search=\'+search.value;
            }
        </script>
    ';

    echo '
    <div class="m-5">
        <table class="table text-white table-bg">
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Senha</th>
                    <th>Imagem</th>
                    <th>Dados</th>
                </tr>
            </thead>
            <tbody>';
    
        while ($user_data = mysqli_fetch_assoc($result)) {
            echo "<tr>";    
            echo "<td>".$user_data['nome']."</td>";
            echo "<td>".$user_data['email']."</td>";
            echo "<td>".$user_data['senha']."</td>";
            if (!empty($user_data['image'])) {
                echo "<td><img src='" . $user_data['image'] . "' alt='Imagem do usuário' style='max-width:50px;'></td>";
            } else {
                echo "<td><i class='fa-solid fa-user fa-2x'></i></td>";
            }
            echo "<td>
            <a class='btnEdit' href='edit.php?id=$user_data[id]' title='Editar'>
            <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' class='bi bi-pencil-square' viewBox='0 0 16 16'>
              <path d='M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z'/>
              <path fill-rule='evenodd' d='M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z'/>
            </svg>
          </a> 
                <a class='btnDelete' href='delete.php?id=$user_data[id]' title='Deletar'>
                    <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' class='bi bi-trash-fill' viewBox='0 0 16 16'>
                        <path d='M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z'/>
                    </svg>
                </a>
                </td>";
            echo "</tr>";
        }
    
    echo '
            </tbody>
        </table>
    </div>';

}
?>
</body>
</html>