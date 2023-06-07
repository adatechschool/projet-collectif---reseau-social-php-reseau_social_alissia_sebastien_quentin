<?php
session_start();

if ($_SESSION['connected_id'] != null) {
    $followerId = $_SESSION['connected_id'];
    $followedUserId = $_GET['user_id'];

    include 'serv.php';

    // Vérification de la présence de la relation de suivi
    $query = "SELECT id FROM followers WHERE following_user_id='$followerId' AND followed_user_id='$followedUserId'";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        // Suppression de la relation de suivi
        $deleteQuery = "DELETE FROM followers WHERE following_user_id='$followerId' AND followed_user_id='$followedUserId'";
        $deleteResult = $mysqli->query($deleteQuery);

        if ($deleteResult) {
            // Redirection vers la page du mur de l'utilisateur non suivi
            header("Location: wall.php?user_id=$followedUserId");
            exit();
        } else {
            echo "Erreur lors de la suppression de la relation de suivi.";
        }
    } else {
        echo "La relation de suivi n'existe pas.";
    }
} else {
    echo "Utilisateur non connecté.";
}
?>
