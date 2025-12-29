<?php
// Database Connection
require '../../conn.php';

// Session Check
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Assy') {
    header('location: ../../index.php');
    exit;
}

// Auto Refresh 60s
echo '<meta http-equiv="refresh" content="60">';

// Logout
if (isset($_POST['btn_logout'])) {
    session_destroy();
    header('location: ../../index.php');
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$currentDate = date('Y-m-d');

// Function Production Date
function getProductionDateOnly($datetime)
{
    $time = date('H:i', strtotime($datetime));
    $date = date('Y-m-d', strtotime($datetime));

    if ($time < '08:00') {
        return date('Y-m-d', strtotime($date . ' -1 day'));
    }
    return $date;
}

// Function Shift
function getShift($time)
{
    if ($time >= '08:00' && $time < '17:00') return 1;
    if ($time >= '17:00' || $time < '00:30') return 2;
    return 3;
}

// Function Update Table History_LS
function updateHistoryLS($conn)
{
    $partResult = mysqli_query($conn, "
        SELECT part_code, qty_press, qty_paint 
        FROM part
    ");

    while ($row = mysqli_fetch_assoc($partResult)) {
        $partCode   = $row['part_code'];
        $qtyPress   = (int)$row['qty_press'];
        $qtyPaint   = (int)$row['qty_paint'];

        // CEK HISTORY_LS TABLE FOR MONTHLY IF EXISTS UPDATE ELSE INSERT
        $historyCheck = mysqli_query($conn, "
            SELECT * FROM history_ls
            WHERE part_code = '$partCode'
            AND DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ");

        // TABLE HISTORY_LS UPDATE / INSERT
        if (mysqli_num_rows($historyCheck) > 0) {
            // UPDATE
            mysqli_query($conn, "
                UPDATE history_ls
                SET qty_end_press = $qtyPress,
                    qty_end_paint = $qtyPaint
                WHERE part_code = '$partCode'
                AND DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
            ");
        } else {
            // INSERT
            mysqli_query($conn, "
                INSERT INTO history_ls
                (date_prod, part_code, qty_end_press, qty_end_paint)
                VALUES
                (CURDATE(), '$partCode', $qtyPress, $qtyPaint)
            ");
        }

        // END STOCK ASSY FROM TRANSACTION TABLE 
        $assyStockQuery = "
            SELECT SUM(qty) AS total_assy
            FROM `transaction`
            WHERE status = 'ASSY'
                AND DATE_FORMAT(date_tr, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
                AND part_code = '$partCode'
        ";
        $assyStockResult = mysqli_query($conn, $assyStockQuery);
        $assyStockData = mysqli_fetch_assoc($assyStockResult);
        $totalAssy = (int)$assyStockData['total_assy'];
    }
}
updateHistoryLS($conn);

// GET DATA PART
$partResult = mysqli_query($conn, "
    SELECT part_code, part_name 
    FROM part
");
$komponen = [];
while ($row = mysqli_fetch_assoc($partResult)) {
    $komponen[$row['part_code']] = [
        'part_code' => $row['part_code'],
        'part_name' => $row['part_name'],
        'total_press'  => 0,
        'total_paint'  => 0,
        'total_assy'   => 0,
        'daily_press'  => 0,
        'daily_paint'  => 0,
        'daily_assy'   => 0,
        'shift1_press' => 0,
        'shift1_paint' => 0,
        'shift1_assy'  => 0,
        'shift2_press' => 0,
        'shift2_paint' => 0,
        'shift2_assy'  => 0,
        'shift3_press' => 0,
        'shift3_paint' => 0,
        'shift3_assy'  => 0,
        'stock_press' => 0,
        'stock_paint' => 0,
        'stock_assy'  => 0,
        'qty_bk_press' => 0,
        'qty_bk_paint' => 0,
        'qty_bk_assy'  => 0
    ];
}

// GET TRANSACTIONS (BULAN INI)
function getTrans($status)
{
    return "
        SELECT part_code, date_tr, shift, qty
        FROM `transaction`
        WHERE status='$status'
        AND DATE_FORMAT(date_tr, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
    ";
}
$pressData  = mysqli_query($conn, getTrans('PRESS'));
$paintData = mysqli_query($conn, getTrans('PAINT'));
$assyData  = mysqli_query($conn, getTrans('ASSY'));

// GET VOUCHER DATA (BULAN INI) FROM TABLE HISTORY_LS
$voucherQuery = "
    SELECT part_code, qty_bk_press, qty_bk_paint, qty_bk_assy
    FROM history_ls
    WHERE DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
";
$voucherResult = mysqli_query($conn, $voucherQuery);
while ($row = mysqli_fetch_assoc($voucherResult)) {
    $p = $row['part_code'];
    $komponen[$p]['qty_bk_press'] = (int)$row['qty_bk_press'];
    $komponen[$p]['qty_bk_paint'] = (int)$row['qty_bk_paint'];
    $komponen[$p]['qty_bk_assy']  = (int)$row['qty_bk_assy'];
}

// TRANSACTION PRESS
while ($tr = mysqli_fetch_assoc($pressData)) {
    $p     = $tr['part_code'];
    $shift = (int)$tr['shift'];
    $qty   = (int)$tr['qty'];
    $prodDate = getProductionDateOnly($tr['date_tr']);
    // Monthly
    $komponen[$p]['total_press'] += $qty;
    // Daily
    if ($prodDate === $currentDate) {
        $komponen[$p]['daily_press'] += $qty;
        $komponen[$p]["shift{$shift}_press"] += $qty;
    }
}

// TRANSACTION PAINT
while ($tr = mysqli_fetch_assoc($paintData)) {
    $p     = $tr['part_code'];
    $shift = (int)$tr['shift'];
    $qty   = (int)$tr['qty'];
    $prodDate = getProductionDateOnly($tr['date_tr']);
    // Monthly
    $komponen[$p]['total_paint'] += $qty;
    // Daily
    if ($prodDate === $currentDate) {
        $komponen[$p]['daily_paint'] += $qty;
        $komponen[$p]["shift{$shift}_paint"] += $qty;
    }
}

// TRANSACTION ASSY
while ($tr = mysqli_fetch_assoc($assyData)) {
    $p     = $tr['part_code'];
    $shift = (int)$tr['shift'];
    $qty   = (int)$tr['qty'];
    $prodDate = getProductionDateOnly($tr['date_tr']);
    // Monthly
    $komponen[$p]['total_assy'] += $qty;
    // Daily
    if ($prodDate === $currentDate) {
        $komponen[$p]['daily_assy'] += $qty;
        $komponen[$p]["shift{$shift}_assy"] += $qty;
    }
}

// HITUNG STOCK
foreach ($komponen as &$d) {
    // Press
    $queryStock = mysqli_query($conn, "SELECT * FROM part WHERE part_code='" . $d['part_code'] . "'");
    $stockData = mysqli_fetch_assoc($queryStock);

    $d['stock_press'] = (int)$stockData['qty_press'];
    $d['stock_paint'] = (int)$stockData['qty_paint'];
}
unset($d);

// HANDLE FINISH PRODUCTION - ASSY
if (isset($_POST['btn_finish'])) {

    $partCode = $_POST['part_code'] ?? '';
    $qty      = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

    // VALIDASI INPUT
    if ($partCode === '' || $qty <= 0) {
        echo "<script>
            alert('Part Code or Quantity is invalid');
            history.back();
        </script>";
        exit;
    }

    $now   = date('Y-m-d H:i:s');
    $shift = getShift(date('H:i'));

    // START TRANSACTION
    mysqli_begin_transaction($conn);

    try {
        // INSERT TRANSACTION ASSY
        if (!mysqli_query($conn, "
            INSERT INTO `transaction`
            (part_code, date_tr, shift, qty, status)
            VALUES
            ('$partCode', '$now', '$shift', '$qty', 'ASSY')
        ")) {
            throw new Exception('Transaction Failed');
        }

        // UPDATE STOCK PAINT
        if (!mysqli_query($conn, "
            UPDATE part 
            SET qty_paint = qty_paint - $qty 
            WHERE part_code = '$partCode'
        ")) {
            throw new Exception('Update Stock Failed');
        }

        // COMMIT
        mysqli_commit($conn);

        echo "<script>
            alert('Finish production recorded successfully');
            location.href='index.php';
        </script>";
        exit;
    } catch (Exception $e) {
        // ROLLBACK
        mysqli_rollback($conn);

        echo "<script>
            alert('ERROR: {$e->getMessage()}');
            history.back();
        </script>";
        exit;
    }
}

// HANDLE BLUE & YELLOW VOUCHER
if (isset($_POST['btn_voucher'])) {

    $partCode = $_POST['part_code'] ?? '';
    $area     = $_POST['area'] ?? '';
    $qty      = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

    // VALIDASI INPUT
    if ($partCode === '' || $qty <= 0) {
        echo "<script>
            alert('Input tidak valid');
            history.back();
        </script>";
        exit;
    }

    // START TRANSACTION
    mysqli_begin_transaction($conn);

    try {
        // CEK HISTORY_LS TABLE FOR MONTHLY IF EXISTS UPDATE ELSE INSERT
        $historyCheck = mysqli_query($conn, "
            SELECT * FROM history_ls
            WHERE part_code = '$partCode'
            AND DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ");

        // TABLE HISTORY_LS UPDATE / INSERT
        if (mysqli_num_rows($historyCheck) > 0) {
            if (!mysqli_query($conn, "
                UPDATE history_ls
                SET qty_bk_{$area} = qty_bk_{$area} + $qty
                WHERE part_code = '$partCode'
                AND DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
            ")) {
                throw new Exception('Gagal update history_ls');
            }
        } else {
            if (!mysqli_query($conn, "
                INSERT INTO history_ls
                (date_prod, part_code, qty_bk_{$area})
                VALUES
                (CURDATE(), '$partCode', $qty)
            ")) {
                throw new Exception('Gagal insert history_ls');
            }
        }

        // UPDATE TABLE PART STOCK
        if ($area === 'assy') {
            if (!mysqli_query($conn, "
                UPDATE part
                SET qty_paint = qty_paint - $qty
                WHERE part_code = '$partCode'
            ")) {
                throw new Exception('Gagal update part stock untuk assy');
            }
        } else {
            if (!mysqli_query($conn, "
                UPDATE part
                SET qty_{$area} = qty_{$area} - $qty
                WHERE part_code = '$partCode'
            ")) {
                throw new Exception('Gagal update part stock untuk press/paint');
            }
        }

        // COMMIT
        mysqli_commit($conn);

        echo "<script>
            alert('Voucher recorded successfully');
            location.href='index.php';
        </script>";
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);

        echo "<script>
            alert('ERROR: {$e->getMessage()}');
            history.back();
        </script>";
        exit;
    }
}

// Display Current Production Date and Shift
$now = date('Y-m-d H:i:s');
$productionDateDisplay = date(
    'd/m/Y',
    strtotime(getProductionDateOnly($now))
);
$productionShiftDisplay = getShift(date('H:i'));
