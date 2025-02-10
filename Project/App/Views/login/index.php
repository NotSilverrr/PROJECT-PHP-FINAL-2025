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
          <a href="/forgot-password" class="forgot-password">Forgot password</a>
        </div>
        <button type="submit" class="login-button">Login</button>
        <a href="/create-account" class="create-account">Create Account</a>
      </form>
    </div>
  </div>

</body>

</html>
