<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../config/map_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='service_admin') {
    header('Location: ../../auth/login.php');
    exit;
}

$uid = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET full_name=?, phone=?, address=?, lat=?, lng=? WHERE id=?");
    $stmt->execute([
        $_POST['full_name'],
        $_POST['phone'],
        $_POST['address'],
        $_POST['lat'],
        $_POST['lng'],
        $uid
    ]);
    $msg = "Profile updated successfully!";
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$uid]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Provider Profile | MSAP</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAP_API_KEY ?>&libraries=places"></script>
</head>
<body class="bg-gray-50 text-gray-800">
    <?php require_once __DIR__ . '/../../includes/header.php'; ?>

    <main class="max-w-3xl mx-auto mt-10 px-4">
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold mb-4">My Profile</h2>
            <?php if(isset($msg)): ?><p class="text-green-600 mb-4"><?= htmlspecialchars($msg) ?></p><?php endif; ?>

            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" class="w-full mt-1 p-2 border rounded-md" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" value="<?= htmlspecialchars($user['phone']) ?>" class="w-full mt-1 p-2 border rounded-md" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Address</label>
                    <input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>" class="w-full mt-1 p-2 border rounded-md" />
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Location</label>
                    <div id="map" class="w-full h-64 rounded-md border mt-2"></div>
                </div>

                <input type="hidden" name="lat" id="lat" value="<?= $user['lat'] ?>">
                <input type="hidden" name="lng" id="lng" value="<?= $user['lng'] ?>">

                <div class="text-right">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md">Save Profile</button>
                </div>
            </form>
        </div>
    </main>

    <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

    <script>
    function initMap() {
        let lat = parseFloat(document.getElementById('lat').value) || 23.7808875;
        let lng = parseFloat(document.getElementById('lng').value) || 90.2792371;
        const map = new google.maps.Map(document.getElementById('map'), {
            center: { lat, lng },
            zoom: 14
        });

        const marker = new google.maps.Marker({
            position: { lat, lng },
            map,
            draggable: true
        });

        google.maps.event.addListener(marker, 'dragend', function(e) {
            document.getElementById('lat').value = e.latLng.lat();
            document.getElementById('lng').value = e.latLng.lng();
        });
    }
    window.onload = initMap;
    </script>
</body>
</html>
