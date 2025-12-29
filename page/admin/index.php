<?php
require '../../conn.php';
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'Admin') {
    header('location: ../../index.php');
    exit;
}

// Logout
if (isset($_POST['btn_logout'])) {
    session_destroy();
    header('location: ../../index.php');
    exit;
}

// Get data from table user
$query = "SELECT * FROM user";
$result = mysqli_query($conn, $query);
$users = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get data from table part
$query = "SELECT * FROM part";
$result = mysqli_query($conn, $query);
$parts = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Add User
if (isset($_POST['btn_add_user'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $query = "INSERT INTO user (username, password, role) VALUES ('$username', '$password', '$role')";
    mysqli_query($conn, $query);
    // Alert Success
    echo "<script>alert('Success');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

// Add Part
if (isset($_POST['btn_add_part'])) {
    $part_code = $_POST['part_code'];
    $nama_part = $_POST['nama_part'];

    $query = "INSERT INTO part (part_code, nama_part) VALUES ('$part_code', '$nama_part')";
    mysqli_query($conn, $query);
    echo "<script>alert('Success');</script>";
    echo "<script>window.location.href = 'index.php';</script>";
    exit;
}

?>

<!doctype html>
<html lang="en" data-bs-theme="auto">

<head>
    <title>AC System</title>
    <script src="../../js/color-modes.js"></script>
    <script src="../../js/jquery-3.7.1.js"></script>
    <script src="../../js/jquery-ui.js"></script>
    <link rel="stylesheet" href="../../css/jquery-ui.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/dashboard.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css" rel="stylesheet">
    <style>
        .btn-sm {
            width: 80px;
        }
    </style>
</head>

<body>
    <!-- Themes Mode -->
    <?php include '../../library/themes.php'; ?>

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
            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        <span class="float-start my-1">User Management</span>
                        <span class="float-end">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddUserModal">
                                Add User
                            </button>
                            <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="AddUserModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Add User</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingUsername" name="username">
                                                    <label for="floatingUsername">Username</label>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="password" class="form-control" id="floatingPassword" name="password">
                                                    <label for="floatingPassword">Password</label>
                                                </div>
                                                <div class="form-floating mt-3">
                                                    <select class="form-select" id="floatingSelect" name="role">
                                                        <option value="Press">Press</option>
                                                        <option value="Paint">Paint</option>
                                                        <option value="Assy">Assy</option>
                                                    </select>
                                                    <label for="floatingSelect">Role</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="btn_add_user">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- Tabel User -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Username</th>
                                    <th scope="col">Password</th>
                                    <th scope="col">Role</th>
                                    <th scope="col" style="width: 90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- List Data User -->
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($user['username']); ?></td>
                                        <td>********</td>
                                        <td><?php echo htmlspecialchars($user['role']); ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="function/user_action.php?username=<?php echo $user['username']; ?>">Manage</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- Part Management -->
            <div class="col">
                <div class="card text-center">
                    <div class="card-header">
                        <span class="float-start my-1">Part Management</span>
                        <span class="float-end">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#AddPartModal">
                                Add Part
                            </button>
                            <div class="modal fade" id="AddPartModal" tabindex="-1" aria-labelledby="AddPartModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <form action="" method="POST">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5">Add Part</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-floating mb-3">
                                                    <input type="text" class="form-control" id="floatingPartCode" name="part_code">
                                                    <label for="floatingPartCode">Part Code</label>
                                                </div>
                                                <div class="form-floating">
                                                    <input type="text" class="form-control" id="floatingPartName" name="nama_part">
                                                    <label for="floatingPartName">Nama Part</label>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary" name="btn_add_part">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div class="card-body">
                        <!-- List Data Part -->
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Part Code</th>
                                    <th scope="col">Nama Part</th>
                                    <th scope="col" style="width: 90px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- List Data Part -->
                                <?php foreach ($parts as $part) : ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($part['part_code']); ?></td>
                                        <td><?php echo htmlspecialchars($part['part_name']); ?></td>
                                        <td>
                                            <a class="btn btn-sm btn-warning" href="function/part_action.php?part_code=<?php echo $part['part_code']; ?>">Manage</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Javascript -->
        <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>