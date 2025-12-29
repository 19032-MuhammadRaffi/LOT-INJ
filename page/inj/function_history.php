<?php
/* ===============================
   DATABASE & SESSION
================================ */
require '../../conn.php';

session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Press') {
    header('location: ../../index.php');
    exit;
}

echo '<meta http-equiv="refresh" content="60">';
date_default_timezone_set('Asia/Jakarta');

/* ===============================
   DATE & SHIFT FUNCTIONS
================================ */
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

/* ===============================
   DISPLAY CURRENT PRODUCTION INFO
================================ */
$now = date('Y-m-d H:i:s');
$currentDate = date('Y-m-d');

$productionDateDisplay  = date('d/m/Y', strtotime(getProductionDateOnly($now)));
$productionShiftDisplay = getShift(date('H:i'));

/* ===============================
   SELECTED MONTH & YEAR
================================ */
$selectedMonth = isset($_POST['month']) ? (int)$_POST['month'] : (int)date('m');
$selectedYear  = isset($_POST['year'])  ? (int)$_POST['year']  : (int)date('Y');

/* ===============================
   GENERATE DATE LIST
================================ */
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $selectedMonth, $selectedYear);
$dates = [];

for ($d = 1; $d <= $daysInMonth; $d++) {
    $dates[] = sprintf('%04d-%02d-%02d', $selectedYear, $selectedMonth, $d);
}

/* ===============================
   GET PART MASTER
================================ */
$komponen = [];
$qPart = mysqli_query($conn, "SELECT part_code, part_name FROM part");

while ($r = mysqli_fetch_assoc($qPart)) {
    $komponen[$r['part_code']] = [
        'part_code' => $r['part_code'],
        'part_name' => $r['part_name'],
        'total_press' => 0,
        'total_paint' => 0,
        'total_assy'  => 0,
        'qty_end_press' => 0,
        'qty_end_paint' => 0,
        'qty_end_assy'  => 0,
        'qty_bk_press'  => 0,
        'qty_bk_paint'  => 0,
        'qty_bk_assy'   => 0
    ];
}

/* ===============================
   TRANSACTION QUERY (MONTH FILTER)
================================ */
function getTrans($conn, $status, $month, $year)
{
    return mysqli_query($conn, "
        SELECT part_code, qty
        FROM `transaction`
        WHERE status = '$status'
        AND MONTH(date_tr) = $month
        AND YEAR(date_tr)  = $year
    ");
}

/* ===============================
   MONTHLY TOTAL
================================ */
foreach (['PRESS', 'PAINT', 'ASSY'] as $st) {
    $res = getTrans($conn, $st, $selectedMonth, $selectedYear);
    while ($r = mysqli_fetch_assoc($res)) {
        $komponen[$r['part_code']]['total_' . strtolower($st)] += (int)$r['qty'];
    }
}

/* ===============================
   HISTORY LS (END STOCK + VOUCHER)
================================ */
$historyLS = [];
$qLS = mysqli_query($conn, "
    SELECT part_code, qty_end_press, qty_end_paint, qty_end_assy,
           qty_bk_press, qty_bk_paint, qty_bk_assy
    FROM history_ls
    WHERE MONTH(date_prod) = $selectedMonth
    AND YEAR(date_prod)  = $selectedYear
");

while ($r = mysqli_fetch_assoc($qLS)) {
    $historyLS[$r['part_code']] = $r;
}

/* ===============================
   MERGE HISTORY LS DATA
================================ */
foreach ($komponen as &$d) {
    $p = $d['part_code'];

    if (isset($historyLS[$p])) {
        $d['qty_end_press'] = (int)$historyLS[$p]['qty_end_press'];
        $d['qty_end_paint'] = (int)$historyLS[$p]['qty_end_paint'];
        $d['qty_end_assy']  = (int)$historyLS[$p]['qty_end_assy'];

        $d['qty_bk_press']  = (int)$historyLS[$p]['qty_bk_press'];
        $d['qty_bk_paint']  = (int)$historyLS[$p]['qty_bk_paint'];
        $d['qty_bk_assy']   = (int)$historyLS[$p]['qty_bk_assy'];
    }
}
unset($d);

/* ===============================
   DAILY HISTORY DATA
================================ */
$dataPress = [];
$dataPaint = [];
$dataAssy  = [];

function getHistory($conn, $status, $month, $year)
{
    return mysqli_query($conn, "
        SELECT DATE(date_tr) AS tanggal, part_code, shift, SUM(qty) total_qty
        FROM `transaction`
        WHERE status = '$status'
        AND MONTH(date_tr) = $month
        AND YEAR(date_tr)  = $year
        GROUP BY DATE(date_tr), part_code, shift
    ");
}

foreach (['PRESS', 'PAINT', 'ASSY'] as $st) {
    $res = getHistory($conn, $st, $selectedMonth, $selectedYear);
    while ($r = mysqli_fetch_assoc($res)) {
        ${'data' . ucfirst(strtolower($st))}[$r['part_code']][$r['tanggal']][$r['shift']]
            = (int)$r['total_qty'];
    }
}
