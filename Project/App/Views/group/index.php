<?php use App\Services\Auth; ?>

<?php if(isset($message)) : ?>
    <h1 class="text-8"><?= $message ?></h1>
<?php else: ?>
<?php
if (isset($group) && !empty($group)) : ?>
<h1 class="text-8"><?= $group->name ?></h1>


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
                    <span><?=$photo->user->first_name?> <?=$photo->user->last_name?></span>
                    <div class="image-card__button-wrapper">
                    <a href="/group/<?=$group->id?>/showImage/<?=$photo->id?>" download="<?=$fileName?>" class="button button--primary ">
                        <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="none"><path d="M480-320 280-520l56-58 104 104v-326h80v326l104-104 56 58-200 200ZM240-160q-33 0-56.5-23.5T160-240v-120h80v120h480v-120h80v120q0 33-23.5 56.5T720-160H240Z"/></svg>
                    </a>
                    <?php if($photo->isOwner($photo->id, Auth::id())) :?>
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
