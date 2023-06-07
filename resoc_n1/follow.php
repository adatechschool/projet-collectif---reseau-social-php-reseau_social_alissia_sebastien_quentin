<?php
session_start();
if ($_SESSION['connected_id'] != null) {
    include 'serv.php';

    $followingUserId = $_POST['following_user_id'];
    $followedUserId = $_POST['followed_user_id'];

    $insertQuery = "INSERT INTO followers (followed_user_id, following_user_id) VALUES ('$followedUserId', '$followingUserId')";
    $result = $mysqli->query($insertQuery);

    if ($result) {
        // Suivi réussi
        header("Location: wall.php?user_id=$followedUserId");
        exit();
    } else {
        // Erreur lors du suivi
        echo "Erreur lors du suivi : " . $mysqli->error;
    }
} else {
    // Utilisateur non connecté
    echo "Erreur : utilisateur non connecté.";
}
?>
