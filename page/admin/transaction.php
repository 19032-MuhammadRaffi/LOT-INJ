<!-- Gabungkan dengan function.php -->
<?php
require 'function_transaction.php';
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
</head>

<body>
    <!-- Themes Mode -->
    <?php include '../../library/themes.php'; ?>

    <div class="container-fluid text-center">
        <!-- Heading -->
        <div class="row mt-3">
            <div class="col text-start">
                <button class="btn btn-sm btn-outline-success" disabled><?php echo "Admin" ?></button>
            </div>
            <div class="col text-center">
                <a href="index.php" class="btn btn-sm btn-outline-primary mb-1" style="width: 150px;">Dashboard</a>
            </div>
            <div class="col text-end">
                <button type="button" class="btn btn-sm btn-danger" style="width: 150px;" data-bs-toggle="modal" data-bs-target="#logoutModal">Logout</button>
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
        <!-- Data -->
        <div class="row mt-4">
            <p class="h2">Injection Production Data</p>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm mt-3 text-center mx-auto" style="min-width: 1000px; width: auto;">
                    <thead class="table-dark">
                        <tr>
                            <th style="width: 50px;">No</th>
                            <th>Part Code</th>
                            <th>Part Name</th>
                            <th>Date</th>
                            <th>Shift</th>
                            <th>Qty</th>
                            <th style="width: 100px;">Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (mysqli_num_rows($queryTransaction) > 0) {
                            while ($row = mysqli_fetch_assoc($queryTransaction)) {
                        ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['part_code']; ?></td>
                                    <td><?= $row['part_name'] ?? '-'; ?></td>
                                    <td><?= date('d-m-Y H:i', strtotime($row['date_tr'])); ?></td>
                                    <td class="text-center"><?= $row['shift']; ?></td>
                                    <td class="text-end"><?= number_format($row['qty']); ?></td>
                                    <td class="text-center">
                                        <span class="badge bg-success w-100"><?= $row['status']; ?></span>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" onsubmit="return confirm('Delete this transaction?')" class="m-0 p-0">
                                            <input type="hidden" style="width: 1px; height:1px;" name="delete_id" value="<?= $row['id']; ?>">
                                            <button type="submit" class="btn btn-sm btn-danger my-auto w-100" style="font-size: 8px;">
                                                DELETE
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                        <?php
                            }
                        } else {
                            echo '<tr><td colspan="8" class="text-center">No data found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Javascript -->
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>