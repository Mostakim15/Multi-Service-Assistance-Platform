<?php
session_start();
require_once '../../config/db_connect.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role']!='manager') {
    header('Location: ../../auth/login.php');
    exit;
}

$stmt = $pdo->query("SELECT s.*, u.full_name as provider, c.category_name 
                     FROM services s 
                     JOIN users u ON s.provider_id=u.id
                     JOIN service_categories c ON s.category_id=c.id
                     WHERE s.status='pending'");
$pending = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Manager Dashboard</title></head>
<body>
<h2>Pending Services</h2>
<table border="1" cellpadding="5">
<tr><th>Service Name</th><th>Provider</th><th>Category</th><th>Action</th></tr>
<?php foreach($pending as $s): ?>
<tr>
<td><?= $s['service_name'] ?></td>
<td><?= $s['provider'] ?></td>
<td><?= $s['category_name'] ?></td>
<td><a href="approve_service.php?id=<?= $s['id'] ?>">Review</a></td>
</tr>
<?php endforeach; ?>
</table>
</body>
</html>
