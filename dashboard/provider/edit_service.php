<?php
// edit_service.php
session_start();
require_once '../../config/db_connect.php';

// শুধুমাত্র service_admin রা অ্যাক্সেস পাবে
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'service_admin') {
  header("Location: ../../auth/login.php");
  exit();
}

$provider_id = $_SESSION['user']['id'];
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// সার্ভিস তথ্য লোড করা (PDO)
$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ? AND provider_id = ?");
$stmt->execute([$service_id, $provider_id]);
$service = $stmt->fetch();

if (!$service) {
  echo "<h2>Service not found or you don’t have permission!</h2>";
  exit();
}

// ক্যাটাগরি লিস্ট
$cat_query = $pdo->query("SELECT id, category_name FROM service_categories");
$categories = $cat_query->fetchAll();

// সার্ভিস আপডেট
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $service_name = $_POST['service_name'];
  $category_id = $_POST['category_id'];
  $description = $_POST['description'];
  $price_range = $_POST['price_range'];
  $lat = $_POST['lat'];
  $lng = $_POST['lng'];

  // ইমেজ আপলোড (optional)
  $image_path = $service['image'];
  if (!empty($_FILES['image']['name'])) {
    $upload_dir = "../../public/images/services/";
    if (!is_dir($upload_dir)) mkdir($upload_dir, 0777, true);
    $image_name = time() . "_" . basename($_FILES['image']['name']);
    $target_file = $upload_dir . $image_name;
    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
      $image_path = "public/images/services/" . $image_name;
    }
  }

  $update_sql = "UPDATE services 
           SET service_name=?, category_id=?, description=?, price_range=?, lat=?, lng=?, image=?, status='pending' 
           WHERE id=? AND provider_id=?";
  $stmt = $pdo->prepare($update_sql);
  $ok = $stmt->execute([$service_name, $category_id, $description, $price_range, $lat, $lng, $image_path, $service_id, $provider_id]);

  if ($ok) {
    echo "<script>alert('Service updated successfully! Waiting for manager approval.'); window.location='index.php';</script>";
    exit;
  } else {
    $error = 'Error updating service.';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Service | MSAP</title>
  <link rel="stylesheet" href="../../public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="max-w-3xl mx-auto mt-10 px-4">
        <div class="bg-white shadow rounded-lg p-8">
            <h2 class="text-2xl font-bold text-center mb-6 text-indigo-600">Edit Your Service</h2>

            <?php if (isset($error)): ?>
                <p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
    <div>
      <label class="block text-gray-700">Service Name</label>
      <input type="text" name="service_name" value="<?= htmlspecialchars($service['service_name']) ?>" required class="w-full border border-gray-300 p-2 rounded-lg focus:ring-2 focus:ring-indigo-500">
    </div>

    <div>
      <label class="block text-gray-700">Category</label>
      <select name="category_id" required class="w-full border border-gray-300 p-2 rounded-lg">
        <?php foreach ($categories as $cat): ?>
          <option value="<?= $cat['id'] ?>" <?= ($cat['id'] == $service['category_id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($cat['category_name']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div>
      <label class="block text-gray-700">Description</label>
      <textarea name="description" rows="4" class="w-full border border-gray-300 p-2 rounded-lg"><?= htmlspecialchars($service['description']) ?></textarea>
    </div>

    <div>
      <label class="block text-gray-700">Price Range</label>
      <input type="text" name="price_range" value="<?= htmlspecialchars($service['price_range']) ?>" class="w-full border border-gray-300 p-2 rounded-lg">
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div>
        <label class="block text-gray-700">Latitude</label>
        <input type="text" name="lat" value="<?= htmlspecialchars($service['lat']) ?>" class="w-full border border-gray-300 p-2 rounded-lg">
      </div>
      <div>
        <label class="block text-gray-700">Longitude</label>
        <input type="text" name="lng" value="<?= htmlspecialchars($service['lng']) ?>" class="w-full border border-gray-300 p-2 rounded-lg">
      </div>
    </div>

    <div>
      <label class="block text-gray-700">Service Image (optional)</label>
      <input type="file" name="image" accept="image/*" class="w-full border border-gray-300 p-2 rounded-lg">
      <?php if (!empty($service['image'])): ?>
        <img src="../../<?= htmlspecialchars($service['image']) ?>" alt="Service Image" class="mt-3 w-40 rounded-lg border">
      <?php endif; ?>
    </div>

    <div class="text-center mt-6">
      <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700 transition">Update Service</button>
    </div>
  </form>
</div>

  </main>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
