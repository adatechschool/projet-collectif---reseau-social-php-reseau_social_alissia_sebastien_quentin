<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Les message par mot-clé</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>

        <?php
        
        if ($_SESSION['connected_id']== null) {
        ?>
        <nav id="menu">
            <a href="news.php">Actualités</a>
            <a href="login.php">Connexion</a>
            <a href="registration.php">Inscription</a>
        </nav>
        <?php
        } else {
            include "header.php"; } ?>
        </header>

        <div id="wrapper">
            <?php
            /**
             * Cette page est similaire à wall.php ou feed.php 
             * mais elle porte sur les mots-clés (tags)
             */
            /**
             * Etape 1: Le mur concerne un mot-clé en particulier
             */
            $tagId = intval($_GET['tag_id']);
        
            ?>
            <?php
            /**
             * Etape 2: se connecter à la base de donnée
             */
            include 'serv.php';
            ?>

            <aside>
                <?php
                /**
                 * Etape 3: récupérer le nom du mot-clé
                 */
                $laQuestionEnSql = "SELECT * FROM tags WHERE id= '$tagId' ";
                $lesInformations = $mysqli->query($laQuestionEnSql);
                $tag = $lesInformations->fetch_assoc();
                $allTagsQuery = "SELECT * FROM tags";
                $allTagsResult = $mysqli->query($allTagsQuery);
                $allTags = $allTagsResult->fetch_all(MYSQLI_ASSOC);
                //@todo: afficher le résultat de la ligne ci dessous, remplacer XXX par le label et effacer la ligne ci-dessous
            
                ?>
                <img src="user.png" alt="Portrait de l'utilisatrice"/>
                <section>
                    
                    <h3>All Tags</h3>
                        <ul>
                        <?php foreach ($allTags as $tag) { ?>
                            <li><a class="tags" href="tags.php?tag_id=<?php echo $tag['id']; ?>">#<?php echo $tag['label']; ?></a></li>
                        <?php } ?>
                        </ul>

                </section>
            </aside>
            <main>
                <?php
                if (isset($_GET['tag_id'])) {
                    $tagId = intval($_GET['tag_id']);
                    $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.created,
                        users.alias as author_name,  
                        count(likes.id) as like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                        GROUP_CONCAT(DISTINCT tags.id) AS tag_id, 
                        GROUP_CONCAT(DISTINCT users.id) AS user_id
                        FROM posts_tags as filter 
                        JOIN posts ON posts.id=filter.post_id
                        JOIN users ON users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id
                        WHERE filter.tag_id = '$tagId'   
                        GROUP BY posts.id
                        ORDER BY posts.created DESC
                        ";
                } else {
                    $laQuestionEnSql = "
                        SELECT posts.content,
                        posts.created,
                        users.alias as author_name,  
                        count(likes.id) as like_number,  
                        GROUP_CONCAT(DISTINCT tags.label) AS taglist,
                        GROUP_CONCAT(DISTINCT tags.id) AS tag_id, 
                        GROUP_CONCAT(DISTINCT users.id) AS user_id
                        FROM posts 
                        JOIN users ON users.id=posts.user_id
                        LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                        LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                        LEFT JOIN likes      ON likes.post_id  = posts.id
                        GROUP BY posts.id
                        ORDER BY posts.created DESC
                        LIMIT 10
                        ";
                }
                $lesInformations = $mysqli->query($laQuestionEnSql);
                if ( ! $lesInformations)
                {
                    echo("Échec de la requete : ". $mysqli->error);
                }

                /**
                 * Etape 4: @todo Parcourir les messsages et remplir correctement le HTML avec les bonnes valeurs php
                 */
                while ($post = $lesInformations->fetch_assoc())
                {
                    ?>                
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address><a href="wall.php?user_id=<?php echo $post['user_id'] ?>"><?php echo $post['author_name'] ?></a></address>
                        <div>
                            <p><?php echo $post['content'] ?></p>
                        </div>
                        <footer>
                            <small>♥ <?php echo $post['like_number'] ?> </small>
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
                        </footer>
                    </article>
                <?php } ?>


            </main>
        </div>
    </body>
</html>