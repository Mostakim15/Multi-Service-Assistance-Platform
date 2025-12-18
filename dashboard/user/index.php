<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
require_once '../../config/db_connect.php';
require_once '../../config/map_config.php';

// If user not logged in, send them to login and indicate user role
if (!isset($_SESSION['user'])) {
  header('Location: ../../auth/login.php?role=user');
  exit;
}

// If logged in but not a normal user, route them to their dashboard
if ($_SESSION['user']['role'] !== 'user') {
  $role = $_SESSION['user']['role'];
  if ($role === 'service_admin') {
    header('Location: ../provider/index.php');
    exit;
  } elseif (in_array($role, ['manager','owner'])) {
    header('Location: ../manager/index.php');
    exit;
  } else {
    // fallback to login
    header('Location: ../../auth/login.php');
    exit;
  }
}

// Load categories for filter dropdown
$catStmt = $pdo->query("SELECT * FROM service_categories ORDER BY category_name ASC");
$categories = $catStmt->fetchAll();

// Load all approved services
$serviceStmt = $pdo->query("SELECT s.*, u.full_name AS provider_name, c.category_name 
                            FROM services s 
                            JOIN users u ON s.provider_id=u.id 
                            JOIN service_categories c ON s.category_id=c.id 
                            WHERE s.status='approved'");
$services = $serviceStmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
<title>Nearby Services</title>
<link rel="stylesheet" href="../../public/css/style.css">
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCml7ltvPdyMRwRd7w6sQ_Fxs-sXiMkoOQ"></script>
<style>
#controls { margin-bottom: 10px; display: flex; gap: 10px; }
input, select, button { padding: 6px 8px; border: 1px solid #ccc; border-radius: 4px; }
.dashboard-grid { display: grid; grid-template-columns: 1fr 2fr; gap: 18px; align-items: start; }
.cards { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
.service-card { background: #fff; border-radius: 8px; padding: 18px; box-shadow: 0 6px 18px rgba(20,30,60,0.08); }
.service-card .title { font-weight:700; margin-bottom:8px; }
.map-card { background: #fff; padding: 12px; border-radius:8px; box-shadow: 0 6px 18px rgba(20,30,60,0.08); }
.active-requests { margin-top:20px; }
.container { max-width:1100px; margin:20px auto; padding:0 18px; }
</style>
</head>
<body>
  <?php require_once __DIR__ . '/../../includes/header.php'; ?>

  <main class="container">
    <h2 style="margin:12px 0 18px 0;">Nearby Services</h2>

    <div class="dashboard-grid">
      <section class="left-col">
        <div class="cards">
          <div class="service-card" style="background:#ff7f50;color:#fff;">
            <div class="title">Ambulance</div>
            <div class="desc">Emergency medical transport</div>
            <div style="margin-top:12px;"><a href="request_service.php?type=ambulance" class="btn">Request Service</a></div>
          </div>

          <div class="service-card" style="background:#ff6b6b;color:#fff;">
            <div class="title">Fire</div>
            <div class="desc">Fire rescue and suppression</div>
            <div style="margin-top:12px;"><a href="request_service.php?type=fire" class="btn light">Request Service</a></div>
          </div>

          <div class="service-card" style="background:#4caf50;color:#fff;">
            <div class="title">Police</div>
            <div class="desc">Public safety services</div>
            <div style="margin-top:12px;"><a href="request_service.php?type=police" class="btn">Request Service</a></div>
          </div>

          <div class="service-card" style="background:#9ad0f5;color:#0b3a66;">
            <div class="title">Blood Donation</div>
            <div class="desc">Blood donation requests and info</div>
            <div style="margin-top:12px;"><a href="request_service.php?type=blood" class="btn light">Request Service</a></div>
          </div>
        </div>

        <div class="active-requests">
          <h3>Active Requests</h3>
          <table class="requests-table">
            <thead><tr><th>Service Type</th><th>Location</th><th>Status</th></tr></thead>
            <tbody>
              <tr><td>Ambulance</td><td>123 Main St</td><td>In Progress</td></tr>
              <tr><td>Fire</td><td>456 Pine Ave</td><td>Assigned</td></tr>
            </tbody>
          </table>
        </div>
      </section>

      <section class="right-col">
        <div class="map-card">
          <div id="map" style="width:100%;height:520px;border-radius:6px;overflow:hidden;"></div>
        </div>
      </section>
    </div>
  </main>

  <?php require_once __DIR__ . '/../../includes/footer.php'; ?>

<script>
let services = <?= json_encode($services) ?>;
let map, userLoc, markers = [];

function initMap() {
  navigator.geolocation.getCurrentPosition(pos => {
    userLoc = { lat: pos.coords.latitude, lng: pos.coords.longitude };
    map = new google.maps.Map(document.getElementById('map'), {
      center: userLoc,
      zoom: 13
    });

    new google.maps.Marker({
      position: userLoc,
      map,
      title: "You are here",
      icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
    });

    showMarkers(services);
  });
}

function showMarkers(data) {
  markers.forEach(m => m.setMap(null)); // clear old markers
  markers = [];

  data.forEach(s => {
    if (!s.lat || !s.lng) return;

    let marker = new google.maps.Marker({
      position: { lat: parseFloat(s.lat), lng: parseFloat(s.lng) },
      map,
      title: s.service_name
    });

    let dist = calcDistance(userLoc.lat, userLoc.lng, s.lat, s.lng).toFixed(2);

    let info = new google.maps.InfoWindow({
      content: `<b>${s.service_name}</b><br>
                Provider: ${s.provider_name}<br>
                Category: ${s.category_name}<br>
                Distance: ${dist} km<br>
                <a href="service_details.php?id=${s.id}">View Details</a>`
    });

    marker.addListener('click', () => info.open(map, marker));
    markers.push(marker);
  });
}

function calcDistance(lat1, lon1, lat2, lon2) {
  const R = 6371; // Earth radius in km
  const dLat = (lat2 - lat1) * Math.PI / 180;
  const dLon = (lon2 - lon1) * Math.PI / 180;
  const a = Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) *
            Math.sin(dLon/2) * Math.sin(dLon/2);
  const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
  return R * c;
}

function applyFilters() {
  const catEl = document.getElementById('categoryFilter');
  const searchEl = document.getElementById('searchBox');
  const cat = catEl ? (catEl.value || '').toLowerCase() : '';
  const search = searchEl ? (searchEl.value || '').toLowerCase() : '';

  let filtered = services.filter(s => {
    return (!cat || (s.category_name && s.category_name.toLowerCase() === cat)) &&
           (!search || (s.service_name && s.service_name.toLowerCase().includes(search)));
  });
  showMarkers(filtered);
}

window.onload = initMap;
</script>
</body>
</html>
