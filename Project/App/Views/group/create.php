<h1 class="text-8">Create   a Group</h1>

<form class="form" action="/group" method="post" enctype="multipart/form-data">

  <input type="hidden" name="owner" value="<?= $_SESSION['user_id'] ?>" />
  <label for="name">Name</label>
  <input type="text" name="name" value="<?= $_SESSION['group_create']->name ?? '' ?>">
  <label for="profile_picture">Image</label>
  <input type="file" name="profile_picture" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif">
  <button type="submit" class="button button--primary">Create</button>
</form>
<?php
unset($_SESSION['group_create']);
?>