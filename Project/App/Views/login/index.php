<?php 
$title = "Login"
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="./dist/framework-esgi.css" />
    <script src="./dist/framework-esgi.js"></script>
  </head>

  <body class="">
    <div class="form__page">
      <form method="POST" action="/login" class="form">
        <h1 class="form__title">Login</h1>
        <div>
          <label class="form__label"for="email">Email</label>
          <input
            type="email"
            name="email"
            placeholder="exemple@ex.com"
          />
        </div>
        <div>
        <label class="form__label"for="password">Mot de passe</label>
          <input
            type="password"
            name="password"
            placeholder="1234"
          />
        </div>
        <div class="options">
          <label class="checkbox">
            <input type="checkbox" name="remember_me">
            <span>Remember me</span>
          </label>
          <a href="/password-reset" class="forgot-password">Forgot password</a>
        </div>
        <button type="submit" class="login-button">Login</button>
        <a href="/register" class="create-account">Create Account</a>
      </form>
    </div>
  </div>

</body>

</html>
