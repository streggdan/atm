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
    <link rel="stylesheet" href="style.css">
    <title>Check Machine Balance</title>
</head>
<body class="admin-body">
    <div class="admin-container">
        <div class="echo">
    <?php
        echo "The machine balance is $".$machine_balance;
    ?>
    </div>
    <br><br>
    <div class="but">
    <table name="admin">
        <tr>
            <td><a href="admin.php">Back</a></td>
            <td><a href="logout.php">Logout</a></td>
        </tr>
    </table>
    </div>
    
    
    </div>
</body>
</html>