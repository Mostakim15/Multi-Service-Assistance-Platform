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
    // prevent duplicate/active requests
    $chk = $pdo->prepare("SELECT COUNT(*) FROM service_requests WHERE user_id = ? AND service_id = ? AND status IN ('pending','accepted','on_route')");
    $chk->execute([$_SESSION['user']['id'], $id]);
    if ($chk->fetchColumn() > 0) {
        $error = 'You already have an active request for this service.';
    } else {
        try {
            // note: schema uses `requested_at` (not request_date)
            $req = $pdo->prepare("INSERT INTO service_requests (user_id, service_id, status, requested_at) VALUES (?, ?, 'pending', NOW())");
            $req->execute([$_SESSION['user']['id'], $id]);
            $msg = "Your request has been sent successfully!";
        } catch (PDOException $e) {
            error_log('Service request error: ' . $e->getMessage());
            $error = 'Unable to send request at this time. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Service Details | MSAP</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="max-w-3xl mx-auto mt-10 px-4">
        <a href="index.php" class="text-slate-600 hover:text-sky-600">&larr; Back to Map</a>

        <div class="bg-white shadow rounded-lg p-6 mt-4">
            <h2 class="text-2xl font-bold mb-2"><?= htmlspecialchars($service['service_name']) ?></h2>
            <p class="text-sm text-slate-600 mb-1"><strong>Category:</strong> <?= htmlspecialchars($service['category_name']) ?></p>
            <p class="text-sm text-slate-600 mb-1"><strong>Provider:</strong> <?= htmlspecialchars($service['provider_name']) ?></p>
            <p class="text-sm text-slate-600 mb-3"><strong>Phone:</strong> <?= htmlspecialchars($service['phone']) ?></p>
            <div class="prose max-w-none mb-4"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($service['description'])) ?></div>

            <?php if(isset($msg)): ?><p class="text-green-600 mb-4"><?= htmlspecialchars($msg) ?></p><?php endif; ?>
            <?php if(isset($error)): ?><p class="text-red-600 mb-4"><?= htmlspecialchars($error) ?></p><?php endif; ?>

            <form method="POST">
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Request This Service</button>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
