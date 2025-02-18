<?php

use App\Services\Auth; ?>
<a class="mt-6" href="/group/<?=$group->id?>">Back</a>

<h1 class="text-8">Add a Member</h1>


<form class="search-bar mb-5 " action="" method="GET">
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

<div class="table">
        <div class="table__wrapper">
          <table>
            <thead>
              <tr>
                <th>FistName</th>
                <th>LastName</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
            <?php
            if (isset($allUsers) && !empty($allUsers)) {
              foreach ($allUsers as $user) {
                ?>
              <tr>
                <td><?=$user->first_name?></td>
                <td><?=$user->last_name?></td>
                <td class="table__action">
                  <form action="/group/<?= $groupId ?>/addMember" method="post">
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                    <button class="button button--transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
                    </button>
                  </form>
                  <span>/</span>
                  <form action="/group/<?= $groupId ?>/addMember" method="post">
                    <input type="hidden" name="user_id" value="<?= $user->id ?>">
                    <input type="hidden" name="read_only" value="true">
                    <button class="button button--transparent">
                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m622-453-56-56 82-82-57-57-82 82-56-56 195-195q12-12 26.5-17.5T705-840q16 0 31 6t26 18l55 56q12 11 17.5 26t5.5 30q0 16-5.5 30.5T817-647L622-453ZM200-200h57l195-195-28-29-29-28-195 195v57ZM792-56 509-338 290-120H120v-169l219-219L56-792l57-57 736 736-57 57Zm-32-648-56-56 56 56Zm-169 56 57 57-57-57ZM424-424l-29-28 57 57-28-29Z"/></svg>
                    </button>
                  </form>
                </td>
              </tr>
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
              
            </tbody>
          </table>
        </div>
        
      </div>

