<!-- Gabungkan dengan function.php -->
<?php
require 'function_index.php';
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
                <button class="btn btn-sm btn-outline-success" disabled><?php echo "Leader " . $_SESSION['role'] . " - " . $productionDateDisplay . " - Shift " . $productionShiftDisplay ?></button>
            </div>
            <div class="col text-center">
                <a href="history.php" class="btn btn-sm btn-outline-primary" style="width: 150px;">Production Report</a>
            </div>
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
        <!-- Data -->
        <div class="row mt-3">
            <p class="h2">Press Painting Production Data</p>
            <?php foreach ($komponen as $kode => $data): ?>
                <div class="col-md-6 col-lg-6 col-xl-3 mt-3">
                    <div class="card text-center mb-3">
                        <div class="card-body align-middle" style="font-size: 10px;">
                            <h4 class="card-title"><?= $data['part_name'] ?></h4>
                            <!-- Header -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-primary w-100" disabled>Keterangan</button>
                                <button type="button" class="me-1 btn btn-sm btn-primary w-100" disabled>Press</button>
                                <button type="button" class="me-1 btn btn-sm btn-primary w-100" disabled>Painting</button>
                                <button type="button" class="btn btn-sm btn-primary w-100" disabled>Assy</button>
                            </div>

                            <!-- Monthly Transaction -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled>Monthly</button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['total_press'] ?></button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['total_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline w-100" disabled><?= $data['total_assy'] ?></button>
                            </div>

                            <!-- Daily Transaction -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled>Daily</button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['daily_press'] ?></button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['daily_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline w-100" disabled><?= $data['daily_assy'] ?></button>
                            </div>

                            <!-- Shift 1 Transaction -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled>Shift 1</button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift1_press'] ?></button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift1_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline w-100" disabled><?= $data['shift1_assy'] ?></button>
                            </div>

                            <!-- Shift 2 Transaction -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled>Shift 2</button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift2_press'] ?></button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift2_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline w-100" disabled><?= $data['shift2_assy'] ?></button>
                            </div>

                            <!-- Shift 3 Transaction -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled>Shift 3</button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift3_press'] ?></button>
                                <button type="button" class="me-1 btn btn-sm btn-outline w-100" disabled><?= $data['shift3_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline w-100" disabled><?= $data['shift3_assy'] ?></button>
                            </div>

                            <!-- Total BK -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-warning w-100 me-1" disabled>Total Voucher</button>
                                <button type="button" class="btn btn-sm btn-outline-warning w-100 me-1" disabled><?= $data['qty_bk_press'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline-warning w-100 me-1" disabled><?= $data['qty_bk_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline-warning w-100" disabled><?= $data['qty_bk_assy'] ?></button>
                            </div>

                            <!-- Live Stock -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-sm btn-outline-success w-100 me-1" disabled>Live Stock</button>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 me-1" disabled><?= $data['stock_press'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline-success w-100 me-1" disabled><?= $data['stock_paint'] ?></button>
                                <button type="button" class="btn btn-sm btn-outline-success w-100" disabled><?= $data['total_assy'] ?></button>
                            </div>

                            <!-- Button Finish Production -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-sm btn-primary w-100" data-bs-toggle="modal" data-bs-target="#InputProdModal<?= $data['part_code'] ?>">Finish Production</button>
                                <div class="modal fade" id="InputProdModal<?= $data['part_code'] ?>" tabindex="-1" aria-labelledby="InputProdModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="" method="post">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="InputProdModalLabel">Finish Production</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" readonly value="<?= $data['part_code'] ?>" name="part_code">
                                                        <label for="floatingInput">Part Code</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" readonly value="<?= $data['part_name'] ?>" name="part_name">
                                                        <label for="floatingInput">Part Name</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="qty">
                                                        <label for="floatingInput">Qty</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" name="btn_finish">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Button BK Production -->
                            <div class="d-flex justify-content-center mt-2">
                                <button type="button" class="btn btn-sm btn-warning w-100" data-bs-toggle="modal" data-bs-target="#InputBKModal<?= $data['part_code'] ?>">Blue & Yellow Voucher</button>
                                <div class="modal fade" id="InputBKModal<?= $data['part_code'] ?>" tabindex="-1" aria-labelledby="InputBKModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form action="" method="post">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5" id="InputBKModalLabel">Blue & Yellow Voucher</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" readonly value="<?= $data['part_code'] ?>" name="part_code">
                                                        <label for="floatingInput">Part Code</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" readonly value="<?= $data['part_name'] ?>" name="part_name">
                                                        <label for="floatingInput">Part Name</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <select name="area" class="form-select" id="floatingSelect" aria-label="Floating label select example">
                                                            <option value="press">PRESS</option>
                                                            <option value="paint">PAINT</option>
                                                            <option value="assy">ASSY</option>
                                                        </select>
                                                        <label for="floatingSelect">Area</label>
                                                    </div>
                                                    <div class="form-floating mb-3">
                                                        <input type="text" class="form-control" name="qty">
                                                        <label for="floatingInput">Qty</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-primary" name="btn_voucher">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Javascript -->
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>