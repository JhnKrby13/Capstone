<?php
require '../../vendor/autoload.php';
require '../../connection.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars($_POST['email']);
    $token = bin2hex(random_bytes(50)); 

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->rowCount() > 0) {
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email = ?");
        $stmt->execute([$token, $email]);

        $reset_link = "http://localhost/FinalWebsite/auth/password/reset_password.php?token=" . $token;

        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; 
        $mail->SMTPAuth = true;
        $mail->Username = 'haojohnkirby@gmail.com'; 
        $mail->Password = 'hlrm fbtd xftt fkmb'; 
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->setFrom('haojohnkirby@gmail.com', 'Mhark Photography');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = "Click on the link to reset your password: " . $reset_link;
        $mail->isHTML(true);

        if ($mail->send()) {
            header("Location: forgot_password.php?success=true");
            exit();
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Failed to send the password reset link.'
                });
            </script>";
        }
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'No user found with that email address.'
            });
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="forgot_password.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="wrapper">
        <form action="forgot_password.php" method="POST">
            <h2>Forgot Password</h2>
            <div class="input-field">
                <input type="email" id="email" name="email" required>
                <label for="email">Enter your Email Address</label>
            </div>
            <button type="submit">Reset Password</button>
        </form>
    </div>

    <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
        <script>
            Swal.fire({
                title: 'Password Reset Link Sent!',
                text: 'Please check your email to reset your password.',
                customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-content',
                    confirmButton: 'custom-confirm-button',
                },
                background: 'rgba(255, 255, 255, 0.15)',
                showConfirmButton: true,
                confirmButtonText: 'Okay',
                timer: 10000
            });
        </script>
    <?php endif; ?>
</body>

</html>
