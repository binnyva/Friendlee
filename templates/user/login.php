<div class="container">

<form action="login.php" method="post" class="form-signin" role="form">
<h2 class="form-signin-heading">Login</h2>

<input type="text" id="username" class="form-control" placeholder="Username" name="username" required autofocus>
<input type="password" id="password" class="form-control" placeholder="Password" name="password" required>
<a href="forgot_password.php" class="pull-right">Forgot Password?</a>
<label class="checkbox">
  <input type="checkbox" value="1" name="remember" checked> Remember me
</label>
<button class="btn btn-lg btn-primary btn-block" type="submit" name="action" value="Login">Sign in</button><br />

<h3>Alternatively...</h3>

<div><?php echo $login_button; ?></div>

<br /><br />
<p>Don't have an account? <a href="signup.php">Sign up</a>!</p>
</form>


</div>