<?php
require '../../../conn.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('location: ../../../index.php');
    exit;
}

// Logout
if (isset($_POST['btn_logout'])) {
    session_destroy();
    header('location: ../../../index.php');
    exit;
}

// Get data from table User
$username = isset($_GET['username']) ? $_GET['username'] : '';
$query = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

// Update user
if (isset($_POST['btn_update_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "UPDATE user SET password = '$password', role = '$role' WHERE username = '$username'";
    mysqli_query($conn, $query);
    echo "<script>alert('Success');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
     exit;
}

// Delete user
if (isset($_POST['btn_delete_user'])) {
    $username = $_POST['username'];

    $query = "DELETE FROM user WHERE username = '$username'";
    mysqli_query($conn, $query);
    // Alert Success
    echo "<script>alert('Success');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
    exit;
}
?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <title>AC System</title>
    <script src="../../../js/color-modes.js"></script>
    <script src="../../../js/jquery-3.7.1.js"></script>
    <script src="../../../js/jquery-ui.js"></script>
    <link rel="stylesheet" href="../../../css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../../css/style.css" rel="stylesheet">
    <style>
        .btn-sm {
            width: 80px;
        }
    </style>
</head>

<body>
    <!-- Themes Mode -->
    <?php include '../../../library/themes.php'; ?>

    <div class="container-fluid text-center">
        <!-- ROW 1 -->
        <div class="row mt-3">
            <div class="col text-end">
                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
            </div>

            <!-- Modal Logout -->
            <div class="text-start modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form action="" method="POST">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="logoutModalLabel">Notification</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                Logout?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                <button type="submit" class="btn btn-primary" name="btn_logout">Yes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- ROW 2 -->
        <h3>Data Master</h3>
        <div class="row mt-3">
            <!-- User Management -->
            <div class="col-4 mx-auto">
                <div class="card text-center">
                    <div class="card-header">
                        <div>User Management</span>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingInput" name="password" value="<?php echo htmlspecialchars($user['password']); ?>">
                                    <label for="floatingInput">Password</label>
                                </div>
                                <div class="form-floating mt-3">
                                    <select class="form-select" id="floatingSelect" name="role">
                                        <option value="Admin" <?php if ($user['role'] == 'Admin') echo 'selected'; ?>>Admin</option>
                                        <option value="Injection" <?php if ($user['role'] == 'Injection') echo 'selected'; ?>>Injection</option>
                                        <option value="Assy" <?php if ($user['role'] == 'Assy') echo 'selected'; ?>>Assy</option>
                                    </select>
                                    <label for="floatingSelect">Role</label>
                                </div>
                                <!-- Button Edit -->
                                <button type="submit" class="btn btn-primary w-100 mt-3" name="btn_update_user">Update User</button>
                                <!-- Button Delete -->
                                <button type="button" class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#deleteUserModal">
                                    Delete User
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="deleteUserModalLabel">Delete User</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete user "<?php echo htmlspecialchars($user['username']); ?>"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                <button type="submit" name="btn_delete_user" class="btn btn-danger">Yes</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Javascript -->
            <script src="../../../js/bootstrap.bundle.min.js"></script>
</body>

</html>