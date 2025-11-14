<?php
session_start();
require_once '../../config/db_connect.php';
require_once '../../config/map_config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role']!=='user') {
    header('Location: ../../auth/login.php');
    exit;
}

$uid = $_SESSION['user']['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET full_name=?, address=?, lat=?, lng=? WHERE id=?");
    $stmt->execute([$_POST['full_name'], $_POST['address'], $_POST['lat'], $_POST['lng'], $uid]);
    $msg = "Profile updated successfully!";
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$uid]);
$user = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>User Profile</title>
<link rel="stylesheet" href="../../public/css/style.css">
<script src="https://maps.googleapis.com/maps/api/js?key=<?= GOOGLE_MAP_API_KEY ?>&libraries=places"></script>
</head>
<body>
<h2>My Profile</h2>
<?php if(isset($msg)) echo "<p style='color:green;'>$msg</p>"; ?>
<form method="POST">
<input type="text" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>"><br>
<input type="text" name="address" value="<?= htmlspecialchars($user['address']) ?>"><br>

<div id="map" style="width:100%;height:400px;margin-top:10px;"></div>
<input type="hidden" name="lat" id="lat" value="<?= $user['lat'] ?>">
<input type="hidden" name="lng" id="lng" value="<?= $user['lng'] ?>">

<button type="submit">Update</button>
</form>

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
