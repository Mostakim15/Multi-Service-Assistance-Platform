<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
$sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // simple contact capture (no email sending)
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    if ($name && $email && $message) {
        // store in a simple table if exists, otherwise ignore - for now we just log
        error_log("Contact message from $name <$email>: $message");
        $sent = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact | MSAP</title>
  <link rel="stylesheet" href="/msap/public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-3xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold mb-4">Contact Us</h1>

    <?php if ($sent): ?>
      <div class="bg-green-100 border border-green-200 p-4 rounded mb-4">Thanks, your message has been received.</div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 bg-white p-6 rounded shadow">
      <label class="block text-sm font-medium">Name</label>
      <input name="name" required class="w-full p-2 border rounded-md" />

      <label class="block text-sm font-medium">Email</label>
      <input name="email" type="email" required class="w-full p-2 border rounded-md" />

      <label class="block text-sm font-medium">Message</label>
      <textarea name="message" rows="6" class="w-full p-2 border rounded-md" required></textarea>

      <div class="text-right">
        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Send Message</button>
      </div>
    </form>
  </main>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>