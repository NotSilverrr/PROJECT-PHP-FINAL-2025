<h1 class="form__title"><?= isset($photo) ? 'Update Photo' : 'Add Photo' ?></h1>

<form method="POST" action="<?= isset($update) ? '/admin/photo/update' : '/admin/photo/add' ?>" class="form form--full" enctype="multipart/form-data">
<?php if (isset($photo)): ?>
    <input type="hidden" name="id" value="<?= $photo['id'] ?>" />
    <?php endif; ?>
    <div class="">
        <label class="form__label">Photo</label>
        <?php if(isset($photo) && $photo['file']): ?>
            <img src="<?= $photo['file'] ?>" alt="Current photo" style="max-width: 200px; margin-bottom: 10px;">
        <?php endif; ?>
        <input type="file" name="photo" accept="image/*" class="" <?= !isset($photo) ? 'required' : '' ?> />
    </div>
    <div class="">
        <label class="form__label">Group</label>
        <select name="group_id" class="" required>
            <option value="">Select a group</option>
            <?php foreach ($group_list as $group): ?>
                <option value="<?= $group->id ?>" <?= isset($_SESSION['photo_update']) && $_SESSION['photo_update']->group_id == $group->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group->name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="">
        <label class="form__label">User</label>
        <select name="user_id" class="" required>
            <option value="">Select a user</option>
            <?php foreach ($user_list as $user): ?>
                <option value="<?= $user->id ?>" <?= isset($_SESSION['photo_update']) && $_SESSION['photo_update']->user_id == $user->id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($user->email) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="button button--primary"><?= isset($_SESSION['photo_update']) ? 'Update Photo' : 'Add Photo' ?></button>
    <a href="/admin/photo" class="form__a">Back to Photos List</a>
</form>
<?php
unset($_SESSION['photo_update']);
?>
