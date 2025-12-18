<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';

// Fetch approved services
try {
		$stmt = $pdo->query("SELECT s.*, c.category_name, u.full_name AS provider_name
												 FROM services s
												 JOIN service_categories c ON s.category_id=c.id
												 JOIN users u ON s.provider_id=u.id
												 WHERE s.status = 'approved' ORDER BY s.id DESC");
		$services = $stmt->fetchAll();
} catch (Exception $e) {
		$services = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>All Services | MSAP</title>
	<link rel="stylesheet" href="/msap/public/css/style.css">
	<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
	<?php require_once __DIR__ . '/../includes/header.php'; ?>

	<main class="max-w-6xl mx-auto px-6 py-10">
		<div class="flex items-center justify-between mb-6">
			<h1 class="text-2xl font-bold">All Services</h1>
			<div class="text-sm text-slate-600">Showing <?= count($services) ?> services</div>
		</div>

		<?php if (empty($services)): ?>
			<div class="bg-white shadow rounded-lg p-6 text-center text-gray-500">No services available right now.</div>
		<?php else: ?>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php foreach ($services as $s): ?>
					<div class="bg-white rounded-2xl shadow p-4 flex flex-col">
						<div class="h-40 w-full mb-3 rounded overflow-hidden bg-gray-100 flex items-center justify-center">
							<?php if (!empty($s['image'])): ?>
								<img src="/msap/<?= htmlspecialchars($s['image']) ?>" alt="<?= htmlspecialchars($s['service_name']) ?>" class="w-full h-full object-cover">
							<?php else: ?>
								<div class="text-gray-400">No image</div>
							<?php endif; ?>
						</div>
						<h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($s['service_name']) ?></h3>
						<p class="text-sm text-slate-600 mb-2"><strong>Category:</strong> <?= htmlspecialchars($s['category_name']) ?></p>
						<p class="text-sm text-slate-600 mb-3 flex-1"><?= nl2br(htmlspecialchars(substr($s['description'] ?? '', 0, 140))) ?><?= (strlen($s['description'] ?? '') > 140) ? '...' : '' ?></p>
						<div class="flex items-center justify-between mt-3">
							<div class="text-xs text-slate-500">Provider: <?= htmlspecialchars($s['provider_name']) ?></div>
							<?php
								$request_link = '/msap/auth/login.php?role=user';
								if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'user') {
										$request_link = "/msap/dashboard/user/service_details.php?id=" . intval($s['id']);
								}
							?>
							<a href="<?= $request_link ?>" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm">Request</a>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</main>

	<?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>
