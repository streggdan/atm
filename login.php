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
        <input type="text" name="acct_num" id="acct_num" placeholder="Enter Account Number" required><br>
        <input type="password" name="acct_pin" id="acct_pin" placeholder="Enter your PIN" required><br><br>
        <input type="submit" value="Login">
    </form>

    <br><br>


    <?php
        $cuser="admin";
        $cpass="admin";

        if($_SERVER['REQUEST_METHOD']=='POST'){
            $acct = $_POST["acct_num"];
            $pin = $_POST["acct_pin"];

            if($acct==$cuser && $pin==$cpass){
                header("Location: main.php");
            }elseif($acct==$cuser && $pin!=$cpass){
                echo "Incorrect PIN. Please try again";
            }else{
                echo "The account number that you have entered does not exist";
            }
        }
    
    ?>
    
</body>
</html>