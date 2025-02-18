<form action="/reset-password" method="POST" class="form">
  <h1 class="form__title">Reset your password</h1>
  <input type="hidden" name="token" value="<?= $_GET['token'] ?>" />
  <div>
  <label class="form__label" for="password">Password</label>
    <input
      type="password"
      name="password"
    />
  </div>
  <div>
  <label class="form__label" for="password_check">Confirm Password</label>
    <input
      type="password"
      name="password_check"
    />
  </div>
  <button type="submit" class="button button--primary">Réinitialiser le mot de passe</button>
</form>
