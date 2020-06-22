<?php
  include_once 'conexao.php';
  $msg = "";

  if(isset($_GET['token'])){
    $token = mysqli_real_escape_string($conn,$_GET['token']);
    $query = "SELECT * FROM password_reset WHERE token='$token'";
    $run = mysqli_query($conn, $query);
    if(mysqli_num_rows($run)>0){
      $row = mysqli_fetch_array($run);
      $token = $row['token'];
      $email = $row['email'];
    }else {
      header("location: login-pf.php");
    }
  }

  if(isset($_POST['submit'])){
    $password = mysqli_real_escape_string($conn,$_POST['password']);
    $confirmpassword = mysqli_real_escape_string($conn,$_POST['confirmpassword']);
    $options = ['cost'=>11];
    $hashed = password_hash($password, PASSWORD_BCRYPT, $options);
    if($password != $confirmpassword) {
      $msg = "<div class='alert alert-danger'>Senhas não combinam</div>";
    }elseif(strlen($password)<6){
      $msg="<div class='alert alert-danger'>Sua senha precisa ter pelo menos 6 dígitos</div>";
    }else {
      $query = "UPDATE usuarios SET senha='$hashed' WHERE email='$email'";
      mysqli_query($conn, $query);
      $query = "DELETE FROM password_reset WHERE email='$email'";
      mysqli_query($conn, $query);
      $msg = "<div class='alert alert-success'>Senha redefinida com sucesso</div>";
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

					<h2 class="text-align-center mb-4">Recuperar Senha</h2>

					<?php
						if(isset($msg)) {
							echo $msg;
						}
					?>

          <form action="" method="POST">
          <label>Digite seu E-mail</label>
            <input type="text" readonly name="email" class="form-control" value="<?php echo $email; ?>"><br>
            <label>Nova senha:</label>
            <input type="password" name="password" placeholder="Digite sua nova senha" class="form-control"><br>
            <label>Nova senha:</label>
            <input type="password" name="confirmpassword" placeholder="Confirme sua nova senha" class="form-control"><br>
            <button type="submit" name="submit" class="btn btn-success col-4 offset-4">Redefinir senha</button><br><br>
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