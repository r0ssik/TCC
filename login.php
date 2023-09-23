<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="shortcut icon" type="image/x-icon" href="img/icon.ico">
    <style>
        <?php include 'style/login.css'; ?>
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
  -webkit-text-fill-color: white !important;
  -webkit-box-shadow: 0 0 0px 0px #000 inset;
  transition: background-color 5000s ease-in-out 0s;
}
  
    </style>
</head>
<body>
<a href="default.php" class="voltar">< Principal</a>
    

    <div class="CardLogin">

        <center><h1>Login</h1></center>

          <form action="testLogin.php" method="POST">
            <div class="inputbox">
                            <input class="InputEmail" type="text" name="email" required>
                            <label for="">Email</label>
            </div>
            <br><br>

            <div class="inputbox">
                            <input class="InputSenha" type="password" name="senha" required>
                            <label for="">Senha</label>
            </div>
            <br><br>
       
            <input class="inputSubmit" type="submit" name="submit" value="ENTRAR">

            

        </form>
     </div>
     <img src="img/logoCatalogXSemTexto.svg">
</body>
</html>