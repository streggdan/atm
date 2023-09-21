<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css">
    <title>ATM TEST</title>
    <title>KALDER ATM</title>
</head>
<body>
<div class="container">
    <h1>ATM Simulator</h1>
    <ul>
        <li><a href="enquiry.php">Balance Enquiry</a></li>
        <li><a href="withdraw.php">Withdraw</a></li>
        <li><a href="deposit.php">Deposit</a></li>
        <li><a href="statement.php">View Statement</a></li> 
    </ul>
    <form action="?logout=true" method="post">
        <button class="logout-button" type="submit">Logout</button>
    </form>
</div>    
</body>
</html>