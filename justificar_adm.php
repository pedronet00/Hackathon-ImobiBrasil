<?php 

    include('conn.php');

    session_start();

    $user = $_SESSION['usuario'];

    if($_SESSION['logado'] == false){
        header('Location: login.php');
    }

    $query_justificativas = "SELECT * FROM justificativa WHERE resolvido = 0 LIMIT 1";
    $result_justificativas = mysqli_query($conn, $query_justificativas);

    if(isset($_POST['acao'])){
        $resolvido = $_POST['resolvido'];
        $nao_resolvido = $_POST['nao_resolvido'];
        $usuario = $_POST['usuario'];
        $justificativa = $_POST['justificativa'];

        if (isset($resolvido)) {
            $query_atualizar_justificativa = "UPDATE justificativa SET resolvido = 1 WHERE nomeUser = '$usuario' AND textoJustificativa = '$justificativa'";
            $result_atualizar_justificativa = mysqli_query($conn, $query_atualizar_justificativa);
        } elseif (isset($nao_resolvido)) {
            $query_atualizar_justificativa = "UPDATE justificativa SET resolvido = 2 WHERE nomeUser = '$usuario' AND textoJustificativa = '$justificativa'";
            $result_atualizar_justificativa = mysqli_query($conn, $query_atualizar_justificativa);
        }
    }

?>

<html>
  <head>
    <meta charset="UTF-8">
    <title>Justifique-se!</title>
    <link rel="stylesheet" href="estilo.css">
    <style>

        *{
            font-family: arial;
        }

        body{
            background-color: white;
        }

        h1{
            text-align: center;
        }

        h5{
            text-align: center;
        }

        label{
            margin-left: 40%;
        }

        textarea.justificativa {
            height: 120px;
        }

        input[type="checkbox"] + label {
            font-size: 16px;
            margin-left: 5px;
        }

        input.salvar {
            padding: 10px 30px;
            font-size: 18px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            margin-left: 45%;
        }

        input.salvar:hover {
            background-color: #0056b3;
        }

        input[type="checkbox"]:checked + label::before {
            content: "\2713";
            display: inline-block;
            font-size: 16px;
            vertical-align: bottom;
            margin-right: 5px;
        }


    </style>
  </head>

<body>
    <div class="container">
      <h1>Analisar justificativas</h1>
      <h5>Seus colaboradores enviam as justificativas por aqui. Verifique a procedência delas!</h5>

      <?php if(mysqli_num_rows($result_justificativas) > 0){ ?>

            <form method="POST" id="form_justificativas" action="justificar_adm.php">

            <input type="hidden" name="acao" value="acao"/>

                <?php while($linha_justificativas = mysqli_fetch_assoc($result_justificativas)){ ?>

                    <b>Usuário:</b><input class="usuario" type="textarea" style="border: none;" value="<?php echo $linha_justificativas['nomeUser']; ?>" readonly name="usuario" >

                    <br/><br/>

                    <b>Justificativa:</b><input class="usuario" type="textarea" style="border: none; width: 100%; height: 50px;" value="<?php echo $linha_justificativas['textoJustificativa']; ?>" readonly name="justificativa" >

                    <br/><br/>
                    
                    <img src="<?php echo $linha_justificativas['arqJustificativa']; ?>" width="400" height="450" style="margin-left: 23%;" alt="Arquivo">

                    <br/><br/>

                    <label>Resolvido?</label>
                    <input class="checkbox" type="checkbox" name="resolvido" value="resolvido">Sim</input>
                    <input class="checkbox" type="checkbox" name="nao_resolvido" value="nao_resolvido">Não</input>

                    <br/><br/>

                    <input class="salvar" type="submit" name="salvar" value="Salvar"/>

                    <br/><br/><br/><br/>

                <?php } ?>
            </form>
        <?php } else{ echo "Nenhuma justificativa para analisar."; }?>

    </div>

</body>
</html>