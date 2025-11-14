<?php
session_start();
require_once '../../config/db_connect.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='user') {
    header('Location: ../../auth/login.php');
    exit;
}

if (!isset($_GET['id'])) {
    die("Invalid service ID");
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT s.*, u.full_name AS provider_name, u.phone, c.category_name 
                       FROM services s 
                       JOIN users u ON s.provider_id=u.id 
                       JOIN service_categories c ON s.category_id=c.id 
                       WHERE s.id=? AND s.status='approved'");
$stmt->execute([$id]);
$service = $stmt->fetch();

if (!$service) {
    die("Service not found or not approved.");
}

// Handle request button
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $req = $pdo->prepare("INSERT INTO service_requests (user_id, service_id, status, request_date) VALUES (?, ?, 'pending', NOW())");
    $req->execute([$_SESSION['user']['id'], $id]);
    $msg = "Your request has been sent successfully!";
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Service Details</title>
<link rel="stylesheet" href="../../public/css/style.css">
</head>
<body>
<a href="index.php">&larr; Back to Map</a>

<h2><?= htmlspecialchars($service['service_name']) ?></h2>
<p><b>Category:</b> <?= htmlspecialchars($service['category_name']) ?></p>
<p><b>Provider:</b> <?= htmlspecialchars($service['provider_name']) ?></p>
<p><b>Phone:</b> <?= htmlspecialchars($service['phone']) ?></p>
<p><b>Description:</b><br><?= nl2br(htmlspecialchars($service['description'])) ?></p>

<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>

<form method="POST">
  <button type="submit">Request This Service</button>
</form>

</body>
</html>
