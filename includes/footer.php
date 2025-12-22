<?php
// Tailwind-based footer include
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<footer class="bg-[#0b2545] text-white shadow-lg msap-footer msap-footer-fixed" role="contentinfo" style="bottom: 0; width: 100%; height: 50px;">
  <div class="max-w-7xl mx-auto px-4 py-5 flex flex-col sm:flex-row items-center justify-between gap-3">
    <div class="text-sm text-slate-200">&copy; <?= date('Y') ?> MSAP. All rights reserved.</div>
    <div class="flex items-center gap-4 text-sm links">
      <a href="/msap/privacy.php" class="hover:underline">Privacy</a>
      <a href="/msap/terms.php" class="hover:underline">Terms</a>
      <a href="/msap/auth/logout.php" class="hover:underline text-red-200">Logout</a>
    </div>
  </div>
</footer>
