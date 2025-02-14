<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($photo) ? 'Update Photo' : 'Add Photo' ?></h1>
        <form method="POST" action="<?= isset($photo) ? '/admin/photo/update' : '/admin/photo/add' ?>" class="form" enctype="multipart/form-data">
            <?php if (isset($photo)): ?>
            <input type="hidden" name="id" value="<?= $photo['id'] ?>" />
            <?php endif; ?>
            <div class="input-group">
                <label class="form-label">Photo</label>
                <?php if(isset($photo) && $photo['file']): ?>
                    <img src="<?= $photo['file'] ?>" alt="Current photo" style="max-width: 200px; margin-bottom: 10px;">
                <?php endif; ?>
                <input type="file" name="photo" accept="image/*" class="input-field" <?= !isset($photo) ? 'required' : '' ?> />
            </div>
            <div class="input-group">
                <label class="form-label">Group</label>
                <select name="group_id" class="input-field" required>
                    <option value="">Select a group</option>
                    <?php foreach ($group_list as $group): ?>
                        <option value="<?= $group['id'] ?>" <?= isset($photo) && $photo['group_id'] == $group['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($group['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="input-group">
                <label class="form-label">User</label>
                <select name="user_id" class="input-field" required>
                    <option value="">Select a user</option>
                    <?php foreach ($user_list as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= isset($photo) && $photo['user_id'] == $user['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="form-button"><?= isset($photo) ? 'Update Photo' : 'Add Photo' ?></button>
            <a href="/admin/photo" class="create-account">Back to Photos List</a>
        </form>
    </div>
</div>