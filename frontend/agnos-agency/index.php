<?php
$pageTitle = "Agnos - Creative Agency Framer Template Recreation";
$pageDesc = "Showcase your studio with a premium agency template featuring clean layouts, strong visuals, and CMS-powered sections designed to convert clients effectively.";
require_once __DIR__ . '/includes/header.php';
?>

  <!-- Hero Section -->
  <section class="hero-section py-5 mt-5">
    <!-- Mesh Glow Orbs -->
    <div class="hero-glow-container">
      <div class="glow-orb glow-orb-1"></div>
      <div class="glow-orb glow-orb-2"></div>
    </div>
    <!-- Grid lines background -->
    <div class="grid-bg-overlay"></div>

    <div class="container py-5 mt-4 text-center reveal-stagger">
      <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold badge-float reveal">Digital Agency / Get Started</span>
      <h1 class="display-2 fw-bold text-dark-heading mb-4 mx-auto reveal" style="max-width: 900px;">We design brands that move people</h1>
      <p class="lead text-secondary mb-5 mx-auto reveal" style="max-width: 650px;">
        We combine strategy, design, and technology to help ambitious brands stand out & create meaningful digital experiences.
      </p>
      <div class="d-flex justify-content-center align-items-center flex-wrap gap-3 mb-4 reveal">
        <a href="contact.php" class="btn btn-accent btn-lg btn-pill px-4 py-3">Discuss Your Ideas</a>
        <a href="#services" class="btn btn-outline-dark btn-lg btn-pill px-4 py-3">View Services</a>
      </div>
      <div class="reveal d-flex justify-content-center align-items-center gap-3 flex-wrap">
        <span class="handwritten-tag mb-1">Available for project</span>
        
        <!-- Premium Spinning SVG Badge -->
        <div class="spinning-badge-wrapper ms-3">
          <svg class="spin-loop" width="110" height="110" viewBox="0 0 100 100">
            <defs>
              <path id="circlePath" d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" />
            </defs>
            <text fill="var(--text-secondary)" font-size="8.5" font-weight="600" letter-spacing="1.8">
              <textPath href="#circlePath">AGNOS DESIGN STUDIO • AGENCY CREATIVE •</textPath>
            </text>
          </svg>
          <div class="position-absolute text-accent">
            <i class="bi bi-arrow-up-right fs-4"></i>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Trusted Logos Marquee -->
  <section class="py-4 bg-white border-top border-bottom overflow-hidden">
    <div class="container-fluid px-0">
      <div class="marquee-container">
        <div class="marquee-content">
          <!-- Slide 1 -->
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">TechVibe</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">NovaCore</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">StellarUX</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">CloudBase</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">AlphaLab</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">FramerCraft</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">BrandScale</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">ApexCreative</span></div>
          <!-- Loop duplicate for seamless scroll -->
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">TechVibe</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">NovaCore</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">StellarUX</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">CloudBase</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">AlphaLab</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">FramerCraft</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">BrandScale</span></div>
          <div class="marquee-item"><span class="fs-4 fw-bold text-muted px-4">ApexCreative</span></div>
        </div>
      </div>
    </div>
  </section>

  <!-- Comparison Section -->
  <section class="py-5 my-5">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2 class="display-5 fw-bold text-dark-heading mx-auto mb-3" style="max-width: 700px;">
          We know choosing the right agency is hard because few truly deliver
        </h2>
        <p class="text-secondary max-width-md mx-auto">Compare how we stack up against traditional digital agencies.</p>
      </div>
      
      <div class="comparison-container shadow-sm border">
        <div class="row g-0">
          <div class="col-lg-6 comparison-column left-col reveal-left">
            <div class="comparison-header">
              <h3 class="fw-bold text-muted mb-2">Other agencies</h3>
              <p class="small text-secondary mb-0">The typical headaches of outsourced design work</p>
            </div>
            <ul class="list-unstyled mb-0">
              <li class="comparison-list-item mb-4">
                <span class="text-danger fs-5"><i class="bi bi-x-circle-fill"></i></span>
                <span>Slow, unclear timelines</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-danger fs-5"><i class="bi bi-x-circle-fill"></i></span>
                <span>Extra charges for changes</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-danger fs-5"><i class="bi bi-x-circle-fill"></i></span>
                <span>No clear design process</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-danger fs-5"><i class="bi bi-x-circle-fill"></i></span>
                <span>Designs break in development</span>
              </li>
              <li class="comparison-list-item">
                <span class="text-danger fs-5"><i class="bi bi-x-circle-fill"></i></span>
                <span>Complex, hard-to-maintain builds</span>
              </li>
            </ul>
          </div>
          
          <div class="col-lg-6 comparison-column bg-white reveal-right">
            <div class="comparison-header">
              <h3 class="fw-bold text-accent mb-2">Agnos agency</h3>
              <p class="small text-secondary mb-0">A collaborative, modern design partnership</p>
            </div>
            <ul class="list-unstyled mb-0">
              <li class="comparison-list-item mb-4">
                <span class="text-accent fs-5"><i class="bi bi-check-circle-fill"></i></span>
                <span>Clear weekly progress updates</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-accent fs-5"><i class="bi bi-check-circle-fill"></i></span>
                <span>Transparent fixed pricing models</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-accent fs-5"><i class="bi bi-check-circle-fill"></i></span>
                <span>Fully documented design workflow</span>
              </li>
              <li class="comparison-list-item mb-4">
                <span class="text-accent fs-5"><i class="bi bi-check-circle-fill"></i></span>
                <span>Flawless design–dev alignment</span>
              </li>
              <li class="comparison-list-item">
                <span class="text-accent fs-5"><i class="bi bi-check-circle-fill"></i></span>
                <span>Clean, fast, and scalable builds</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Counters -->
  <section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
      <div class="row text-center g-4">
        <div class="col-md-4">
          <h2 class="display-3 fw-extrabold text-accent mb-2 stat-number" data-target="4.9" data-suffix="/5" data-decimal="true">0.0</h2>
          <h5 class="fw-bold text-dark mb-2">CSAT Rating</h5>
          <p class="text-secondary small mb-0 px-md-3">Measures and improves client satisfaction. Trusted by 54+ visionary brands.</p>
        </div>
        <div class="col-md-4">
          <h2 class="display-3 fw-extrabold text-accent mb-2 stat-number" data-target="100" data-suffix="+" data-decimal="false">0</h2>
          <h5 class="fw-bold text-dark mb-2">Refined Projects</h5>
          <p class="text-secondary small mb-0 px-md-3">Driven by clarity, quality, and a transparent execution process.</p>
        </div>
        <div class="col-md-4">
          <h2 class="display-3 fw-extrabold text-accent mb-2 stat-number" data-target="6" data-suffix="+" data-decimal="false">0</h2>
          <h5 class="fw-bold text-dark mb-2">Years of Experience</h5>
          <p class="text-secondary small mb-0 px-md-3">Built on years of refined skills, proven strategy, and industry knowledge.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Services Section -->
  <span id="services"></span>
  <section class="py-5 my-5">
    <div class="container py-4">
      <div class="row align-items-end mb-5 reveal">
        <div class="col-md-8">
          <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">What we do</span>
          <h2 class="display-5 fw-bold text-dark-heading mb-0">Services built to drive impact</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a href="contact.php" class="btn btn-outline-dark btn-pill px-4">Discuss Your Project</a>
        </div>
      </div>

      <div class="row g-4 reveal-stagger">
        <!-- Service 1 -->
        <div class="col-lg-4 reveal">
          <div class="card h-100 p-5 rounded-4 shadow-sm border transition-hover">
            <div class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
              <i class="bi bi-palette fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">Branding & Identity</h3>
            <p class="text-secondary mb-4">
              We define direction, structure, and positioning to build unified identity systems that stand out.
            </p>
            <div class="mt-auto">
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Identity</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Positioning</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">Voice</span>
            </div>
          </div>
        </div>

        <!-- Service 2 -->
        <div class="col-lg-4 reveal">
          <div class="card h-100 p-5 rounded-4 shadow-sm border transition-hover">
            <div class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
              <i class="bi bi-window-fullscreen fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">UI/UX Design</h3>
            <p class="text-secondary mb-4">
              Crafting intuitive, user-centered interfaces that blend clarity, beauty, and effortless interaction.
            </p>
            <div class="mt-auto">
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Web</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Product</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">App</span>
            </div>
          </div>
        </div>

        <!-- Service 3 -->
        <div class="col-lg-4 reveal">
          <div class="card h-100 p-5 rounded-4 shadow-sm border transition-hover">
            <div class="bg-accent-light text-accent rounded-circle d-flex align-items-center justify-content-center mb-4" style="width: 60px; height: 60px;">
              <i class="bi bi-code-slash fs-3"></i>
            </div>
            <h3 class="fw-bold text-dark mb-3">Web Development</h3>
            <p class="text-secondary mb-4">
              We build fast, scalable, and responsive websites that perform beautifully across all devices.
            </p>
            <div class="mt-auto">
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Framer</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill me-2 border">Front-end</span>
              <span class="badge bg-light text-dark px-3 py-2 rounded-pill border">CMS</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Featured Projects Section -->
  <section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
      <div class="row align-items-end mb-5 reveal">
        <div class="col-md-8">
          <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Featured Projects</span>
          <h2 class="display-5 fw-bold text-dark-heading mb-0">Refined projects with purpose</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a href="projects.php" class="btn btn-outline-dark btn-pill px-4">Explore All Projects</a>
        </div>
      </div>

      <div class="row g-4 reveal-stagger">
        <?php 
        $featuredKeys = ['atelier-nine', 'nova-studio', 'haven-living', 'orion-fitness'];
        foreach ($featuredKeys as $key): 
            if (isset($cmsData['projects'][$key])):
                $p = $cmsData['projects'][$key];
        ?>
        <!-- Project Card -->
        <div class="col-md-6 reveal">
          <a href="project-detail.php?id=<?php echo $p['id']; ?>" class="card-project-link text-decoration-none">
            <div class="card h-100 border rounded-4 overflow-hidden shadow-sm transition-hover">
              <div class="card-img-wrapper overflow-hidden position-relative" style="height: 350px;">
                <img src="<?php echo $p['image']; ?>" class="w-100 h-100 object-fit-cover transition-scale" alt="<?php echo $p['title']; ?>" onerror="this.src='https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=600&q=80'">
                <div class="card-hover-overlay d-flex align-items-center justify-content-center">
                  <span class="btn btn-light rounded-pill px-4 fw-semibold py-2">View Case Study</span>
                </div>
              </div>
              <div class="card-body p-4">
                <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block"><?php echo $p['industry']; ?></span>
                <h4 class="card-title fw-bold text-dark mb-2"><?php echo $p['title']; ?></h4>
                <p class="card-text text-secondary mb-0"><?php echo $p['description']; ?></p>
              </div>
            </div>
          </a>
        </div>
        <?php 
            endif;
        endforeach; 
        ?>
      </div>
    </div>
  </section>

  <!-- Process Section -->
  <section class="py-5 my-5">
    <div class="container py-4">
      <div class="text-center mb-5 reveal">
        <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Our Workflow</span>
        <h2 class="display-5 fw-bold text-dark-heading mb-3">A collaborative approach</h2>
        <p class="text-secondary max-width-sm mx-auto">We follow a simple, highly iterative process to ship outstanding layouts from concept to deployment.</p>
      </div>

      <div class="row g-4 justify-content-center reveal-stagger">
        <!-- Step 1 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="process-step-card shadow-sm border transition-hover">
            <span class="process-step-num">01</span>
            <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold">Step 01 / 05</span>
            <h4 class="fw-bold text-dark mb-3">Brand Strategy</h4>
            <p class="text-secondary small mb-0">
              We define brand direction, customer personas, structure, and positioning to lay a solid conceptual blueprint for our visual layouts.
            </p>
          </div>
        </div>

        <!-- Step 2 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="process-step-card shadow-sm border transition-hover">
            <span class="process-step-num">02</span>
            <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold">Step 02 / 05</span>
            <h4 class="fw-bold text-dark mb-3">Design Direction</h4>
            <p class="text-secondary small mb-0">
              Building visual identity systems, selecting harmonious color schemes, and constructing moodboards that match your product vision.
            </p>
          </div>
        </div>

        <!-- Step 3 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="process-step-card shadow-sm border transition-hover">
            <span class="process-step-num">03</span>
            <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold">Step 03 / 05</span>
            <h4 class="fw-bold text-dark mb-3">UI/UX Crafting</h4>
            <p class="text-secondary small mb-0">
              Designing refined, high-fidelity responsive mockups, mapping out navigation layouts, and embedding micro-animations on interactive components.
            </p>
          </div>
        </div>

        <!-- Step 4 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="process-step-card shadow-sm border transition-hover">
            <span class="process-step-num">04</span>
            <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold">Step 04 / 05</span>
            <h4 class="fw-bold text-dark mb-3">Development</h4>
            <p class="text-secondary small mb-0">
              Building lightweight templates using custom tokens, CSS grid columns, dynamic JS CMS databases, and optimizing web loading speeds.
            </p>
          </div>
        </div>

        <!-- Step 5 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="process-step-card shadow-sm border transition-hover">
            <span class="process-step-num">05</span>
            <span class="badge bg-accent-light text-accent mb-3 px-3 py-2 rounded-pill fw-semibold">Step 05 / 05</span>
            <h4 class="fw-bold text-dark mb-3">Final Launch</h4>
            <p class="text-secondary small mb-0">
              Ensuring SEO best practices are integrated, running cross-device tests, loading favicon visual packages, and deploying live production builds.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Pricing Section -->
  <section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
      <div class="text-center mb-5 reveal">
        <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Pricing plans</span>
        <h2 class="display-5 fw-bold text-dark-heading mb-3">Plans that fit your growth stage</h2>
        <p class="text-secondary max-width-md mx-auto">Choose a plan that matches your current product roadmap and design ambitions.</p>
      </div>

      <div class="row g-4 justify-content-center reveal-stagger">
        <!-- Tier 1: Starter -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="pricing-card border transition-hover shadow-sm">
            <h4 class="fw-bold text-dark mb-2">Starter</h4>
            <p class="text-secondary small mb-4">Perfect for early startups launching their first landing page.</p>
            <div class="mb-4">
              <span class="price-value"><span class="price-currency">$</span>999</span>
              <span class="text-muted small fw-medium">/ project</span>
            </div>
            <hr class="my-4 border-color">
            <ul class="list-unstyled mb-5">
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> 1 Landing Page Design</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Basic Brand Guidelines</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Mobile Responsive Layout</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> 2 Complete Revisions</li>
              <li class="d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Delivery in 7 days</li>
            </ul>
            <a href="contact.php" class="btn btn-outline-dark btn-pill w-100 py-3 mt-auto">Get Started Now</a>
          </div>
        </div>

        <!-- Tier 2: Growth -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="pricing-card popular transition-hover shadow">
            <h4 class="fw-bold text-dark mb-2">Growth</h4>
            <p class="text-secondary small mb-4">Best for growing brands looking to scale their digital footprint.</p>
            <div class="mb-4">
              <span class="price-value"><span class="price-currency">$</span>2,499</span>
              <span class="text-muted small fw-medium">/ project</span>
            </div>
            <hr class="my-4 border-color">
            <ul class="list-unstyled mb-5">
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Multi-page Website Design</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Complete Brand Strategy</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Advanced Web Animations</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> 4 Complete Revisions</li>
              <li class="d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Delivery in 14 days</li>
            </ul>
            <a href="contact.php" class="btn btn-accent btn-pill w-100 py-3 mt-auto">Choose Growth Plan</a>
          </div>
        </div>

        <!-- Tier 3: Enterprise -->
        <div class="col-lg-4 col-md-6 reveal">
          <div class="pricing-card border transition-hover shadow-sm">
            <h4 class="fw-bold text-dark mb-2">Enterprise</h4>
            <p class="text-secondary small mb-4">For agencies and companies needing custom strategy & execution.</p>
            <div class="mb-4">
              <span class="price-value">Custom</span>
              <span class="text-muted small fw-medium">/ monthly</span>
            </div>
            <hr class="my-4 border-color">
            <ul class="list-unstyled mb-5">
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Dedicated Design Team</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Complete Product Scoping</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Custom Integration Systems</li>
              <li class="mb-3 d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Unlimited Revision Cycles</li>
              <li class="d-flex align-items-center"><i class="bi bi-check2 text-accent me-2 fs-5"></i> Continuous 24/7 Slack Support</li>
            </ul>
            <a href="contact.php" class="btn btn-outline-dark btn-pill w-100 py-3 mt-auto">Contact Us</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="py-5 my-5">
    <div class="container py-4">
      <div class="text-center mb-5 reveal">
        <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Testimonials</span>
        <h2 class="display-5 fw-bold text-dark-heading mb-3">What our clients say</h2>
        <p class="text-secondary max-width-sm mx-auto">Read reviews from visionary business leaders who partnered with us.</p>
      </div>

      <div class="row g-4 reveal-stagger">
        <!-- Testimonial 1 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="testimonial-card border shadow-sm transition-hover bg-white d-flex flex-column">
            <div class="mb-4 text-warning">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small flex-grow-1 fst-italic">
              "Working with this team was effortless. They understood our brand vision & turned it into a digital experience that truly represents who we are."
            </p>
            <hr class="border-color my-3">
            <div class="d-flex align-items-center">
              <div class="avatar-circle me-3 bg-accent text-white d-flex align-items-center justify-content-center fw-bold rounded-circle" style="width: 40px; height: 40px;">OC</div>
              <div>
                <h6 class="fw-bold text-dark mb-0">Olivia Carter</h6>
                <span class="small text-muted">Brand Director</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial 2 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="testimonial-card border shadow-sm transition-hover bg-white d-flex flex-column">
            <div class="mb-4 text-warning">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small flex-grow-1 fst-italic">
              "They translated our ideas into a clean, modern digital presence that feels exactly right for our brand. The feedback has been stellar."
            </p>
            <hr class="border-color my-3">
            <div class="d-flex align-items-center">
              <div class="avatar-circle me-3 bg-accent text-white d-flex align-items-center justify-content-center fw-bold rounded-circle" style="width: 40px; height: 40px;">EM</div>
              <div>
                <h6 class="fw-bold text-dark mb-0">Ethan Miller</h6>
                <span class="small text-muted">Director, TechMart</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Testimonial 3 -->
        <div class="col-md-6 col-lg-4 reveal">
          <div class="testimonial-card border shadow-sm transition-hover bg-white d-flex flex-column">
            <div class="mb-4 text-warning">
              <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
            </div>
            <p class="text-secondary small flex-grow-1 fst-italic">
              "The team instantly grasped what we needed and delivered a seamless experience that exceeded every single expectation."
            </p>
            <hr class="border-color my-3">
            <div class="d-flex align-items-center">
              <div class="avatar-circle me-3 bg-accent text-white d-flex align-items-center justify-content-center fw-bold rounded-circle" style="width: 40px; height: 40px;">SR</div>
              <div>
                <h6 class="fw-bold text-dark mb-0">Sophia Reyes</h6>
                <span class="small text-muted">Data Consultant</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Latest Blog Section -->
  <section class="py-5 bg-white border-top border-bottom">
    <div class="container py-4">
      <div class="row align-items-end mb-5 reveal">
        <div class="col-md-8">
          <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Insights</span>
          <h2 class="display-5 fw-bold text-dark-heading mb-0">Latest design thinking</h2>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
          <a href="blog.php" class="btn btn-outline-dark btn-pill px-4">View All Articles</a>
        </div>
      </div>

      <div class="row g-4 reveal-stagger">
        <?php 
        $blogKeys = ['why-storytelling-shapes-brand-success', 'the-future-of-scalable-design-systems-in-2025', 'built-for-high-performance-in-framer'];
        foreach ($blogKeys as $key): 
            if (isset($cmsData['blogs'][$key])):
                $b = $cmsData['blogs'][$key];
        ?>
        <!-- Blog Card -->
        <div class="col-md-4 reveal">
          <a href="blog-detail.php?id=<?php echo $b['id']; ?>" class="text-decoration-none card-project-link">
            <div class="card h-100 border rounded-4 overflow-hidden shadow-sm transition-hover">
              <div class="card-img-wrapper" style="height: 200px;">
                <img src="<?php echo $b['image']; ?>" class="w-100 h-100 object-fit-cover transition-scale" alt="<?php echo $b['title']; ?>" onerror="this.src='https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=600&q=80'">
              </div>
              <div class="card-body p-4">
                <span class="badge bg-light text-dark border px-2 py-1 mb-2"><?php echo $b['category']; ?></span>
                <h5 class="fw-bold text-dark mb-2"><?php echo $b['title']; ?></h5>
                <p class="text-secondary small mb-0"><?php echo substr($b['introduction'], 0, 110) . '...'; ?></p>
              </div>
              <div class="card-footer bg-transparent border-top-0 p-4 pt-0 text-muted small">
                <span><?php echo $b['date']; ?> &middot; <?php echo $b['author']; ?></span>
              </div>
            </div>
          </a>
        </div>
        <?php 
            endif;
        endforeach; 
        ?>
      </div>
    </div>
  </section>

  <!-- FAQ Section -->
  <section class="py-5 my-5">
    <div class="container py-4">
      <div class="text-center mb-5 reveal">
        <span class="text-uppercase text-accent small fw-bold tracking-wider mb-2 d-block">Support</span>
        <h2 class="display-5 fw-bold text-dark-heading mb-3">Frequently Asked Questions</h2>
        <p class="text-secondary max-width-sm mx-auto">Here are some of the most common questions clients ask before hiring us.</p>
      </div>

      <div class="faq-list reveal-stagger">
        <?php foreach ($cmsData['faqs'] as $index => $faq): ?>
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

  <!-- Footer Call to Action -->
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
