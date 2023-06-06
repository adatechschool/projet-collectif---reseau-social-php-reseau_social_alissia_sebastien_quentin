<?php
include 'serv.php';
// if (isset($_GET['t'], $_GET['id']) AND !empty($_GET['id']) AND !empty($_GET['t'])){
//     $getid = (int) $_GET['id']
//     $gett = (int) $_GET['t']
    
//     $check = $mysqli->prepare('SELECT * FROM likes WHERE id = ?')
//     $check->execute(array($getid))

//     if ($check->rowCount() == 1) {
//         if ($gett == 0){
//             $ins = $mysqli-> prepare('INSERT INTO likes (post_id) VALUES (?)')
//             $ins -> execute(array($getid))
//         } elseif ($gett == 1) {

//         }
//         header('Location : ' .$_SERVER['HTTP_REFERER'])
//     }
// }

if (isset($_POST['btnLike'])) {
    $newLikes = $nbLike + 1
    $updateSql = "UPDATE likes SET count = $newLikes WHERE id = 1";
    if ($conn->query($updateSql) === TRUE) {
        $nbLike = $newLikes;
    
}
}





