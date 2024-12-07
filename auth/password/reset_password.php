<?php
require '../../connection.php';
session_start();

date_default_timezone_set('Asia/Manila');

if (!isset($_GET['token']) || empty($_GET['token'])) {
    echo "Invalid or missing token!";
    exit();
}

$token = htmlspecialchars($_GET['token']);

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE reset_token = ?");
    $stmt->execute([$token]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if (strtotime($user['reset_expires']) < time()) {
            echo "The token has expired!";
            exit();
        }
    } else {
        echo "Invalid token!";
        exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $password = htmlspecialchars($_POST['password']);
        $confirm_password = htmlspecialchars($_POST['confirm_password']);

        if ($password !== $confirm_password) {
            // Store the error in a session variable to display it with SweetAlert
            $_SESSION['error'] = "Passwords do not match!";
            header("Location: reset_password.php?token=$token");
            exit();
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
        $stmt->execute([$hashed_password, $token]);

        if ($stmt->rowCount() > 0) {
            $_SESSION['password_reset'] = true;
        } else {
            echo "No rows were updated. Token might be invalid or expired.";
            exit();
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link rel="stylesheet" href="reset_password.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <form action="" method="post">
            <h2>Reset Password</h2>
            <div class="input-field">
                <input type="password" id="password" name="password" required>
                <label for="password">New Password</label>
            </div>
            <div class="input-field">
                <input type="password" id="confirm_password" name="confirm_password" required>
                <label for="confirm_password">Confirm New Password</label>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "<?php echo $_SESSION['error']; ?>",
                icon: "error",
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-content',
                    confirmButton: 'custom-confirm-button',
                    timer: 1500
                },
                background: 'rgba(255, 255, 255, 0.15)',
                sshowConfirmButton: false,
                confirmButtonText: 'Okay'
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['password_reset']) && $_SESSION['password_reset'] == true): ?>
        <script>
            Swal.fire({
                title: "Reset Successful!",
                text: "Your password has been reset.",
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-content',
                    confirmButton: 'custom-confirm-button',
                    timer: 1500
                },
                background: 'rgba(255, 255, 255, 0.15)',
                sshowConfirmButton: false,
                confirmButtonText: 'Okay'
            }).then(function() {
                window.location.href = "../../auth/login.php";
            });
        </script>
        <?php unset($_SESSION['password_reset']); ?>
    <?php endif; ?>
</body>

</html>
