<?php 

include('conn.php');

if(isset($_POST['acao'])){

    $usuario = $_POST['user'];
    $password = $_POST['pass'];
    
    $query_verifica_user = "SELECT * FROM cadastro WHERE nome = '$usuario' AND senha = '$password'";

    if($query_verifica_user === FALSE){
        die(mysqli_error());
    } else{
        $result = mysqli_query($conn, $query_verifica_user);
        $linhas = mysqli_fetch_assoc($result);

        

        if(mysqli_num_rows($result) == 1){
            
            $_SESSION['logado'] = true;
            $_SESSION['usuario'] = $_POST['user'];

            if($_SESSION['usuario'] == "adm"){
                header('Location: adm.php');
            } else{
                header('Location: index.php');
            }
            

        } else{
            ?> <script>alert("Usuário ou senha inválidos!");</script><?php
        }
    }
}

?>