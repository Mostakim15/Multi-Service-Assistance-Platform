<?php
session_start();
require_once '../../config/db_connect.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!='service_admin') {
    header('Location: ../../auth/login.php');
    exit;
}
$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT s.*, c.category_name FROM services s 
                       JOIN service_categories c ON s.category_id=c.id 
                       WHERE s.provider_id=?");
$stmt->execute([$uid]);
$services = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>My Services</title></head>
<body>
<h2>My Services</h2>
<a href="add_service.php">+ Add New Service</a> | 
<a href="../../auth/logout.php">Logout</a>
<table border="1" cellpadding="5">
<tr><th>Service Name</th><th>Category</th><th>Status</th><th>Action</th></tr>
<?php foreach($services as $s): ?>
<tr>
  <td><?= htmlspecialchars($s['service_name']) ?></td>
  <td><?= htmlspecialchars($s['category_name']) ?></td>
  <td><?= htmlspecialchars($s['status']) ?></td>
  <td><a href="edit_service.php?id=<?= $s['id'] ?>">Edit</a></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
