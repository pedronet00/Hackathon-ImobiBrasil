<?php 

    include('conn.php');

    session_start();

    $user = $_SESSION['usuario'];



    if($_SESSION['logado'] == false){
        header('Location: login.php');
    }

    $date = date('Y-m-d H:i:s');
    
    if(isset($_POST['acao'])){
        
        $texto = $_POST['texto_justificacao'];
        $arquivo = $_POST['arquivo_anexado'];

        $query_inserir_justificativa = "INSERT INTO justificativa(textoJustificativa, dataJustificativa, nomeUser, arqJustificativa, resolvido) VALUES ('$texto', '$date', '$user', '$arquivo', 0)";
        $result_inserir_justificativa = mysqli_query($conn, $query_inserir_justificativa);
    }

    


?>


<html>
  <head>
    <meta charset="UTF-8">
    <title>Justifique-se!</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: arial;
            }

            body {
            background-color: #F7F5E6;
            }

            .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            }

            h1 {
            font-size: 3rem;
            color: #3E6E3E;
            margin-bottom: 1rem;
            }

            h6{
                text-align: center;
                color: #3E6E3E;
            }

            form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background-color: #FEFAC7;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            }

            label {
            font-size: 1.2rem;
            color: #3E6E3E;
            margin-bottom: 0.5rem;
            }

            textarea {
            width: 100%;
            max-width: 500px;
            height: 10rem;
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: none;
            border-radius: 0.5rem;
            }

            input[type="file"] {
            margin-bottom: 1rem;
            }

            input[type="submit"] {
            background-color: #3E6E3E;
            color: #FEFAC7;
            border: none;
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 1.2rem;
            cursor: pointer;
            transition: background-color 0.3s ease-in-out;
            }

            input[type="submit"]:hover {
            background-color: #618E58;
            }

    </style>
  </head>

  <body>
    <div class="container">
      <h1>Justifique-se!</h1>
      <h6>Você não completou o objetivo a tempo. Por favor, escreva o motivo e, se necessário, anexe atestados, documentos, etc.</h6>
      <br/>
      <form method="POST" action="index.php" id="justificar_conquista">
        <input type="hidden" value="acao" name="acao"/>
        <label for="texto_justificacao" >Escreva aqui o texto:</label>
        <textarea placeholder="Digite seu texto aqui" name="texto_justificacao" id="texto_justificacao"></textarea>
        <label for="arquivo_anexado">Anexe um arquivo:</label>
        <input type="file" name="arquivo_anexado" id="arquivo_anexado"/>
        <input type="submit" name="enviar" id="enviar" value="Enviar"/>
      </form>
    </div>
  </body>
</html>