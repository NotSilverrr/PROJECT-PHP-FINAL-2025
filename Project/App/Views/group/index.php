<?php use App\Services\Auth; ?>

<section class="grow-1 m-4 overflow-y-scroll">
    <?php


 if (isset($group) && !empty($group)) : ?>
    <h1 class="mt-24 text-8 lg-mt-4"><?= $group['name'] ?></h1>
    <?php
    session_start();
    print_r($_SESSION) ?>
    <?php print_r(Auth::user()->email ?? 'Utilsateur non connécté') ?>

    <ul class="image-grid">
        <?php print_r($group) ?>
    </ul>
    <?php else: ?>
        <h1>Ce groupe n'exsite pas</h1>

    <?php endif; ?>

</section>