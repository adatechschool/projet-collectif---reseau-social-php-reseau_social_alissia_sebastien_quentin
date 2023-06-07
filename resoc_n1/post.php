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