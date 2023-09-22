<?php
    session_start();
    include 'config.php';

    $acc_number = $_SESSION['acc_number'];

    $machine_id = 1;

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $amount = $_POST['with_num'];
        $machine_id = 1;
        
        $query_check_user_balance = "SELECT account_balance FROM user_accounts WHERE acc_number = $acc_number";
        $result_check_user_balance = mysqli_query($conn, $query_check_user_balance);
        
        if ($result_check_user_balance && mysqli_num_rows($result_check_user_balance) > 0) {
            $row_check_user_balance = mysqli_fetch_assoc($result_check_user_balance);
            $user_account_balance = $row_check_user_balance['account_balance'];
        
            $query_check_machine_balance = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
            $result_check_machine_balance = mysqli_query($conn, $query_check_machine_balance);
            
            if ($result_check_machine_balance && mysqli_num_rows($result_check_machine_balance) > 0) {
                $row_check_machine_balance = mysqli_fetch_assoc($result_check_machine_balance);
                $machine_balance = $row_check_machine_balance['machine_balance'];
        
                if ($user_account_balance >= $amount && $machine_balance >= $amount) {
                    $query_update_user_balance = "UPDATE user_accounts SET account_balance = account_balance - $amount WHERE acc_number = $acc_number";
                    mysqli_query($conn, $query_update_user_balance);
        
                    $query_update_machine_balance = "UPDATE machine SET machine_balance = machine_balance - $amount WHERE machine_id = $machine_id";
                    mysqli_query($conn, $query_update_machine_balance);
        
                    $query_insert_transaction = "INSERT INTO transactions (acc_number, transaction_type, amount) VALUES ($acc_number, 'withdrawal', $amount)";
                    mysqli_query($conn, $query_insert_transaction);
        
                    $successMessage = "Withdrawal successful!";
                } else {
                    $errorMessage = "Insufficient balance for withdrawal.";
                }
            } else {
                $errorMessage = "Machine balance data not available.";
            }
        } else {
            $errorMessage = "User balance data not available.";
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Withdraw</title>
</head>
<body class="user-body">

    <div class="user-container">
        <h1>ATM Simulator</h1>
        <h2>Withdraw Funds</h2>
        <form action="withdraw.php" method="post">
            <label for="with_num">Enter amount to withdraw</label><br>
            <input type="number" name="with_num" id="with_num" placeholder="Enter withdraw here" required><br><br>
            <input type="submit" value="Withdraw">
        </form>
        <br>
        <a href="main.php">Back</a>
        <a href="logout.php">Logout</a><br>
        <div>
            <?php if (!empty($successMessage)) { ?>
                <div class="success">
                    <?php echo $successMessage; ?>
                </div>
            <?php } ?>

            <?php if (!empty($errorMessage)) { ?>
                <div class="error">
                    <?php echo $errorMessage; ?>
                </div>
            <?php } ?>
        </div>
    </div>
    
</body>
</html>