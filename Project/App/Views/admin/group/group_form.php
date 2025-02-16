<div class="form-container-admin">
    <div class="form-card-admin">
        <h1 class="form-title"><?= isset($group) ? 'Update Group' : 'Add Group' ?></h1>
        <div class="error">
        <?php 
            if (isset($errors) && !empty($errors)){
                echo $errors;
            }
            if (isset($_SESSION['error'])) {
                echo $_SESSION['error'];
                unset($_SESSION['error']);
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
                    <img src="<?= $group['profile_picture'] ?>" alt="Current profile picture" class="profile-picture">
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

        <?php if (isset($group)): ?>
        <div class="many-to-many-table-section">
            <h2>Group Members</h2>
            <?php if (isset($members) && !empty($members)): ?>
                <form method="POST" action="/admin/member/add/<?= $group['id'] ?>" class="many-to-many-table-form">
                <h3>Add New Members</h3>
                <div class="many-to-many-table-selection">
                    <select name="user_id" class="input-field" required>
                        <option value="">Select a user to add</option>
                        <?php foreach ($available_users as $user): ?>
                            <option value="<?= $user['id'] ?>">
                                <?= htmlspecialchars($user['email']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="form-button">Add Member</button>
            </form>
            <table class="many-to-many-table-table">
                <thead>
                    <tr>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= htmlspecialchars($member->email) ?></td>
                        <td>
                            <form method="POST" action="/admin/member/delete/<?= $group['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $member->id ?>" />
                                <button type="submit" class="many-to-many-table-delete">
                                <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                    <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                                </svg>
                                </button>
                            </form>
                            <form method="POST" action="/admin/member/toggle-readonly/<?= $group['id'] ?>">
                                <input type="hidden" name="user_id" value="<?= $member->id ?>" />
                                <button type="submit" class="many-to-many-table-toggle" title="Toggle Read-only Status">
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <?php else: ?>
            <p>No members in this group yet.</p>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>