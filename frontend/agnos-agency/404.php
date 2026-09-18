<?php
$pageTitle = "Page Not Found - Agnos Agency";
$pageDesc = "The page you are looking for doesn't exist or has been moved.";
require_once __DIR__ . '/includes/header.php';
?>

  <!-- 404 Body -->
  <section class="py-5 my-5 mt-5">
    <div class="container py-5 my-5 text-center">
      <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill fw-semibold">Error 404</span>
      <h1 class="display-1 fw-bold text-dark-heading mb-2">404</h1>
      <h2 class="fw-bold text-dark mb-4">Something went wrong</h2>
      <p class="text-secondary mb-5 mx-auto" style="max-width: 450px;">
        The page you are looking for doesn't exist or has been moved.
      </p>
      <a href="index.php" class="btn btn-accent btn-pill btn-lg px-4">Back to home</a>
    </div>
  </section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
