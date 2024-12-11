<?php
require '../connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $userId = intval($_POST['id']);

    try {
        $pdo->beginTransaction();

        // Fetch user data from the archive
        $stmt = $pdo->prepare("SELECT * FROM client_archive WHERE id = :id");
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Insert into the main table
            $restoreStmt = $pdo->prepare("
                INSERT INTO clients (id, firstname, lastname, address, email, contact)
                VALUES (:id, :firstname, :lastname, :address, :email, :contact)
            ");
            $restoreStmt->execute([
                'id' => $user['id'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'address' => $user['address'],
                'email' => $user['email'],
                'contact' => $user['contact']
            ]);

            // Remove from the archive table
            $deleteStmt = $pdo->prepare("DELETE FROM client_archive WHERE id = :id");
            $deleteStmt->execute(['id' => $userId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Client restored successfully!']);
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Client not found.']);
        }
    } catch (PDOException $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'An error occurred: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
}
