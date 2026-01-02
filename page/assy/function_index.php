<?php
// =====================
// DATABASE & SESSION
// =====================
require '../../conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Assy') {
    header('location: ../../index.php');
    exit;
}

date_default_timezone_set('Asia/Jakarta');
$currentDate = date('Y-m-d');

// =====================
// AUTO REFRESH
// =====================
echo '<meta http-equiv="refresh" content="60">';

// =====================
// LOGOUT
// =====================
if (isset($_POST['btn_logout'])) {
    session_destroy();
    header('location: ../../index.php');
    exit;
}

// =====================
// FUNCTION
// =====================
function getProductionDateOnly($datetime)
{
    $time = date('H:i', strtotime($datetime));
    $date = date('Y-m-d', strtotime($datetime));
    return ($time < '08:00') ? date('Y-m-d', strtotime($date . ' -1 day')) : $date;
}

function getShift($time)
{
    if ($time >= '08:00' && $time < '17:00') return 1;
    if ($time >= '17:00' || $time < '00:30') return 2;
    return 3;
}

// =====================
// FLAG LOAD DATA (FROM SESSION AREA)
// =====================
$komponen = [];
$area = $_SESSION['area'] ?? '';
$loadData = in_array($area, ['AC', 'WM']);

// =====================
// LOAD DATA ONLY IF AREA VALID
// =====================
if ($loadData) {

    // ---------- PART ----------
    $partResult = mysqli_query($conn, "
        SELECT part_code, part_name, area
        FROM part
        WHERE area = '$area'
    ");

    while ($row = mysqli_fetch_assoc($partResult)) {
        $komponen[$row['part_code']] = [
            'part_code' => $row['part_code'],
            'part_name' => $row['part_name'],
            'area'      => $row['area'],
            'total_injection'  => 0,
            'total_assy'       => 0,
            'daily_injection'  => 0,
            'daily_assy'       => 0,
            'shift1_injection' => 0,
            'shift1_assy'      => 0,
            'shift2_injection' => 0,
            'shift2_assy'      => 0,
            'shift3_injection' => 0,
            'shift3_assy'      => 0,
            'stock_injection'  => 0,
            'stock_assy'       => 0,
            'qty_bk_injection' => 0,
            'qty_bk_assy'      => 0
        ];
    }

    // ---------- TRANSACTIONS ----------
    function getTrans($status)
    {
        return "
            SELECT part_code, date_tr, shift, qty
            FROM `transaction`
            WHERE status='$status'
              AND DATE_FORMAT(date_tr, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
        ";
    }

    $injectionData = mysqli_query($conn, getTrans('INJECTION'));
    $assyData      = mysqli_query($conn, getTrans('ASSY'));

    // ---------- VOUCHER ----------
    $voucherResult = mysqli_query($conn, "
        SELECT part_code, qty_bk_injection, qty_bk_assy
        FROM history_ls
        WHERE DATE_FORMAT(date_prod, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')
    ");

    while ($v = mysqli_fetch_assoc($voucherResult)) {
        if (isset($komponen[$v['part_code']])) {
            $komponen[$v['part_code']]['qty_bk_injection'] = (int)$v['qty_bk_injection'];
            $komponen[$v['part_code']]['qty_bk_assy']      = (int)$v['qty_bk_assy'];
        }
    }

    // ---------- INJECTION ----------
    while ($tr = mysqli_fetch_assoc($injectionData)) {
        if (!isset($komponen[$tr['part_code']])) continue;

        $qty = (int)$tr['qty'];
        $shift = (int)$tr['shift'];
        $prodDate = getProductionDateOnly($tr['date_tr']);

        $komponen[$tr['part_code']]['total_injection'] += $qty;

        if ($prodDate === $currentDate) {
            $komponen[$tr['part_code']]['daily_injection'] += $qty;
            $komponen[$tr['part_code']]["shift{$shift}_injection"] += $qty;
        }
    }

    // ---------- ASSY ----------
    while ($tr = mysqli_fetch_assoc($assyData)) {
        if (!isset($komponen[$tr['part_code']])) continue;

        $qty = (int)$tr['qty'];
        $shift = (int)$tr['shift'];
        $prodDate = getProductionDateOnly($tr['date_tr']);

        $komponen[$tr['part_code']]['total_assy'] += $qty;

        if ($prodDate === $currentDate) {
            $komponen[$tr['part_code']]['daily_assy'] += $qty;
            $komponen[$tr['part_code']]["shift{$shift}_assy"] += $qty;
        }
    }

    // ---------- STOCK ----------
    foreach ($komponen as &$d) {
        $s = mysqli_fetch_assoc(mysqli_query($conn, "
            SELECT qty_injection
            FROM part
            WHERE part_code='{$d['part_code']}'
        "));
        $d['stock_injection'] = (int)$s['qty_injection'];
    }
    unset($d);
}

// =====================
// DISPLAY DATE & SHIFT
// =====================
$now = date('Y-m-d H:i:s');
$productionDateDisplay = date('d/m/Y', strtotime(getProductionDateOnly($now)));
$productionShiftDisplay = getShift(date('H:i'));

// ========================
// HANDLE FINISH PRODUCTION
// ========================
if (isset($_POST['btn_finish'])) {

    $partCode = $_POST['part_code'] ?? '';
    $qty      = isset($_POST['qty']) ? (int)$_POST['qty'] : 0;

    // VALIDASI INPUT
    if ($partCode === '' || $qty <= 0) {
        echo "<script>
            alert('Part code atau qty tidak valid');
            history.back();
        </script>";
        exit;
    }

    $now   = date('Y-m-d H:i:s');
    $shift = getShift(date('H:i'));

    // START TRANSACTION
    mysqli_begin_transaction($conn);

    try {
        // Cek stock injection cukup
        $stockCheck = mysqli_query($conn, "
            SELECT qty_injection
            FROM part 
            WHERE part_code = '$partCode'
        ");

        if (mysqli_num_rows($stockCheck) == 0) {
            throw new Exception('Part not found');
        }

        $stockRow = mysqli_fetch_assoc($stockCheck);
        if ($stockRow['qty_injection'] < $qty) {
            throw new Exception('Insufficient stock in injection');
        }
        // INSERT TRANSACTION ASSY
        if (!mysqli_query($conn, "
            INSERT INTO `transaction`
            (part_code, date_tr, shift, qty, status)
            VALUES
            ('$partCode', '$now', '$shift', '$qty', 'ASSY')
        ")) {
            throw new Exception('Gagal insert transaction ASSY');
        }

        // UPDATE STOCK ASSY
        if (!mysqli_query($conn, "
            UPDATE part 
            SET qty_injection = qty_injection - $qty 
            WHERE part_code = '$partCode'
        ")) {
            throw new Exception('Gagal update stok ASSY');
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

// ============================
// HANDLE BLUE & YELLOW VOUCHER
// ============================
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
        if (!mysqli_query($conn, "
                UPDATE part
                SET qty_injection = qty_injection - $qty
                WHERE part_code = '$partCode'
            ")) {
            throw new Exception('Gagal update part stock untuk assy');
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
