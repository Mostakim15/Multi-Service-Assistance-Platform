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
<html>
<head><title>Add Service</title></head>
<body>
<h2>Add New Service</h2>
<form method="post">
  <input name="service_name" placeholder="Service Name" required><br>
  <textarea name="description" placeholder="Description"></textarea><br>
  <select name="category_id">
    <?php foreach($cats as $c): ?>
    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['category_name']) ?></option>
    <?php endforeach; ?>
  </select><br>
  <input name="lat" placeholder="Latitude" required><br>
  <input name="lng" placeholder="Longitude" required><br>
  <button type="submit">Save</button>
</form>
</body>
</html>
