<?php
$title = "Page d'accueil";
$styles = "<link rel='stylesheet' href='/css/home.css'>";
?>

<body>
  <h1>Testttt</h1>
  <?= $article ?>
    <ul>
    <?php 
    foreach ($array as $merci) {
        ?>
        <li>
            <?= $merci ?>
        </li>

        <?php 
    }
    ?>
        
    </ul>
  
</body>
