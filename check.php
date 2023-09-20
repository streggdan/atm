<?php

    include 'config.php';

    $machine_id = 1;
    $query = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $machine_balance = $row['machine_balance'];
    

    echo "The machine balance is $".$machine_balance;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check Machine Balance</title>
</head>
<body>
    <br><br>
    <a href="admin.php">Back</a><br><br>
    <a href="logout.php">Logout</a>
</body>
</html>