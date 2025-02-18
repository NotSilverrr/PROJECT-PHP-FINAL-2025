<?php
startSession();

use App\Models\Group;
use App\Models\Member;
use App\Models\User;
use App\Services\Auth;

  if (!Auth::check()) {
    header('Location: /login');
    exit;
  }
  ?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mon Application' ?></title>
    <link rel="stylesheet" href="/dist/framework-esgi.css">
    <?= $styles ?? '' ?>
</head>
<body class="">
  <?if (isset($error) || isset($_SESSION["error"])): ?>
    <div class="error mt-4">
    <svg class="error__icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="M480-280q17 0 28.5-11.5T520-320q0-17-11.5-28.5T480-360q-17 0-28.5 11.5T440-320q0 17 11.5 28.5T480-280Zm-40-160h80v-240h-80v240Zm40 360q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
    <p class="error__text"><?=$error ?? $_SESSION["error"]?></p>
    <button class="error__close">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
    </button>
  </div>
  <?php unset($_SESSION['error']); ?>
  <?php endif; ?>

  <?if (isset($success) || isset($_SESSION['success'])): ?>
    <div class="error error--info mt-4">
    <svg class="error__icon" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined"><path d="M440-280h80v-240h-80v240Zm40-320q17 0 28.5-11.5T520-640q0-17-11.5-28.5T480-680q-17 0-28.5 11.5T440-640q0 17 11.5 28.5T480-600Zm0 520q-83 0-156-31.5T197-197q-54-54-85.5-127T80-480q0-83 31.5-156T197-763q54-54 127-85.5T480-880q83 0 156 31.5T763-763q54 54 85.5 127T880-480q0 83-31.5 156T763-197q-54 54-127 85.5T480-80Zm0-80q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>
    <p class="error__text"><?=$success ?? $_SESSION['success']?></p>
    <button class="error__close">
      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" ><path d="m256-200-56-56 224-224-224-224 56-56 224 224 224-224 56 56-224 224 224 224-56 56-224-224-224 224Z"/></svg>
    </button>
  </div>
  <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
    <div class="container flex">
      <nav class="side-bar side-bar--collapsed">
        <div class="side-bar__header">
          <button class="side-bar__toggle">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="undefined">
              <path d="M360-400h400L622-580l-92 120-62-80-108 140Zm-40 160q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z"/>
            </svg>
          </button>
          <h2>Groups</h2>
        </div>
        <div class="side-bar__content">
          <form class="search-bar" id="search-groups-form">
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
            <input type="text" placeholder="Search" id="search-groups"/>
          </form>
          <ul class="scrollable-list scrollable-list--square" id="groups-list">
          </ul>
          <a href="/group/create" class="button button--primary">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="none"
            >
              <path
                d="M500-482q29-32 44.5-73t15.5-85q0-44-15.5-85T500-798q60 8 100 53t40 105q0 60-40 105t-100 53Zm220 322v-120q0-36-16-68.5T662-406q51 18 94.5 46.5T800-280v120h-80Zm80-280v-80h-80v-80h80v-80h80v80h80v80h-80v80h-80Zm-480-40q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM0-160v-112q0-34 17.5-62.5T64-378q62-31 126-46.5T320-440q66 0 130 15.5T576-378q29 15 46.5 43.5T640-272v112H0Zm320-400q33 0 56.5-23.5T400-640q0-33-23.5-56.5T320-720q-33 0-56.5 23.5T240-640q0 33 23.5 56.5T320-560ZM80-240h480v-32q0-11-5.5-20T540-306q-54-27-109-40.5T320-360q-56 0-111 13.5T100-306q-9 5-14.5 14T80-272v32Zm240-400Zm0 400Z"
              />
            </svg>
            Create a group
          </a>
        </div>
      </nav>
      
      <section class="center-container">
        <section class="center-container__content mt-24 lg-mt-0">
          <?= $content ?>
        </section>
        <?php if (isset($group) && (Member::canEdit($group->id, Auth::id()) || Auth::user()->isAdmin())) :?>
        <div class="center-container__button">
          <a href="/group/<?=$group->id?>/upload" class="button button--primary button--rounded button--lg button--icon button--floating">
          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/></svg>
          </a>
        </div>
        <?php endif; ?>
        
      </section>
      
      
      
      <nav class="side-bar side-bar--collapsed side-bar--right">
        <div class="side-bar__header">
          <button class="side-bar__toggle">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              height="24px"
              viewBox="0 -960 960 960"
              width="24px"
              fill="none"
            >
              <path
                d="M0-240v-63q0-43 44-70t116-27q13 0 25 .5t23 2.5q-14 21-21 44t-7 48v65H0Zm240 0v-65q0-32 17.5-58.5T307-410q32-20 76.5-30t96.5-10q53 0 97.5 10t76.5 30q32 20 49 46.5t17 58.5v65H240Zm540 0v-65q0-26-6.5-49T754-397q11-2 22.5-2.5t23.5-.5q72 0 116 26.5t44 70.5v63H780Zm-455-80h311q-10-20-55.5-35T480-370q-55 0-100.5 15T325-320ZM160-440q-33 0-56.5-23.5T80-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T160-440Zm640 0q-33 0-56.5-23.5T720-520q0-34 23.5-57t56.5-23q34 0 57 23t23 57q0 33-23 56.5T800-440Zm-320-40q-50 0-85-35t-35-85q0-51 35-85.5t85-34.5q51 0 85.5 34.5T600-600q0 50-34.5 85T480-480Zm0-80q17 0 28.5-11.5T520-600q0-17-11.5-28.5T480-640q-17 0-28.5 11.5T440-600q0 17 11.5 28.5T480-560Zm1 240Zm-1-280Z"
              />
            </svg>
          </button>
          <h2>Members</h2>
        </div>
        <div class="side-bar__content">
          <form class="search-bar" action="" method="GET">
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
            <input name="m" type="text" placeholder="Search" value="<?= isset($_GET['m']) ? htmlspecialchars($_GET['m']) : '' ?>" />
          </form>
          <ul class="scrollable-list action" id="members-list">
            <?php
            if (isset($members) && !empty($members)) {
              foreach ($members as $member) {
                ?>
                <li>
                  <?php if($group->isOwner(Auth::id()) || Auth::isadmin()) :?>
                    <a href="/group/<?=$group->id?>/user/<?=$member->id?>" class="<?= $member->id == $group->ownerId ? "scrollable-list__selected" : "" ?>">
                  <?php else :?>
                    <a href="#" class="<?= $member->id == $group->ownerId ? "scrollable-list__selected" : "" ?>">
                  <?php endif; ?>
                    <span
                      class="scrollable-list__img"
                      style="background-image: url('/user/<?= $member->id ?>/profilePicture')"
                    ></span>
                    <span class="scrollable-list__text"><?= $member->first_name ?></span>
                    <?php if ($group->isOwner(Auth::id()) && ($member->id != $group->ownerId)): ?>
                    <form action="/group/<?= $group->id ?>/deleteUser" method="post">
                      <input type="hidden" name="user_id" value="<?= $member->id ?>">
                      <button type="submit" class="button button--transparent button--md button--rounded">
                      <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                      </button>
                    </form>
                    <?php endif; ?>
                  </a>
                </li>
                <?php
              }
            } else {
              ?>
              <li>
                <span>No members</span>
              </li>
              <?php
            }
            
             ?>
          </ul>
          <?php if (isset($group) && $group->isOwner(Auth::id())) :?>
          <a href="/group/<?= $group->id ?>/addMember" class="button button--primary">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed">
              <path d="M720-400v-120H600v-80h120v-120h80v120h120v80H800v120h-80Zm-360-80q-66 0-113-47t-47-113q0-66 47-113t113-47q66 0 113 47t47 113q0 66-47 113t-113 47ZM40-160v-112q0-34 17.5-62.5T104-378q62-31 126-46.5T360-440q66 0 130 15.5T616-378q29 15 46.5 43.5T680-272v112H40Zm80-80h480v-32q0-11-5.5-20T580-306q-54-27-109-40.5T360-360q-56 0-111 13.5T140-306q-9 5-14.5 14t-5.5 20v32Zm240-320q33 0 56.5-23.5T440-640q0-33-23.5-56.5T360-720q-33 0-56.5 23.5T280-640q0 33 23.5 56.5T360-560Zm0-80Zm0 400Z"/>
            </svg>
            Add a member
          </a>
          <?php endif; ?>
        </div>
      </nav>
    </div>
    <?= $script ?? '' ?>
    <script src="/dist/framework-esgi.js"></script>
    <script src="/js/layout_main.js"></script>

  </body>
</html>