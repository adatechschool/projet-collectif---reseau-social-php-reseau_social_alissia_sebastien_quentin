<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Actualités</title> 
        <meta name="author" content="Julien Falconnet">
        <link href="style.css" rel="stylesheet">
    </head>
    <body>
        <header>
        <img src="resoc.jpg" alt="Logo de notre réseau social"/>

        <?php
        session_start();
        if ($_SESSION['connected_id']== null) {
        ?>
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="login.php">Connexion</a>
            <a href="registration.php">Inscription</a>
        </nav>
        <?php
        } else {
        ?>
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="mywall.php">Mur</a>
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
                <li><a href="logout.php">Déconnexion</a></li>
            </ul>
        </nav>
        <?php
        }
        ?>
        </header>

        <div id="wrapper">
            <aside>
                <img src="user.jpg" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        tous les utilisatrices du site.</p>
                </section>
            </aside>
            <main>
                <!-- L'article qui suit est un exemple pour la présentation et 
                  @todo: doit etre retiré -->
                   
                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                // Etape 1: Ouvrir une connexion avec la base de donnée.
                include 'serv.php';
                //verification
                if ($mysqli->connect_errno)
                {
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte, 
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    users.alias as author_name,
                    users.id as user_id,
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                    GROUP_CONCAT(DISTINCT tags.id) AS tag_id,
                    GROUP_CONCAT(DISTINCT likes.id) AS id_like,
                    GROUP_CONCAT(DISTINCT likes.post_id) AS id_post 
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id 
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    //la ligne ci-dessous doit etre supprimée mais regardez ce 
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                    

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                    // on vous met le pied à l'étrier avec created
                    // 
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle
                    ?>
                    <?php $nbLike = $post['like_number'] ?>

                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>

                        <address>

                        <?php
                        session_start();
                        if ($_SESSION['connected_id']!== null) {
                            ?><a href="mywall.php?user_id=<?php echo $post['user_id'] ?>"> 
                            <?php } else { ?>
                            <a href="login.php">
                        <?php } ?>
                        <?php echo $post['author_name'] ?></a></address>

                        <div>
                            <br/>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                        <?php
                        session_start();
                        if ($_SESSION['connected_id']!== null) {
                            ?><small><button>♥<?php echo $nbLike ?></button></small>
                            
                            <a href="php/like.php?t=like&id=<?= $id ?>">♥ <?php echo $nbLike ?> </a>

                            $taglist = explode(",", $post['taglist']);
                            $tag_ids = explode(",", $post['tag_id']);
                            for ($i = 0; $i < count($taglist); $i++) {
                                echo '<a href="tags.php?tag_id=' . $tag_ids[$i] . '">#' . $taglist[$i] . '</a>';
                                if ($i < count($taglist) - 1) {
                                    echo ', ';
                                }
                            }

                            <form method="post">
                                <small><input type="submit" name="btnLike" value="♥ <?php echo $post['like_number'] ?>"/></small>
                            </form>

                            <?php

                                if (isset($_POST["like-number"])) {
                                    $newLikes = $totalLikes + 1;
                                    $updateSql = "UPDATE likes SET count = $newLikes WHERE id = 1";
                                    if ($lesInformations->query($mysqli) === TRUE) {
                                        $totalLikes = $newLikes;
                                    } else {
                                        echo "Erreur lors de la mise à jour du nombre de likes : " . $mysqli->error;
                                    }
                                }
                                

                                
                                ?>

                                <?php
                                $taglist = explode(",", $post['taglist']);
                                $tag_ids = explode(",", $post['tag_id']);
                                for ($i = 0; $i < count($taglist); $i++) {
                                    echo '<a href="tags.php?tag_id=' . $tag_ids[$i] . '">#' . $taglist[$i] . '</a>';
                                    if ($i < count($taglist) - 1) {
                                        echo ', ';
                                    }
                                }
                                ?>
                            ?>
                        <?php } ?>
                        </footer>
                    </article>
                    <?php
                    // avec le <?php ci-dessus on retourne en mode php 
                }// cette accolade ferme et termine la boucle while ouverte avant.
                ?>

            </main>
        </div>
    </body>
</html>