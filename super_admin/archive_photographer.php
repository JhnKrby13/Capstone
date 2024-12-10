<?php
require '../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    try {
        $pdo->beginTransaction();

        // Fetch the photographer's details
        $stmt = $pdo->prepare("SELECT * FROM photographers WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $photographer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photographer) {
            // Insert into photographer_archive
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

            // Delete from photographers table
            $deleteStmt = $pdo->prepare("DELETE FROM photographers WHERE id = :id");
            $deleteStmt->execute(['id' => $id]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Photographer archived successfully.']);
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Photographer not found.']);
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
