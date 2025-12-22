<?php
session_start();
require_once __DIR__ . '/../config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Contact | MSAP</title>
  <link rel="stylesheet" href="/msap/public/css/style.css">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* Zoho Frame height reduced to prevent excessive scrolling */
    .zoho-frame {
      height: 620px; 
      width: 100%;
      border: none;
      overflow: hidden;
    }
  </style>
</head>
<body class="bg-gray-50 text-gray-800">
  <?php require_once __DIR__ . '/../includes/header.php'; ?>

  <main class="max-w-6xl mx-auto px-6 py-12">
    <div class="mb-10 text-left">
        <h1 class="text-3xl font-bold text-gray-900">Contact Us</h1>
        <p class="text-gray-600 mt-2">Please contact us through the following channels for any assistance.</p>
    </div>

    <div class="grid gap-8 lg:grid-cols-12 items-start">
      
      <div class="lg:col-span-5 space-y-6">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
          
          <div class="space-y-6">
            <div class="group flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-indigo-50 transition-all duration-300 border border-transparent hover:border-indigo-100">
              <div class="flex items-center gap-4">
                <div class="bg-indigo-600 text-white p-2.5 rounded-lg shadow-md">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Email Address</h3>
                  <p class="text-gray-900 font-medium break-all text-sm">mdmostakimhossen0176@gmail.com</p>
                </div>
              </div>
              <a href="mailto:mdmostakimhossen0176@gmail.com" class="ml-4 bg-white text-indigo-600 border border-indigo-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-indigo-600 hover:text-white transition-colors whitespace-nowrap">
                Email Now
              </a>
            </div>

            <div class="group flex items-center justify-between p-4 rounded-lg bg-gray-50 hover:bg-green-50 transition-all duration-300 border border-transparent hover:border-green-100">
              <div class="flex items-center gap-4">
                <div class="bg-green-600 text-white p-2.5 rounded-lg shadow-md">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </div>
                <div>
                  <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Phone Number</h3>
                  <p class="text-gray-900 font-medium">+880 1335-570685</p>
                </div>
              </div>
              <a href="tel:+8801335570685" class="ml-4 bg-white text-green-600 border border-green-600 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-green-600 hover:text-white transition-colors whitespace-nowrap">
                Call Now
              </a>
            </div>
          </div>

          <div class="mt-6 flex items-center justify-between px-2 pt-4 border-t border-gray-100">
            <button onclick="copyEmail()" class="text-sm text-white hover:text-indigo-800 font-medium underline decoration-dotted underline-offset-4">
                Copy Email Address
            </button>
            <div class="text-right text-xs text-gray-500">
                <span class="block font-bold">Mon–Fri</span>
                <span>9am – 5pm</span>
            </div>
          </div>

          <div id="copy-success" class="hidden mt-4 p-2 bg-indigo-100 text-indigo-700 text-center text-xs rounded-lg animate-pulse">
             Email Address Copied!
          </div>
        </div>

        <div class="bg-indigo-900 text-white p-6 rounded-xl shadow-md">
            <h3 class="text-lg font-bold mb-3">Support Center</h3>
            <p class="text-indigo-100 text-sm mb-4">আমাদের সরাসরি ইমেইল বা কল ছাড়াও সোশ্যাল মিডিয়ার মাধ্যমে দ্রুত আপডেট পেতে পারেন।</p>
            <div class="flex gap-4">
                <a href="#" class="bg-white/10 hover:bg-white/20 p-2 rounded-full transition-colors">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                </a>
                <a href="#" class="bg-white/10 hover:bg-white/20 p-2 rounded-full transition-colors">
                    <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.84 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                </a>
            </div>
        </div>
      </div>

      <div class="lg:col-span-7 bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex justify-between items-center">
            <h2 class="font-bold text-gray-700 text-sm uppercase tracking-wide">Quick Inquiry Form</h2>
            <span class="text-[10px] bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-bold">Fast Response</span>
        </div>
        <iframe 
            class="zoho-frame"
            aria-label='MSAP-Contact Us' 
            src='https://forms.zohopublic.com/mdmostakimhossen0176gm1/form/ContactUs/formperma/etYiJgGbTCB5obiimrBfLR-61AL-nekdAJiQXvzoPwU'>
        </iframe>
      </div>

    </div>
  </main>

  <script>
    function copyEmail() {
      var email = 'mdmostakimhossen0176@gmail.com';
      navigator.clipboard.writeText(email).then(function(){
        var el = document.getElementById('copy-success');
        el.classList.remove('hidden');
        setTimeout(function(){ el.classList.add('hidden'); }, 2000);
      });
    }
  </script>

  <?php require_once __DIR__ . '/../includes/footer.php'; ?>
</body>
</html>