<?php 

    include('conn.php');

    session_start();

    include('verificar_login.php');

    $user = $_SESSION['usuario'];

    if($_SESSION['logado'] == false){
        header('Location: login.php');
    }

    if(isset($_POST['acao_deslogar'])){
        session_destroy();
        header('Location: login.php');
    }

/* Tipos de Conquista: 

    0 = cadastrar imovel
    1 = cadastrar contrato
    2 = cadastrar negocio
    3 = Mensagem

*/


/* Ação de deslogar por inatividade */
    
    $inactive = 1200; //20 min

    $currentTime = time();

    if (isset($_SESSION['lastActivity']) && ($currentTime - $_SESSION['lastActivity'] > $inactive)) {
        
        session_unset();
        session_destroy();
        header('Location: login.php');
        exit;
    }

    $_SESSION['lastActivity'] = $currentTime;

/* Fim da Ação de deslogar por inatividade */

    $selecionar_cadastro = "SELECT * FROM cadastro WHERE nome = '$user'";
    $result_selecionar_cadastro = mysqli_query($conn, $selecionar_cadastro);
    $linha_selecionar_cadastro = mysqli_fetch_assoc($result_selecionar_cadastro);

    $id_user_logado = $linha_selecionar_cadastro['id'];

    $selecionar_conquistas = "SELECT * FROM conquistas WHERE atribuidoPara = 0 OR atribuidoPara = '$id_user_logado'";
    $result_selecionar_conquistas = mysqli_query($conn, $selecionar_conquistas);
    $linha_selecionar_conquistas = mysqli_fetch_assoc($result_selecionar_conquistas);

    $selecionar_historico = "SELECT * FROM historico WHERE idUser = '$id_user_logado' LIMIT 1";
    $result_selecionar_historico = mysqli_query($conn, $selecionar_historico);
    $linha_selecionar_historico = mysqli_fetch_assoc($result_selecionar_historico);



    mysqli_data_seek($result_selecionar_historico, 0);
    mysqli_data_seek($result_selecionar_conquistas, 0);

    
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Conquistas | ImobiBrasil</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
    <script src="https://code.jquery.com/jquery-3.6.4.min.js" integrity="sha256-oP6HI9z1XaZNBrJURtCoUT5SUnxFr8s3BzRl+cbzUq8=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        *{
            margin: 0;
            font-family: arial;
        }
        
        body{
            background-color: #ebe8e8;
        }

        .mae{
            width: 90%;
            height: 730px;
            display: flex;
            margin-left: 5%;
        }

        .menu_superior{
            width: 100%;
            height: 100px;
            background-image: url('header.png');
        }

        footer{
            width: 100%;
            height: 35px;
            background-image: url('footer.png');
        }

        .menu_esquerda{
            width: 27%;
            height: 500px;
        }

        .profile_pic{
            width: 300px;
            height: 300px;
            background-color: #DCDCDC;
            border-radius: 50%;
            margin: auto;
        }

        .fa-user{
            font-size: 200px;
            margin-left: 21%;
            margin-top: 13%;
        }

        h1{
            text-align: center;
            font-size: 55px;
        }

        .conquistas{
            width: 73%;
        }

        .conquistas-listar{
            background-color: #dcdcdc;
            width: 80%;
            border-radius: 30px;
            height: 70px;
            margin-top: 2%;
            margin-left: 8%;
        }

        .progress {
            border: 1px solid #B0B0B0;
            width: 90%;
            margin: auto;
            border-radius: 4px;
            box-shadow: 0 0 3px #B0B0;
            backdrop-filter: blur(2px) brightness(101%);
        }

        .fa-info-circle:hover ~ .texto {
            display: block;
        }

        .texto {
            display: none;
            color: #fff;
            background-color: red;
            width: 13%;
            padding: 10px;
            border-radius: 4px;
            position: relative;
            top: -100px;
            left: 60%;
        }

        .estatisticas{
            color: white;
            background-color: green;
            padding: 1.5%;
            border-radius: 5px;
            text-decoration: none;
        }

        .estatisticas:hover{
            color: white;
            text-decoration: none;
            background-color: #2ca62f;
        }

        /* botao popup */

        .popup-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
        }

        .popup {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #4CAF50;
            padding: 20px;
            border-radius: 60px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            color: #fff;
            width: 600px;
            height: 100px;
        }

        .popup-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .popup-icon {
            background-color: #fff;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 20px;
        }

        .popup-header h1 {
            margin: 0;
            font-size: 24px;
        }

        .popup-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
        }

        .popup-content p {
            margin: 0;
            font-size: 18px;
        }

        #close-popup-btn {
            display: block;
            margin: 20px auto 0;
            color: #fff;
            background-color: #333;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        #close-popup-btn:hover {
            background-color: #fff;
            color: #333;
        }

        .pai_conquistas{
            display: flex;
        }

        ..search-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        input[type=text] {
            padding: 10px;
            border: none;
            border-radius: 5px 0 0 5px;
            width: 50%;
            margin-left: 15%;
            background-color: #f1f1f1;
        }

        button[type=submit] {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        input[type=text]:focus {
            outline: none;
            background-color: #fff;
        }

        button[type=submit]:hover {
            background-color: #3e8e41;
        }





    </style>
</head>

<body>

    <header>
        <div class="menu_superior"></div>
    </header>

    <?php echo "<b>Usuário logado:</b> ". $user; ?>

    <form action="" method="POST">
        <input type="hidden" name="acao_deslogar" value="acao">
        <input style="width: 80px; float: right; margin-right: 20px;" type="submit" value="Sair" name="unlog">
    </form>


    <div class="mae">
    
        <div class="menu_esquerda">

            <div class="profile_pic">
                <i class="fa fa-user" style="margin-left: 27%;" aria-hidden="true"></i>  
            </div>

            <p style="text-align: center; margin-top: 2%;"><?php echo $_SESSION['usuario'];?></p>

            <br/><br/>

            <a class="estatisticas" href="estatisticas.php?user=<?php echo $user;?>">Suas Estatísticas</a>

        </div>

        

        
    <?php 
        mysqli_data_seek($result_selecionar_historico, 0);
        mysqli_data_seek($result_selecionar_conquistas, 0);
    ?>

        <div class="conquistas">

        <div class="search-container">
            <form action="#">
                <input type="text" placeholder="Pesquisar Conquista">
                <button type="submit">Buscar</button>
            </form>
        </div>
                <h1>Galeria de Conquistas</h1>

                <?php 
                
                while($linha_selecionar_historico = mysqli_fetch_assoc($result_selecionar_historico)){

                while($linha_selecionar_conquistas = mysqli_fetch_assoc($result_selecionar_conquistas)){ 

                    if($linha_selecionar_conquistas['atribuidoPara'] == 0 || $linha_selecionar_conquistas['atribuidoPara'] == $linha_selecionar_cadastro['id']){
                    
                    
                    ?>
                    <div class="conquistas-listar">

                    <?php if($linha_selecionar_conquistas['tipoConquista'] == 0){ ?>





                        <!-- Imóveis -->

                        <?php $progresso_qtdeImoveis = $linha_selecionar_historico['qtdeImoveis'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; ?>

                        <div class="pai_conquistas">

                            <i class="fa fa-home" aria-hidden="true" style="font-size: 32px; color: green;"></i>
                            
                            <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                            <?php echo $linha_selecionar_historico['qtdeImoveis']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                            <!-- <i class="fa fa-info-circle" data-toggle="tooltip" style="margin-left: 35%;" data-placement="bottom" title="<?php echo $linha_selecionar_conquistas['tooltips'];?>"></i> -->
                        </div>

                        <?php if($progresso_qtdeImoveis != 100){ ?>

                        <div class="progress">
                            <div class="bar cadastrar_imoveis" style="width: <?php echo $progresso_qtdeImoveis; ?>%; background-color: lightgreen;"><?php echo $progresso_qtdeImoveis;?>%</div>
                        </div>

                        <?php } else{
                            echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                        }

                        ?>





                        <!-- Contratos -->

                        <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 1){ ?>

                            <?php $progresso_qtdeContratos = $linha_selecionar_historico['qtdeContratos'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; ?>

                            <div class="pai_conquistas">

                                <i class="fa fa-folder-open" aria-hidden="true" style="font-size: 32px; color: green;"></i>

                                <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                <?php echo $linha_selecionar_historico['qtdeContratos']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="<?php echo $linha_selecionar_conquistas['tooltips'];?>"></i> -->

                            </div>

                            <?php if($progresso_qtdeContratos != 100){ ?>

                                <div class="progress">
                                    <div class="bar cadastrar_contratos" style="width: <?php echo $progresso_qtdeContratos; ?>%; background-color: #a9f2a9;"><?php echo $progresso_qtdeContratos;?>%</div>
                                </div>

                            <?php } else{
                                echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                            } ?>






                        <!-- Negócios -->
                        
                        <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 2){ ?>

                            <?php $progresso_qtdeNegocios = $linha_selecionar_historico['qtdeNegocios'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; ?>

                            <div class="pai_conquistas">

                                <i class="fa fa-briefcase" aria-hidden="true" style="font-size: 32px; color: green;"></i>

                                <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                <?php echo $linha_selecionar_historico['qtdeNegocios']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="<?php echo $linha_selecionar_conquistas['tooltips'];?>"></i> -->
                            </div>

                        <?php if($progresso_qtdeNegocios != 100){ ?>

                            <div class="progress">
                                <div class="bar cadastrar_negocios" style="width: <?php echo $progresso_qtdeNegocios; ?>%; background-color: #a9f2a9;"><?php echo $progresso_qtdeNegocios;?>%</div>
                            </div>

                        <?php } else{
                                echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                            } ?>





                        <!-- Mensagens -->

                        <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 3){ ?>

                            <?php $progresso_qtdeMensagens = $linha_selecionar_historico['qtdeMensagens'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; ?>

                            <div class="pai_conquistas">

                                <i class="fa fa-comments" aria-hidden="true" style="font-size: 32px; color: green;"></i>

                                <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                <?php echo $linha_selecionar_historico['qtdeMensagens']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                <!-- <i class="fa fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="<?php echo $linha_selecionar_conquistas['tooltips'];?>"></i> -->
                            </div>

                            <?php if($progresso_qtdeMensagens != 100){ ?>

                        <div class="progress">
                            <div class="bar cadastrar_mensagens" style="width: <?php echo $progresso_qtdeMensagens; ?>%; background-color: #a9f2a9;"><?php echo $progresso_qtdeMensagens;?>%</div>
                        </div>

                        <?php } else{
                                echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                            } }?>

                    </div>
                    
           

            <?php } } }?>

        </div>
    
    </div>

    <button id="open-popup-btn">Abrir popup</button>
	
	<div class="popup-container">
		<div class="popup">
			<div class="popup-header">
				<div class="popup-icon"></div>
				<h1>Conquista Desbloqueada!</h1>
			</div>
			<div class="popup-content"></div>
			<button id="close-popup-btn">Fechar</button>
		</div>
	</div>

    <footer></footer>

    <script>

        const openPopupBtn = document.querySelector('#open-popup-btn');
        const popupContainer = document.querySelector('.popup-container');
        const closePopupBtn = document.querySelector('#close-popup-btn');

        function openPopup() {
            popupContainer.style.display = 'block';
        }

        function closePopup() {
            popupContainer.style.display = 'none';
        }

        openPopupBtn.addEventListener('click', openPopup);
        closePopupBtn.addEventListener('click', closePopup);

    </script>

    <script>

$(document).ready(function() {
  // Quando o usuário digita algo no input de pesquisa
  $('#search-input').on('input', function() {
    // Obter o valor do input de pesquisa
    var searchValue = $(this).val();

    // Verificar se o valor do input de pesquisa é válido
    if (searchValue.length > 0) {
      // Fazer uma requisição Ajax para obter os resultados de pesquisa
      $.ajax({
        url: 'index.php',
        method: 'POST',
        data: {
          search: searchValue
        },
        dataType: 'json',
        success: function(results) {
          // Limpar a lista de resultados de pesquisa
          $('#search-results').empty();

          // Adicionar cada resultado à lista de resultados de pesquisa
          $.each(results, function(index, result) {
            $('#search-results').append('<li>' + result + '</li>');
          });
        }
      });
    } else {
      // Se o valor do input de pesquisa não for válido, limpar a lista de resultados de pesquisa
      $('#search-results').empty();
    }
  });
});


    </script>

</body>
</html>