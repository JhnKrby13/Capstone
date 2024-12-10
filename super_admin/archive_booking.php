<?php
require '../connection.php';  // Make sure to include your database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $photographerId = intval($_POST['id']);  // Get the photographer ID from the POST request

    try {
        $pdo->beginTransaction();

        // Fetch the photographer details
        $stmt = $pdo->prepare("SELECT * FROM photographers WHERE id = :id");
        $stmt->execute(['id' => $photographerId]);
        $photographer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photographer) {
            // Insert photographer into the photographer_archive table
            $archiveStmt = $pdo->prepare("
                INSERT INTO photographer_archive (id, name, email, contact, address)
                VALUES (:id, :name, :email, :contact, :address)
            ");
            $archiveStmt->execute([
                'id' => $photographer['id'],
                'name' => $photographer['name'],
                'email' => $photographer['email'],
                'contact' => $photographer['contact'],
                'address' => $photographer['address']
            ]);

            // Delete the photographer from the photographers table
            $deleteStmt = $pdo->prepare("DELETE FROM photographers WHERE id = :id");
            $deleteStmt->execute(['id' => $photographerId]);

            $pdo->commit();

            // Redirect to the manage photographers page with a success flag
            header('Location: manage-photographers.php?success=1');
            exit;
        } else {
            // Rollback if the photographer is not found
            $pdo->rollBack();
            echo "Photographer not found.";
        }
    } catch (Exception $e) {
        // Rollback in case of error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
    }
} else {
    // If the request method is not POST or ID is not set, redirect
    header('Location: manage-photographers.php');
    exit;
}
?>
