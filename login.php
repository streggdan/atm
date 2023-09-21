<?php
session_start();
include 'config.php';

if (isset($_POST['passcode']) && isset($_POST['acc_number'])) {

    $passcode = $_POST['passcode'];
    $acc_number = $_POST['acc_number'];

    $query = "SELECT * FROM users WHERE pin = ? AND acc_number = ?";
    
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "ss", $passcode, $acc_number);

    mysqli_stmt_execute($stmt);

 
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                $errorMessage="Database query error: " . mysqli_error($conn);
            }
        } else {
            $successMessage= "Invalid account number or passcode. Please try again.";
        }
    } else {
        $errorMessage="Database query error: " . mysqli_error($conn);
    }


    mysqli_stmt_close($stmt);
}
?>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="style.css?>=<?php echo time();?>">
    <title>ATM Login</title>
</head>
<body class="login-body">
    <div class="login-container" >
        <h1>ATM Login</h1>

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

        <form action="login.php" method="post">
            <label for="acc_number">Enter Account Number:</label> <br>
            <input type="text" id="acc_number" name="acc_number" required>

            <br><br>

            <label for="passcode">Enter Passcode:</label><br>
            <input type="password" id="passcode" name="passcode" required>

            <br><br>
            <input type="submit" value="Login">
        </form>  
    </div>
</body>
</html>
