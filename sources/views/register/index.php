<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Document</title>
</head>

<body>
<form method="POST" action="/register" enctype="multipart/form-data">
  <input type="email" name="email" id="email" required>
  <input type="text" name="profile_picture" id="profile_picture" required>
  <input type="password" name="password" id="password" required>
  <input type="password" name="password_check" id="password_check" required>
  <button type="submit">Inscription</button>
</form>

</body>

</html>