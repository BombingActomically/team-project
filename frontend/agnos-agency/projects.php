<?php
$pageTitle = "Refined Projects - Agnos Agency";
$pageDesc = "Explore our portfolio of refined case studies across brand identity, custom website design, and front-end development.";
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Projects Hero Header -->
  <section class="py-5 mt-5 bg-white border-bottom position-relative overflow-hidden">
    <!-- Grid lines background -->
    <div class="grid-bg-overlay"></div>
    <div class="container py-5 mt-4 text-center reveal-stagger">
      <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold badge-float reveal">Portfolio</span>
      <h1 class="display-3 fw-bold text-dark-heading mb-3 reveal">Project Showcase</h1>
      <p class="lead text-secondary mx-auto max-width-sm reveal" style="max-width: 600px;">
        Explore how we design and build scalable products, digital systems, and brand packaging that drive real business growth.
      </p>
    </div>
  </section>

  <!-- Projects Listing & Filters -->
  <section class="py-5 my-4">
    <div class="container">
      <!-- Filter Triggers -->
      <div class="d-flex justify-content-center flex-wrap gap-2 mb-5 reveal">
        <button class="btn btn-dark btn-pill px-4 filter-btn active" data-filter="all">All Projects</button>
        <button class="btn btn-outline-dark btn-pill px-4 filter-btn" data-filter="branding">Branding</button>
        <button class="btn btn-outline-dark btn-pill px-4 filter-btn" data-filter="design">UI/UX Design</button>
        <button class="btn btn-outline-dark btn-pill px-4 filter-btn" data-filter="development">Development</button>
      </div>

      <!-- Projects Grid Container (Server-side rendered in PHP, client-side filtered) -->
      <div class="row g-4 reveal-stagger" id="projects-listing-grid">
        <?php 
        foreach ($cmsData['projects'] as $p): 
            // Determine filter category strings based on services/industry
            $cats = [];
            $text = strtolower($p['services'] . ' ' . $p['industry']);
            if (strpos($text, 'brand') !== false || strpos($text, 'packaging') !== false || strpos($text, 'identity') !== false) {
                $cats[] = 'branding';
            }
            if (strpos($text, 'design') !== false || strpos($text, 'ui') !== false || strpos($text, 'visual') !== false || strpos($text, 'refresh') !== false) {
                $cats[] = 'design';
            }
            if (strpos($text, 'development') !== false || strpos($text, 'web') !== false || strpos($text, 'platform') !== false || strpos($text, 'framer') !== false) {
                $cats[] = 'development';
            }
            $catString = implode(' ', $cats);
        ?>
        <!-- Project Card Item -->
        <div class="col-md-6 col-lg-4 mb-4 project-card-item reveal" data-category="<?php echo $catString; ?>">
          <a href="project-detail.php?id=<?php echo $p['id']; ?>" class="card-project-link text-decoration-none">
            <div class="card h-100 border rounded-4 overflow-hidden shadow-sm transition-hover">
              <div class="card-img-wrapper overflow-hidden position-relative" style="height: 250px;">
                <img src="<?php echo $p['image']; ?>" class="w-100 h-100 object-fit-cover transition-scale" alt="<?php echo $p['title']; ?>" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'">
                <div class="card-hover-overlay d-flex align-items-center justify-content-center">
                  <span class="btn btn-light rounded-pill px-4 fw-semibold py-2">View Case</span>
                </div>
              </div>
              <div class="card-body p-4 d-flex flex-column">
                <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2"><?php echo $p['industry']; ?></span>
                <h4 class="card-title fw-bold text-dark mb-2"><?php echo $p['title']; ?></h4>
                <p class="card-text text-secondary small flex-grow-1"><?php echo $p['description']; ?></p>
                <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center text-muted small">
                  <span><?php echo $p['services']; ?></span>
                  <span><i class="bi bi-clock me-1"></i><?php echo $p['timeline']; ?></span>
                </div>
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
