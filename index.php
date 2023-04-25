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

    $selecionar_cadastro = "SELECT * FROM cadastro WHERE nome = '$user'"; //query
    $result_selecionar_cadastro = mysqli_query($conn, $selecionar_cadastro); //result
    $linha_selecionar_cadastro = mysqli_fetch_assoc($result_selecionar_cadastro); //array

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
            height: 60px;
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
            height: 90px;
            margin-top: 1.2%;
            margin-left: 8%;
        }

        .conquistas-listar i{

        }

        .progress {
            border: 1px solid #B0B0B0;
            width: 80%;
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
            margin-left: 37%;
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
            margin-left: 15%;
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

        .badges{
            background-color: white;
            width: 400px;
            height: 300px;
            border-radius: 20px;
            margin-top: 5%;
            margin-left: 5%;
            border: 2px solid #cecaca;
        }

        .badges i{
            margin-left: 5%;
            margin-top: 5%;
        }

        #fundo-escuro {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
            display: none;
        }

        #meu-iframe-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 800px;
            height: 80%;
            max-height: 600px;
            background-color: white;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 10000;
            display: none;
        }

        #meu-iframe {
            width: 100%;
            height: 100%;
            border: none;
        }




    </style>
</head>

<body>

    <header>
        <div class="menu_superior"></div>
    </header>


    <form action="" method="POST">
        <input type="hidden" name="acao_deslogar" value="acao">
        <input style="width: 80px; float: right; margin-right: 20px;" type="submit" value="Sair" name="unlog">
    </form>


    <div class="mae">

        <!-- menu lateral esquerdo -->
        <div class="menu_esquerda">

            <div class="profile_pic">
                <i class="fa fa-user" style="margin-left: 27%;" aria-hidden="true"></i>  
            </div>

            <p style="text-align: center; margin-top: 2%;"><?php echo $_SESSION['usuario'];?></p>

            <br/><br/>

            <!-- estatísticas -->
            <a class="estatisticas" href="estatisticas.php?user=<?php echo $user;?>">Suas Estatísticas</a>

            <div class="badges">

                <h3 style="text-align: center;"><b>Insígnias</b></h3>

                <i class="fa fa-home insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Cadastrador de Imóveis Profissional" aria-hidden="true"></i>
                <i class="fa fa-comments insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Conversador Profissional" aria-hidden="true"></i>
                <i class="fa fa-folder-open insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true" ></i>
                <i class="fa fa-book insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-camera-retro insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-eyedropper insignias" style="color: #2ca62f; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-download insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-graduation-cap insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-users insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-gavel insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-lightbulb-o insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-picture-o insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-search insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>
                <i class="fa fa-tag insignias" style="color: lightgrey; font-size: 30px;" data-toggle="tooltip" data-placement="top" title="Documentador Profissional" aria-hidden="true"></i>

            </div>
            
            <br/><br/>

        </div> 

        
    <?php 

        mysqli_data_seek($result_selecionar_historico, 0);
        mysqli_data_seek($result_selecionar_conquistas, 0);

    ?>

        
            <div class="conquistas">

                    <?php 

                    $date = date('Y-m-d');
                    
                    while($linha_selecionar_historico = mysqli_fetch_assoc($result_selecionar_historico)){

                    while($linha_selecionar_conquistas = mysqli_fetch_assoc($result_selecionar_conquistas)){ 

                        if($linha_selecionar_conquistas['atribuidoPara'] == 0 || $linha_selecionar_conquistas['atribuidoPara'] == $linha_selecionar_cadastro['id']){ ?>

                        <div class="conquistas-listar">

                        <?php if($linha_selecionar_conquistas['tipoConquista'] == 0){ ?>

                            <!-- Imóveis -->

                            <?php $progresso_qtdeImoveis = $linha_selecionar_historico['qtdeImoveis'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; 

                                $data = $linha_selecionar_conquistas['dataLimiteConquista'];
                                $date = new DateTime($data);
                                $data_formatada = $date->format('d/m/Y');
                            
                            ?>

                            <div class="pai_conquistas">

                                <i class="fa fa-home" aria-hidden="true" style="font-size: 32px; color: green;"></i>
                                
                                <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                <?php echo $linha_selecionar_historico['qtdeImoveis']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                            </div>

                            <?php 
                            
                            $progresso_qtdeImoveis = sprintf("%.0f", $progresso_qtdeImoveis);
                            
                            if($progresso_qtdeImoveis < 100){ ?>

                            <div class="progress">
                                <div class="bar cadastrar_imoveis" style="width: <?php echo $progresso_qtdeImoveis; ?>%; color: white; font-weight: bold; background-color: #2ca62f;"><?php echo $progresso_qtdeImoveis;?>%</div>
                            </div>

                            <?php } else{
                                echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                            }

                            if($linha_selecionar_conquistas['conquistaSistema'] == 1){
                                echo "<p style='font-size: 15px; margin-top: 0.5%; color: white; font-weight: bold; border-radius: 50%; background-color: green; border: 1px solid green; width: 23px; text-align: center;' data-toggle='tooltip' data-placement='top' title='Cadastrado por ADM'>i</p>";
                                
                                echo "<a style='float: right; margin-right: 5%; margin-top: -2%;'>Prazo: ". $data_formatada. "</a>";
                            }

                            ?>





                            <!-- Contratos -->

                            <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 1){ ?>

                                <?php $progresso_qtdeContratos = $linha_selecionar_historico['qtdeContratos'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; 
                                
                                    $data = $linha_selecionar_conquistas['dataLimiteConquista'];
                                    $date = new DateTime($data);
                                    $data_formatada = $date->format('d/m/Y');
                                
                                ?>

                                <div class="pai_conquistas">

                                    <i class="fa fa-folder-open" aria-hidden="true" style="font-size: 32px; color: green;"></i>

                                    <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                    <?php echo $linha_selecionar_historico['qtdeContratos']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                </div>
                                    
                                <?php 
                                
                                $progresso_qtdeContratos = sprintf("%.0f", $progresso_qtdeContratos);

                                if($progresso_qtdeContratos < 100){ ?>

                                    <div class="progress">
                                        <div class="bar cadastrar_contratos" style="width: <?php echo $progresso_qtdeContratos; ?>%; color: white; font-weight: bold; background-color: #2ca62f;"><?php echo $progresso_qtdeContratos;?>%</div>
                                        
                                    </div>

                                <?php } else{
                                    echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                                } 
                                
                                if($linha_selecionar_conquistas['conquistaSistema'] == 1){
                                    echo "<p style='font-size: 15px; margin-top: 0.5%; color: white; font-weight: bold; border-radius: 50%; background-color: green; border: 1px solid green; width: 23px; text-align: center;' data-toggle='tooltip' data-placement='top' title='Cadastrado por ADM'>i</p>";
                                    echo "<p style='float: right; margin-right: 5%; margin-top: -2%;'>Prazo: ". $data_formatada. "</p>";
                                }
                                
                                ?>






                            <!-- Negócios -->
                            
                            <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 2){ ?>

                                <?php $progresso_qtdeNegocios = $linha_selecionar_historico['qtdeNegocios'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; 
                                
                                    $data = $linha_selecionar_conquistas['dataLimiteConquista'];
                                    $date = new DateTime($data);
                                    $data_formatada = $date->format('d/m/Y');

                                ?>

                                <div class="pai_conquistas">

                                    <i class="fa fa-briefcase" aria-hidden="true" style="font-size: 32px; color: green;"></i>

                                    <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                    <?php echo $linha_selecionar_historico['qtdeNegocios']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                </div>

                            <?php 
                            
                            $progresso_qtdeNegocios = sprintf("%.0f", $progresso_qtdeNegocios);
                            
                            if($progresso_qtdeNegocios < 100){ ?>

                                <div class="progress">
                                    <div class="bar cadastrar_negocios" style="width: <?php echo $progresso_qtdeNegocios; ?>%; color: white; font-weight: bold; background-color: #2ca62f;"><?php echo $progresso_qtdeNegocios;?>%</div>
                                </div>

                            <?php } else{
                                    echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                                } 
                                
                                
                                if($linha_selecionar_conquistas['conquistaSistema'] == 1){
                                    echo "<p style='font-size: 15px; margin-top: 0.5%; color: white; font-weight: bold; border-radius: 50%; background-color: green; border: 1px solid green; width: 23px; text-align: center;' data-toggle='tooltip' data-placement='top' title='Cadastrado por ADM'>i</p>";
                                    echo "<p style='float: right; margin-right: 5%; margin-top: -2%;'>Prazo: ". $data_formatada. "</p>";
                                }
                                
                                ?>





                            <!-- Mensagens -->

                            <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 3){ ?>

                                <?php $progresso_qtdeMensagens = $linha_selecionar_historico['qtdeMensagens'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; 
                                
                                
                                $data = $linha_selecionar_conquistas['dataLimiteConquista'];
                                $date = new DateTime($data);
                                $data_formatada = $date->format('d/m/Y');
                                
                                

                                
                                
                                ?>

                                <div class="pai_conquistas">

                                    <i class="fa fa-comments" aria-hidden="true" style="font-size: 32px; color: green; "></i>

                                    <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                    <?php echo $linha_selecionar_historico['qtdeMensagens']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                </div>

                                <?php 
                                
                                $progresso_qtdeMensagens = sprintf("%.0f", $progresso_qtdeMensagens);
                                
                                if($progresso_qtdeMensagens < 100){ ?>

                            <div class="progress">
                                <div class="bar cadastrar_mensagens" style="width: <?php echo $progresso_qtdeMensagens; ?>%; color: white; font-weight: bold; background-color: #2ca62f;"><?php echo $progresso_qtdeMensagens;?>%</div>
                            </div>

                            <?php } else{
                                    echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                                } 
                                
                                if($linha_selecionar_conquistas['conquistaSistema'] == 1){
                                    echo "<p style='font-size: 15px; margin-top: 0.5%; color: white; font-weight: bold; border-radius: 50%; background-color: green; border: 1px solid green; width: 23px; text-align: center;' data-toggle='tooltip' data-placement='top' title='Cadastrado por ADM'>i</p>";
                                    echo "<p style='float: right; margin-right: 5%; margin-top: -2%;'>Prazo: ". $data_formatada. "</p>";
                                }
                                
                                
                                ?>




                                <!-- Imagens -->

                                <?php }elseif($linha_selecionar_conquistas['tipoConquista'] == 4){ ?>

                                <?php $progresso_qtdeImagens = $linha_selecionar_historico['imagensTotais'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100; 
                                
                                
                                    $data = $linha_selecionar_conquistas['dataLimiteConquista'];
                                    $date = new DateTime($data);
                                    $data_formatada = $date->format('d/m/Y');
                                
                                
                                ?>

                                <div class="pai_conquistas">

                                <i class="fa fa-picture-o" style="color: green; font-size: 32px;" aria-hidden="true"></i>

                                    <?php echo "<p style='margin-left: 40%; margin-top: 0.5%;'>". $linha_selecionar_conquistas['descricaoConquista']. ":&nbsp <b>";?>

                                    <?php echo $linha_selecionar_historico['imagensTotais']. "/". $linha_selecionar_conquistas['tamanhoMaximo']. "</p></b>"; ?>

                                </div>

                                <?php 
                                
                                $progresso_qtdeImagens = sprintf("%.0f", $progresso_qtdeImagens);
                                
                                if($progresso_qtdeImagens < 100){ ?>

                                <div class="progress">
                                <div class="bar imagens_totais" style="width: <?php echo $progresso_qtdeImagens; ?>%; color: white; font-weight: bold; background-color: #2ca62f;"><?php echo $progresso_qtdeImagens;?>%</div>
                                </div>

                                <?php } else{
                                    echo "<p style='text-align: center; font-size: 22px; color: green; font-weight: bold;'>Conquista Concluída!</p>";
                                } 
                                
                                if($linha_selecionar_conquistas['conquistaSistema'] == 1){
                                    $id_conquista = $linha_selecionar_conquistas['idConquista'];
                                    echo "<p style='font-size: 15px; margin-top: 0.5%; color: white; font-weight: bold; border-radius: 50%; background-color: green; border: 1px solid green; width: 23px; text-align: center;' data-toggle='tooltip' data-placement='top' title='Cadastrado por ADM'>i</p>";
                                    echo "<a id='mostrar-iframe' href='#' style='float: right; margin-right: 5%; margin-top: -2%;'>Prazo: ". $data_formatada. "</a>"; 
                                }
                                
                                
                                }?>

                        </div>
                <?php } } } ?>
            </div>
        </div>


    <div id="fundo-escuro"></div>

    <div id="meu-iframe-container">
        <iframe id="meu-iframe" src="justificar.php?id=<?php echo $id_user_logado; ?>"></iframe>
    </div>
	

        <!-- script para iframe de justificação -->

    <script>

        const link = document.getElementById('mostrar-iframe');
        const fundoEscuro = document.getElementById('fundo-escuro');
        const iframeContainer = document.getElementById('meu-iframe-container');
        const iframe = document.getElementById('meu-iframe');

        link.addEventListener('click', function(e) {
            iframeContainer.style.display = 'block';
            fundoEscuro.style.display = 'block';
        });

        fundoEscuro.addEventListener('click', function() {
            iframeContainer.style.display = 'none';
            fundoEscuro.style.display = 'none';
        });

    </script>

        <!-- fim do script de iframe de justificação -->

</body>
</html>