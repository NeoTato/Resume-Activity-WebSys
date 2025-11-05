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
    <link rel="stylesheet" href="assets/css/resumeStyles.css">
    <link rel="stylesheet" href="assets/css/dashboardStyles.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
        <h2>Choose one of the actions below</h2>

        <div class="dashboard-actions">
            <a href="edit_resume.php" class="btn-dashboard btn-edit">Edit Resume</a>
            <a href="resume.php" class="btn-dashboard btn-view">View Resume</a>
            <form action="logout.php" method="post">
                <button type="submit" class="btn-dashboard-logout">Logout</button>
            </form>
        </div>
    </div>
</body>
</html>