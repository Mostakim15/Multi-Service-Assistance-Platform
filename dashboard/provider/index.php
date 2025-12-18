<?php
$sessionStarted = session_status() === PHP_SESSION_ACTIVE;
if (!$sessionStarted) session_start();
require_once '../../config/db_connect.php';

// If not logged in, send to login with intended role so login validates against provider accounts
if (!isset($_SESSION['user'])) {
  header('Location: ../../auth/login.php?role=provider');
  exit;
}
// If logged in but not a provider, redirect to their correct dashboard
if ($_SESSION['user']['role'] !== 'service_admin') {
  $role = $_SESSION['user']['role'];
  if ($role === 'manager' || $role === 'owner') {
    header('Location: ../manager/index.php');
    exit;
  } else {
    header('Location: ../user/index.php');
    exit;
  }
}
$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT s.*, c.category_name FROM services s 
                       JOIN service_categories c ON s.category_id=c.id 
                       WHERE s.provider_id=?");
$stmt->execute([$uid]);
$services = $stmt->fetchAll();

// Handle provider actions on requests (approve/ cancel)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id'], $_POST['action'])) {
  $rid = intval($_POST['request_id']);
  $action = $_POST['action'];

  // Ensure the request belongs to a service owned by this provider
  $check = $pdo->prepare("SELECT sr.*, s.provider_id FROM service_requests sr JOIN services s ON sr.service_id=s.id WHERE sr.id = ?");
  $check->execute([$rid]);
  $row = $check->fetch();
  if ($row && $row['provider_id'] == $uid) {
    if ($action === 'accept') {
      $u = $pdo->prepare("UPDATE service_requests SET status='accepted' WHERE id=?");
      $u->execute([$rid]);
      $msg = 'Request accepted.';
    } elseif ($action === 'cancel' || $action === 'reject') {
      $u = $pdo->prepare("UPDATE service_requests SET status='cancelled' WHERE id=?");
      $u->execute([$rid]);
      $msg = 'Request cancelled.';
    } elseif ($action === 'complete') {
      $u = $pdo->prepare("UPDATE service_requests SET status='completed', completed_at = NOW() WHERE id=?");
      $u->execute([$rid]);
      $msg = 'Request marked as completed.';
    }
  }
  header('Location: index.php#requests');
  exit;
}

// Fetch pending requests for this provider's services
$req_stmt = $pdo->prepare("SELECT sr.*, s.service_name, u.full_name as requester_name, u.phone as requester_phone
               FROM service_requests sr
               JOIN services s ON sr.service_id=s.id
               JOIN users u ON sr.user_id=u.id
               WHERE s.provider_id = ? AND sr.status = 'pending'
               ORDER BY sr.requested_at DESC");
$req_stmt->execute([$uid]);
$pending_requests = $req_stmt->fetchAll();

// Fetch accepted requests so provider can contact users
$accepted_stmt = $pdo->prepare("SELECT sr.*, s.service_name, u.full_name as requester_name, u.phone as requester_phone, u.email as requester_email
                               FROM service_requests sr
                               JOIN services s ON sr.service_id=s.id
                               JOIN users u ON sr.user_id=u.id
                               WHERE s.provider_id = ? AND sr.status = 'accepted'
                               ORDER BY sr.requested_at DESC");
$accepted_stmt->execute([$uid]);
$accepted_requests = $accepted_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>My Services | Provider</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../../includes/header.php'; ?>

  <main class="max-w-6xl mx-auto px-6 py-10">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold">My Services</h1>
      <div class="flex items-center gap-3">
        <a href="add_service.php" class="px-4 py-2 bg-green-600 text-white rounded-md shadow">+ Add New Service</a>
        <a href="../../auth/logout.php" class="px-4 py-2 bg-gray-100 rounded-md">Logout</a>
      </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
      <section id="requests" class="p-6 border-b">
        <h2 class="text-lg font-semibold mb-4">Pending Requests</h2>
        <?php if (empty($pending_requests)): ?>
          <div class="text-sm text-slate-500">No pending requests at this time.</div>
        <?php else: ?>
          <div class="space-y-4">
            <?php foreach($pending_requests as $r): ?>
              <div class="p-4 bg-gray-50 rounded-lg border">
                <div class="flex items-start justify-between">
                  <div>
                    <div class="font-semibold"><?= htmlspecialchars($r['requester_name']) ?></div>
                    <div class="text-xs text-slate-500">Requested: <?= htmlspecialchars($r['requested_at']) ?></div>
                    <div class="text-sm mt-2"><strong>Service:</strong> <?= htmlspecialchars($r['service_name']) ?></div>
                    <?php if (!empty($r['message'])): ?>
                      <div class="text-sm mt-2"><strong>Message:</strong> <?= nl2br(htmlspecialchars($r['message'])) ?></div>
                    <?php endif; ?>
                  </div>
                  <div class="text-right">
                    <form method="POST" class="inline-block">
                      <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                      <button name="action" value="accept" class="px-3 py-1 bg-green-600 text-white rounded-md mr-2">Accept</button>
                    </form>
                    <form method="POST" class="inline-block">
                      <input type="hidden" name="request_id" value="<?= $r['id'] ?>">
                      <button name="action" value="cancel" class="px-3 py-1 bg-red-600 text-white rounded-md">Cancel</button>
                    </form>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </section>
      <section id="accepted" class="p-6">
        <h2 class="text-lg font-semibold mb-4">Accepted Requests</h2>
        <?php if (empty($accepted_requests)): ?>
          <div class="text-sm text-slate-500">No accepted requests yet.</div>
        <?php else: ?>
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requester</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested At</th>
                  <th class="px-6 py-3"></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
              <?php foreach($accepted_requests as $a): ?>
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['requester_name']) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm">Phone: <?= htmlspecialchars($a['requester_phone'] ?? 'N/A') ?></div>
                    <div class="text-xs text-slate-500">Email: <?= htmlspecialchars($a['requester_email'] ?? 'N/A') ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['service_name']) ?></td>
                  <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($a['requested_at']) ?></td>
                  <td class="px-6 py-4 text-right">
                    <form method="POST" class="inline-block">
                      <input type="hidden" name="request_id" value="<?= $a['id'] ?>">
                      <button name="action" value="complete" class="px-3 py-1 bg-sky-600 text-white rounded-md">Mark Complete</button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </section>
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Service Name</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Category</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-bold text-white uppercase tracking-wider">Action</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
          <?php foreach($services as $s): ?>
          <tr>
            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['service_name']) ?></td>
            <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($s['category_name']) ?></td>
            <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $s['status']==='approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>"><?= htmlspecialchars($s['status']) ?></span></td>
            <td class="px-6 py-4 whitespace-nowrap"><a href="edit_service.php?id=<?= $s['id'] ?>" class="text-sky-600 hover:underline">Edit</a></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </main>

  <!-- <?php require_once __DIR__ . '/../../includes/footer.php'; ?> -->
</body>
</html>
