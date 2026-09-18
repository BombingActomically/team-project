<?php
require_once __DIR__ . '/includes/data.php';

$projectId = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($projectId) || !isset($cmsData['projects'][$projectId])) {
    header("Location: projects.php");
    exit();
}
$project = $cmsData['projects'][$projectId];

$pageTitle = $project['title'] . " - Agnos Case Study";
$pageDesc = $project['description'];

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Banner -->
  <section class="project-hero py-5 mt-5">
    <div class="container py-4">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="projects.php" class="text-decoration-none text-muted">Projects</a></li>
              <li class="breadcrumb-item active text-primary" aria-current="page"><?php echo $project['title']; ?></li>
            </ol>
          </nav>
          <h1 class="display-4 fw-bold mb-3 text-dark"><?php echo $project['title']; ?></h1>
          <p class="lead text-secondary mb-4"><?php echo $project['description']; ?></p>
        </div>
        <div class="col-lg-5 offset-lg-1">
          <div class="project-meta-card p-4 rounded-4 shadow-sm border bg-white">
            <div class="mb-3 pb-3 border-bottom">
              <span class="text-uppercase text-muted small fw-bold">Services</span>
              <p class="mb-0 fw-semibold text-dark"><?php echo $project['services']; ?></p>
            </div>
            <div class="mb-3 pb-3 border-bottom">
              <span class="text-uppercase text-muted small fw-bold">Industry</span>
              <p class="mb-0 fw-semibold text-dark"><?php echo $project['industry']; ?></p>
            </div>
            <div>
              <span class="text-uppercase text-muted small fw-bold">Timeline</span>
              <p class="mb-0 fw-semibold text-dark"><?php echo $project['timeline']; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Main Project Showcase Image -->
  <section class="py-3">
    <div class="container">
      <div class="project-showcase-img-wrapper rounded-4 overflow-hidden shadow">
        <img src="<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?> Banner" class="img-fluid w-100 object-fit-cover" style="max-height: 550px;" onerror="this.src='https://images.unsplash.com/photo-1507238691740-187a5b1d37b8?auto=format&fit=crop&w=1200&q=80'">
      </div>
    </div>
  </section>

  <!-- Project Study Detail -->
  <section class="py-5">
    <div class="container py-4">
      <div class="row g-5">
        <!-- The Challenge -->
        <div class="col-lg-6">
          <div class="p-4 p-xl-5 bg-white rounded-4 border h-100 shadow-sm">
            <span class="badge bg-danger bg-opacity-10 text-danger mb-3 px-3 py-2 rounded-pill">01 / Challenge</span>
            <h2 class="fw-bold mb-4 text-dark">The Challenge</h2>
            <p class="text-secondary mb-4"><?php echo $project['challenge']; ?></p>
            <ul class="list-unstyled mb-0">
              <?php foreach ($project['challengesList'] as $item): ?>
              <li class="d-flex align-items-start mb-3">
                <span class="text-danger me-2"><i class="bi bi-x-circle-fill"></i></span>
                <span><?php echo $item; ?></span>
              </li>
              <?php endforeach; ?>
            </ul>
          </div>
        </div>

        <!-- The Solution -->
        <div class="col-lg-6">
          <div class="p-4 p-xl-5 bg-white rounded-4 border h-100 shadow-sm">
            <span class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill">02 / Solution</span>
            <h2 class="fw-bold mb-4 text-dark">The Solution</h2>
            <p class="text-secondary mb-4"><?php echo $project['solution']; ?></p>
            <h4 class="fw-bold text-dark mb-3">Key Implementations</h4>
            <p class="text-secondary mb-0">We centered our UI upgrade on reducing user onboarding drop-off, unifying elements under a clean design framework, and building optimized components tailored for instant interaction.</p>
          </div>
        </div>

        <!-- The Result -->
        <div class="col-lg-12 mt-5">
          <div class="p-4 p-xl-5 bg-white rounded-4 border shadow-sm">
            <div class="row align-items-center">
              <div class="col-lg-7 mb-4 mb-lg-0">
                <span class="badge bg-success bg-opacity-10 text-success mb-3 px-3 py-2 rounded-pill">03 / Result</span>
                <h2 class="fw-bold mb-4 text-dark">The Outcome</h2>
                <p class="text-secondary mb-4"><?php echo $project['result']; ?></p>
                <ul class="list-unstyled mb-0">
                  <?php foreach ($project['resultsList'] as $item): ?>
                  <li class="d-flex align-items-start mb-3">
                    <span class="text-success me-2"><i class="bi bi-check-circle-fill"></i></span>
                    <span><?php echo $item; ?></span>
                  </li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div class="col-lg-5">
                <div class="project-quote-card p-4 rounded-4 border-start border-primary border-5 bg-light">
                  <p class="fst-italic text-dark mb-3">"<?php echo $project['testimonial']['text']; ?>"</p>
                  <div class="d-flex align-items-center">
                    <div class="avatar-circle me-3 bg-primary text-white d-flex align-items-center justify-content-center fw-bold rounded-circle" style="width: 45px; height: 45px;">
                      <?php echo substr($project['testimonial']['author'], 0, 1); ?>
                    </div>
                    <div>
                      <h6 class="fw-bold mb-0 text-dark"><?php echo $project['testimonial']['author']; ?></h6>
                      <span class="small text-muted"><?php echo $project['testimonial']['role']; ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-dark text-white rounded-4 mx-3 my-5">
    <div class="container py-4 text-center">
      <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Next Step</span>
      <h2 class="display-5 fw-bold mb-4">Want outcomes like this for your brand?</h2>
      <div class="d-flex justify-content-center gap-3">
        <a href="contact.php" class="btn btn-accent btn-lg btn-pill px-4 py-3">Discuss Your Project</a>
        <a href="projects.php" class="btn btn-outline-light btn-lg btn-pill px-4 py-3">View More Projects</a>
      </div>
    </div>
  </section>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
