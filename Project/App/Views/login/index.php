<?php 
$title = "Login"
?>

<div class="">
<h1 class="text-primary-500">PhotoShare</h1>
<form method="POST" action="/login" class="form">
  <h1 class="form__title">Login</h1>
  <div>
    <label class="form__label" for="email">Email</label>
    <input
      type="email"
      name="email"
      placeholder="exemple@ex.com"
      value="<?= $_SESSION['login_email'] ?? '' ?>"
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
</div>

</body>

</html>
<?php
unset($_SESSION['login_email']);
?>
