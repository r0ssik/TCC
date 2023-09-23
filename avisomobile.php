<!DOCTYPE html>
<html>
<head>
    <title>Acesse por um Computador!</title>
    <style>
        #aviso {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(15px);
            z-index: 9999;
            text-align: center;
            padding-top: 20%;
            color: #fff;
            font-size: 24px;
        }
        .logo{
            position: relative;
            max-width: 150px;
        }
        .roxo{
            color: #2910BC ; /* #3c1a7d */
            font-weight: bold;
        }
        .laranja{
            color: #ff8f1c;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="aviso">
        <img src="img/logoCatalogXTextoEmbaixo.png" alt="" class="logo">
        <br><br>
        <p>Desculpe, esta página não pode ser acessada por dispositivos móveis. Por favor, acesse-a a partir de um computador.</p>
        <br>
        <p>A equipe <span class="roxo">Norteam</span> ainda está trabalhando na versão mobile do <span class="laranja">CatalogX™</span>.</p>
    </div>

    <script>
        window.onload = function() {
            var avisoDiv = document.getElementById('aviso');
            if (window.innerWidth < 1230) {
                avisoDiv.style.display = 'block';
            }
        };
    </script>
</body>
</html>