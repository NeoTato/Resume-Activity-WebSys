<?php
session_start();
include __DIR__ . '/../includes/logindb.php';

$error = "";
$success = "";

// Handles user registration.
function registerUser($connection, $username, $password, $confirmPassword) {
    // Check for empty fields
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        return ['success' => false, 'message' => "Please fill in all fields."];
    }

    // Check for password mismatch
    if ($password !== $confirmPassword) {
        return ['success' => false, 'message' => "Error: Passwords do not match."];
    }

    // Check if username exists
    $checkUser = pg_query_params($connection, "SELECT 1 FROM users WHERE username = $1", [$username]);
    if (pg_num_rows($checkUser) > 0) {
        return ['success' => false, 'message' => "Username already exists!"];
    }

    // Insert new user
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $result = pg_query_params($connection, 
        "INSERT INTO users (username, password) VALUES ($1, $2)", 
        [$username, $hashedPassword]
    );

    if ($result) {
        return ['success' => true, 'message' => "Registration successful! You will be redirected to login..."];
    } else {
        return ['success' => false, 'message' => "Database Error: " . pg_last_error($connection)];
    }
}

// Main execution block
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $confirmPassword = trim($_POST["confirmpassword"] ?? '');

    $registrationResult = registerUser($connection, $username, $password, $confirmPassword);

    if ($registrationResult['success']) {
        $success = $registrationResult['message'];
        $switchToLogin = true;
    } else {
        $error = $registrationResult['message'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Page</title>
    <link rel="stylesheet" href="assets/css/loginStyles.css">
    <?php if (isset($switchToLogin) && $switchToLogin): ?>
        <meta http-equiv="refresh" content="2;url=login.php">
    <?php endif; ?>
</head>
<body>
    <div class="container">
        <h2>Account Creation</h2>
        <h3>Sign up to access the resume</h3>

        <?php if (!empty($error)): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php endif; ?>

        <form action="register.php" method="post">
            <label>Username</label>
            <input type="text" name="username" required value="<?php echo htmlspecialchars($username ?? ''); ?>">
            
            <label>Password</label>
            <input type="password" name="password" required>
            
            <label>Confirm Password</label>
            <input type="password" name="confirmpassword" required>
            
            <input type="submit" value="Register">
        </form>

        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
</body>
</html>