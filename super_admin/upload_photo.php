<?php
require '../connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    $photo = $_FILES['photo'];
    $folderId = $_POST['folder_id'];

    $targetDir = "../uploads/photos/";
    $targetFile = $targetDir . basename($photo["name"]);
    move_uploaded_file($photo["tmp_name"], $targetFile);

    // Insert photo into the database
    $stmt = $pdo->prepare("INSERT INTO gallery_photos (image_path, folder_id) VALUES (?, ?)");
    $stmt->execute([basename($photo["name"]), $folderId]);

    header("Location: manage-gallery.php?folder_id=$folderId");
    exit;
}
?>
