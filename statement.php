<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Generating Statements</title>
</head>
<body class="statement-body">
    <br><br>
    <div class="stat">
<?php
            session_start();
            include 'config.php';

            $acc_number=$_SESSION['acc_number'];
            $sql="SELECT * FROM transactions WHERE acc_number=$acc_number";
            $query=mysqli_query($conn,$sql);

            if(mysqli_num_rows($query) > 0){?>

    <table name = "statement">

        <div class="header">
        <tr><th colspan="3">Statement for account number: <?php echo $acc_number?></th></tr>
        </div>

        <tr>
            <td>Transaction ID</td>
            <td>Transaction Type</td>
            <td>Transaction Amount</td>
        </tr>

            <?php
                while($row=mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["transaction_id"].'</td>';
                    echo '<td>' .$row["transaction_type"]. '</td>';
                    echo '<td>$' .$row["amount"]. '</td>';
                }
            }else{
                echo "No transactions";
            }
        ?>

    </table>
    <br><br>
    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
    </div>
</body>
</html>