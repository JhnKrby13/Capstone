<?php
require '../connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['restore'])) {
    $packageId = intval($_GET['restore']);

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM package_archive WHERE id = :id");
        $stmt->execute(['id' => $packageId]);
        $package = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($package) {
            $restoreStmt = $pdo->prepare("
                INSERT INTO packages (id, name, price, description)
                VALUES (:id, :name, :price, :description)
            ");
            $restoreStmt->execute([
                'id' => $package['id'],
                'name' => $package['name'],
                'price' => $package['price'],
                'description' => $package['description']
            ]);

            $deleteStmt = $pdo->prepare("DELETE FROM package_archive WHERE id = :id");
            $deleteStmt->execute(['id' => $packageId]);

            $pdo->commit();

            echo json_encode(['status' => 'success', 'message' => 'Package restored successfully.']);
            exit;
        } else {
            $pdo->rollBack();
            echo json_encode(['status' => 'error', 'message' => 'Package not found in the archive.']);
            exit;
        }
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(['status' => 'error', 'message' => 'Error restoring package: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}
?>
