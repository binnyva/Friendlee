<div class="container">
	<form method="POST" action="" accept-charset="UTF-8" role="form" class="form-signin">
	<h3 class="form-signin-heading">Register for an account:</h3>

	<fieldset>
		<input class="form-control top" placeholder="Username" name="username" type="text" value="<?php echo i($QUERY, 'username'); ?>" required autofocus>
		<input class="form-control middle" placeholder="Password" name="password" type="password" value="" required>
		<input class="form-control middle" placeholder="Confirm Password" name="confirm_password" type="password" value="" required>

		<input class="form-control middle" placeholder="E-mail" name="email" type="text" value="<?php echo i($QUERY, 'email'); ?>"  required>
		<input class="form-control bottom" placeholder="Name" name="name" type="text" value="<?php echo i($QUERY, 'name'); ?>" >
		<input class="btn btn-lg btn-primary btn-block" type="submit" name="action" value="Register">

	  	<p class="text-center"><a href="login.php">Already have an account?</a></p>

		<p>We will not give away your email address to anyone. We just need it in case you forgot your password.</p>
	</fieldset>


	<h3>Alternatively...</h3>
	<div><?php echo $login_button; ?></div>

  	</form>
</div>
