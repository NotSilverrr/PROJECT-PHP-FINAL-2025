<h1 class="form__title"><?= isset($update) ? 'Update User' : 'Add User' ?></h1>

<form method="POST" action="<?= isset($update) ? '/admin/user/update' : '/admin/user/add' ?>" class="form" enctype="multipart/form-data">
    <?php if (isset($_SESSION['user_update'])): ?>
        <input type="hidden" name="id" value="<?= $_SESSION['user_update']->id ?>" />
    <?php endif; ?>        
    <div>
        <label class="form__label">Email</label>
        <input type="email" name="email" placeholder="Email" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->email : '' ?>" required />
    </div>
    <div>
        <label class="form__label">First Name</label>
        <input type="text" name="first_name" placeholder="First Name" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->first_name : '' ?>" required />
    </div>
    <div>
        <label class="form__label">Last Name</label>
        <input type="text" name="last_name" placeholder="Last Name" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->last_name : '' ?>" required />
    </div>
    <div>
        <label class="form__label"><?= isset($_SESSION['user_update']) ? 'New password' : 'Password' ?></label>
        <input type="password" name="password" placeholder="" <?= !isset($_SESSION['user_update']) ? 'required' : '' ?> />
    </div>
    <div>
        <label class="checkbox">
            <input type="checkbox" name="is_admin" <?= isset($_SESSION['user_update']) && $_SESSION['user_update']->isadmin ? 'checked' : '' ?> />
            <span>Statut Admin</span>
        </label>
    </div>
    <div>
        <label>Profile Picture</label>
        <input type="file" name="profile_picture" accept="image/*" <?= !isset($_SESSION['user_update']) ? 'required' : '' ?> />
    </div>
    <button type="submit" class="button button--primary"><?= isset($_SESSION['user_update']) ? 'Update User' : 'Add User' ?></button>
    <a href="/admin/user" class="form__a">Back to Users List</a>
</form>
<?php
unset($_SESSION['user_update']);
?>