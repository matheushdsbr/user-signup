<?php
session_start();
ob_start();

$errors = array();

$nome = "";
$email = "";
$usuario = "";
$bordaErroNome = "";
$bordaErroEmail = "";
$bordaErroUsuario = "";
$bordaErroSenha = "";

$btnCadUsuario = filter_input(INPUT_POST, 'btnCadUsuario', FILTER_SANITIZE_STRING);
if($btnCadUsuario){
	include_once 'conexao.php';

	$nome = $_POST['nome'];
	$email = $_POST['email'];
	$usuario = $_POST['usuario'];
	$senha = $_POST['senha'];

	$dados_rc = filter_input_array(INPUT_POST, FILTER_DEFAULT);

	$erro = false;

	$dados_st = array_map('strip_tags', $dados_rc);
	$dados = array_map('trim', $dados_st);

	if(in_array('',$dados)){
		$erro = true;
		$_SESSION['msg'] = '<p class="text-danger">Necessário preencher todos os campos</p>';

		if (empty($nome)) {
			$bordaErroNome = 'border: 2px solid red;';
			$errors['nome'] = '<p class="text-danger">*Preencha o campo Nome</p>';
		}

		//if (!filter_var($email, FILTER_VALIDATE_EMAIL) {
		//	$errors['email'] = "E-mail Inválido";
		//	$bordaErroEmail = 'border: 2px solid red;';
		//}

		if (empty($email)) {
			$bordaErroEmail = 'border: 2px solid red;';
			$errors['email'] = '<p class="text-danger">*Preencha o campo E-mail</p>';
		}
		if (empty($usuario)) {
			$bordaErroUsuario = 'border: 2px solid red;';
			$errors['usuario'] = '<p class="text-danger">*Preencha o campo Usuário</p>';
		}
		if (empty($senha)) {
			$bordaErroSenha = 'border: 2px solid red;';
			$errors['senha'] = '<p class="text-danger">*Preencha o campo Senha</p>';
		}
	}

	else{
		$result_usuario = "SELECT id FROM usuarios WHERE usuario='". $dados['usuario'] ."'";
		$resultado_usuario = mysqli_query($conn, $result_usuario);
		if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
			$erro = true;
			$_SESSION['msg'] = '<p class="text-danger">Este usuário já está sendo utilizado</p>';

			$bordaErroUsuario = 'border: 2px solid red;';
		}

		$result_usuario = "SELECT id FROM usuarios WHERE email='". $dados['email'] ."'";
		$resultado_usuario = mysqli_query($conn, $result_usuario);
		if(($resultado_usuario) AND ($resultado_usuario->num_rows != 0)){
			$erro = true;
			$_SESSION['msg'] = '<p class="text-danger">Este e-mail já está cadastrado</p>';

			$bordaErroEmail = 'border: 2px solid red;';
		}
	}


	//var_dump($dados);
	if(!$erro){
		//var_dump($dados);
		$dados['senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);

		$result_usuario = "INSERT INTO usuarios (nome, email, usuario, senha) VALUES (
						'" .$dados['nome']. "',
						'" .$dados['email']. "',
						'" .$dados['usuario']. "',
						'" .$dados['senha']. "'
						)";
		$resultado_usario = mysqli_query($conn, $result_usuario);
		if(mysqli_insert_id($conn)){
			$_SESSION['msgcad'] = '<p class="text-success">Usuário cadastrado com sucesso</p>';
			header("Location: login.php");
		}else{
			$_SESSION['msg'] = '<p class="text-danger">Erro ao cadastrar o usuário</p>';
		}
	}

}
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="style.css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
		<title>Celke - Cadastrar</title>
	</head>
	<body class="mx-2 mt-2">
		<h2>Cadastro</h2>
		<?php
			if(isset($_SESSION['msg'])){
				echo $_SESSION['msg'];
				unset($_SESSION['msg']);
			}
		?>

		<?php if(count($errors) > 0): ?>
				<?php foreach($errors as $error): ?>
					<?php echo $error ?>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<form method="POST" action="">
			<label>Nome</label>
			<input type="text" style="<?php echo $bordaErroNome; ?>" name="nome" value="<?php echo $nome; ?>" placeholder="Digite o nome e o sobrenome" ><br><br>

			<label>E-mail</label>
			<input type="text" style="<?php echo $bordaErroEmail; ?>" name="email" value="<?php echo $email; ?>" placeholder="Digite o seu e-mail" ><br><br>

			<label>Usuário</label>
			<input type="text" style="<?php echo $bordaErroUsuario; ?>" name="usuario" value="<?php echo $usuario; ?>" placeholder="Digite o usuário" ><br><br>

			<label>Senha</label>
			<input type="password" style="<?php echo $bordaErroSenha; ?>" name="senha" placeholder="Digite a senha" ><br><br>

			<input type="submit" name="btnCadUsuario" value="Cadastrar"><br><br>

			<p>Lembrou? <a href="login.php">Clique aqui</a> para logar</p>

		</form>

		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
	</body>
</html>