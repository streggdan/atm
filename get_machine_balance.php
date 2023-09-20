<?php
session_start();
include('config.php');

if ($_SESSION['role'] !== 'admin') {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$machine_id =1;

$query = "SELECT * FROM machine WHERE machine_id = $machine_id";
$result = mysqli_query($conn, $query);

if (!$result) {
    header("HTTP/1.1 500 Internal Server Error");
    exit();
}

$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

$balance = $row['machine_balance'];

header('Content-Type: application/json');
echo json_encode(['balance' => $balance]);
?>
