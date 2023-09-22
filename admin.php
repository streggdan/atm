<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css">
    <title>Replenish Machine</title>
</head>
<body class="admin-body">
    <div class="admin-container" >
        <h1>Admin Panel</h1>
            <a href="check.php">Check Machine Balance</a><br><br><br>
            <a href="replenish.php">Replenish Machine</a><br><br><br>
            <a href="statement.php">Check Logs</a><br><br><br><br>
            <a href="logout.php">Logout</a><br>
    </div>
    

    <!-- Logout Script -->
    <script>
        function confirmLogout() {
            var result = confirm("Are you sure you want to log out?");
            if (result) {
                window.location.href = "logout.php?logout=true";
            } else {
                var dropdown = document.getElementById("myDropdown");
                if (dropdown.classList.contains('show')) {
                    dropdown.classList.remove('show');
                }
            }
        }
    </script>
</body>
</html>
