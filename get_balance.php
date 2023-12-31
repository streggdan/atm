<?php
session_start();
include('config.php');

if ($_SESSION['role'] !== 'user') {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
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

$balance = $row['account_balance'];

header('Content-Type: application/json');
echo json_encode(['balance' => $balance]);
?>
