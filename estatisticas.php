<?php 

    include('conn.php');

    session_start();

    include('verificar_login.php');

    $user = $_SESSION['usuario'];

    if($_SESSION['logado'] == false){
        header('Location: login.php');
    }

    $selecionar_cadastro = "SELECT * FROM cadastro WHERE nome = '$user'";
    $result_selecionar_cadastro = mysqli_query($conn, $selecionar_cadastro);
    $linha_selecionar_cadastro = mysqli_fetch_assoc($result_selecionar_cadastro);

    $id_user_logado = $linha_selecionar_cadastro['id'];

    $selecionar_conquistas = "SELECT * FROM conquistas WHERE atribuidoPara = 0 OR atribuidoPara = '$id_user_logado'";
    $result_selecionar_conquistas = mysqli_query($conn, $selecionar_conquistas);
    

    $selecionar_historico = "SELECT * FROM historico WHERE idUser = '$id_user_logado' LIMIT 1";
    $result_selecionar_historico = mysqli_query($conn, $selecionar_historico);

    while($linha_selecionar_conquistas = mysqli_fetch_assoc($result_selecionar_conquistas)){
        while($linha_selecionar_historico = mysqli_fetch_assoc($result_selecionar_historico)){
            $progresso_qtdeImoveis = $linha_selecionar_historico['qtdeImoveis'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100;
            $progresso_qtdeContratos = $linha_selecionar_historico['qtdeContratos'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100;
            $progresso_qtdeNegocios = $linha_selecionar_historico['qtdeNegocios'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100;
            $progresso_qtdeMensagens = $linha_selecionar_historico['qtdeMensagens'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100;
            $progresso_imagensTotais = $linha_selecionar_historico['imagensTotais'] / $linha_selecionar_conquistas['tamanhoMaximo'] * 100;
        }

        $i = 0;
        $c = 0;
        $m = 0;
        $n = 0;
        $iT = 0;

        if($progresso_qtdeImoveis == 100){
            $i++;
        }

        if($progresso_qtdeContratos == 100){
            $c++;
        }

        if($progresso_qtdeMensagens == 100){
            $m++;
        }

        if($progresso_qtdeNegocios == 100){
            $n++;
        }

        if($progresso_imagensTotais == 100){
            $iT++;
        }

        
}

?>


<html>
<head>
	<title>Estatísticas de Conquistas</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
	<link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

        *{
            text-decoration: none;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            font-family: 'Ubuntu', sans-serif;
        }

        h1 {
            text-align: center;
            padding: 20px;
            background-color: #2ca62f;
            color: #fff;
        }

        .conquistas {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0px 0px 10px #ccc;
            margin-top: 50px;
            margin-bottom: 50px;
        }

        h2 {
            margin-bottom: 10px;
            color: black;
        }

        p {
            line-height: 1.5;
            color: #333;
        }

        .voltar{
            text-decoration: none;
            color: white;
            font-size: 16px;
            margin-left: 9%;
        }

        a{
            color: white;
            background-color: green;
            padding: 0.5%;
            border-radius: 10px;
        }

        a:hover{
            color: white;
            background-color: #2ca62f;
        }


    </style>
</head>


<body>
	<h1>Estatísticas de Conquistas</h1>

    <a class="voltar" href="index.php">Voltar</a>

	<div class="conquistas">
		<h2>Total de Conquistas de Imóveis: <?php echo $i; ?></h2>
		<p>Uma venda concreta exige boas captações. Nossas estatísticas apontam que uma boa média para começar o site é uma média de <b>50 imóveis</b>.</p>
        <p style="font-size: 12px;">Clique <span style="color: blue; cursor: pointer;">aqui</span> para ler um artigo em nosso Blog sobre o cadastro de Imóveis!</p>
        <a href="">Cadastrar Novo Imóvel</a>
	</div>

    <div class="conquistas">
		<h2>Total de Conquistas de Contratos: <?php echo $c; ?></h2>
		<p>Sabemos que montar um Contrato pode ser uma tarefa complicada. Que tal uma <b>ajuda</b>?</p>
        <a href=""><i class="fa fa-download" aria-hidden="true"></i> Baixar Modelo de Contrato</a>
	</div>

    <div class="conquistas">
		<h2>Total de Conquistas de Mensagens: <?php echo $m; ?></h2>
		<p>Você tem <b>5 novas Mensagens</b>. Corra para conversar com seus Clientes e garantir mais vendas!</p>
        <a href="">Conversar com Clientes</a>
	</div>

    <div class="conquistas">
		<h2>Total de Conquistas de Negócios: <?php echo $n; ?></h2>
		<p>Deixe o papel de lado. Cadastre seus Negócios no CRM ImobiBrasil e veja o Funil trabalhar!</p>
        <a href="">Ver Negócios</a>
	</div>

    <div class="conquistas">
		<h2>Total de Conquistas de Imagens: <?php echo $iT; ?></h2>
		<p>Boas Fotos são <b style="font-size: 18px;"><i>fundamentais</i></b> para que seus clientes resolvam fechar um negócio com você. Invista nisso!</p>
        <a href="https://www.imobibrasil.com.br/blog/qual-a-importancia-de-boas-fotos-para-anuncios-de-imoveis/" target="_blank">Dicas de boas fotos</a>
	</div>

</body>
</html>
