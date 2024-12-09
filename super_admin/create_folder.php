<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $folderName = $_POST['folder_name'];
    $photographerId = $_POST['photographer_id'];

    $stmt = $pdo->prepare("INSERT INTO folders (name, photographer_id) VALUES (?, ?)");
    $stmt->execute([$folderName, $photographerId]);

    header("Location: manage-gallery.php?photographer_id=$photographerId");
    exit;
}
?>
