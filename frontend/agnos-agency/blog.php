<?php
$pageTitle = "Insights & Articles - Agnos Agency";
$pageDesc = "Read the latest thoughts, workflows, design trends, and development guides from our creative specialists.";
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Blog Hero Header -->
  <section class="py-5 mt-5 bg-white border-bottom position-relative overflow-hidden">
    <!-- Grid lines background -->
    <div class="grid-bg-overlay"></div>
    <div class="container py-5 mt-4 text-center reveal-stagger">
      <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold badge-float reveal">Journal</span>
      <h1 class="display-3 fw-bold text-dark-heading mb-3 reveal">Our Blogs</h1>
      <p class="lead text-secondary mx-auto reveal" style="max-width: 600px;">
        Updates, methods, and strategic design guidelines that keep you ahead.
      </p>
    </div>
  </section>

  <!-- Blog Listing -->
  <section class="py-5 my-4">
    <div class="container">
      <div class="row g-4 reveal-stagger">
        <?php foreach ($cmsData['blogs'] as $b): ?>
        <!-- Blog Card Item -->
        <div class="col-lg-4 col-md-6 reveal">
          <a href="blog-detail.php?id=<?php echo $b['id']; ?>" class="text-decoration-none card-project-link">
            <div class="card h-100 border rounded-4 overflow-hidden shadow-sm transition-hover">
              <div class="card-img-wrapper" style="height: 200px;">
                <img src="<?php echo $b['image']; ?>" class="w-100 h-100 object-fit-cover transition-scale" alt="<?php echo $b['title']; ?>" onerror="this.src='https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=600&q=80'">
              </div>
              <div class="card-body p-4">
                <span class="badge bg-light text-dark border px-2 py-1 mb-2"><?php echo $b['category']; ?></span>
                <h5 class="fw-bold text-dark mb-2"><?php echo $b['title']; ?></h5>
                <p class="text-secondary small mb-0"><?php echo substr($b['introduction'], 0, 120) . '...'; ?></p>
              </div>
              <div class="card-footer bg-transparent border-top-0 p-4 pt-0 text-muted small">
                <span><?php echo $b['date']; ?> &middot; <?php echo $b['author']; ?> &middot; <?php echo $b['readTime']; ?></span>
              </div>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-dark text-white rounded-4 mx-3 my-5 reveal">
    <div class="container py-4 text-center">
      <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Let’s Build Something Great</span>
      <h2 class="display-4 fw-bold mb-4">Ready to start your next project?</h2>
      <p class="text-light text-opacity-75 max-width-sm mx-auto mb-5">
        Pick a time that works for you, and let's discuss your design ideas in a quick, no-pressure 15-minute chat.
      </p>
      <div class="d-flex justify-content-center gap-3">
        <a href="contact.php" class="btn btn-accent btn-lg btn-pill px-4">Book a Free Call</a>
        <a href="contact.php" class="btn btn-outline-light btn-lg btn-pill px-4">Get in Touch</a>
      </div>
    </div>
  </section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
