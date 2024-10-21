<?php
require '../connection.php';

if (isset($_GET['photographer_id'])) {
    $photographer_id = $_GET['photographer_id'];

    $query = $pdo->prepare("SELECT photo_url FROM photos WHERE photographer_id = :photographer_id");
    $query->bindParam(':photographer_id', $photographer_id);
    $query->execute();
    $photos = $query->fetchAll(PDO::FETCH_ASSOC);

    if (count($photos) > 0) {
        foreach ($photos as $photo) {
            echo '<img src="' . htmlspecialchars($photo['photo_url']) . '" alt="Photo" class="gallery-photo">';
        }
    } else {
        echo '<p>No photos available for this photographer.</p>';
    }
}
?>
