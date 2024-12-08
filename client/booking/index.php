<?php
require '../../connection.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header('Location: ../../auth/login.php');
    exit;
}

// Role-based redirection
if ($_SESSION["role"] !== "admin" && $_SESSION["role"] !== "client") {
    if (!empty($_SERVER['HTTP_REFERER'])) {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    } else {
        header('Location: ../../admin/admin_dashboard.php');
    }
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !isset($_POST['logout'])) {
    try {
        $name = htmlspecialchars($_POST['name']);
        $address = htmlspecialchars($_POST['address']);
        $package_types = array_map('htmlspecialchars', $_POST['package_type'] ?? []);
        $price = htmlspecialchars($_POST['price']);
        $venue = htmlspecialchars($_POST['venue']);
        $datetime = htmlspecialchars($_POST['datetime']);
        $payment_mode = htmlspecialchars($_POST['payment_mode']);
        $photographer_ids = array_map('htmlspecialchars', $_POST['photographer_id'] ?? []);
        $client_id = $_SESSION['user_id'];

        $datetime = DateTime::createFromFormat('M d, Y h:i A', $datetime);
        if ($datetime === false) {
            throw new Exception("Invalid datetime format.");
        }
        $datetime = $datetime->format('Y-m-d H:i:s');

        $pdo->beginTransaction();

        $stmt = $pdo->prepare("INSERT INTO booking (name, address, package_type, venue, datetime, price, payment_mode, client_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$name, $address, json_encode($package_types), $venue, $datetime, $price, $payment_mode, $client_id]);
        $booking_id = $pdo->lastInsertId();
        
        $pdo->commit();
        echo json_encode(['success' => true]);
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("PDOException: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: ../../auth/login.php');
    exit;
}

$package_prices = [];
$stmt = $pdo->query("SELECT name, price FROM packages");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $package_prices[$row['name']] = $row['price'];
}

$photographers = [];
$stmt = $pdo->query("SELECT id, name FROM photographers");
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $photographers[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings</title>
    <link rel="stylesheet" href="booking.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    <div class="header">
        <div class="sub-header">
            <i class="fas fa-bars hamburger" id="toggleSidebar"></i>
            <img src="../image/logo.png" alt="Logo" class="logo">
            <p id="mark">Mhark Photography</p>
        </div>
        <div class="profile-dropdown">
            <h1 style="color:white; font-size: 24px; margin-right: 15px; ">
                <?php
                echo $_SESSION['firstname'];
                ?>
            </h1>
            <div class="dropdown">
                <button class="btn btn-secondary rounded-circle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user-circle "></i>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <a class="dropdown-item" href="../../auth/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </ul>
            </div>
        </div>
    </div>
    <div class="dashboard">
        <div class="sidebar">
            <ul>
                <li><a href="../packages/"><i class="fas fa-box"></i> <span>Packages</span></a></li>
                <li><a href="./" class="active"><i class="fas fa-calendar-check"></i> <span>Booking</span></a></li>
                <li><a href="../photographer/"><i class="fas fa-camera"></i> <span>Photographer List</span></a></li>
                <li><a href="../gallery/"><i class="fas fa-images"></i> <span>Gallery</span></a></li>
            </ul>
        </div>
        <div class="content">
            <h1>Booking</h1>
            <form id="bookingForm" action="index.php" method="post" autocomplete="off">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="address">Address:</label>
                    <input type="text" id="address" name="address" required>
                </div>

                <div class="form-group">
                    <label for="package_type">Package Type:</label>
                    <div id="package_type_dropdown" class="dropdown">

                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Packages
                        </button>

                        <div class="dropdown-menu p-3 shadow-lg" aria-labelledby="dropdownMenuButton" style="max-height: 300px; width: 100%; overflow-y: auto; border-radius: 10px; border: 1px solid #ddd;">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select_all_packages">
                                <label class="form-check-label" for="select_all_packages">Select All Packages</label>
                            </div>
                            <hr>

                            <?php foreach ($package_prices as $type => $pkg_price): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="package_type[]" value="<?php echo htmlspecialchars($type); ?>" id="package_<?php echo htmlspecialchars($type); ?>" data-price="<?php echo htmlspecialchars($pkg_price); ?>">
                                    <label class="form-check-label" for="package_<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="text" id="price" name="price" required readonly>
                </div>

                <div class="form-group">
                    <label for="venue">Venue:</label>
                    <input type="text" id="venue" name="venue" required>
                </div>

                <div class="form-group">
                    <label for="datetime">Date and Time:</label>
                    <input type="text" id="datetime" name="datetime" required>
                </div>

                <div class="form-group">
                    <label for="payment_mode">Payment Mode:</label>
                    <select id="payment_mode" name="payment_mode" required>
                        <option value="cash">Cash</option>
                        <option value="gcash">GCash</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="photographer_id">Photographers:</label>
                    <div id="photographer_dropdown" class="dropdown">

                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPhotographersButton" data-bs-toggle="dropdown" aria-expanded="false">
                            Select Photographers
                        </button>

                        <div class="dropdown-menu p-3 shadow-lg" aria-labelledby="dropdownPhotographersButton" style="max-height: 300px; width: 300%; overflow-y: auto; border-radius: 10px; border: 1px solid #ddd;">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="select_all_photographers">
                                <label class="form-check-label" for="select_all_photographers">Select All Photographers</label>
                            </div>
                            <hr>
                            <?php foreach ($photographers as $photographer): ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="photographer_id[]" value="<?php echo htmlspecialchars($photographer['id']); ?>" id="photographer_<?php echo htmlspecialchars($photographer['id']); ?>">
                                    <label class="form-check-label" for="photographer_<?php echo htmlspecialchars($photographer['id']); ?>"><?php echo htmlspecialchars($photographer['name']); ?></label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <button type="submit" id="submitButton" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelector('.hamburger').addEventListener('click', () => {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            sidebar.classList.toggle('collapsed');
        });

        $(document).ready(function() {
            $('#datetime').datetimepicker({
                dateFormat: 'M dd, yy',
                timeFormat: 'hh:mm TT'
            });

            function updatePackagePrice() {
                var total = 0;
                $('input[name="package_type[]"]:checked').each(function() {
                    total += parseFloat($(this).data('price'));
                });
                $('#price').val(total.toFixed(2));
            }

            $('input[name="package_type[]"]').change(updatePackagePrice);

            $('#select_all_packages').change(function() {
                var isChecked = $(this).is(':checked');
                $('input[name="package_type[]"]').prop('checked', isChecked);
                updatePackagePrice();
            });

            $('#select_all_photographers').change(function() {
                var isChecked = $(this).is(':checked');
                $('input[name="photographer_id[]"]').prop('checked', isChecked);
            });

            $('#bookingForm').submit(function(e) {
                e.preventDefault();

                Swal.fire({
                    title: "Are you sure?",
                    text: "You want to book this?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, book it",
                    cancelButtonText: "No, cancel",
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'POST',
                            url: 'index.php',
                            data: $('#bookingForm').serialize(),
                            success: function(response) {
                                console.log('Server response:', response);
                                try {
                                    const res = JSON.parse(response);
                                    if (res.success) {
                                        Swal.fire({
                                            title: "Booking Confirmed!",
                                            text: "Your booking has been successfully made.",
                                            icon: "success"
                                        }).then(() => {
                                            window.location.href = 'index.php';
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Error!",
                                            text: res.message || "Unknown error occurred.",
                                            icon: "error"
                                        });
                                    }
                                } catch (e) {
                                    console.error('Parsing error:', e);
                                    Swal.fire({
                                        title: "Error!",
                                        text: "An error occurred while processing your request. Could not parse server response.",
                                        icon: "error"
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                console.error('AJAX error:', status, error);
                                console.log('Server response:', xhr.responseText);
                                Swal.fire({
                                    title: "Error!",
                                    text: "An error occurred while processing your request. See console for details.",
                                    icon: "error"
                                });
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Cancelled",
                            text: "Your booking request has been cancelled.",
                            icon: "error"
                        });
                    }
                });
            });
        });

        const accountButton = document.getElementById('accountButton');
        const dropdownContent = document.getElementById('dropdownContent');


        accountButton.addEventListener('click', function() {
            dropdownContent.classList.toggle('show');
        });


        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                if (dropdownContent.classList.contains('show')) {
                    dropdownContent.classList.remove('show');
                }
            }
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>

</html>