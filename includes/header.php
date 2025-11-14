<?php
// Tailwind-based site header used across dashboard pages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Safe user data
$userName = isset($_SESSION['user']['full_name']) ? htmlspecialchars($_SESSION['user']['full_name']) : '';
$userAvatar = '';
if (!empty($_SESSION['user']['avatar'])) {
    // assume avatar path stored relative to project root, otherwise fallback
    $userAvatar = '/msap/' . ltrim($_SESSION['user']['avatar'], '/');
} else {
    $userAvatar = '/msap/public/images/default-avatar.png';
}
?>
<!-- Tailwind Play CDN (fast way to use Tailwind utilities) -->
<script src="https://cdn.tailwindcss.com"></script>

<header class="bg-white shadow-sm">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16 items-center">
      <!-- Left: brand -->
      <div class="flex items-center space-x-1 mr-[20px]">
        <a href="/msap/index.php" class="flex items-center">
          <img src="/msap/public/images/logo_w.png" alt="MSAP" class="h-[65px] w-[65px] rounded-full object-cover shadow-sm">
          <span class="text-lg font-semibold text-slate-700">MSAP</span>
        </a>
      </div>

      <!-- Center: nav -->
      <nav class="hidden md:flex space-x-6 text-sm font-semibold text-slate-600">
        <a href="/msap/index.php" class="hover:text-sky-600 font-semibold">Home</a>
        <a href="/msap/public/services.php" class="hover:text-sky-600">Services</a>
        <a href="/msap/dashboard/user/index.php" class="hover:text-sky-600">Dashboard</a>
        <a href="/msap/index.php#contact" class="hover:text-sky-600">Contact</a>
      </nav>

      <!-- Right: search + user -->
      <div class="flex items-center space-x-4">
        <form action="/msap/search.php" method="get" class="hidden sm:flex">
          <label for="header-search" class="sr-only">Search</label>
          <div class="relative">
            <input id="header-search" name="q" type="search" placeholder="Search" class="block w-64 pl-3 pr-10 py-2 border border-gray-200 rounded-md text-sm bg-white placeholder-gray-400 focus:outline-none focus:ring-1 focus:ring-sky-500" />
            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 text-slate-500 hover:text-slate-700">🔍</button>
          </div>
        </form>

        <!-- Notifications placeholder -->
        <button class="p-2 rounded-full hover:bg-gray-100 text-slate-600 hidden sm:inline-flex" title="Notifications">🔔</button>

        <!-- User profile / logout -->
        <div class="relative">
          <button id="userMenuBtn" class="flex items-center gap-2 text-sm rounded-md focus:outline-none" aria-expanded="false">
            <img src="<?= htmlspecialchars($userAvatar) ?>" alt="Profile" class="h-9 w-9 rounded-full object-cover border border-gray-100">
            <span class="hidden sm:inline-block text-slate-700"><?= $userName ?></span>
            <svg class="w-4 h-4 text-slate-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </button>

          <!-- Dropdown -->
          <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-100 rounded-md shadow-lg py-1 text-sm">
            <a href="/msap/dashboard/user/profile.php" class="block px-4 py-2 text-slate-700 hover:bg-gray-50">Profile</a>
            <a href="/msap/auth/logout.php" class="block px-4 py-2 text-red-600 hover:bg-gray-50">Logout</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<script>
  // Simple dropdown toggle without external libs
  (function(){
    const btn = document.getElementById('userMenuBtn');
    const dd = document.getElementById('userDropdown');
    if (!btn || !dd) return;
    btn.addEventListener('click', function(e){
      e.preventDefault();
      const isHidden = dd.classList.contains('hidden');
      if (isHidden) {
        dd.classList.remove('hidden');
      } else {
        dd.classList.add('hidden');
      }
    });
    // close when clicking outside
    document.addEventListener('click', function(ev){
      if (!btn.contains(ev.target) && !dd.contains(ev.target)) {
        dd.classList.add('hidden');
      }
    });
  })();
</script>
