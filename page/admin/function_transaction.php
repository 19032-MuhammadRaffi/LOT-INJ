<?php
// DATABASE & SESSION
require '../../conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header('location: ../../index.php');
    exit;
}

// AUTO REFRESH
echo '<meta http-equiv="refresh" content="60">';

// LOGOUT
if (isset($_POST['btn_logout'])) {
    session_destroy();
    header('location: ../../index.php');
    exit;
}

date_default_timezone_set('Asia/Jakarta');
// Function Production Date
function getProductionDateOnly($datetime)
{
    $time = date('H:i', strtotime($datetime));
    $date = date('Y-m-d', strtotime($datetime));

    if ($time < '09:00') {
        return date('Y-m-d', strtotime($date . ' -1 day'));
    }
    return $date;
}

// Function Shift
function getShift($time)
{
    if ($time >= '09:00' && $time < '18:00') return 1;
    if ($time >= '18:00' || $time < '01:30') return 2;
    return 3;
}

// SINGLE SOURCE OF TRUTH (WAJIB)
$now = date('Y-m-d H:i:s');

// KHUSUS TESTING
// $now = '2026-01-07 01:40:00';

$currentDate  = getProductionDateOnly($now);               // PRODUCTION DATE
$currentShift = getShift(date('H:i', strtotime($now)));   // PRODUCTION SHIFT

$currentDateTr  = $currentDate . ' ' . date('H:i:s', strtotime($now));
$currentShiftTr = $currentShift;

$productionDateDisplay  = date('d/m/Y', strtotime($currentDate));
$productionShiftDisplay = $currentShift;

// DELETE TRANSACTION
if (isset($_POST['delete_id'])) {
    $deleteId = intval($_POST['delete_id']);

    $get = mysqli_query($conn, "
        SELECT part_code, qty, status
        FROM `transaction`
        WHERE id = $deleteId
    ");

    if ($get && mysqli_num_rows($get) > 0) {
        $row = mysqli_fetch_assoc($get);

        $partCode = $row['part_code'];
        $qty      = $row['qty'];
        $status   = strtoupper($row['status']);

        mysqli_query($conn, "
            DELETE FROM `transaction`
            WHERE id = $deleteId
        ");

        if ($status === 'ASSY') {
            mysqli_query($conn, "
                UPDATE part
                SET qty_injection = qty_injection + $qty
                WHERE part_code = '$partCode'
            ");
        } elseif ($status === 'INJECTION') {
            mysqli_query($conn, "
                UPDATE part
                SET qty_injection = qty_injection - $qty
                WHERE part_code = '$partCode'
            ");
        }
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// SELECT TRANSACTION
$currentMonth = date('m');
$currentYear  = date('Y');
$queryTransaction = mysqli_query($conn, "
    SELECT 
        t.id,
        t.part_code,
        p.part_name,
        t.date_tr,
        t.shift,
        t.qty,
        t.status
    FROM `transaction` t
    LEFT JOIN part p ON t.part_code = p.part_code
    WHERE MONTH(t.date_tr) = '$currentMonth'
      AND YEAR(t.date_tr)  = '$currentYear'
    ORDER BY t.date_tr DESC
");

// Error Handling Query
if (!$queryTransaction) {
    die("Query Error: " . mysqli_error($conn));
}
?>
<!-- @raffizh24 -->