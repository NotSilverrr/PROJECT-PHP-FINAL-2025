<form action="/reset-password" method="POST">
  <input type="hidden" name="token" value="<?= $_GET['token'] ?>" />
  <label for="password">Nouveau mot de passe :</label>
  <input type="password" name="password" required />
  <button type="submit">Réinitialiser le mot de passe</button>
</form>