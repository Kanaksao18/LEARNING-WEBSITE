<?php
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'Guest';
?>

<nav class="bg-white shadow-md sticky top-0 z-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between h-16">
      <!-- Logo -->
      <div class="flex items-center">
        <a href="index.php?page=home" class="text-2xl font-bold text-purple-600">EduHub</a>
      </div>

      <!-- Desktop Menu -->
      <div class="hidden md:flex items-center space-x-6">
        <a href="index.php?page=home" class="text-gray-700 hover:text-indigo-600">Home</a>
        <a href="index.php?page=course" class="text-gray-700 hover:text-indigo-600">Courses</a>
        <?php if ($isLoggedIn): ?>
          <a href="index.php?page=dashboard" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
          <span class="text-gray-500">👋 <?= htmlspecialchars($userName) ?></span>
          <a href="index.php?page=logout" class="text-red-500 hover:text-red-700">Logout</a>
        <?php else: ?>
          <a href="index.php?page=login" class="text-gray-700 hover:text-indigo-600">Login</a>
          <a href="index.php?page=register" class="text-gray-700 hover:text-indigo-600">Register</a>
        <?php endif; ?>
      </div>

      <!-- Mobile Menu Button -->
      <div class="flex items-center md:hidden">
        <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')">
          <svg class="h-6 w-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
      </div>
    </div>
  </div>

  <!-- Mobile Menu -->
  <div class="md:hidden hidden" id="mobile-menu">
    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
      <a href="index.php?page=home" class="block text-gray-700 hover:text-indigo-600">Home</a>
      <a href="index.php?page=course" class="block text-gray-700 hover:text-indigo-600">Courses</a>
      <?php if ($isLoggedIn): ?>
        <a href="index.php?page=dashboard" class="block text-gray-700 hover:text-indigo-600">Dashboard</a>
        <span class="block text-gray-500">👋 <?= htmlspecialchars($userName) ?></span>
        <a href="index.php?page=logout" class="block text-red-500 hover:text-red-700">Logout</a>
      <?php else: ?>
        <a href="index.php?page=login" class="block text-gray-700 hover:text-indigo-600">Login</a>
        <a href="index.php?page=register" class="block text-gray-700 hover:text-indigo-600">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
