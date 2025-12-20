<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once '../../config/db_connect.php';

// If not logged in, request login for manager role
if (!isset($_SESSION['user'])) {
    header('Location: ../../auth/login.php?role=manager');
    exit;
}

// Allow owners to access manager area; otherwise redirect to their dashboard if role mismatched
if (!in_array($_SESSION['user']['role'], ['manager', 'owner'])) {
    $role = $_SESSION['user']['role'];
    if ($role === 'service_admin') {
        header('Location: ../provider/index.php');
        exit;
    } else {
        header('Location: ../user/index.php');
        exit;
    }
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
<head>
    <title>Manager Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="max-w-6xl mx-auto px-6 py-10">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Pending Services</h1>
            <a href="../../auth/logout.php" class="px-4 py-2 bg-gray-100 rounded-md">Logout</a>
        </div>

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Service Name</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Provider</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    <?php foreach($pending as $s): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['service_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['provider']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['category_name']) ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><a href="approve_service.php?id=<?= $s['id'] ?>" class="text-sky-600 hover:underline">Review</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
