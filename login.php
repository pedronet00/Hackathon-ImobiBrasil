<?php 

    include('conn.php');

    session_start();

    include('verificar_login.php');

    

?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>ImobiBrasil | Login</title>
<style>
    
    body {
    background-color: #ebe8e8;
    font-family: Ubuntu, sans-serif;
}

.box {
    background-color: #fff;
    max-width: 400px;
    margin: 0 auto;
    padding: 30px;
    border-radius: 5px;
    box-shadow: 0px 0px 10px rgba(0,0,0,0.2);
}

.box h2 {
    color: #4CAF50;
    text-align: center;
    font-weight: normal;
    margin-bottom: 30px;
}

.form {
    text-align: center;
}

.inputBox {
    position: relative;
    margin-bottom: 20px;
}

.inputBox input {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    color: #555;
    border: none;
    border-bottom: 1px solid #ccc;
    background-color: transparent;
    transition: all 0.3s ease;
}

.inputBox input:focus {
    outline: none;
    border-bottom: 1px solid #4CAF50;
}

.inputBox span {
    position: absolute;
    top: 10px;
    left: 0;
    color: #999;
    transition: all 0.3s ease;
}

.inputBox i {
    position: absolute;
    top: 10px;
    right: 0;
    color: #ccc;
    transition: all 0.3s ease;
}

.inputBox input:focus ~ span,
.inputBox input:valid ~ span {
    top: -20px;
    left: 0;
    font-size: 12px;
    color: #4CAF50;
}

.inputBox input:focus ~ i,
.inputBox input:valid ~ i {
    color: #4CAF50;
}

.links {
    text-align: center;
    margin-top: 20px;
}

.links a {
    color: #ffcc00;
    text-decoration: none;
    margin: 0 10px;
    transition: all 0.3s ease;
}

.links a:hover {
    color: #4CAF50;
}

.c {
    background-color: #4CAF50;
    color: #fff;
    border: none;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    border-radius: 5px;
    transition: all 0.3s ease;
}

.c:hover {
    background-color: #fff;
    color: #4CAF50;
    border: 1px solid #4CAF50;
}

.box {
    width: 350px;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.brasil{
    color: #4CAF50;
    font-weight: bold;
    border: 1px sol
}

</style>
</head>

<body>


<div class="box">
    <div class="form">
        <form action="login.php" method="POST">
        <input type="hidden" name="acao" value="acao">

            <h2>Imobi<span class="brasil">Brasil</span></h2>
            <div class="inputBox">
                <input name="user" type="text" required>
                <span>Nome de Usuário</span> <i></i>
            </div>
            <div class="inputBox">
                <input name="pass" type="Password" required>
                <span>Senha</span> <i></i>
            </div>

            <input type="submit" value="Entrar" class="c">

            <div class="links">
                <br><br>    <a href="#">Esqueceu a Senha?</a>

                <br><br>

                Não tem uma conta? <a href="registro.php">Registre-se</a>
            </div>
            
        </form>
    </div>
</div>

</body>
</html>