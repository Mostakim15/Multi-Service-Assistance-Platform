<?php
session_start();
require_once '../config/db_connect.php';

// Allow login page to accept an intended role (via GET or POST)
$intended = '';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['role'])) {
    $intended = trim($_GET['role']);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = hash('sha256', $_POST['password']);
    $intended = isset($_POST['intended_role']) ? trim($_POST['intended_role']) : '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!($user && $password === $user['password'])) {
        $error = "Invalid email or password!";
    } else {
        // If an intended role was requested (e.g., provider/manager), ensure the account matches
        $requested_role_map = [
            'provider' => 'service_admin',
            'manager' => 'manager',
            'user' => 'user'
        ];

        if ($intended && isset($requested_role_map[$intended])) {
            $expected_db_role = $requested_role_map[$intended];
            // allow 'owner' to access manager area
            $is_allowed = ($user['role'] === $expected_db_role) || ($expected_db_role === 'manager' && $user['role'] === 'owner');
            if (!$is_allowed) {
                $error = "This account does not have the requested role ('$intended'). Please use the correct login link or contact admin.";
            }
        }

        if (empty($error)) {
            // Authorized: create session and redirect according to real role
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
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
        <title>Login | MSAP</title>
        <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../includes/header.php'; ?>

    <main class="max-w-md mx-auto px-6 py-12" style="min-height: calc(100vh - var(--msap-footer-height)); display:flex; align-items:center;">
        <div class="bg-white shadow rounded-lg p-6 w-full">
            <h2 class="text-xl font-bold mb-4">Login</h2>
            <!-- Requested role is carried in a hidden input but not shown visually to avoid confusion -->
            <?php if(isset($error)) echo "<p class='error text-red-600 mb-2'>$error</p>"; ?>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="intended_role" value="<?= htmlspecialchars($intended) ?>">
                <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded-md">
                <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 border rounded-md">
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Login</button>
                    <a href="register.php" class="text-sm text-sky-600">Register</a>
                </div>
            </form>
        </div>
       
    </main>
<section class="bg-white py-12">
    <div class="max-w-4xl mx-auto px-6">
        <h3 class="text-2xl font-bold mb-6 text-center">Why Join MSAP?</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <h4 class="font-semibold mb-2">🚀 Easy Access</h4>
                <p class="text-gray-600 text-sm">Manage your services and requests in one centralized platform.</p>
            </div>
            <div class="text-center">
                <h4 class="font-semibold mb-2">🛡️ Secure & Reliable</h4>
                <p class="text-gray-600 text-sm">Your data is protected with enterprise-grade security measures.</p>
            </div>
            <div class="text-center">
                <h4 class="font-semibold mb-2">⚡ Real-Time Updates</h4>
                <p class="text-gray-600 text-sm">Stay informed with instant notifications and live service tracking.</p>
            </div>
        </div>
    </div>
</section>
    <?php require_once __DIR__ . '/../includes/footer.php'; ?>

</body>
</html>

