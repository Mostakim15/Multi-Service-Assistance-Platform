<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>About | MSAP</title>
  <link rel="stylesheet" href="/msap/public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-6xl mx-auto px-6 py-16">
    <!-- Hero -->
    <div class="relative rounded-2xl overflow-hidden bg-gradient-to-r from-indigo-500 to-pink-500 text-white p-8 mb-10 lg:flex lg:items-center lg:gap-8">
      <div class="lg:flex-1">
        <h1 class="text-4xl font-bold mb-2">About MSAP Connecting communities with trusted local services</h1>
        <p class="mb-4 max-w-xl">We help users find the right providers fast, help providers grow their bookings, and make management straightforward for organizations all in one platform.</p>
        <div class="flex gap-3 items-center">
          <a href="/msap/public/services.php" class="px-4 py-2 bg-white text-indigo-600 rounded-md font-medium shadow">Explore Services</a>
          <a href="/msap/public/contact.php" class="px-4 py-2 border border-white/30 text-white rounded-md hover:bg-white/10">Get in touch</a>
        </div>

        <div class="mt-6 grid grid-cols-3 gap-3 max-w-sm">
          <div class="bg-white/20 rounded-lg p-3 text-center">
            <div class="text-2xl font-bold">1,200+</div>
            <div class="text-sm">Requests served</div>
          </div>
          <div class="bg-white/20 rounded-lg p-3 text-center">
            <div class="text-2xl font-bold">350+</div>
            <div class="text-sm">Providers</div>
          </div>
          <div class="bg-white/20 rounded-lg p-3 text-center">
            <div class="text-2xl font-bold">98%</div>
            <div class="text-sm">Satisfaction</div>
          </div>
        </div>
      </div>

      <div class="mt-6 lg:mt-0 lg:flex-1 flex justify-center">
        <img src="/msap/public/images/logo_w.png" alt="Team at work" class="w-72 h-52 object-cover rounded-xl shadow-2xl border-4 border-white/20" />
      </div>

      <div class="absolute -bottom-8 -right-8 opacity-40">
        <!-- decorative circle -->
        <svg width="160" height="160" viewBox="0 0 160 160" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="80" cy="80" r="80" fill="white"/></svg>
      </div>
    </div>

    <div class="grid gap-12 grid-cols-1">
      <!-- Our Work (with thumbnails) -->
      <div>
        <h2 class="text-2xl font-semibold mb-4 text-indigo-700">Our Work</h2>
        <p class="text-gray-700 mb-6">We partner with local providers across categories — from home repair to health assistance and help them serve customers faster using our request & approval workflows.</p>

        <div class="grid gap-4 sm:grid-cols-2">
          <div class="bg-white rounded-lg shadow p-4 border flex gap-4">
            <img src="/msap/public/images/services/Plumbing.png" alt="Plumbing" class="w-28 h-28 md:w-32 md:h-32 object-cover rounded-md flex-shrink-0" />
            <div>
              <h4 class="font-semibold">Emergency Plumbing</h4>
              <p class="text-sm text-gray-600 mt-1">Rapid response teams connected to users in under 30 minutes in many areas.</p>
              <div class="mt-2 text-xs text-gray-500">Category: Plumbing</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 border flex gap-4">
            <img src="/msap/public/images/services/Caregiving.jpg" alt="Care" class="w-28 h-28 md:w-32 md:h-32 object-cover rounded-md flex-shrink-0" />
            <div>
              <h4 class="font-semibold">Senior Care Support</h4>
              <p class="text-sm text-gray-600 mt-1">Non-medical daily assistance and check-ins provided by trusted local carers.</p>
              <div class="mt-2 text-xs text-gray-500">Category: Caregiving</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 border flex gap-4">
            <img src="/msap/public/images/services/Cleaning.jpeg" alt="Cleaning" class="w-28 h-28 md:w-32 md:h-32 object-cover rounded-md flex-shrink-0" />
            <div>
              <h4 class="font-semibold">Home Cleaning & Sanitization</h4>
              <p class="text-sm text-gray-600 mt-1">Flexible scheduling and reliable teams for deep cleaning services.</p>
              <div class="mt-2 text-xs text-gray-500">Category: Cleaning</div>
            </div>
          </div>

          <div class="bg-white rounded-lg shadow p-4 border flex gap-4">
            <img src="/msap/public/images/services/Electrical.jpg" alt="Electrical" class="w-28 h-28 md:w-32 md:h-32 object-cover rounded-md flex-shrink-0" />
            <div>
              <h4 class="font-semibold">Electrical Repairs</h4>
              <p class="text-sm text-gray-600 mt-1">Certified electricians for urgent and planned electrical work.</p>
              <div class="mt-2 text-xs text-gray-500">Category: Electrical</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Reviews & Testimonials -->
      <div>
        <h2 class="text-2xl font-semibold mb-4 text-pink-600">Reviews & Experiences</h2>
        <p class="text-gray-700 mb-6">Here are a few recent reviews from users and partner providers who rely on MSAP to connect them with the right customers and keep operations smooth.</p>

        <div class="space-y-4">
          <div class="p-4 bg-white rounded-lg shadow border flex gap-3">
            <img src="/msap/public/images/logo.png" alt="avatar" class="w-10 h-10 rounded-full object-cover" />
            <div>
              <blockquote class="text-gray-700">“MSAP helped us cut response time in half — the request workflow is simple and the provider dashboard keeps everything organized.”</blockquote>
              <div class="mt-2 text-sm font-semibold">— Ayesha Rahman</div>
              <div class="text-xs text-gray-500">Homeowner</div>
            </div>
          </div>

          <div class="p-4 bg-white rounded-lg shadow border flex gap-3">
            <img src="/msap/public/images/logo_w.png" alt="avatar" class="w-10 h-10 rounded-full object-cover" />
            <div>
              <blockquote class="text-gray-700">“We saw more bookings in the first month after joining. Notifications for pending requests are especially helpful.”</blockquote>
              <div class="mt-2 text-sm font-semibold">— Noor Ali</div>
              <div class="text-xs text-gray-500">Provider</div>
            </div>
          </div>

          <div class="p-4 bg-white rounded-lg shadow border flex gap-3">
            <img src="/msap/public/images/logo.png" alt="avatar" class="w-10 h-10 rounded-full object-cover" />
            <div>
              <blockquote class="text-gray-700">“Manager approval tools simplified our vetting process and reduced manual follow-up.”</blockquote>
              <div class="mt-2 text-sm font-semibold">— Rashed H.</div>
              <div class="text-xs text-gray-500">Operations Manager</div>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <a href="/msap/public/contact.php" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Share your experience</a>
        </div>
      </div>
    </div>

    <!-- Colorful stats -->
    <div class="mt-12 grid gap-6 sm:grid-cols-4">
      <div class="p-6 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-500 text-white shadow-lg text-center">
        <div class="text-3xl font-bold">1,200+</div>
        <div class="text-sm">Requests served</div>
      </div>
      <div class="p-6 rounded-lg bg-gradient-to-br from-pink-500 to-red-400 text-white shadow-lg text-center">
        <div class="text-3xl font-bold">350+</div>
        <div class="text-sm">Registered providers</div>
      </div>
      <div class="p-6 rounded-lg bg-gradient-to-br from-emerald-400 to-green-500 text-white shadow-lg text-center">
        <div class="text-3xl font-bold">98%</div>
        <div class="text-sm">Satisfaction rate</div>
      </div>
      <div class="p-6 rounded-lg bg-gradient-to-br from-yellow-400 to-orange-400 text-white shadow-lg text-center">
        <div class="text-3xl font-bold">4+</div>
        <div class="text-sm">Years running</div>
      </div>
    </div>

    <div class="mt-6 text-center text-gray-600">If you'd like to share your experience or partner with us, <a href="/msap/public/contact.php" class="text-indigo-600 underline">get in touch</a>.</div>
  </main>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>