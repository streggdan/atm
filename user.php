<?php
session_start();
include('config.php');

if ($_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$successMessage = $errorMessage = "";
$machine_id =1; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'balance') {
        $query = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $balance = $row['account_balance'];
    } elseif ($action === 'deposit') {
        $amount = $_POST['amount'];
        
        $query_insert_deposit = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES ($user_id, 'deposit', $amount)";
        
        $query_select_balance = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query_select_balance);
        
        if ($result) {
            $row = mysqli_fetch_assoc($result);
            $current_balance = $row['account_balance'];
        
            $new_balance = $current_balance + $amount;
        
            $query_update_balance = "UPDATE user_accounts SET account_balance = $new_balance WHERE user_id = $user_id";
        
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
    }elseif ($action === 'withdraw') {
        $amount = $_POST['amount'];
        
        $machine_id = 1;
        
        $query_check_user_balance = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
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
                    $query_update_user_balance = "UPDATE user_accounts SET account_balance = account_balance - $amount WHERE user_id = $user_id";
                    mysqli_query($conn, $query_update_user_balance);
        
                    $query_update_machine_balance = "UPDATE machine SET machine_balance = machine_balance - $amount WHERE machine_id = $machine_id";
                    mysqli_query($conn, $query_update_machine_balance);
        
                    $query_insert_transaction = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES ($user_id, 'withdrawal', $amount)";
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
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <link rel="stylesheet" href="style.css?>=<?php echo time();?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="user-container">
        <h1>User Panel</h1>

        <!-- Error / Success Message -->
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

        <!-- Balance Shows here -->
        <div class="balance-Container" id="balance-container">
            <input type="text" name="balance_output" id="balance-output" readonly value="<?php echo isset($balance) ? $balance : ''; ?>">
        </div>

        <!-- Buttons -->
        <div class="button-bg">
            <button id="balance-btn">Check Balance</button>
            <button id="deposit-btn" onclick="showContainer('deposit-container')">Deposit Funds</button>
            <button id="withdraw-btn" onclick="showContainer('withdraw-container')">Withdraw Funds</button>
        </div>

        <!-- Deposit Pop Up Field -->
        <div class="deposit-Container" id="deposit-container">
            <form action="user.php" method="POST">
                <label class="lbl" for="action">Enter Amount:</label> <br>
                <input type="hidden" name="action" value="deposit">
                <input type="number" id="amount" name="amount" placeholder="Deposit" required>
                <input type="submit" value="Submit">
            </form>
        </div>

        <!-- Withdraw Pop Up Field -->
        <div class="withdraw-Container" id="withdraw-container">
            <form action="user.php" method="POST">
                <label for="action">Enter Amount:</label> <br>
                <input type="hidden" name="action" value="withdraw">
                <input type="number" id="amount" name="amount" placeholder="Withdraw" required>
                <input type="submit" value="Submit">
            </form>
        </div>        
        <a href="javascript:void(0);p" onclick="confirmLogout()">Log Out</a>
    </div>
    
    <!-- Pop Up Script -->
    <script>
        function showContainer(containerId) {
            var containers = document.querySelectorAll('.deposit-Container, .withdraw-Container, .balance-container');
            containers.forEach(function (container) {
                container.style.display = 'none';
            });

            var container = document.getElementById(containerId);
            container.style.display = 'block';
        }
    </script>

    <!-- Balance Script -->
    <script>
        $("#balance-btn").click(function () {
            $.ajax({
                url: "get_balance.php",
                method: "POST",
                dataType: "json",
                success: function (data) {
                    if (data.hasOwnProperty('balance')) {
                        $("#balance-output").val(data.balance);
                    } else {
                        $("#balance-output").val("Balance data not available");
                    }
                },
                error: function () {
                    $("#balance").val("Error fetching balance");
                }
            });
        });
    </script>

    <!-- Logout Script -->
    <script>
    function confirmLogout() {
        var result = confirm("Are you sure you want to log out?");
        if (result) {
            window.location.href = "logout.php?logout=true";
        } else {
        }
    }
    </script>
</body>
</html>
