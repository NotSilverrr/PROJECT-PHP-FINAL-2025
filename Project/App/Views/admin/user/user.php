<section class="grow-1 m-4 overflow-y-scroll">
<div class="flex justify-between items-center">
  <h1 class="mt-24 text-8 lg-mt-4">Users</h1>
  <a href="/admin/user/add">
    <button class="button button--primary button--admin">+</button>
  </a>
</div>
<div class="table">
    <div class="table__wrapper">
        <table>
        <thead>
            <tr>
            <th>Email</th>
            <th>Profile Picture</th>
            <th>Admin Status</th>
            <th>Created At</th>
            <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
              <tr>
                <td><?= htmlspecialchars($user['email']) ?></td>
                <td><?= $user['profile_picture'] ? htmlspecialchars($user['profile_picture']) : 'No picture' ?></td>
                <td><?= $user['is_admin'] ? 'Yes' : 'No' ?></td>
                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                <td class="table__action">
                  <a href="/admin/user/update/<?= $user['id'] ?>" class="button button--primary button--sm">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
                      <path d="M200-200h57l391-391-57-57-391 391v57Zm-80 80v-170l528-527q12-11 26.5-17t30.5-6q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L290-120H120Zm640-584-56-56 56 56Zm-141 85-28-29 57 57-29-28Z"/>
                    </svg>
                  </a>
                  <span>/</span>
                  <form action="/admin/user/delete" method="POST" style="display: inline;">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <button type="submit" class="button button--danger button--sm" style="border: none; padding: 0; background: none; cursor: pointer;">
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
    </div>
</div>
</section>