<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/css/loginStyles.css">
    <link rel="stylesheet" href="assets/css/dashboardStyles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h2>
        <h3>Dashboard</h3>

        <div class="dashboard-actions">
            <a href="edit_resume.php" class="btn-dashboard btn-edit">Edit Resume</a>
            <a href="resume.php" class="btn-dashboard btn-view">View Public Resume</a>
        </div>

        <form action="logout.php" method="post">
            <button type="submit" class="btn-dashboard-logout">Logout</button>
        </form>
    </div>
</body>
</html>