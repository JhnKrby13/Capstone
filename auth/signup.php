<?php
require '../connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT role FROM users WHERE id = :id";
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':id', $user_id);
    $statement->execute();
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $role = $user['role'];
        switch ($role) {
            case 'admin':
                header('Location: ../super_admin/super_admin_dashboard.php');
                exit;
            case 'photographer':
                header('Location: ../admin/admin_dashboard.php');
                exit;
            case 'client':
            default:
                header('Location: ../client/packages/');
                exit;
        }
    } else {
        header('Location: ../client/packages/');
        exit;
    }
}

$firstname = $lastname = $email = $contact = $address = '';
$error = '';
$successMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $email = htmlspecialchars($_POST['email']);
    $contact = htmlspecialchars($_POST['contact']);
    $address = htmlspecialchars($_POST['address']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($firstname) || empty($lastname) || empty($email) || empty($contact) || empty($address) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        $sql = "SELECT * FROM users WHERE email = :email";
        $statement = $pdo->prepare($sql);
        $statement->bindParam(':email', $email);
        $statement->execute();

        if ($statement->rowCount() > 0) {
            $error = "Email already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $verification_code = md5(rand());

            $sql = "INSERT INTO users (firstname, lastname, email, contact, address, password, role, verification_code) 
                    VALUES (:firstname, :lastname, :email, :contact, :address, :password, 'client', :verification_code)";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':firstname', $firstname);
            $statement->bindParam(':lastname', $lastname);
            $statement->bindParam(':email', $email);
            $statement->bindParam(':contact', $contact);
            $statement->bindParam(':address', $address);
            $statement->bindParam(':password', $hashed_password);
            $statement->bindParam(':verification_code', $verification_code);

            if ($statement->execute()) {
                $mail = new PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'haojohnkirby@gmail.com';
                    $mail->Password = 'hlrm fbtd xftt fkmb';
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true,
                        ),
                    );

                    $mail->SMTPDebug = 2;
                    $mail->Debugoutput = 'html';

                    $mail->setFrom('haojohnkirby@gmail.com', 'Mhark Photography');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Verify your email address';
                    $mail->Body = 'Please click the link to verify your email: <a href="http://localhost/FinalWebsite/auth/verify.php?code=' . $verification_code . '">Verify Email</a>';

                    $mail->send();

                    $successMessage = "Please check your email to verify your account.";
                    header('Location: signup.php?success=true');
                    exit;
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                $error = "An error occurred during registration!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style/signup.css">
    <link rel="stylesheet" href="style/form-popup.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>

    <div class="wrapper">
        <form action="signup.php" method="post" autocomplete="off">
            <h2>Sign Up</h2>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($successMessage): ?>
                <div class="alert alert-success"><?php echo $successMessage; ?></div>
            <?php endif; ?>

            <div class="input-field">
                <input type="text" id="fname" name="firstname" value="<?php echo htmlspecialchars($firstname); ?>" required>
                <label for="fname">First Name</label>
            </div>
            <div class="input-field">
                <input type="text" id="lname" name="lastname" value="<?php echo htmlspecialchars($lastname); ?>" required>
                <label for="lname">Last Name</label>
            </div>
            <div class="input-field">
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                <label for="email">Email Address</label>
            </div>
            <div class="input-field">
                <input type="text" id="contact" name="contact" value="<?php echo htmlspecialchars($contact); ?>" required>
                <label for="contact">Contact Number</label>
            </div>
            <div class="input-field">
                <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                <label for="address">Address</label>
            </div>
            <div class="input-field password-field">
                <input type="password" name="password" id="password" required>
                <span class="toggle-password" id="toggle_password" onclick="togglePasswordVisibility('password')"></span>
                <label for="password">Password</label>
            </div>
            <div class="input-field password-field">
                <input type="password" name="confirm_password" id="confirm_password" required>
                <span class="toggle-password" id="toggle_confirm_password" onclick="togglePasswordVisibility('confirm_password')"></span>
                <label for="confirm_password">Confirm Password</label>
            </div>
            <div class="button-container">
                <button type="submit">Sign Up</button>
                <button type="button" onclick="window.location.href='login.php'">Cancel</button>
            </div>
        </form>
    </div>

    <script>
        function togglePasswordVisibility(fieldId) {
            var passwordField = document.getElementById(fieldId);
            var toggleBtn = document.getElementById('toggle_' + fieldId);

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleBtn.style.backgroundImage = 'url("https://img.icons8.com/material-outlined/24/visible.png")';
            } else {
                passwordField.type = 'password';
                toggleBtn.style.backgroundImage = 'url("https://img.icons8.com/material-outlined/24/invisible.png")';
            }
        }

        <?php if (isset($_GET['success']) && $_GET['success'] == 'true'): ?>
            Swal.fire({
                title: 'Registration successful!',
                text: '"Please check your email to verify your account."', 
                // icon: 'success',
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

        <?php elseif ($error == "Email already exists!"): ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?php echo addslashes($error); ?>',
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
        <?php endif; ?>
    </script>
</body>

</html>