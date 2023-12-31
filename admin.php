<?php
session_start();

include 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    
    exit();
}

$user_id = $_SESSION['user_id'];
$successMessage = $errorMessage = "";
$machine_id =1; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $action = $_POST['action'];

    if ($action === 'deposit') {

        $amount = $_POST['amount'];

        $query_insert_deposit = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES ($user_id, 'admin_replenish', $amount)";

        $query_select_machine_balance = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
        $result_machine_balance = mysqli_query($conn, $query_select_machine_balance);
        
        if ($result_machine_balance) {
            $row_machine_balance = mysqli_fetch_assoc($result_machine_balance);
            $current_machine_balance = $row_machine_balance['machine_balance'];
        
            $new_machine_balance = $current_machine_balance + $amount;
        
            $query_update_machine_balance = "UPDATE machine SET machine_balance = $new_machine_balance WHERE machine_id = $machine_id";
        
            mysqli_autocommit($conn, false);
        
            if ( mysqli_query($conn, $query_update_machine_balance)) {
                mysqli_commit($conn);
                $successMessage = "Machine Replenish Successful!";
            } else {
                mysqli_rollback($conn);
                $errorMessage = "Error Replenishing Funds: " . mysqli_error($conn);
            }
        
            mysqli_autocommit($conn, true);
        } else {
            $errorMessage = "Error Retrieving Machine Balance: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css?>=<?php echo time();?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="admin-body">
    <div class="admin-container" >

        <h1>Admin Panel</h1>
        
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
        <div class="balance-container" id="balance-container">
            <input type="text" name="balance_output" id="balance-output" readonly value="<?php echo isset($balance) ? $balance : ''; ?>">
        </div> <br> <br> 

        <!-- Buttons -->
        <div class="button-bg">
            <button id="balance-btn"onclick="showContainer('balance-container')">Check Balance</button>
            <button id="deposit-btn" onclick="showContainer('deposit-container')">Deposit Funds</button>
        </div>

                <!-- Deposit Pop Up Field -->
        <div class="deposit-container" id="deposit-container">
            <form action="admin.php" method="POST">
                <label class="lbl" for="action">Enter Amount:</label> <br>
                <input type="hidden" name="action" value="deposit">
                <input type="number" id="amount" name="amount" placeholder="Replenish" required>
                <input type="submit" value="Submit">
            </form>
        </div>
        <a href="javascript:void(0);p" onclick="confirmLogout()"><button class="logout-button">Log Out</button></a>
    </div>
    

        <!-- Pop Up Script -->
    <script>
    function showContainer(containerId) {
        var containers = document.querySelectorAll('.admin-deposit-Container, .balance-container');
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
                url: "get_machine_balance.php",
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
