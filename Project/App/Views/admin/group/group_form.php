<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($group) ? 'Update Group' : 'Add Group' ?></h1>
        <form method="POST" action="<?= isset($group) ? '/admin/group/update' : '/admin/group/add' ?>" class="form" enctype="multipart/form-data">
            <?php if (isset($group)): ?>
            <input type="hidden" name="id" value="<?= $group['id'] ?>" />
            <?php endif; ?>
            <div class="input-group">
                <label class="form-label">Name</label>
                <input type="text" name="name" placeholder="Group Name" class="input-field" value="<?= isset($group) ? $group['name'] : '' ?>" required />
            </div>
            <div class="input-group">
                <label class="form-label">Profile Picture</label>
                <input type="text" name="profile_picture" placeholder="Profile Picture URL" class="input-field" value="<?= isset($group) ? $group['profile_picture'] : '' ?>" required />
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