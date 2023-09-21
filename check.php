<?php

    include 'config.php';

    $machine_id = 1;
    $query = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_assoc($result);
    $machine_balance = $row['machine_balance'];

   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ATM TEST</title>
    <title>Check Machine Balance</title>
</head>
<body>
    
<div class="container">
        <h1>Check Machine Balance</h1>
            <p>The machine balance is: <span id="balance">Loading...</span></p>
        </div>
        <div class="buttons-container"><br>
            <a href="admin.php" class="back-button">Back</a><br>
            <button id="check-balance-button">Check Balance</button>
            <a href="logout.php" class="logout-button">Logout</a>
        </div>

    </div>
</body>
</html>