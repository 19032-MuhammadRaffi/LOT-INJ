<?php
// DATABASE & SESSION
require '../../conn.php';
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Assy') {
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

$role = $_SESSION['role'];
$area = $_SESSION['area'];

// bulan & tahun sekarang
$currentMonth = date('m');
$currentYear  = date('Y');

$queryTransaction = mysqli_query($conn, "
    SELECT 
        t.part_code,
        p.part_name,
        p.area,
        t.date_tr,
        t.shift,
        t.qty,
        t.status
    FROM `transaction` t
    INNER JOIN part p 
        ON t.part_code = p.part_code
    WHERE t.status = '$role'
      AND p.area = '$area'
      AND MONTH(t.date_tr) = '$currentMonth'
      AND YEAR(t.date_tr) = '$currentYear'
    ORDER BY t.date_tr DESC
");
?>
<!-- @raffizh24 -->