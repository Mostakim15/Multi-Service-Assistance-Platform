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
// handle approval actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['service_id'], $_POST['action'])) {
    $sid = intval($_POST['service_id']);
    $act = $_POST['action'] === 'approve' ? 'approved' : 'rejected';
    $u = $pdo->prepare("UPDATE services SET status=? WHERE id=?");
    $u->execute([$act, $sid]);
    header('Location: approve_service.php');
    exit;
}

// If a specific service id is requested, load full details for review
$service_detail = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $sid = intval($_GET['id']);
    $q = $pdo->prepare("SELECT s.*, u.full_name as provider, u.phone as provider_phone, u.address as provider_address, c.category_name
                       FROM services s
                       JOIN users u ON s.provider_id=u.id
                       JOIN service_categories c ON s.category_id=c.id
                       WHERE s.id = ?");
    $q->execute([$sid]);
    $service_detail = $q->fetch();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Manager - Pending Services | MSAP</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="max-w-5xl mx-auto mt-10 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">Pending Services</h2>

            <?php if ($service_detail): ?>
                <div class="mb-6 bg-gray-50 border rounded p-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-full sm:w-1/3">
                            <?php if (!empty($service_detail['image'])): ?>
                                <img src="/msap/<?= htmlspecialchars($service_detail['image']) ?>" alt="Service Image" class="w-full h-48 object-cover rounded">
                            <?php else: ?>
                                <div class="w-full h-48 bg-gray-100 rounded flex items-center justify-center text-gray-400">No image</div>
                            <?php endif; ?>
                        </div>
                        <div class="w-full sm:w-2/3">
                            <h3 class="text-lg font-bold mb-1"><?= htmlspecialchars($service_detail['service_name']) ?></h3>
                            <p class="text-sm text-slate-600 mb-2"><strong>Category:</strong> <?= htmlspecialchars($service_detail['category_name']) ?></p>
                            <p class="text-sm text-slate-600 mb-2"><strong>Provider:</strong> <?= htmlspecialchars($service_detail['provider']) ?> — <?= htmlspecialchars($service_detail['provider_phone'] ?? '') ?></p>
                            <p class="text-sm text-slate-600 mb-2"><strong>Price:</strong> <?= htmlspecialchars($service_detail['price_range'] ?? 'N/A') ?></p>
                            <p class="prose mb-3"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($service_detail['description'])) ?></p>
                            <p class="text-sm text-slate-600"><strong>Location:</strong> <?= htmlspecialchars($service_detail['lat']) ?>, <?= htmlspecialchars($service_detail['lng']) ?></p>

                            <div class="mt-4">
                                <form method="POST" class="inline-block mr-2">
                                    <input type="hidden" name="service_id" value="<?= $service_detail['id'] ?>">
                                    <button name="action" value="approve" class="px-4 py-2 bg-green-600 text-white rounded-md">Approve</button>
                                </form>
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="service_id" value="<?= $service_detail['id'] ?>">
                                    <button name="action" value="reject" class="px-4 py-2 bg-red-600 text-white rounded-md">Reject</button>
                                </form>
                                <a href="approve_service.php" class="inline-block ml-4 text-sm text-slate-600">Back to list</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Service Name</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Provider</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach($pending as $s): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['service_name']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['provider']) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['category_name']) ?></td>
                            <td class="px-6 py-4 text-right">
                                <form method="POST" class="inline-block">
                                    <input type="hidden" name="service_id" value="<?= $s['id'] ?>">
                                    <button name="action" value="approve" class="px-3 py-1 bg-green-600 text-white rounded-md mr-2">Approve</button>
                                    <button name="action" value="reject" class="px-3 py-1 bg-red-600 text-white rounded-md">Reject</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>
</body>
</html>
