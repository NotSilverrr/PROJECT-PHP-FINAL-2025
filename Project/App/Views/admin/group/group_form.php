
<div class="">
    <h1 class=""><?= isset($update) ? 'Update Group' : 'Add Group' ?></h1>
    <form method="POST" action="<?= isset($update) ? '/admin/group/update' : '/admin/group/add' ?>" class="form" enctype="multipart/form-data">
        <?php if (isset($_SESSION['group_update'])): ?>
        <input type="hidden" name="id" value="<?= $_SESSION['group_update']->id ?>" />
        <?php endif; ?>
        <div class="">
            <label class="form__label">Name</label>
            <input type="text" name="name" placeholder="Group Name" class="" value="<?= isset($_SESSION['group_update']) ? $_SESSION['group_update']->name : '' ?>" required />
        </div>
        <div class="">
            <label class="form__label">Profile Picture</label>
            <input type="file" name="profile_picture" accept="image/*" class="" <?= !isset($_SESSION['group_update']) ? 'required' : '' ?> />
        </div>
        <div class="">
            <label class="form__label">Owner</label>
            <select name="owner" class="" required>
                <option value="">Select an owner</option>
                <?php foreach ($user_list as $user): ?>
                    <option value="<?= $user->id ?>" <?= isset($_SESSION['group_update']) && $_SESSION['group_update']->ownerId == $user->id ? 'selected' : '' ?>>
                        <?= htmlspecialchars($user->email) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="button button--primary"><?= isset($_SESSION['group_update']) ? 'Update Group' : 'Add Group' ?></button>
        <a href="/admin/group" class="form__a">Back to Groups List</a>
    </form>

    <?php if (isset($update)): ?>
    <div class="many-to-many-table-section">
        <h2>Group Members</h2>
        <?php if (isset($members) && !empty($members)): ?>
            <form method="POST" action="/admin/member/add/<?= $_SESSION['group_update']->id ?>" class="form many-to-many-table-form">
            <h3>Add New Members</h3>
            <div class="many-to-many-table-selection">
                <select name="user_id" required>
                    <option value="">Select a user to add</option>
                    <?php foreach ($available_users as $user): ?>
                        <option value="<?= $user->id ?>">
                            <?= htmlspecialchars($user->email) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="button button--primary">Add Member</button>
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
                        <form method="POST" action="/admin/member/delete/<?= $_SESSION['group_update']->id ?>">
                            <input type="hidden" name="user_id" value="<?= $member->id ?>" />
                            <button type="submit" class="many-to-many-table-delete button button--danger button--sm ">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                                <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
                            </svg>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </form>
        
        <?php else: ?>
        <p>No members in this group yet.</p>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php
unset($_SESSION['group_update']);
?>