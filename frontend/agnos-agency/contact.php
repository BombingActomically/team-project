<?php
$pageTitle = "Start a Conversation - Agnos Agency";
$pageDesc = "Get in touch with Agnos agency to discuss your custom branding, website design, and front-end development needs.";
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Contact Hero Header -->
  <section class="py-5 mt-5 bg-white border-bottom position-relative overflow-hidden">
    <!-- Grid lines background -->
    <div class="grid-bg-overlay"></div>
    <div class="container py-5 mt-4 text-center reveal-stagger">
      <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold badge-float reveal">Connect</span>
      <h1 class="display-3 fw-bold text-dark-heading mb-3 reveal">Contact us</h1>
      <p class="lead text-secondary mx-auto reveal" style="max-width: 600px;">
        Have a project idea? Start a conversation with our creative specialists.
      </p>
    </div>
  </section>

  <!-- Contact Form & Info Columns -->
  <section class="py-5 my-4">
    <div class="container py-4">
      <div class="row g-5">
        <!-- Contact Information Column -->
        <div class="col-lg-5 reveal-left">
          <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Let’s Connect</span>
          <h2 class="display-5 fw-bold text-dark mb-4">Start a Conversation</h2>
          <p class="text-secondary mb-5">
            We are typically available to kickstart discovery processes within a few business days. Let us know what parameters your team is dealing with, and we’ll match you with a roadmap.
          </p>
          
          <ul class="list-unstyled mb-5 text-secondary">
            <li class="mb-4 d-flex align-items-start">
              <span class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                <i class="bi bi-telephone fs-5"></i>
              </span>
              <div>
                <span class="d-block small text-muted text-uppercase fw-bold tracking-wider">Call on:</span>
                <span class="fw-semibold text-dark fs-5">+1 (234) 567-89-01</span>
              </div>
            </li>
            <li class="mb-4 d-flex align-items-start">
              <span class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                <i class="bi bi-envelope fs-5"></i>
              </span>
              <div>
                <span class="d-block small text-muted text-uppercase fw-bold tracking-wider">Email on:</span>
                <span class="fw-semibold text-dark fs-5">support@example.com</span>
              </div>
            </li>
            <li class="d-flex align-items-start">
              <span class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; flex-shrink: 0;">
                <i class="bi bi-geo-alt fs-5"></i>
              </span>
              <div>
                <span class="d-block small text-muted text-uppercase fw-bold tracking-wider">Address:</span>
                <span class="fw-semibold text-dark fs-5">1238 Echo Ridge Blvd, Suite 400<br>San Francisco, CA 94103, United States</span>
              </div>
            </li>
          </ul>

          <div class="project-quote-card p-4 rounded-4 bg-light">
            <p class="fst-italic text-dark mb-3">
              "Their attention to detail and commitment to quality set them apart. The new dashboard improved both usability and client satisfaction."
            </p>
            <div class="d-flex align-items-center">
              <div class="avatar-circle me-3 bg-accent text-white d-flex align-items-center justify-content-center fw-bold rounded-circle" style="width: 40px; height: 40px;">EL</div>
              <div>
                <h6 class="fw-bold mb-0 text-dark">Ethan Lewis</h6>
                <span class="small text-muted">Founder, UrbanPay</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Contact Form Column -->
        <div class="col-lg-7 reveal-right">
          <div class="p-4 p-xl-5 bg-white border rounded-4 shadow-sm">
            <h3 class="fw-bold text-dark mb-4">Send a message</h3>
            <form id="contact-agency-form">
              <div class="row g-3">
                <div class="col-md-6">
                  <label for="name" class="form-label text-dark fw-medium small">Name*</label>
                  <input type="text" class="form-control rounded-pill px-4 py-3 border-color" id="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label text-dark fw-medium small">Email*</label>
                  <input type="email" class="form-control rounded-pill px-4 py-3 border-color" id="email" placeholder="Your Email Address" required>
                </div>
                <div class="col-md-6">
                  <label for="phone" class="form-label text-dark fw-medium small">Phone*</label>
                  <input type="tel" class="form-control rounded-pill px-4 py-3 border-color" id="phone" placeholder="Your Phone Number" required>
                </div>
                <div class="col-md-6">
                  <label for="company" class="form-label text-dark fw-medium small">Company</label>
                  <input type="text" class="form-control rounded-pill px-4 py-3 border-color" id="company" placeholder="Your Company Name">
                </div>
                <div class="col-12">
                  <label for="message" class="form-label text-dark fw-medium small">Message</label>
                  <textarea class="form-control px-4 py-3 border-color" id="message" rows="5" placeholder="Tell us about your brand goals, scope, and parameters..." style="border-radius: 1.25rem;"></textarea>
                </div>
                <div class="col-12 mt-4 text-end">
                  <button type="submit" class="btn btn-accent btn-pill btn-lg px-5 py-3">Send a message</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
      <div class="text-center mb-5 reveal">
        <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Got questions</span>
        <h2 class="display-5 fw-bold text-dark-heading mb-3">Let’s clear things up</h2>
        <p class="text-secondary max-width-sm mx-auto">Get fast answers to common billing and design workflow inquiries.</p>
      </div>

      <div class="faq-list reveal-stagger">
        <?php foreach ($cmsData['faqs'] as $faq): ?>
        <!-- FAQ Item -->
        <div class="faq-item reveal">
          <div class="faq-question">
            <span><?php echo $faq['q']; ?></span>
            <span class="icon"><i class="bi bi-chevron-down"></i></span>
          </div>
          <div class="faq-answer">
            <div class="faq-answer-inner">
              <?php echo $faq['a']; ?>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Success Notification Toast -->
  <div id="form-success-toast" class="toast-success">
    <span class="text-success fs-5"><i class="bi bi-check-circle-fill"></i></span>
    <div>
      <h6 class="fw-bold mb-0">Message Sent!</h6>
      <span class="small text-light text-opacity-75">Thank you, we'll contact you in 24 hours.</span>
    </div>
  </div>

<?php 
require_once __DIR__ . '/includes/footer.php'; 
?>
