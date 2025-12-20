<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
// Contact page no longer uses a server-side form handler since we use email and phone CTAs.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact | MSAP</title>
  <link rel="stylesheet" href="/msap/public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-5xl mx-auto px-6 py-16">
    <h1 class="text-3xl font-bold mb-6">Contact Us</h1>

    <div class="grid gap-8 lg:grid-cols-2 items-start">
      <div class="space-y-6">
        <p class="text-gray-700 leading-relaxed">Have questions, feedback, or need help finding the right service? We’re here to help reach us directly using the options on the right. We typically respond within 1–2 business days.</p>

        <div class="grid gap-4 sm:grid-cols-3">
          <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm border">
            <svg class="h-6 w-6 text-blue-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12l-4-3-4 3m8 0v6M4 7h16M4 7v10a2 2 0 002 2h12a2 2 0 002-2V7"/></svg>
            <div>
              <h3 class="font-semibold">Email</h3>
              <p class="text-sm text-gray-700">mdmostakimhossen0176@gmail.com
</p>
            </div>
          </div>

          <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm border">
            <svg class="h-6 w-6 text-blue-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5h2l.4 2M7 13l3 3 6-6M15 5h6M7 5v6"/></svg>
            <div>
              <h3 class="font-semibold">Phone</h3>
              <p class="text-sm text-gray-700">+880 1335-570685</p>
            </div>
          </div>

          <div class="flex items-start gap-3 bg-white p-4 rounded-lg shadow-sm border">
            <svg class="h-6 w-6 text-blue-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3M12 20a8 8 0 100-16 8 8 0 000 16z"/></svg>
            <div>
              <h3 class="font-semibold">Hours</h3>
              <p class="text-sm text-gray-700">Mon–Fri, 9am–5pm</p>
            </div>
          </div>
        </div>

        <div class="mt-6">
          <div class="bg-white p-4 rounded-lg shadow-sm border flex flex-col md:flex-row md:items-center gap-4">
            <div class="flex items-center gap-4">
              <svg class="h-8 w-8 text-blue-600 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12H8m8-4H8m12 8V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2z"/></svg>
              <div>
                <h4 class="font-semibold">Send email directly</h4>
                <p class="text-sm text-gray-600">Open your email client to message us directly, or copy the address to paste elsewhere.</p>
                <div class="mt-2 flex items-center gap-3">
                  <a href="mailto:mdmostakimhossen0176@gmail.com?subject=MSAP%20Contact" class="px-4 py-2 bg-blue-600 text-white rounded-md shadow">Email us</a>
                  <button type="button" onclick="copyEmail()" class="px-4 py-2 border rounded-md">Copy email</button>
                  <div id="copy-success" class="hidden text-sm text-green-600">Copied!</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-4 text-sm text-gray-600">Prefer to browse first? Check our <a href="/msap/public/services.php" class="text-blue-600 underline">services</a> or see the <a href="#" class="text-blue-600 underline">FAQ</a>.</div>
      </div>

      <div>
        <div class="bg-gradient-to-br from-indigo-600 to-pink-500 text-white p-8 rounded-lg shadow-lg">
          <div class="flex items-start gap-4">
            <svg class="h-8 w-8 text-white flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12H8m8-4H8m12 8V8a2 2 0 00-2-2H6a2 2 0 00-2 2v8a2 2 0 002 2h10a2 2 0 002-2z"/></svg>
            <div>
              <h3 class="text-xl font-semibold">Contact Us</h3>
              <p class="text-white/90 mt-1">Questions or requests? Reach out via email or phone and we’ll get back during business hours.</p>
            </div>
          </div>

          <div class="mt-6 grid gap-3 sm:flex sm:gap-4">
            <a href="mailto:mdmostakimhossen0176@gmail.com?subject=MSAP%20Contact" class="flex-1 px-4 py-3 bg-white text-indigo-600 rounded-md font-semibold text-center shadow">Email Us</a>
            <a href="tel:+8801335570685" class="flex-1 px-4 py-3 bg-white text-indigo-600 rounded-md font-semibold text-center shadow">Call: +880 1335-570685</a>
          </div>

          <div class="mt-4 flex items-center justify-between">
            <div class="text-sm opacity-90">Hours: <strong class="font-medium">Mon–Fri, 9am–5pm</strong></div>
            <div>
              <button type="button" onclick="copyEmail()" class="px-3 py-2 bg-white/20 border border-white/30 rounded-md">Copy email</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    function copyEmail() {
      var email = 'mdmostakimhossen0176@gmail.com';
      if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(email).then(function(){
          var el = document.getElementById('copy-success');
          if (el) { el.classList.remove('hidden'); setTimeout(function(){ el.classList.add('hidden'); }, 1800); }
        });
      } else {
        // older browsers
        var ta = document.createElement('textarea');
        ta.value = email;
        document.body.appendChild(ta);
        ta.select();
        try { document.execCommand('copy'); var el = document.getElementById('copy-success'); if (el) { el.classList.remove('hidden'); setTimeout(function(){ el.classList.add('hidden'); }, 1800); } } catch(e) {}
        document.body.removeChild(ta);
      }
    }
  </script>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>