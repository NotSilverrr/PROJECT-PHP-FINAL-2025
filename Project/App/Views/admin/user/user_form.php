<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title">Update User</h1>
        <form method="POST" action="/admin/user/update" class="form" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $user['id'] ?>" />
            <div class="input-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="input-field" value="<?= $user['email'] ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label">Nouveau mot de passe (laisser vide pour ne pas modifier)</label>
                <input type="password" name="password" placeholder="" class="input-field" />
            </div>
            <div class="input-group">
                <label class="checkbox">
                    <input type="checkbox" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?> />
                    <span>Statut Admin</span>
                </label>
            </div>
            <div class="input-group">
                <label>Profile Picture</label>
                <input type="text" name="profile_picture" placeholder="Photo de profil" class="input-field" value="<?= $user['profile_picture'] ?>" required />
            </div>
            <button type="submit" class="form-button">Update User</button>
            <a href="/admin/user" class="create-account">Back to Users List</a>
        </form>
    </div>
</div>