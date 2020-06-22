<?php
include_once 'conexao.php';

$msg = "";

if (isset($_POST['submit'])) {
	$email = mysqli_real_escape_string($conn, $_POST['email']);
	$query = "SELECT * FROM usuarios WHERE email='$email'";
	$run = mysqli_query($conn, $query);
	if(mysqli_num_rows($run)>0){
		$row = mysqli_fetch_array($run);
		$db_email = $row['email'];
		$db_id = $row['id'];
		$token = uniqid(md5(time())); //GERA UM TOKEN ALEATORIO
		$query = "INSERT INTO password_reset(id, email, token) VALUES (NULL,'$email', '$token')";

		if (mysqli_query($conn, $query)){
			$to = $db_email;
			$subject = "Password reset link";
			$message = "Click <a href='http://localhost/validar_cad_usuario/reset-pf.php?token=$token'>here</a> to reset your password.";
			$headers = "MIME-VERSION: 1.0" . "\r\n";
			$headers = "Content-type:text/html;chartset=UTF-8" . "\r\n";
			$headers = 'From: <mizugamer21@gmail.com>' . "\r\n"; //EMAIL QUE VAI ENVIAR A MENSAGEM
			//mail($to, $subject, $message, $headers);
			//o codigo acima está comentado porque está em um LOCALHOST, e um servidor local não pode mandar email com a função do php sem o smtp.
			$msg = "<div class='alert alert-success'>Seu link para redefinir sua senha foi enviado para seu email.</div>";
		}
	}else {
		$msg = "<div class='alert alert-danger'>E-mail de usuário não encontrado.</div>";
	}
}


?>
<!doctype html>
<html lang="pt-br">
  <head>
	<title>Recuperar Senha - Pessoa Física</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  </head>
  <body>
		<div class="container">
			<div class="row justify-content-center mt-5">
				<div class="col-5">

					<h2 class="text-align-center">Recuperar Senha</h2>

					<?php
						if(isset($msg)) {
							echo $msg;
						}
					?>

          <form action="reset-password-pf.php" method="POST">
						<p class="my-4">Informe seu email cadastrado e nós enviaremos um link para você resetar sua senha.</p>
            <input type="email" name="email" placeholder="Insira aqui seu endereço de E-mail" class="form-control"><br>
            <button type="submit" name="submit" class="btn btn-success col-4 offset-4">Enviar</button><br><br>
            <a href="login-pf.php">Voltar</a><br><br>
          </form>
				</div>
      </div>
		</div>
		<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>