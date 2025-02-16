
<?php if (isset($member)) :?>
    <h1 class="text-8">Mettre a jour les permissions de <?= $member->user->first_name ?> <?= $member->user->last_name ?></h1>
    <p>Ajouté le <?= $member->created_at->format('d/m/Y')?> à <?= $member->created_at->format('H:i')?></p>
    <form action="">
        <label for="read_only">
            <input type="checkbox" name="read_only" id="read_only" <?= $member->read_only ? "checked" : "" ?>>
            <span>Lecture seule</span>
        </label>
    </form>
<?php endif;?>