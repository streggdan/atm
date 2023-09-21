<?php
session_start();
include 'config.php';

if (isset($_POST['passcode']) && isset($_POST['acc_number'])) {

    $passcode = $_POST['passcode'];
    $acc_number = $_POST['acc_number'];

    $query = "SELECT * FROM users WHERE acc_number = '$acc_number'";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        if($user['pin']!=$passcode){
            echo "Invalid PIN. Please try again";
        }else{
            $_SESSION['acc_number'] = $user['acc_number'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: main.php");
            }
        }
    }else{
        echo "Invalid account number. Please try again";
    }


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ATM TEST</title>
    <title>ATM Simulator</title>
</head>
<body>
        <div class="login-container">
        <h1>ATM Simulator</h1>
            <h2>Login</h2>
            <form action="login.php" method="post" class="login-form">
                <label for="acc_number">Enter Account Number:</label><br>
                <input type="text" id="acc_number" name="acc_number" required><br>
                <label for="passcode">Enter password:</label><br>
                <input type="password" id="passcode" name="password" required><br><br>
                <input type="submit" value="Login" class="login-button">
            </form>
        </div>
    </div>
</body>
</html>
