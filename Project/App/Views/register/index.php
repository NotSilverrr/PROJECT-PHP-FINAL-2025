<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <title>Créer un compte</title>
  <link rel="stylesheet" href="./dist/framework-esgi.css" />
  <script src="./dist/framework-esgi.js"></script>
</head>

<body>
<?php 
    if (isset($errors) && !empty($errors)){
        echo $errors;
    }
?>
<form method="POST" action="" enctype="multipart/form-data">
  <input type="email" name="email" id="email" value="<?= $user['email'] ?? '' ?>" placeholder="Enter your email address" required>
  <input type="text" name="first_name" id="first_name"  value="<?= $user['first_name'] ?? '' ?>" placeholder="Enter your first name" required>
  <input type="text" name="last_name" id="last_name"  value="<?= $user['last_name'] ?? '' ?>" placeholder="Enter your last name" required>
  <input type="file" name="profile_picture" id="profile_picture"  accept="image/jpeg,image/png" required>
  <input type="password" name="password" id="password"  placeholder="Enter your password (min. 6 characters)" required>
  <input type="password" name="password_check" id="password_check"  placeholder="Confirm your password" required>
  <button type="submit">Inscription</button>
</form>

      <label class="form form__label"for="email">Nom</label>
      <input type="text" name="first_name" id="first_name" required>

      <label class="form form__label"for="email">Prénom</label>
      <input type="text" name="last_name" id="last_name" required>

      <label class="form form__label"for="email">Photo de profil</label>
      <input type="text" name="profile_picture" id="profile_picture" required>

      <label class="form form__label"for="email">Mot de passe</label>
      <input type="password" name="password" id="password" required>

      <label class="form form__label"for="email">Vérifier le mot de passe</label>
      <input type="password" name="password_check" id="password_check" required>

      <button type="submit" class="form form__button">S'inscrire</button>
    </form>
  </div>
</body>
</html>