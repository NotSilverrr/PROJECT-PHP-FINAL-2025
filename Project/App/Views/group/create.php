<form action="/group" method="post" enctype="multipart/form-data">
  <label for="name">Name</label>
  <input type="text" name="name">
  <label for="profile_picture">Image</label>
  <input type="file" name="profile_picture" accept="image/png, image/jpeg, image/jpg, image/webp, image/gif">
  <button type="submit" class="button button--primary">Create</button>
</form>