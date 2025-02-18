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
  <div>
  <?php 
    if (isset($_SESSION['error'])) {
        echo $_SESSION['error'];
        unset($_SESSION['error']);
    }
  ?>
  </div>
      <form method="POST" action="/login" class="form">
        <h1 class="form__title">Login</h1>
        <div>
          <label class="form__label" for="email">Email</label>
          <input
            type="email"
            name="email"
            placeholder="exemple@ex.com"
          />
        </div>
        <div>
        <label class="form__label" for="password">Password</label>
          <input
            type="password"
            name="password"
            placeholder="1234"
          />
        </div>
        <div>
          <a href="/password-reset" class="form__a">I have forgot my password</a>
        </div>
        <button type="submit" class="button button--primary">Login</button>
        <a href="/register" class="form__a">Not register yet ? Click here</a>
      </form>

</body>

</html>
