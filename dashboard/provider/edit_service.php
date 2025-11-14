<?php
// edit_service.php
session_start();
require_once '../../config/db_connect.php';

// শুধুমাত্র service_admin রা অ্যাক্সেস পাবে
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'service_admin') {
    header("Location: ../../auth/login.php");
    exit();
}

$provider_id = $_SESSION['user_id'];
$service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// সার্ভিস তথ্য লোড করা
$sql = "SELECT * FROM services WHERE id = ? AND provider_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $service_id, $provider_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<h2>Service not found or you don’t have permission!</h2>";
    exit();
}

$service = $result->fetch_assoc();

// ক্যাটাগরি লিস্ট
$cat_query = $conn->query("SELECT id, category_name FROM service_categories");
$categories = $cat_query->fetch_all(MYSQLI_ASSOC);

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
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("sissdssiii", $service_name, $category_id, $description, $price_range, $lat, $lng, $image_path, $service_id, $provider_id);

    if ($stmt->execute()) {
        echo "<script>alert('Service updated successfully! Waiting for manager approval.'); window.location='index.php';</script>";
    } else {
        echo "<script>alert('Error updating service.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Service | MSAP</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

<div class="w-full max-w-3xl bg-white p-8 rounded-2xl shadow-lg">
  <h2 class="text-2xl font-bold text-center mb-6 text-indigo-600">Edit Your Service</h2>

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

</body>
</html>
