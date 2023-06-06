<?php
include 'serv.php';
if (isset($_GET['t'], $_GET['id']) AND !empty($_GET['id']) AND !empty($_GET['t'])){
    $getid = (int) $_GET['id'];
    $gett = (int) $_GET['t'];
    
    $check = $mysqli->prepare('SELECT * FROM posts WHERE id = ?');
    $check->execute(array($getid));

    if ($check->rowCount() == 1) {
        if ($gett);
    }
}
