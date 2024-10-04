<?php
require '../connection.php';

session_start();

if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

if ($_SESSION["role"] === "admin") {
    
} else {
    if ($_SESSION["role"] === "client") {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: ../client/packages');
            exit();
        }
        
    } else if ($_SESSION["role"] === "photographer"){
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            header('Location: ../admin/admin_dashboard.php');
            exit();
        }
    }
}   

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'] ?? '';
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'] ?? '';

    try {
        if ($user_id) {
            if (!empty($password)) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ?, password = ? WHERE id = ?");
                $updateQuery->execute([$firstname, $lastname, $email, $hashedPassword, $user_id]);
            } else {
                $updateQuery = $pdo->prepare("UPDATE users SET firstname = ?, lastname = ?, email = ? WHERE id = ?");
                $updateQuery->execute([$firstname, $lastname, $email, $user_id]);
            }
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $insertQuery = $pdo->prepare("INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?)");
            $insertQuery->execute([$firstname, $lastname, $email, $hashedPassword]);
        }

        header("Location: manage-users.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_GET['delete'])) {
    $user_id = $_GET['delete'];
    $deleteQuery = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $deleteQuery->execute([$user_id]);
    header("Location: manage-users.php");
    exit();
}

$userQuery = $pdo->query("SELECT * FROM users");
$userResult = $userQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Clients</title>
    <link rel="stylesheet" href="manage-users.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="header">
        <img src="image/logo.png" alt="Logo" class="logo">
        <h1>Mhark Photography Manage Clients</h1>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="super_admin_dashboard.php"><i class="fas fa-home"></i> Super Admin Dashboard</a></li>
                <li><a href="manage-bookings.php"><i class="fas fa-calendar-check"></i> Manage Bookings</a></li>
                <li><a href="manage-packages.php"><i class="fas fa-box"></i> Manage Packages</a></li>
                <li><a href="manage-photographer.php"><i class="fas fa-camera"></i> Manage Photographers</a></li>
                <li><a href="manage-users.php"><i class="fas fa-users"></i> Manage Clients</a></li>
                <li><a href="manage-gallery.php"><i class="fas fa-images"></i> Manage Gallery</a></li>
                <li><a href="recent-history.php"><i class="fas fa-history"></i> Recent History</a></li>
                <li><a href="recycle-bin.php"><i class="fas fa-trash-alt"></i> Archieve</a></li>
                <li><a href="system-settings.php"><i class="fas fa-cogs"></i> Settings</a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Manage Users</h1>
            <div class="user-form">
                <h2>Add/Edit User</h2>
                <form action="manage-users.php" method="post">
                    <input type="hidden" id="user_id" name="user_id">
                    <label for="firstname">First Name:</label>
                    <input type="text" id="firstname" name="firstname" required>

                    <label for="lastname">Last Name:</label>
                    <input type="text" id="lastname" name="lastname" required>

                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>

                    <label for="password">Password:</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password">
                        <i class="toggle-password fas fa-eye" onclick="togglePassword()"></i>
                    </div>

                    <input type="submit" value="Save User">
                </form>
            </div>

            <div class="users-table">
                <h2>Users List</h2>
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($userResult)): ?>
                            <?php foreach ($userResult as $row): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['firstname']); ?></td>
                                    <td><?= htmlspecialchars($row['lastname']); ?></td>
                                    <td><?= htmlspecialchars($row['email']); ?></td>
                                    <td>
                                        <a href="javascript:void(0)" onclick="editUser(<?= $row['id']; ?>, '<?= htmlspecialchars($row['firstname']); ?>', '<?= htmlspecialchars($row['lastname']); ?>', '<?= htmlspecialchars($row['email']); ?>')">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="manage-users.php?delete=<?= $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this user?')">
                                            <i class="fas fa-trash"></i> Archieve
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5">No users found</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
        function editUser(id, firstname, lastname, email) {
            document.getElementById('user_id').value = id;
            document.getElementById('firstname').value = firstname;
            document.getElementById('lastname').value = lastname;
            document.getElementById('email').value = email;
            document.getElementById('password').value = '';
        }

        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
