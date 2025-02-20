<?php use App\Services\Auth; ?>

<?php if(isset($message)) : ?>
    <h1 class="text-8"><?= $message ?></h1>
    <h2>Welcome to PhotoShare !</h2>
    <h3>Here you can share your photos with your friends ! :D</h3>
    <h3>For that you have to create select or get added to a group first</h4>
    <h3>Then you can click on the + button to share your photos ^^</h4>
<?php else: ?>
<?php
if (isset($group) && !empty($group)) : ?>
<div class="flex justify-between items-center">
    <h1 class="text-8"><?= $group->name ?></h1>

    <?php if($group->isOwner(Auth::id()) || Auth::user()->isadmin) : ?>
    <form action="/group/<?=$group->id?>/delete" method="post">
        <button class="button button--danger " type="submit">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="none"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
        </button>
    </form>
    <?php endif; ?>
</div>


<ul class="image-grid">
    <?php foreach ($photos as $photo) :

        $fileName = explode("/", $photo->file);
        $fileName = end($fileName);
        $fileName = explode(".", $fileName);
        $fileName = explode("_", $fileName[0]);
        array_shift($fileName);
        $fileName = implode("_", $fileName);
        ?>
        <li>
            <figure class="image-card">
                <img class="image-card__img" src="/group/<?=$group->id?>/showImage/<?=$photo->id?>">
                <figcaption class="image-card__caption">
                    <div class="image-card__title">
                        <h3><?=$photo->user->first_name?> <?=$photo->user->last_name?></h3>
                        
                        <button class="share-button" data-photo-id="<?= $photo->id ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="none"><path d="M680-80q-50 0-85-35t-35-85q0-6 3-28L282-392q-16 15-37 23.5t-45 8.5q-50 0-85-35t-35-85q0-50 35-85t85-35q24 0 45 8.5t37 23.5l281-164q-2-7-2.5-13.5T560-760q0-50 35-85t85-35q50 0 85 35t35 85q0 50-35 85t-85 35q-24 0-45-8.5T598-672L317-508q2 7 2.5 13.5t.5 14.5q0 8-.5 14.5T317-452l281 164q16-15 37-23.5t45-8.5q50 0 85 35t35 85q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T720-200q0-17-11.5-28.5T680-240q-17 0-28.5 11.5T640-200q0 17 11.5 28.5T680-160ZM200-440q17 0 28.5-11.5T240-480q0-17-11.5-28.5T200-520q-17 0-28.5 11.5T160-480q0 17 11.5 28.5T200-440Zm480-280q17 0 28.5-11.5T720-760q0-17-11.5-28.5T680-800q-17 0-28.5 11.5T640-760q0 17 11.5 28.5T680-720Zm0 520ZM200-480Zm480-280Z"/></svg>
                        </button>
                    </div>
                    <div class="image-card__button-wrapper">
                    <a href="/group/<?=$group->id?>/showImage/<?=$photo->id?>" download="<?=$fileName?>" class="button button--primary ">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="none"><path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>
                    </a>
                    <?php if($photo->isOwner($photo->id, Auth::id()) || $group->isOwner(Auth::id())) :?>
                    <form action="/group/<?=$group->id?>/deleteImage/<?=$photo->id?>" method="post">
                        <button class="button button--danger " type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="none"><path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/></svg>
                        </button>
                    </form>
                    <?php endif; ?>
                    </div>
                </figcaption>
            </figure>
        </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
    <h1>Ce groupe n'exsite pas</h1>

<?php endif; ?>
<?php endif; ?>
