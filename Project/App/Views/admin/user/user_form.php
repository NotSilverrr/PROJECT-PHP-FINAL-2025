<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($update) ? 'Update User' : 'Add User' ?></h1>
        <div class="error">
        <?php 
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
        ?>
        </div>
        <form method="POST" action="<?= isset($update) ? '/admin/user/update' : '/admin/user/add' ?>" class="form" enctype="multipart/form-data">
            <?php if (isset($_SESSION['user_update'])): ?>
                <input type="hidden" name="id" value="<?= $_SESSION['user_update']->id ?>" />
            <?php endif; ?>        
            <div class="input-group">
                <label class="form-label">Email</label>
                <input type="email" name="email" placeholder="Email" class="input-field" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->email : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label">First Name</label>
                <input type="text" name="first_name" placeholder="First Name" class="input-field" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->first_name : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label">Last Name</label>
                <input type="text" name="last_name" placeholder="Last Name" class="input-field" value="<?= isset($_SESSION['user_update']) ? $_SESSION['user_update']->last_name : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label"><?= isset($_SESSION['user_update']) ? 'Nouveau mot de passe (laisser vide pour ne pas modifier)' : 'Mot de passe' ?></label>
                <input type="password" name="password" placeholder="" class="input-field" <?= !isset($_SESSION['user_update']) ? 'required' : '' ?> />
            </div>
            <div class="input-group">
                <label class="checkbox">
                    <input type="checkbox" name="is_admin" <?= isset($_SESSION['user_update']) && $_SESSION['user_update']->isadmin ? 'checked' : '' ?> />
                    <span>Statut Admin</span>
                </label>
            </div>
            <div class="input-group">
                <label>Profile Picture</label>
                <?php if(isset($_SESSION['user_update']) && $_SESSION['user_update']->profile_picture): ?>
                    <img src="<?= $_SESSION['user_update']->profile_picture ?>" alt="Current profile picture" style="max-width: 100px; margin-bottom: 10px;">
                <?php endif; ?>
                <input type="file" name="profile_picture" accept="image/*" class="input-field" <?= !isset($_SESSION['user_update']) ? 'required' : '' ?> />
            </div>
            <button type="submit" class="form-button"><?= isset($_SESSION['user_update']) ? 'Update User' : 'Add User' ?></button>
            <a href="/admin/user" class="create-account">Back to Users List</a>
        </form>
    </div>
</div>
<?php
unset($_SESSION['user_update']);
?>