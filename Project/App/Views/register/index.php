<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
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

</body>

</html>