<?php 
$title = "Login"
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="/path/to/main.css">
</head>

<body class="login-page">
<?if (isset($success)): ?>
  <div class="error error--info mt-4">
  <svg class="error__icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
  <p class="error__text"><?=$success?></p>
  <button class="error__close">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
  </button>
</div>
<?php endif; ?>
  <div class="login-container">
    <div class="login-card">
      <h1 class="login-title">Login</h1>
      <form method="POST" action="/login" class="login-form">
        <div class="input-group">
          <input type="email" name="email" placeholder="Username" class="input-field">
        </div>
        <div class="input-group">
          <input type="password" name="password" placeholder="Password" class="input-field">
        </div>
        <div class="options">
          <label class="checkbox">
            <input type="checkbox" name="remember_me">
            <span>Remember me</span>
          </label>
          <a href="/password-reset" class="forgot-password">Forgot password</a>
        </div>
        <button type="submit" class="login-button">Login</button>
        <a href="/create-account" class="create-account">Create Account</a>
      </form>
    </div>
  </div>

</body>

</html>