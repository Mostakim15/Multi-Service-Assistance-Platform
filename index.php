<?php 
session_start();
require_once 'config/db_connect.php';

// Fetch all service categories
$categories = [];
try {
    $stmt = $pdo->query("SELECT id, category_name, icon FROM service_categories ORDER BY category_name ASC");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Log error or handle silently
    error_log("Error fetching categories: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>MSAP - Multi Service Assistance Platform</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="public/css/style.css">
</head>
<body class="bg-gray-50 text-gray-800">

<!-- ✅ NAVBAR -->
<header class="bg-white shadow sticky top-0 z-50 ">
  <div class="max-w-7xl h-20 mx-auto px-6 py-4 flex justify-between items-center">
    <a href="index.php" class="flex items-center">
      <img src="public/images/logo_w.png" alt="MSAP Logo" class="h-20 w-20 rounded-full object-cover shadow-sm">
      <span class="text-xl text-slate-700 font-bold ">MSAP</span>
    </a>
    <nav class="hidden md:flex space-x-6 text-sm font-semibold text-slate-600">
      <a href="index.php" class="text-black-600">Home</a>
      <a href="#services" class="hover:text-black-600">Services</a>
      <a href="#about" class="hover:text-black-600">About</a>
      <a href="#contact" class="hover:text-black-600">Contact</a>
    </nav>
    <div class="flex items-center space-x-3">
      <a href="auth/login.php?role=user" class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 text-sm font-semibold">User Login</a>
      <a href="auth/login.php?role=provider" class="px-3 py-1 bg-green-100 text-green-700 rounded-lg hover:bg-green-200 text-sm font-semibold">Provider Login</a>
      <a href="auth/login.php?role=manager" class="px-3 py-1 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 text-sm font-semibold">Manager Login</a>
    </div>
  </div>
</header>

<!-- ✅ HERO SECTION -->
<section class="relative bg-blue-600 text-white py-24 text-center">
  <div class="max-w-3xl mx-auto px-6">
    <h1 class="text-4xl font-bold mb-4">Find Nearby Essential Services Instantly</h1>
    <p class="text-lg mb-6">Discover emergency, medical, police, or daily assistance services in your area.</p>
    <a href="#services" class="px-6 py-3 bg-white text-blue-700 font-semibold rounded-lg shadow hover:bg-blue-50 transition">Explore Services</a>
  </div>
  <div class="absolute inset-0 bg-blue-600 opacity-20"></div>
</section>

<!-- ✅ SERVICES SECTION -->
 
<section id="services" class="py-16 bg-gray-100">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-10 text-gray-800">Available Services</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 text-center">

      <?php if (!empty($categories)): ?>
        <?php foreach ($categories as $cat): ?>
          <div class="bg-white p-6 rounded-2xl shadow hover:shadow-lg transition transform hover:-translate-y-1">
            <div class="mx-auto h-16 mb-4 flex items-center justify-center text-3xl">
              <?php 
                // Map icon names to emojis or use a default icon class
                $iconEmoji = [
                  'mdi-hospital-box' => '🏥',
                  'mdi-ambulance' => '🚑',
                  'mdi-police-badge' => '🚔',
                  'mdi-fire' => '🔥',
                  'mdi-blood-bag' => '🩸',
                  'mdi-flash' => '⚡',
                  'mdi-hammer-wrench' => '🔧',
                  'mdi-home-floor-organization' => '🧹',
                  'mdi-silverware-fork-knife' => '🍽️',
                  'mdi-car' => '🚗',
                  'mdi-truck-fast' => '🚚',
                  'mdi-school' => '🎓',
                  'mdi-office-building' => '🏛️',
                  'mdi-laptop' => '💻',
                  'mdi-face-woman' => '💄',
                  'mdi-home-city' => '🏠',
                  'mdi-paw' => '🐾',
                ];
                $icon = $iconEmoji[$cat['icon']] ?? '📦';
                echo $icon;
              ?>
            </div>
            <h3 class="font-semibold text-lg mb-3"><?= htmlspecialchars($cat['category_name']) ?></h3>
            <a href="dashboard/user/index.php?category_id=<?= htmlspecialchars($cat['id']) ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-semibold inline-block transition">
              Request Service
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-span-full text-center text-gray-500 py-8">
          <p>No services available at this time.</p>
        </div>
      <?php endif; ?>

    </div>
  </div>
</section><!-- ✅ ABOUT SECTION -->
<section id="about" class="py-16">
  <div class="max-w-4xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold mb-4">About MSAP</h2>
    <p class="text-gray-700 leading-relaxed">
      MSAP (Multi Service Assistance Platform) helps users quickly access emergency and essential services in their area — connecting users, providers, and managers in one integrated system.
    </p>
  </div>
</section>

<!-- ✅ FOOTER -->
<footer class="bg-gray-900 text-gray-400 py-6 mt-16">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <p>&copy; <?php echo date('Y'); ?> MSAP. All rights reserved.</p>
    <p class="text-sm mt-2">Developed with ❤️ by Your Team</p>
  </div>
</footer>

</body>
</html>
