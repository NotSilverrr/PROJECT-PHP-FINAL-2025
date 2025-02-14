<?php

use App\Services\Auth; ?>

<section class="grow-1 m-4 overflow-y-scroll">
  <form action="/group/<?= $groupId ?>/addMember" method="post">
    <select name="user_id" id="new_member">
      <?php

 foreach($allUsers as $user) { ?>
        <option value="<?= $user->id ?>"><?= $user->first_name ?> <?= $user->last_name ?></option>
      <?php } ?>
    </select>
    <button type="submit" class="button button--primary">Add member</button>
  </form>

  <ul class="scrollable-list action" id="members-list">
            <?php
            if (isset($allUsers) && !empty($allUsers)) {
              foreach ($allUsers as $user) {
                ?>
                <li>
                  <a href="#" class="<?= $user->id == Auth::id() ? "scrollable-list__selected" : "" ?>">
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
</section>