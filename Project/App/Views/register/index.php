<?php 
$title = "Register";
?>
<form method="POST" action="" enctype="multipart/form-data" class="form">
  <h1 class="form__title">Register</h1>
  
  <div>
    <label class="form__label" for="email">Email</label>
    <input type="email" name="email" id="email" value="<?= $_SESSION['create_user']->email ?? '' ?>" placeholder="exemple@ex.com" required>
  </div>
  
  <div>
    <label class="form__label" for="first_name">Firstname</label>
    <input type="text" name="first_name" id="first_name" value="<?= $_SESSION['create_user']->first_name ?? '' ?>" placeholder="Enter your first name" required>
  </div>
  
  <div>
    <label class="form__label" for="last_name">Lastname</label>
    <input type="text" name="last_name" id="last_name" value="<?= $_SESSION['create_user']->last_name ?? '' ?>" placeholder="Enter your last name" required>
  </div>
  
  <div>
    <label class="form__label" for="password">Password</label>
    <input type="password" name="password" id="password" placeholder="Enter your password (min. 6 characters)" required>
  </div>
  
  <div>
    <label class="form__label" for="password_check">Confirm password</label>
    <input type="password" name="password_check" id="password_check" placeholder="Confirm your password" required>
  </div>

  <div>
    <label class="form__label" for="profile_picture">Profile picture</label>
    <input class="file" type="file" name="profile_picture" id="profile_picture" accept="image/jpeg,image/png" required>
  </div>

  <button type="submit" class="button button--primary">Register</button>
  <a href="/login" class="form__a">Already have an account? Login</a>
</form>

<?php
unset($_SESSION['create_user']);
?>