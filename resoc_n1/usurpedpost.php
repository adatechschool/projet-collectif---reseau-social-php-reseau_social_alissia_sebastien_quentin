<?php
session_start();
?>
<!doctype html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>ReSoC - Post</title> 
        <meta name="author" content="Julien Falconnet">
        <link rel="stylesheet" href="style.css"/>
    </head>
    <body>
        <header>
        
        <?php
        include "header.php";
        ?>
        </header>

        <div id="wrapper" >

            <aside>
                <h2>Présentation</h2>
                <p>Sur cette page on peut poster un message en son nom.</p>
            </aside>
            <main>
                <article>
                    <h2>Poster un message</h2>
                    <?php
                    /**
                     * BD
                     */
                    include 'serv.php';
                    /**
                     * Récupération de la liste des auteurs
                     */
                    $listAuteurs = [];
                    $laQuestionEnSql = "SELECT * FROM users";
                    $lesInformations = $mysqli->query($laQuestionEnSql);
                    while ($user = $lesInformations->fetch_assoc()) {
                        $listAuteurs[$user['id']] = $user['alias'];
                    }
                    $userId = $_SESSION['connected_id'];
//&& array_key_exists($userId, $listAuteurs)

                    $enCoursDeTraitement = isset($_POST['message']);
                    if ($enCoursDeTraitement) {

                        if (isset($userId)) {
                            $authorId = $userId;
                            //This will ensure that any special characters in the message are properly escaped before being inserted into the SQL query
                            $postContent = mysqli_real_escape_string($mysqli, $_POST['message']);


                            // Extract the hashtags from the post content
                            preg_match_all('/#([\p{L}\p{N}_-]+)/u', $postContent, $matches);
                            $hashtags = $matches[1];

                            $lInstructionSql = "INSERT INTO posts "
                                . "(id, user_id, content, created) "
                                . "VALUES (NULL, "
                                . $authorId . ", "
                                . "'" . $postContent . "', "
                                . "NOW());"
                            ;


                            $messageAjoute = $mysqli->query($lInstructionSql);
                            if (!$messageAjoute) {
                                echo "Impossible d'ajouter le message: " . $mysqli->error;
                            } else {
                                // Get the post ID of the inserted post
                                $postId = $mysqli->insert_id;

                                // Loop through the hashtags and insert them into the tags and posts_tags tables
                                foreach ($hashtags as $tag) {
                                    $tag = trim($tag);

                // Check if the tag already exists in the tags table
                                    $tagExistsQuery = "SELECT id FROM tags WHERE label = '" . $tag . "'";
                                    $tagExistsResult = $mysqli->query($tagExistsQuery);

                                    if ($tagExistsResult->num_rows > 0) {
                    // If the tag exists, get its ID
                                        $tagId = $tagExistsResult->fetch_assoc()['id'];

                                    } else {
                    // If the tag does not exist, insert it into the tags table and get its ID
                                        $insertTagQuery = "INSERT INTO tags (label) VALUES ('" . $tag . "')";
                                        $mysqli->query($insertTagQuery);
                                        $tagId = $mysqli->insert_id;
                                    }

                // Insert the post-tag relationship into the posts_tags table
                                    $insertPostTagQuery = "INSERT INTO posts_tags (post_id, tag_id) VALUES (" . $postId . ", " . $tagId . ")";
                                    $mysqli->query($insertPostTagQuery);
                                }
                            }
                        } else {
                            echo "Error: Invalid user ID or user not logged in.";
                        }
                    }
?>                     
                    <form action="<?php echo $_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']?>" method="post">
                        <dl>
                            <dt><label for='message'>Message</label></dt>
                            <dd><textarea name='message'></textarea></dd>
                        </dl>
                        <input type='submit'>
                    </form>               
                </article>
            </main>
        </div>
    </body>
</html>
