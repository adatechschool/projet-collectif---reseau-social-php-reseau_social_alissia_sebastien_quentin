<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <link rel="stylesheet" href="style.css"/>
    </head>
    <header>
        <?php if (isset($_SESSION ['connected_id'])) {?>

        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="wall.php">Mur</a>
            <a href="feed.php">Flux</a>
            <a href="tags.php">Mots-clés</a>
        </nav>

        <nav id="user">
            <a href="#">Profil</a>
                <ul>
                    <li><a href="settings.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Paramètres</a></li>
                    <li><a href="followers.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes suiveurs</a></li>
                    <li><a href="subscriptions.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Mes abonnements</a></li>
                    <li><a href="usurpedpost.php?user_id=<?php echo $_SESSION['connected_id'] ?>">Posts</a></li>
                </ul>
        </nav>
        
        <?php } else {?>
            <a href="wall.php">Mur</a>
        <?php }?>
    </header>
</html>