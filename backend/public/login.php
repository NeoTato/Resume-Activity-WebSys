<?php
session_start();
include __DIR__ . '/../includes/logindb.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = trim($_POST["password"] ?? "");

    $result = pg_query_params($connection,
        "SELECT * FROM users WHERE username = $1 LIMIT 1",
        [$username]
    );

    if ($row = pg_fetch_assoc($result)) {
        if (password_verify($password, $row['password'])) {
            $_SESSION["user_id"] = $row['id'];
            $_SESSION["username"] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="assets/css/loginStyles.css">
</head>
<body>
    <div class="container">
        <h2>Welcome Back</h2>
        <h3>Sign in to view resume</h3>

        <?php if (!empty($error)) : ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form action="login.php" method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <input type="submit" value="Sign In">
        </form>

        <p>Don't have an account? <a href="register.php">Register here</a></p>
        <p><a href="../index.php">Back to Home</a></p>
    </div>
</body>
</html>