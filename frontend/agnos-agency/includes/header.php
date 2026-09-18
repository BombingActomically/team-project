<?php
require_once __DIR__ . '/data.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo isset($pageTitle) ? $pageTitle : 'Agnos - Creative Agency'; ?></title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="<?php echo isset($pageDesc) ? $pageDesc : 'Showcase your studio with a premium agency template featuring clean layouts, strong visuals, and CMS-powered sections designed to convert clients effectively.'; ?>">
  
  <!-- CSS Stylesheets -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

  <!-- Navigation Bar -->
  <nav class="navbar navbar-expand-lg fixed-top py-3">
    <div class="container">
      <a class="navbar-brand" href="index.php">Agnos<span>.</span></a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <i class="bi bi-list fs-2 text-dark"></i>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'about.php') ? 'active' : ''; ?>" href="about.php">About us</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'projects.php' || $currentPage === 'project-detail.php') ? 'active' : ''; ?>" href="projects.php">Projects</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'blog.php' || $currentPage === 'blog-detail.php') ? 'active' : ''; ?>" href="blog.php">Blog</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?php echo ($currentPage === 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact us</a>
          </li>
        </ul>
        <div class="d-flex align-items-center">
          <a href="contact.php" class="btn btn-accent btn-pill px-4">Start a project</a>
        </div>
      </div>
    </div>
  </nav>
