<?php
    session_start();
    include 'config.php';

    $acc_number = $_SESSION['acc_number'];

    $machine_id = 1;

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $amount = $_POST['dep_num'];
        
        $query_insert_deposit = "INSERT INTO transactions (acc_number, transaction_type, amount) VALUES ($acc_number, 'deposit', $amount)";
        
        $query_select_balance = "SELECT account_balance FROM user_accounts WHERE acc_number = $acc_number";
        $result = mysqli_query($conn, $query_select_balance);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $current_balance = $row['account_balance'];
        
            $new_balance = $current_balance + $amount;
        
            $query_update_balance = "UPDATE user_accounts SET account_balance = $new_balance WHERE acc_number = $acc_number";
        
            if (mysqli_query($conn, $query_update_balance)) {
                $successMessage = "Balance Updated Successfully!";
            } else {
                $errorMessage = "Error updating balance: " . mysqli_error($conn);
            }
        } else {
            $errorMessage = "Error retrieving current balance: " . mysqli_error($conn);
        }
    
        $query_select_machine_balance = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
        $result_machine_balance = mysqli_query($conn, $query_select_machine_balance);
        
        if ($result_machine_balance) {
            $row_machine_balance = mysqli_fetch_assoc($result_machine_balance);
            $current_machine_balance = $row_machine_balance['machine_balance'];
        
            $new_machine_balance = $current_machine_balance + $amount;
        
            $query_update_machine_balance = "UPDATE machine SET machine_balance = $new_machine_balance WHERE machine_id = $machine_id";
        
            mysqli_autocommit($conn, false);
        
            if (
                mysqli_query($conn, $query_insert_deposit) &&
                mysqli_query($conn, $query_update_balance) &&
                mysqli_query($conn, $query_update_machine_balance)
            ) {
                mysqli_commit($conn);
                $successMessage = "Deposit successful!";
            } else {
                mysqli_rollback($conn);
                $errorMessage = "Machine Error depositing funds: " . mysqli_error($conn);
            }
        
            mysqli_autocommit($conn, true);
        } else {
            $errorMessage = "Error retrieving machine balance: " . mysqli_error($conn);
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deposit</title>
</head>
<body>

    <h1>ATM Simulator</h1>
    <h2>Deposit Funds</h2>

    <form action="deposit.php" method="post">
        <input type="number" name="dep_num" id="dep_num" placeholder="Enter the amount to be deposited" required><br><br>
        <input type="submit" value="Deposit">
    </form>
    <br>
    <a href="main.php">Back</a><br>
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

    
</body>
</html>