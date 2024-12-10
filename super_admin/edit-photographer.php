<?php
require '../connection.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch photographer details from the database
    $stmt = $pdo->prepare("SELECT * FROM photographers WHERE id = ?");
    $stmt->execute([$id]);
    $photographer = $stmt->fetch();

    if (!$photographer) {
        echo "Photographer not found.";
        exit();
    }
} else {
    echo "No photographer ID provided.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Photographer</title>
    <link rel="stylesheet" href="edit-photographer.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="container">
        <h1 class="page-title">Edit Photographer</h1>
        <form id="editPhotographerForm" action="update-photographer.php" method="POST">
            <input type="hidden" name="id" value="<?= $photographer['id'] ?>">
            
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" class="form-control" value="<?= htmlspecialchars($photographer['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($photographer['email']) ?>" required>
            </div>

            <div class="form-group">
                <label for="contact">Contact:</label>
                <input type="text" id="contact" name="contact" class="form-control" value="<?= htmlspecialchars($photographer['contact']) ?>" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" class="form-control" value="<?= htmlspecialchars($photographer['address']) ?>" required>
            </div>

            <div class="form-group">
                <button type="button" class="btn btn-primary" onclick="confirmUpdate()">Update Photographer</button>
            </div>
        </form>
    </div>

    <script>
        function confirmUpdate() {
            Swal.fire({
                title: 'Are you sure?',
                text: 'Do you want to save the changes to this photographer?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, save it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form if the user confirms
                    document.getElementById("editPhotographerForm").submit();
                }
            });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
