<?php
    session_start();
    include 'config.php';

    $user_id = $_SESSION['user_id'];

    $machine_id = 1;

    if($_SERVER['REQUEST_METHOD']=='POST'){
        $amount = $_POST['dep_num'];

        $query_balance = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
        $balance = mysqli_query($conn,$query_balance);

        if($balance){
            $data = mysqli_fetch_assoc($balance);
            $current_balance = $data['account_balance'];

            $new_balance = $current_balance + $amount;

            $query_update_balance = "UPDATE user_accounts SET account_balance = $new_balance WHERE user_id = $user_id";

            if(mysqli_query($conn,$query_update_balance)){
                echo "Deposit Successful";
            }else{
                echo "Deposit Failed";
            }

            
        }


        $query_insert_dep = "INSERT INTO transactions (user_id,transaction_type,amount) VALUES 
        ('$user_id','deposit','$amount')";
        $run_query_insert = mysqli_query($conn,$query_insert_dep);
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
    
</body>
</html>