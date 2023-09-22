<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>ATM Simulator</title>
</head>
<body class="login-body">

    <div class="login-container" >
        <h1>ATM Simulator</h1>
        <form action="login.php" method="post">
            <label for="acc_number">Enter Account Number:</label>
            <input type="text" id="acc_number" name="acc_number" required><br><br>
            <label for="passcode">Enter Passcode:</label>
            <input type="password" id="passcode" name="passcode" required><br><br>
            <input type="submit" value="Login">
        </form><br>
        <div>
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
                    echo"Invalid account number. Please try again";
                }
            }
        ?>
        </div>
    </div>
</body>
</html>
