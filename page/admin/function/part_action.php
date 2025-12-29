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

// Get data from table part
$part_code = isset($_GET['part_code']) ? $_GET['part_code'] : '';
$query = "SELECT * FROM part WHERE part_code = '$part_code'";
$result = mysqli_query($conn, $query);
$part = mysqli_fetch_assoc($result);

// Update part
if (isset($_POST['btn_update_part'])) {
    $part_code = $_POST['part_code'];
    $part_name = $_POST['part_name'];
    $qty_press = $_POST['qty_press'];
    $qty_paint = $_POST['qty_paint'];

    $query = "UPDATE part SET part_name = '$part_name', qty_press = '$qty_press', qty_paint = '$qty_paint' WHERE part_code = '$part_code'";
    mysqli_query($conn, $query);
    // Alert Success
    echo "<script>alert('Success');</script>";
    echo "<script>window.location.href = '../index.php';</script>";
    exit;
}

// Delete part
if (isset($_POST['btn_delete_part'])) {
    $part_code = $_POST['part_code'];

    $query = "DELETE FROM part WHERE part_code = '$part_code'";
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
            <!-- Part Management -->
            <div class="col-4 mx-auto">
                <div class="card text-center">
                    <div class="card-header">
                        <div>Part Management</span>
                        </div>
                        <div class="card-body">
                            <form action="" method="post">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="part_code" value="<?php echo htmlspecialchars($part['part_code']); ?>" readonly>
                                    <label for="floatingInput">Part Code</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="part_name" value="<?php echo htmlspecialchars($part['part_name']); ?>">
                                    <label for="floatingInput">Part Name</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="qty_press" value="<?php echo htmlspecialchars($part['qty_press']); ?>">
                                    <label for="floatingInput">Qty Press</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" name="qty_paint" value="<?php echo htmlspecialchars($part['qty_paint']); ?>">
                                    <label for="floatingInput">Qty Paint</label>
                                </div>
                                <!-- Button Edit -->
                                <button type="submit" class="btn btn-primary w-100" name="btn_update_part">Update Part</button>
                                <!-- Button Delete -->
                                <button type="button" class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#deletePartModal">
                                    Delete Part
                                </button>
                                <!-- Modal -->
                                <div class="modal fade" id="deletePartModal" tabindex="-1" aria-labelledby="deletePartModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="deletePartModalLabel">Delete Part</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                Are you sure you want to delete part "<?php echo htmlspecialchars($part['part_code']); ?>"?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                                                <button type="submit" name="btn_delete_part" class="btn btn-danger">Yes</button>
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