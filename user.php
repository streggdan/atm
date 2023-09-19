<?php
session_start();
include('config.php');

if ($_SESSION['role'] !== 'user') {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$successMessage = $errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'balance') {
        $query = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
        $result = mysqli_query($conn, $query);
        $row = mysqli_fetch_assoc($result);
        $balance = $row['account_balance'];
    } elseif ($action === 'deposit') {
        $amount = $_POST['amount'];

        $query = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES ($user_id, 'deposit', $amount)";

        $query_update_balance = "UPDATE user_accounts SET account_balance = account_balance + $amount WHERE user_id = $user_id";

        mysqli_query($conn, $query);
        mysqli_query($conn, $query_update_balance);

        if (mysqli_query($conn, $query)) {
            $successMessage = "Deposit successful!";
        } else {
            $errorMessage = "Error depositing funds: " . mysqli_error($conn);
        }
    } elseif ($action === 'withdraw') {
        $amount = $_POST['amount'];

        $query = "INSERT INTO transactions (user_id, transaction_type, amount) VALUES ($user_id, 'withdrawal', $amount)";

        $query_check_balance = "SELECT account_balance FROM user_accounts WHERE user_id = $user_id";
        $result_check_balance = mysqli_query($conn, $query_check_balance);
        $row_check_balance = mysqli_fetch_assoc($result_check_balance);

        if ($row_check_balance['account_balance'] >= $amount) {
            $query_update_balance = "UPDATE user_accounts SET account_balance = account_balance - $amount WHERE user_id = $user_id";

            mysqli_query($conn, $query);
            mysqli_query($conn, $query_update_balance);

            $successMessage = "Withdrawal successful!";
        } else {
            $errorMessage = "Insufficient balance for withdrawal.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Panel</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="user-container">
        <h1>User Panel</h1>

        <!-- Error / Success Message -->
        <div>
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

        <!-- Balance Shows here -->
        <div class="balance-Container" id="balance-container">
            <input type="text" name="balance_output" id="balance" readonly value="<?php echo isset($balance) ? $balance : ''; ?>">
        </div>

        <!-- Buttons -->
        <div class="button-bg">
            <button id="balance-btn">Check Balance</button>
            <button id="deposit" onclick="showContainer('deposit-container')">Deposit Funds</button>
            <button id="withdraw" onclick="showContainer('withdraw-container')">Withdraw Funds</button>
        </div>

        <!-- Deposit Pop Up Field -->
        <div class="deposit-Container" id="deposit-container">
            <form action="user.php" method="POST">
                <label for="action">Enter Amount:</label> <br>
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

        <script>
            $("#balance-btn").click(function () {
                $.ajax({
                    url: "get_balance.php",
                    method: "POST",
                    dataType: "json",
                    success: function (data) {
                        if (data.hasOwnProperty('balance')) {
                            $("#balance").val(data.balance);
                        } else {
                            $("#balance").val("Balance data not available");
                        }
                    },
                    error: function () {
                        $("#balance").val("Error fetching balance");
                    }
                });
            });
        </script>
    </div>
</body>
</html>
