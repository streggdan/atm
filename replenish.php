<?php
session_start();

include 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    
    exit();
}

$acc_number = $_SESSION['acc_number'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = $_POST['amount'];
    
    $machine_id =1; 

    $query_select_machine_balance = "SELECT machine_balance FROM machine WHERE machine_id = $machine_id";
    $result_machine_balance = mysqli_query($conn, $query_select_machine_balance);
    
    if ($result_machine_balance) {
        $row_machine_balance = mysqli_fetch_assoc($result_machine_balance);
        $current_machine_balance = $row_machine_balance['machine_balance'];

        $query_insert_admin = "INSERT INTO transactions (acc_number, transaction_type, amount) VALUES ($acc_number, 'admin_replenish', $amount)";
        mysqli_query($conn,$query_insert_admin);
    
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
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Replenish Machine</title>
</head>
<body class="admin-body">
    <div class="admin-container" >
        
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

        <h1>Admin Panel</h1>
        <div class="deposit-Container" id="deposit-container">
            <form action="admin.php" method="POST">
                <div class="echo">
                <label for="amount">Replenish ATM Machine (Amount):</label>
                </div>
                <input type="number" id="amount" name="amount" required>
                <input type="submit" value="Replenish">
            </form>
        </div>
        <br><br>
        <a href="logout.php">Logout</a><br>
    </div>
    

    <!-- Logout Script -->
    <script>
        function confirmLogout() {
            var result = confirm("Are you sure you want to log out?");
            if (result) {
                window.location.href = "logout.php?logout=true";
            } else {
                var dropdown = document.getElementById("myDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>
