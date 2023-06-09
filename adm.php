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

	
	$query_conquista = "SELECT * FROM conquistas";
	$result_query_conquista = mysqli_query($conn, $query_conquista);

    if(isset($_POST['acao_cadastrar'])){

        $titulo = $_POST['titulo'];
        $descricao = $_POST['descricao'];
        $tammaximo = $_POST['tammaximo'];
        $tipoconquista = $_POST['tipoconquista'];
        $tooltip = $_POST['tooltip'];
		$select_user = $_POST['usuarios'];
		
		if($select_user === 'valor1'){

			$query_inserir = "INSERT INTO conquistas(nomeConquista, descricaoConquista, tamanhoMaximo, tipoConquista, tooltips, atribuidoPara, conquistaSistema) VALUES ('$titulo','$descricao','$tammaximo','$tipoconquista', '$tooltip', 0, 1)";
			$result_query_inserir = mysqli_query($conn, $query_inserir);

			?> <script>alert("Cadastrado com sucesso!")</script><?php

		} else{
			$query_inserir = "INSERT INTO conquistas(nomeConquista, descricaoConquista, tamanhoMaximo, tipoConquista, tooltips, atribuidoPara, conquistaSistema) VALUES ('$titulo','$descricao','$tammaximo','$tipoconquista', '$tooltip', '$select_user', 0)";
			$result_query_inserir = mysqli_query($conn, $query_inserir);

			?> <script>alert("Cadastrado com sucesso!")</script><?php
		}
    }

    /* Código para selecionar usuario*/

        $selecionar_users = "SELECT id, nome FROM cadastro";
        $result_selecionar_users = mysqli_query($conn, $selecionar_users);


		$id = "";
    /* Código para pesquisar e editar Conquistas */

        if(isset($_POST['acao_editar'])){

				$id = $_POST['idconquista'];
                $titulo = $_POST['novo_titulo'];
                $descricao = $_POST['nova_descricao'];
                $tooltip = $_POST['tooltip'];

            if(isset($_POST['pesquisar'])){
                
                $query_nome_conquista = "AND nomeConquista LIKE '$titulo'";
                $query_descricao_conquista = "AND descricaoConquista LIKE '$descricao'";
                $query_id_conquista = "AND idConquista = '$id'";

                    
            } elseif(isset($_POST['editar'])){

                $query_alterar = "UPDATE conquistas SET nomeConquista = '$titulo', descricaoConquista = '$descricao', tooltips = '$tooltip' WHERE idConquista = '$id'";
                $result_query_alterar = mysqli_query($conn, $query_alterar);

				?> <script>alert("Alterado com sucesso!")</script><?php
                
            }

			
        }

		$query_pesquisar = "SELECT * FROM conquistas WHERE idConquista = '$id' LIMIT 1";
        	$result_query_pesquisar = mysqli_query($conn, $query_pesquisar);
		
        

    /* Fim do Código para pesquisar e editar Conquistas */


	/* Código para Exclusão */

	if(isset($_POST['acao_excluir'])){


			$id_exclusao = $_POST['idconquistaexclusao'];

			$query_excluir = "DELETE FROM conquistas WHERE idConquista = '$id_exclusao'";
			$result_excluir = mysqli_query($conn, $query_excluir);
            
            ?> <script>alert("Excluído com sucesso!")</script>
		

		<?php 

	}

	/* Fim do Código para Exclusão */

?>

<!DOCTYPE html>
<html>
<head>
	<title>Formulário de Conquistas</title>
    <link rel="shortcut icon" href="favicon.png" type="image/x-icon" />
    <style>

        *{
            margin: 0;
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

		form {
			background-color: #F9F9F9;
			border: 1px solid #ccc;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
			border-radius: 10px;
			height: 500px;
			margin-left: 5%;
		}

		label {
			display: block;
			margin-bottom: 5px;
			font-weight: bold;
			color: #333;
		}

		input[type="text"],
		textarea {
			display: inline-block;
			width: 95%;
			padding: 10px;
			border: 1px solid #ccc;
			border-radius: 5px;
			margin-bottom: 10px;
			background-color: #f2f2f2; /* cinza claro */
		}

		button[type="submit"] {
			background-color: #4CAF50; /* verde */
			color: #fff;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-weight: bold;
			text-shadow: 1px 1px #333;
		}

		button[type="submit"]:hover {
			background-color: #2E8B57; /* verde escuro */
		}

		input[type="text"] {
			border: 1px solid black; /* amarelo ouro */
			background-color: #fff; /* branco */
		}

		textarea {
			border: 1px solid black; /* amarelo ouro */
			background-color: #fff; /* branco */
		}

		button[type="submit"] {
			background-color: #ffd700; /* amarelo ouro */
			color: #333;
			padding: 10px 20px;
			border: none;
			border-radius: 5px;
			cursor: pointer;
			font-weight: bold;
			text-shadow: 1px 1px #fff;
		}

		button[type="submit"]:hover {
			background-color: #e6be00; /* amarelo escuro */
			color: #fff;
		}

		#mae{
			display: flex;
			margin-left: -2.5%;
		}

		.ver_justificativas{
			text-align: center;
			text-decoration: none;
			color: yellow;
			border: 1px solid green;
			padding: 0.3%;
			border-radius: 20px;
			background-color: green;
			margin-left: 45%;
			
		}

		#overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			z-index: 9999;
		}

		#iframe {
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 10000;
		}


	</style>
</head>

<body>

        
	<h1>Conquistas - ADM</h1>

	<br/>

	<a class="ver_justificativas" href="adm_justificar.php" onclick="mostrarIframe(event)">Justificativas</a>

	<div id="overlay" style="display: none;"></div>

	<iframe id="iframe" src="justificar_adm.php" style="display: none; width: 750px; height: 700px;"></iframe>


    <form style="width: 82px; border: none; padding: 0; height: 10px;" action="" method="POST">
        <input type="hidden" name="acao_deslogar" value="acao">
        <input style="width: 80px; padding: 0;" type="submit" value="Sair" name="unlog">
    </form>

	













	

	<div id="mae">

	<!-- Formulário para cadastrar uma nova conquista -->
	<form method="POST" style="width: 25%;"  action="adm.php">

		<h2 style="text-align: center;">Nova Conquista</h2>

		<br/>

        <input type="hidden" name="acao_cadastrar" value="acao_cadastrar">

		<label for="titulo">Título:</label>
		<input type="text" name="titulo" id="titulo" required>

		<label for="descricao">Descrição:</label>
		<textarea name="descricao" id="descricao" required></textarea>

        <label for="descricao">ToolTip:</label>
		<textarea name="tooltip" id="tooltip" required></textarea>

        <label for="titulo">Tamanho Máximo:</label>
		<input type="number"  style="width: 110px;" name="tammaximo" id="tammaximo" required>

		<br/><br/>

        <label for="titulo">Tipo da Conquista:</label>
		<select name="tipoconquista" id="tipoconquista" required>
			<option value="0">0 - Imóvel</option>
			<option value="1">1 - Contrato</option>
			<option value="2">2 - Negócio</option>
			<option value="3">3 - Mensagem</option>
			<option value="4">4 - Imagens</option>
		</select>

		<br/><br/>

        <label for="titulo">Atribuir para:</label>

		<select name="usuarios">

			<option value="valor1" selected>Geral</option>

			<?php while($linha_users = mysqli_fetch_assoc($result_selecionar_users)) { 
				
				if($linha_users['nome'] != "adm"){ ?>
					<option value="<?php echo $linha_users['id']; ?>"><?php echo $linha_users['nome']; ?></option>
			<?php } }?>
			
		</select>

		<br/><br/>

		<button type="submit">Cadastrar</button>
	</form>

















	
	<!-- Formulário para editar uma conquista existente -->
	<form method="POST" style="width: 25%;" action="">

		<h2 style="text-align: center;">Pesquisar/Editar Conquista</h2>

		<br/>

        <input type="hidden" name="acao_editar" value="acao_editar">

		<label for="id">Conquista:</label>
		<select name="idconquista" id="idconquista" required>
		<?php 
		
			while($linha_query_conquista = mysqli_fetch_assoc($result_query_conquista)){

				$id_conquista = $linha_query_conquista['idConquista'];

				echo "<option value='$id_conquista'>". $linha_query_conquista['nomeConquista']. "</option>";
			}?>
		</select>

		<br/><br/>

        <?php mysqli_data_seek($result_query_pesquisar, 0); ?>

		<label for="novo_titulo">Novo Título:</label>
		<input type="text" name="novo_titulo" value="<?php while($linha_query_pesquisar = mysqli_fetch_assoc($result_query_pesquisar)){
            echo $linha_query_pesquisar['nomeConquista']; }?>" id="novo_titulo" >

        <?php mysqli_data_seek($result_query_pesquisar, 0); ?>

		<label for="nova_descricao">Nova Descrição:</label>
		<input type="text" name="nova_descricao" id="nova_descricao" value="<?php while($linha_query_pesquisar = mysqli_fetch_assoc($result_query_pesquisar)){
            echo $linha_query_pesquisar['descricaoConquista']; }?>">

        <?php mysqli_data_seek($result_query_pesquisar, 0); ?>


        <label for="descricao">ToolTip:</label>
		<input type="text" name="tooltip" id="tooltip" value="<?php while($linha_query_pesquisar = mysqli_fetch_assoc($result_query_pesquisar)){
            echo $linha_query_pesquisar['tooltips']; }?>">

        <button type="submit" name="pesquisar">Pesquisar</button>
		<button type="submit" name="editar">Salvar</button>
	</form>







	<?php mysqli_data_seek($result_query_conquista, 0); ?>







    


	<!-- Formulário para excluir uma conquista existente -->
	<form method="POST" style="width: 25%;" action="adm.php">

		<h2 style="text-align: center;">Excluir Conquista</h2>

		<br/>

		<input type="hidden" name="acao_excluir" value="acao_excluir">

		<label for="id">Conquista:</label>
		<select name="idconquistaexclusao" id="idconquistaexclusao" required>
		<?php while($linha_query_conquista = mysqli_fetch_assoc($result_query_conquista)){

			$id_conquista = $linha_query_conquista['idConquista'];

            echo "<option value='$id_conquista'>". $linha_query_conquista['nomeConquista']. "</option>";
			}?>
		</select>
			<br/><br/>
		<button type="submit">Excluir</button>
	</form>

		</div>

	<script>

		function mostrarIframe(event) {
				
			event.preventDefault();
				
			// Exibir o overlay escuro
			document.getElementById("overlay").style.display = "block";
				
			// Centralizar o iframe
			var iframe = document.getElementById("iframe");
			iframe.style.display = "block";
			iframe.style.position = "fixed";
			iframe.style.top = "50%";
			iframe.style.left = "50%";
			iframe.style.transform = "translate(-50%, -50%)";

			let fundoEscuro = document.getElementById("overlay");

			fundoEscuro.addEventListener('click', function() {
				iframe.style.display = 'none';
				fundoEscuro.style.display = 'none';
			});
		}

	</script>

</body>
</html>
