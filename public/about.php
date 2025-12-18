<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About | MSAP</title>
  <link rel="stylesheet" href="/msap/public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-4xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold mb-4">About MSAP</h1>
    <p class="text-gray-700 leading-relaxed mb-4">MSAP (Multi Service Assistance Platform) helps users quickly access emergency and essential services in their area — connecting users, providers, and managers in one integrated system.</p>
    <p class="text-gray-700 leading-relaxed">This platform provides a simple interface for users to request services, for providers to manage requests, and for managers to oversee approvals and operations.</p>
  </main>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>