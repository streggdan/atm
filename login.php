<?php
    session_start();
    include 'config.php';

    $passcode = $_POST['passcode'];
    $query = "SELECT * FROM users WHERE pin = '$passcode'";
    $result = mysqli_query($conn, $query);
    
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
        echo "Invalid passcode. Please try again.";
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

<h1>ATM Simulator</h1>
    <form action="login.php" method="post">
        <label for="passcode">Enter Passcode:</label>
        <input type="password" id="passcode" name="passcode" required>
        <input type="submit" value="Login">
    </form>    
</body>
</html>