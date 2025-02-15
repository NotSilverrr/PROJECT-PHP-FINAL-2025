<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($group) ? 'Update Group' : 'Add Group' ?></h1>
        <div class="error">
        <?php 
            if (isset($errors) && !empty($errors)){
                echo $errors;
            }
        ?>
        </div>
        <form method="POST" action="<?= isset($update) ? '/admin/group/update' : '/admin/group/add' ?>" class="form" enctype="multipart/form-data">
            <?php if (isset($group)): ?>
            <input type="hidden" name="id" value="<?= $group['id'] ?>" />
            <?php endif; ?>
            <div class="input-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" placeholder="Group Name" class="input-field" value="<?= isset($group) ? $group['name'] : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label">Profile Picture</label>
                <?php if(isset($group) && $group['profile_picture']): ?>
                    <img src="<?= $group['profile_picture'] ?>" alt="Current profile picture" style="max-width: 100px; margin-bottom: 10px;">
                <?php endif; ?>
                <input type="file" name="profile_picture" accept="image/*" class="input-field" <?= !isset($group) ? 'required' : '' ?> />
            </div>
            <div class="input-group">
                <label class="form-label">Owner</label>
                <select name="owner" class="input-field" required>
                    <option value="">Select an owner</option>
                    <?php foreach ($user_list as $user): ?>
                        <option value="<?= $user['id'] ?>" <?= isset($group) && $group['owner'] == $user['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($user['email']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="form-button"><?= isset($group) ? 'Update Group' : 'Add Group' ?></button>
            <a href="/admin/group" class="create-account">Back to Groups List</a>
        </form>
    </div>
</div>