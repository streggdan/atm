<!DOCTYPE html>
<html>
<head>
    <title>Replenish Machine</title>
</head>
<body>
    <div class="admin-container" >
        <h1>Admin Panel</h1>
            <a href="check.php">Check Machine Balance</a><br>
            <a href="replenish.php">Replenish Machine</a><br>
            <a href="statement.php">Check Logs</a><br><br>
    </div>
    <a href="logout.php">Logout</a>

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
