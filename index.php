<?php
session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: public/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="public/assets/css/loginStyles.css">
    <link rel="stylesheet" href="public/assets/css/indexStyles.css">
</head>
<body>
    <div class="container">
        <h2>Resume Manager</h2>
        <h3>Choose an action below</h3>

        <div class="home-actions">
            <a href="public/login.php" class="btn-home btn-login-group">Login / Sign Up</a>
            <a href="public/resume.php" class="btn-home btn-view-public">View Public Resume</a>
        </div>
    </div>
</body>
</html>