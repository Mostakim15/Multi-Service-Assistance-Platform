<?php
session_start();
require_once '../config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && $password === $user['password']) {
        $_SESSION['user'] = $user;
        
        // Map role to correct dashboard path
        $role_redirect = [
            'user' => 'user',
            'service_admin' => 'provider',
            'manager' => 'manager',
            'owner' => 'manager' // owner can access manager dashboard
        ];
        
        $dashboard_path = $role_redirect[$user['role']] ?? 'user';
        header('Location: ../dashboard/' . $dashboard_path . '/index.php');
        exit;
    } else {
        $error = "Invalid email or password!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login | MSAP</title>
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body>
<div class="auth-container">
    <h2>Login</h2>
    <?php if(isset($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="POST">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
    <p>Don't have account? <a href="register.php">Register</a></p>
</div>
</body>
</html>
