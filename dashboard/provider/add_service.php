<?php
session_start();
require_once '../../config/db_connect.php';
if ($_SESSION['user']['role']!=='service_admin') { header("Location: ../../auth/login.php"); exit; }

$cats = $pdo->query("SELECT * FROM service_categories")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO services (provider_id, category_id, service_name, description, lat, lng, status)
                           VALUES (?, ?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([
        $_SESSION['user']['id'], $_POST['category_id'], $_POST['service_name'],
        $_POST['description'], $_POST['lat'], $_POST['lng']
    ]);
    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Add Service | MSAP</title>
  <link rel="stylesheet" href="../../public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../../includes/header.php'; ?>

  <main class="max-w-3xl mx-auto mt-10 px-4">
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-2xl font-bold text-indigo-600 mb-4">Add New Service</h2>

      <form method="post" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-700">Service Name</label>
          <input name="service_name" placeholder="Service Name" required class="w-full mt-1 p-2 border rounded-md" />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Description</label>
          <textarea name="description" placeholder="Description" class="w-full mt-1 p-2 border rounded-md"></textarea>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Category</label>
          <select name="category_id" class="w-full mt-1 p-2 border rounded-md">
            <?php foreach($cats as $c): ?>
            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Latitude</label>
            <input name="lat" placeholder="Latitude" required class="w-full mt-1 p-2 border rounded-md" />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700">Longitude</label>
            <input name="lng" placeholder="Longitude" required class="w-full mt-1 p-2 border rounded-md" />
          </div>
        </div>

        <div class="text-right">
          <a href="index.php" class="inline-block mr-2 text-sm text-slate-600">Cancel</a>
          <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">Save Service</button>
        </div>
      </form>
    </div>
  </main>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
