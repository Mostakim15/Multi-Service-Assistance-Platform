<?php
require_once '../config/db_connect.php';
require_once '../config/site_config.php';

// Allowed roles that can be self-registered. Keep conservative to avoid privilege escalation.
// Manager registration requires a valid registration code to prevent unauthorized elevation.
$allowed_roles = ['user', 'service_admin', 'manager'];

// Manager registration code (change this to a secure value in production or store in config/env)
$manager_registration_code = MANAGER_REGISTRATION_CODE;

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    // Role chosen by user (if any) — only allow whitelisted roles
    $postedRole = isset($_POST['role']) ? trim($_POST['role']) : '';
    $role = in_array($postedRole, $allowed_roles, true) ? $postedRole : 'user';

    // If user requested manager role, validate manager registration code
    if ($role === 'manager') {
        $mgrCode = trim($_POST['manager_code'] ?? '');
        if ($mgrCode === '') {
            $error = 'Manager registration requires a registration code.';
        } elseif ($mgrCode !== $manager_registration_code) {
            $error = 'Invalid manager registration code.';
        }
    }

    // Basic validation
    if (empty($error)) {
        if ($full_name === '' || $email === '' || $password === '') {
            $error = 'Please fill all required fields.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Please enter a valid email address.';
        } else {
            // Use same hashing as current login (sha256) for compatibility; consider moving to password_hash later.
            $passHash = hash('sha256', $password);

            $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            try {
                $stmt->execute([$full_name, $email, $passHash, $role]);
                // Redirect to login and preselect role to guide users
                header("Location: login.php?role=" . urlencode($role));
                exit;
            } catch (PDOException $e) {
                // Do not reveal raw DB errors to users in production
                $error = "Registration failed — email might already be in use.";
                error_log('Register error: ' . $e->getMessage());
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register | MSAP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../public/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-md mx-auto px-6 py-12">
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-xl font-bold mb-4">Register</h2>
      <?php if(!empty($error)) echo "<p class='error text-red-600 mb-2'>" . htmlspecialchars($error) . "</p>"; ?>

      <form method="POST" class="space-y-4">
        <input type="text" name="full_name" placeholder="Full Name" required class="w-full px-3 py-2 border rounded-md" value="<?= htmlspecialchars($_POST['full_name'] ?? '') ?>">
        <input type="email" name="email" placeholder="Email" required class="w-full px-3 py-2 border rounded-md" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">

        <label for="role" class="block text-sm font-medium">Register as</label>
        <select name="role" id="role" class="w-full px-3 py-2 border rounded-md" aria-label="Role">
            <option value="user" <?= (isset($role) && $role==='user') ? 'selected' : '' ?>>User</option>
            <option value="service_admin" <?= (isset($role) && $role==='service_admin') ? 'selected' : '' ?>>Provider</option>
            <option value="manager" <?= (isset($role) && $role==='manager') ? 'selected' : '' ?>>Manager</option>
        </select>

        <div id="manager-code-wrap" style="display: <?= (isset($role) && $role==='manager') ? 'block' : 'none' ?>; margin-top:8px;">
            <label for="manager_code" class="block text-sm font-medium">Manager registration code</label>
            <input type="text" name="manager_code" id="manager_code" placeholder="Enter manager code" class="w-full px-3 py-2 border rounded-md" value="<?= htmlspecialchars($_POST['manager_code'] ?? '') ?>">
            <small class="text-muted text-gray-500">You need a registration code to create a manager account.</small>
        </div>

        <input type="password" name="password" placeholder="Password" required class="w-full px-3 py-2 border rounded-md">
        <div class="flex items-center justify-between">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Register</button>
          <a href="login.php" class="text-sky-600 text-sm">Back to login</a>
        </div>
      </form>
    </div>
  </main>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>

  <script>
  // Show manager code input only when Manager is selected
  const roleSelect = document.getElementById('role');
  const mgrWrap = document.getElementById('manager-code-wrap');
  function toggleManagerCode(){
      if(!roleSelect || !mgrWrap) return;
      if(roleSelect.value === 'manager') mgrWrap.style.display = 'block';
      else mgrWrap.style.display = 'none';
  }
  if(roleSelect){ roleSelect.addEventListener('change', toggleManagerCode); toggleManagerCode(); }
  </script>
</body>
</html>
