<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generating Statements</title>
</head>
<body>
    <table name = "statement">

        <tr>
            <td>Transaction ID</td>
            <td>Transaction Type</td>
            <td>Transaction Amount</td>
        </tr>

        <?php
            session_start();
            include 'config.php';

            $acc_number=$_SESSION['acc_number'];
            $sql="SELECT * FROM transactions WHERE acc_number=$acc_number";
            $query=mysqli_query($conn,$sql);

            if(mysqli_num_rows($query) > 0){
                while($row=mysqli_fetch_assoc($query)){
                    echo '<tr>';
                    echo '<td>'.$row["transaction_id"].'</td>';
                    echo '<td>' .$row["transaction_type"]. '</td>';
                    echo '<td>' .$row["amount"]. '</td>';
                }
            }else{
                echo "No transactions";
            }
        ?>

    </table>
    <br><br>
    <a href="logout.php">Logout</a>
</body>
</html>