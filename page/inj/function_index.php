<?php
// =====================
// DATABASE & SESSION
// =====================
require '../../conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Injection') {
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
// FLAG LOAD DATA
// =====================
$komponen = [];
$loadData = false;
$area = '';

if (isset($_POST['AC_DATA'])) {
    $area = 'AC';
    $loadData = true;
} elseif (isset($_POST['WM_DATA'])) {
    $area = 'WM';
    $loadData = true;
}

// =====================
// LOAD DATA ONLY IF BUTTON CLICKED
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
