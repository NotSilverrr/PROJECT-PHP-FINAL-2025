<a class="mt-6" href="/group/<?=$group->id?>">Retour</a>

<?php if (isset($user)) :?>
    <h1 class="text-8">Mettre a jour les permissions de <?= $user->user->first_name ?> <?= $user->user->last_name ?></h1>
    <p>Ajouté le <?= $user->created_at->format('d/m/Y')?> à <?= $user->created_at->format('H:i')?></p>
    <form action="" method="POST">
        <label for="read_only">
            <input type="checkbox" name="read_only" id="read_only" <?= $user->read_only ? "checked" : "" ?> onchange="this.form.submit()">
            <span>Lecture seule</span>
        </label>
        <input type="hidden" name="user_id" value="<?=$user->user->id?>">
    </form>
<?php endif;?>