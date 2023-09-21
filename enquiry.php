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
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ATM TEST</title>
    <title>ATM TEST - Withdraw</title>
    <title>Balance Enquiry</title>
</head>
<body>
<div class="message-container">
        <?php if (!empty($successMessage)) { ?>
            <div class="success-message">
                <?php echo $successMessage; ?>
            </div>
        <?php } ?>

        <?php if (!empty($errorMessage)) { ?>
            <div class="error-message">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
    </div>
    <div>
    <br><br>
    <a href="main.php">Back</a><br>
    </div> 
</body>
</html>
