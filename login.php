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
                header("Location: user.php");
            }
        } else {
            echo "Invalid account number or passcode. Please try again.";
        }
    } else {
        echo "Database query error: " . mysqli_error($conn);
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
    <title>ATM Simulator</title>
</head>
<body>

<h1>ATM Simulator</h1>
    <div class="login-container" >
        <form action="login.php" method="post">
            <label for="acc_number">Enter Account Number:</label>
            <input type="text" id="acc_number" name="acc_number" required>
            <label for="passcode">Enter Passcode:</label>
            <input type="password" id="passcode" name="passcode" required>
            <input type="submit" value="Login">
        </form>  
    </div>
</body>
</html>
