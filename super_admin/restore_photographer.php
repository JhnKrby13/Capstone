<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['restore'])) {
    $photographerId = intval($_GET['restore']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM photographer_archive WHERE id = :id");
        $stmt->execute(['id' => $photographerId]);
        $photographer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($photographer) {
            $restoreStmt = $pdo->prepare("
                INSERT INTO photographers (id, name, email, contact, address)
                VALUES (:id, :name, :email, :contact, :address)
            ");
            $restoreStmt->execute([
                'id' => $photographer['id'],
                'name' => $photographer['name'],
                'email' => $photographer['email'],
                'contact' => $photographer['contact'],
                'address' => $photographer['address']
            ]);

            $deleteStmt = $pdo->prepare("DELETE FROM photographer_archive WHERE id = :id");
            $deleteStmt->execute(['id' => $photographerId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Photographer restored successfully.']);
            exit;
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Photographer not found in the archive.']);
            exit;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error restoring photographer: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
