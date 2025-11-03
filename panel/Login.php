<?php
	session_start();
	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Utils.php';
	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'User.php';
	require dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR.'classes'.DIRECTORY_SEPARATOR.'Config.php';	
	define('URL_HOME', $Config['URL']);

	if(isset($_POST) && !empty($_POST)){
		$login = isset($_POST['login']) ? Utils::soNumeros($_POST['login']) : null;
		$senha = isset($_POST['senha']) ? $_POST['senha'] : null;

		if($login!=null && $senha!=null){
			if(User::login(__FILE__, $login, $senha)){
				echo '<meta http-equiv="refresh" content="0; url=panel.php" />';
				exit;
			}
		}
	}

if(isset($_SESSION['SM_Secret'])){
	echo '<meta http-equiv="refresh" content="0; url=index.php" />';
}else{
?>
<!DOCTYPE html>
<HTML lang="pt-br">
 <HEAD>
	<meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="./../assets/img/favicon.ico" type="image/x-icon" />
	<title>Administração</title>
<?php
	require_once('header.php');
?>
 </HEAD>
 <BODY bgcolor="#d2a44d" class="login">
	<MAIN id="main" class="wrapper wrapper-login">
		<section id="area-login" class="container container-login animated fadeIn" style="width: 555px;">
			<div class="login-form">
				<form class="row" method="post" action="?" id="form_login" name="form_login">
					<div class="col-5">
						<a target="_self" href="./Login.php"><img class="logo" width="175" height="auto" src="<?= URL_HOME; ?>assets/img/MyBusinessSalesManager.png" /></a>
					</div>
					<div class="col-7">
						<div class="form-group form-floating-label">
							<input type="text" class="form-control input-border-bottom login-input" id="login" name="login" autocomplete="username" autofocus required />
							<label for="login" class="placeholder">CPF / CNPJ</label>
						</div>
						<div class="form-group form-floating-label">
							<input id="senha" name="senha" type="password" class="form-control input-border-bottom login-input" autocomplete="current-password" required>
							<label for="senha" class="placeholder">Senha</label>
							<div class="show-password">
								<i class="icon-eye"></i>
							</div>
						</div>

						<div class="col-12 form-action">
							<input type="submit" class="btn btn-success btn-inline" value="Entrar" />
							<input type="button" hidden id="show-signup" class="btn btn-inline btn-primary" value="Cadastrar" />
						</div>
					</div>

					<div class="col-12 d-block text-center form-sub form-message border-bottom border-top mt-3">
						<?= isset($_COOKIE['erro'])&&$_COOKIE['erro']!='' ? $_COOKIE['erro'] : ''; ?>
					</div>
				</form>
			</div>
		</section>

		<section id="area-register" class="container container-signup animated fadeIn" style="display: none;padding-top: 20px;">
			<div class="text-center" style="margin-bottom: 1.5rem;">
				<img class="logo" width="200" height="auto" src="<?= URL_HOME; ?>assets/img/MyBusinessSalesManager.png" />
				<h3 class="text-center">Cadastro</h3>
			</div>

			<div class="login-form">
				<form method="post" action="?" id="form_cadastro" name="form_cadastro"></form>
			</div>
		</section>
	<?php
		require_once('footer.php');
	?>
	<script>
	 $(document).ready(function () {
        $("#login").inputmask({
          mask: ["999.999.999-99", "99.999.999/9999-99"],
          keepStatic: true,
        });
	});
	</script>
	</MAIN>
  </BODY>
</HTML>
<?php
}
?>