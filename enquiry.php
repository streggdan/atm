<?php
    session_start();
    include 'config.php';

    $acc_number = $_SESSION['acc_number'];

    $query = "SELECT account_balance FROM user_accounts WHERE acc_number = $acc_number";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $balance = $row['account_balance'];

    echo "Your balance is $".$balance;   


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Enquiry</title>
</head>
<body>
    <br><br>
    <a href="main.php">Back</a>
    
</body>
</html>
