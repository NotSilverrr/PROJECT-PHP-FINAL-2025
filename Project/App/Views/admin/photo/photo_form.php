<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($update) ? 'Update Photo' : 'Add Photo' ?></h1>
        <div class="error">
        <?php 
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            }
        ?>
        </div>
        <form method="POST" action="<?= isset($update) ? '/admin/photo/update' : '/admin/photo/add' ?>" class="form" enctype="multipart/form-data">
        <?php if (isset($_SESSION['photo_update'])): ?>
            <input type="hidden" name="id" value="<?= $_SESSION['photo_update']->id ?>" />
            <?php endif; ?>    
            <div class="input-group">
                <label class="form-label">Photo</label>
                <input type="file" name="photo" accept="image/*" class="input-field" <?= !isset($_SESSION['photo_update']) ? 'required' : '' ?> />
            </div>
            <div class="input-group">
                <label class="form-label">Group</label>
                <select name="group_id" class="input-field" required>
                    <option value="">Select a group</option>
                    <?php foreach ($group_list as $group): ?>
                        <option value="<?= $group->id ?>" <?= isset($_SESSION['photo_update']) && $_SESSION['photo_update']->group_id == $group->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group->name) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label class="form-label">User</label>
                <select name="user_id" class="input-field" required>
                    <option value="">Select a user</option>
                    <?php foreach ($user_list as $user): ?>
                        <option value="<?= $user->id ?>" <?= isset($_SESSION['photo_update']) && $_SESSION['photo_update']->user_id == $user->id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user->email) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="form-button"><?= isset($_SESSION['photo_update']) ? 'Update Photo' : 'Add Photo' ?></button>
            <a href="/admin/photo" class="create-account">Back to Photos List</a>
        </form>
    </div>
</div>
<?php
unset($_SESSION['photo_update']);
?>