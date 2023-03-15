<?php 

    include('conn.php');

    session_start();

    

    if(isset($_POST['acao'])){

        $nome = $_POST['name'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $profile_pic = $_POST['imagem_perfil'];
        

        $query_novo_usuario = 
        "INSERT INTO cadastro
        (nome, email, senha, profilePic) 
        VALUES 
        ('$nome','$email', '$pass', '$profile_pic')";

        $result_novo_usuario = mysqli_query($conn, $query_novo_usuario);

        header('Location: login.php');

    }

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ImobiBrasil | Registro</title>
</head>

<body>


<div class="box">
    <div class="form">
        <form action="registro.php" method="POST">
            <input type="hidden" name="acao" value="acao">

            <h2>ImobiBrasil</h2>
            <div class="inputBox">
                <input name="name" type="text" required>
                <span>Nome Completo</span> <i></i>
            </div>
            <div class="inputBox">
                <input name="email" type="text" required>
                <span>E-mail</span> <i></i>
            </div>
            <div class="inputBox">
                <input name="pass" type="Password" required>
                <span>Senha</span> <i></i>
            </div>
            <br/><br/>

            <h4 style="color: #747576">Imagem de Perfil</h4><br/>
            <input type="file" name="imagem_perfil" class="custom-file-input" id="customFile">
            
            <input type="submit" value="Registrar" class="c">
        </form>
    </div>
</div>

</body>
</html>