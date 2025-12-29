<!-- Gabungkan dengan function.php -->
<?php
require 'function_history.php';
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
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: space-between;
        }

        .chart-container {
            width: 32%;
            /* karena sekarang ada 3 chart */
            height: 300px;
        }
    </style>
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
                <a href="index.php" class="btn btn-sm btn-outline-primary" style="width: 150px;">Dashboard</a>
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
        <!-- Form Select Date -->
        <div class="row">
            <form method="POST" class="mt-3 d-flex gap-2 align-items-center justify-content-center">
                <select name="month" class="form-select w-auto">
                    <?php for ($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $m == (int)$selectedMonth ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <select name="year" class="form-select w-auto">
                    <?php for ($y = 2023; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>" <?= $y == (int)$selectedYear ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
                <button type="submit" class="btn btn-primary">Tampilkan</button>
            </form>
        </div>
        <!-- Table Report -->
        <div class="row">
            <!-- Production History -->
            <h4 class="text-center my-3">History Production - <?= date('F', mktime(0, 0, 0, $selectedMonth, 1)) ?> <?= $selectedYear ?></h4>
            <?php
            foreach ($komponen as $kode => $data): ?>
                <div class="col-md-6 col-lg-6 col-xl-3 mt-2">
                    <div class="card text-center mb-3">
                        <div class="card-body align-middle" style="font-size: 10px;">
                            <h4 class="card-title"><?= $data['part_name'] ?></h4>

                            <!-- Monthly -->
                            <div class="d-flex justify-content-center mt-2">
                                <button class="btn btn-sm btn-primary w-100" disabled>Monthly</button>
                            </div>
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-primary w-100 me-1" disabled>Keterangan</button>
                                <button class="btn btn-sm btn-primary w-100 me-1" disabled>Press</button>
                                <button class="btn btn-sm btn-primary w-100 me-1" disabled>Painting</button>
                                <button class="btn btn-sm btn-primary w-100" disabled>Assy</button>
                            </div>

                            <!-- Total Production -->
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled>Output</button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['total_press'] ?></button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['total_paint'] ?></button>
                                <button class="btn btn-sm btn-outline w-100" disabled><?= $data['total_assy'] ?></button>
                            </div>

                            <!-- Monthly Bon Kuning -->
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled>Voucher</button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['qty_bk_press'] ?></button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['qty_bk_paint'] ?></button>
                                <button class="btn btn-sm btn-outline w-100" disabled><?= $data['qty_bk_assy'] ?></button>
                            </div>

                            <!-- End Stock -->
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled>End Stock</button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['qty_end_press'] ?></button>
                                <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $data['qty_end_paint'] ?></button>
                                <button class="btn btn-sm btn-outline w-100" disabled><?= $data['qty_end_assy'] ?></button>
                            </div>

                            <!-- Daily -->
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-success w-100" disabled>Daily</button>
                            </div>
                            <div class="d-flex justify-content-center mt-1">
                                <button class="btn btn-sm btn-success w-100 me-1" disabled>Keterangan</button>
                                <button class="btn btn-sm btn-success w-100 me-1" disabled>Press</button>
                                <button class="btn btn-sm btn-success w-100 me-1" disabled>Painting</button>
                                <button class="btn btn-sm btn-success w-100" disabled>Assy</button>
                            </div>

                            <!-- Daily per tanggal -->
                            <?php foreach ($dates as $date): ?>
                                <?php
                                $day = date('j', strtotime($date)); // ambil tanggal 1–31
                                $press = array_sum($dataPress[$kode][$date] ?? []);
                                $paint = array_sum($dataPaint[$kode][$date] ?? []);
                                $assy  = array_sum($dataAssy[$kode][$date] ?? []);
                                ?>
                                <div class="d-flex justify-content-center mt-1">
                                    <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $day ?></button>
                                    <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $press ?></button>
                                    <button class="btn btn-sm btn-outline w-100 me-1" disabled><?= $paint ?></button>
                                    <button class="btn btn-sm btn-outline w-100" disabled><?= $assy ?></button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
        <!-- Javascript -->
        <script src="../../js/bootstrap.bundle.min.js"></script>
</body>

</html>