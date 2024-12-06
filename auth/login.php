<?php
require '../connection.php';
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (isset($_SESSION['user_id'])) {
  $user_id = $_SESSION['user_id'];
  $sql = "SELECT role FROM users WHERE id = :user_id";
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':user_id', $user_id);
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
      default:
        header('Location: ../client/packages/');
        exit;
    }
  } else {
    header('Location: ../client/packages/');
    exit;
  }
}

$email = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $email = htmlspecialchars($_POST['email']);
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE email = :email";
  $statement = $pdo->prepare($sql);
  $statement->bindParam(':email', $email);
  $statement->execute();

  $user = $statement->fetch(PDO::FETCH_ASSOC);

  if ($user) {
    if ($user['is_verified'] == 0) {
      $message = "Your account is not verified. Please check your email to verify your account.";
    } elseif (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['firstname'] = $user['firstname'];
      $_SESSION['lastname'] = $user['lastname'];
      $_SESSION['email'] = $user['email'];
      $_SESSION['role'] = $user['role'];

      $role = $user['role'];
      switch ($role) {
        case 'admin':
          header('Location: ../super_admin/super_admin_dashboard.php');
          exit;
        case 'photographer':
          header('Location: ../admin/admin_dashboard.php');
          exit;
        default:
          header('Location: ../client/packages/');
          exit;
      }
    } else {
      $message = "Invalid Password";
    }
  } else {
    $message = "Invalid Email";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Log In</title>
  <link rel="stylesheet" href="style/login.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="wrapper" id="signIn">
    <form id="loginForm" method="post" action="login.php" autocomplete="off">
      <h2>Login</h2>
      <?php if (!empty($message)) : ?>
        <script>
          Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '<?php echo addslashes($message); ?>',
            customClass: {
                    popup: 'custom-swal-popup',
                    title: 'custom-swal-title',
                    content: 'custom-swal-content',
                    confirmButton: 'custom-confirm-button',  
                    cancelButton: 'custom-cancel-button'   
                },
                background: 'rgba(255, 255, 255, 0.15)',
                showCancelButton: true,  
                cancelButtonText: 'Cancel',  
                confirmButtonText: 'Okay'  
            });
        </script>
      <?php endif; ?>
      <div class="input-field">
        <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>" required>
        <label for="email">Enter your email</label>
      </div>
      <div class="input-field password-field">
        <input type="password" name="password" id="password" required>
        <span class="toggle-password" onclick="togglePasswordVisibility()"></span>
        <label for="password">Enter your password</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Remember me</p>
        </label>
        <a href="password/forgot_password.php">Forgot password?</a>
      </div>
      <button type="submit" name="signIn" value="signin">Log In</button>
      <div class="register">
        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
      </div>
    </form>

    <script>
      function togglePasswordVisibility() {
        var passwordField = document.getElementById('password');
        var toggleBtn = document.querySelector('.toggle-password');

        if (passwordField.type === 'password') {
          passwordField.type = 'text';
          toggleBtn.style.backgroundImage = 'url("https://img.icons8.com/material-outlined/24/visible.png")';
        } else {
          passwordField.type = 'password';
          toggleBtn.style.backgroundImage = 'url("https://img.icons8.com/material-outlined/24/invisible.png")';
        }
      }
    </script>
  </div>
</body>

</html>