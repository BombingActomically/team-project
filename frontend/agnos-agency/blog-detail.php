<?php
require_once __DIR__ . '/includes/data.php';

$blogId = isset($_GET['id']) ? $_GET['id'] : '';
if (empty($blogId) || !isset($cmsData['blogs'][$blogId])) {
    header("Location: blog.php");
    exit();
}
$blog = $cmsData['blogs'][$blogId];

$pageTitle = $blog['title'] . " - Agnos Blog";
$pageDesc = substr($blog['introduction'], 0, 160);

require_once __DIR__ . '/includes/header.php';
?>

  <!-- Article Hero -->
  <section class="py-5 mt-5">
    <div class="container py-4">
      <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
          <nav aria-label="breadcrumb" class="mb-3 d-inline-block">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="blog.php" class="text-decoration-none text-muted">Journal</a></li>
              <li class="breadcrumb-item active text-primary" aria-current="page"><?php echo $blog['category']; ?></li>
            </ol>
          </nav>
          <h1 class="display-4 fw-bold mb-4 text-dark-heading"><?php echo $blog['title']; ?></h1>
          <div class="d-flex align-items-center justify-content-center text-muted small gap-3 mb-4">
            <span class="d-flex align-items-center"><i class="bi bi-calendar3 me-2"></i><?php echo $blog['date']; ?></span>
            <span>&middot;</span>
            <span class="d-flex align-items-center"><i class="bi bi-person me-2"></i>By <?php echo $blog['author']; ?></span>
            <span>&middot;</span>
            <span class="d-flex align-items-center"><i class="bi bi-clock me-2"></i><?php echo $blog['readTime']; ?></span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Article Header Cover -->
  <section class="pb-5">
    <div class="container">
      <div class="rounded-4 overflow-hidden shadow" style="max-height: 450px;">
        <img src="<?php echo $blog['image']; ?>" alt="<?php echo $blog['title']; ?> Header" class="w-100 h-100 object-fit-cover" style="min-height: 350px;" onerror="this.src='https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1200&q=80'">
      </div>
    </div>
  </section>

  <!-- Article Body Content -->
  <section class="py-4 mb-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <article class="article-body">
            <p class="lead text-dark mb-5" style="font-size: 1.2rem; line-height: 1.75; font-weight: 500;">
              <?php echo $blog['introduction']; ?>
            </p>
            
            <?php foreach ($blog['sections'] as $sec): ?>
            <h3 class="fw-bold text-dark mt-5 mb-3"><?php echo $sec['title']; ?></h3>
            <p class="text-secondary mb-4" style="line-height: 1.75;"><?php echo $sec['content']; ?></p>
            <?php endforeach; ?>
          </article>

          <hr class="my-5 border-color">

          <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div class="d-flex gap-2">
              <span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><?php echo $blog['category']; ?></span>
              <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Strategy</span>
              <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Insights</span>
            </div>
            <div>
              <a href="blog.php" class="btn btn-outline-dark btn-pill px-4"><i class="bi bi-arrow-left me-2"></i>Back to Blog</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5 bg-dark text-white rounded-4 mx-3 my-5">
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
