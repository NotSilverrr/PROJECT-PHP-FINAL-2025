<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($user) ? 'Update User' : 'Add User' ?></h1>
        <form method="POST" action="<?= isset($user) ? '/admin/user/update' : '/admin/user/add' ?>" class="form" enctype="multipart/form-data">
            <?php if (isset($user)): ?>
            <input type="hidden" name="id" value="<?= $user['id'] ?>" />
            <?php endif; ?>
            <div class="input-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="input-field" value="<?= isset($user) ? $user['email'] : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label"><?= isset($user) ? 'Nouveau mot de passe (laisser vide pour ne pas modifier)' : 'Mot de passe' ?></label>
                <input type="password" name="password" placeholder="" class="input-field" <?= !isset($user) ? 'required' : '' ?> />
            </div>
            <div class="input-group">
                <label class="checkbox">
                    <input type="checkbox" name="is_admin" <?= isset($user) && $user['is_admin'] ? 'checked' : '' ?> />
                    <span>Statut Admin</span>
                </label>
            </div>
            <div class="input-group">
                <label>Profile Picture</label>
                <input type="text" name="profile_picture" placeholder="Photo de profil" class="input-field" value="<?= isset($user) ? $user['profile_picture'] : '' ?>" required />
            </div>
            <button type="submit" class="form-button"><?= isset($user) ? 'Update User' : 'Add User' ?></button>
            <a href="/admin/user" class="create-account">Back to Users List</a>
        </form>
    </div>
</div>