<?php

use App\Services\Auth; ?>

<?if (isset($error)): ?>
  <div class="error mt-4">
  <svg class="error__icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
  <p class="error__text"><?=$error?></p>
  <button class="error__close">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
  </button>
</div>
<?php endif; ?>
<form class="search-bar mb-5" action="" method="GET">
  <button class="">
    <svg
      xmlns="http://www.w3.org/2000/svg"
      height="24px"
      viewBox="0 -960 960 960"
      width="24px"
      fill="none"
    >
      <path
        d="M784-120 532-372q-30 24-69 38t-83 14q-109 0-184.5-75.5T120-580q0-109 75.5-184.5T380-840q109 0 184.5 75.5T640-580q0 44-14 83t-38 69l252 252-56 56ZM380-400q75 0 127.5-52.5T560-580q0-75-52.5-127.5T380-760q-75 0-127.5 52.5T200-580q0 75 52.5 127.5T380-400Z"
      />
    </svg>
  </button>
  <input name="u" type="text" placeholder="Search" value="<?= isset($_GET['u']) ? htmlspecialchars($_GET['u']) : '' ?>" />
</form>
<ul class="scrollable-list action" id="members-list">
          <?php
          if (isset($allUsers) && !empty($allUsers)) {
            foreach ($allUsers as $user) {
              ?>
              <li class="mt-2">
                <a href="#" class="scrollable-list__selected">
                  <span
                    class="scrollable-list__img"
                    style="background-image: url('<?= $user->profile_picture ?>')"
                  ></span>
                  <span class="scrollable-list__text"><?= $user->first_name ?> <?= $user->last_name ?></span>
                  <form action="/group/<?= $groupId ?>/addMember" method="post">
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                    <button class="button button--transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                    </button>
                  </form>
                </a>
              </li>
              <?php
            }
          } else {
            ?>
            <li>
              <span>No users</span>
            </li>
            <?php
          }
          
            ?>
        </ul>