<form class="form" action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>" />
    <input type="hidden" name="group_id" value="<?= $group->id ?>" />
    <label for="file">Photo</label>
    <input type="file" name="photo" id="file" accept="image/png, image/jpeg, image/webp, image/gif" required>
    <button type="submit" class="button button--primary">Upload</button>
</form>