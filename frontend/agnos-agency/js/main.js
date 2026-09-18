// Unified Javascript functionality for Agnos Creative Agency Website (PHP Version)

document.addEventListener("DOMContentLoaded", () => {
  setupNavbar();
  setupFAQAccordions();
  setupStatsCounters();
  setupProjectFiltering();
  setupContactForm();
  setupScrollReveal();
  setupCustomCursor();
});

// 1. Navbar Glassmorphism on Scroll & Mobile Toggle
function setupNavbar() {
  const navbar = document.querySelector(".navbar");
  if (navbar) {
    window.addEventListener("scroll", () => {
      if (window.scrollY > 50) {
        navbar.classList.add("navbar-scrolled");
      } else {
        navbar.classList.remove("navbar-scrolled");
      }
    });
  }

  // Mobile menu close on link click
  const navLinks = document.querySelectorAll(".navbar-nav .nav-link");
  const navCollapse = document.querySelector(".navbar-collapse");
  if (navCollapse) {
    navLinks.forEach(link => {
      link.addEventListener("click", () => {
        if (window.getComputedStyle(navCollapse).display !== "none") {
          const bsCollapse = bootstrap.Collapse.getInstance(navCollapse);
          if (bsCollapse) bsCollapse.hide();
        }
      });
    });
  }
}

// 2. FAQ Accordion Toggles
function setupFAQAccordions() {
  const faqItems = document.querySelectorAll(".faq-item");
  faqItems.forEach(item => {
    const question = item.querySelector(".faq-question");
    const answer = item.querySelector(".faq-answer");
    if (question && answer) {
      question.addEventListener("click", () => {
        const isOpen = item.classList.contains("active");
        
        // Close all other FAQs
        faqItems.forEach(i => {
          i.classList.remove("active");
          const ans = i.querySelector(".faq-answer");
          if (ans) ans.style.maxHeight = null;
        });
 
        // Toggle current FAQ
        if (!isOpen) {
          item.classList.add("active");
          answer.style.maxHeight = answer.scrollHeight + "px";
        }
      });
    }
  });
}

// 3. Stats Counter Animation (Uses IntersectionObserver)
function setupStatsCounters() {
  const statsElements = document.querySelectorAll(".stat-number");
  if (statsElements.length === 0) return;

  const countUp = (element) => {
    const target = parseFloat(element.getAttribute("data-target"));
    const duration = 2000; // 2 seconds
    const suffix = element.getAttribute("data-suffix") || "";
    const isDecimal = element.getAttribute("data-decimal") === "true";
    let start = 0;
    const startTime = performance.now();

    const updateCount = (currentTime) => {
      const elapsed = currentTime - startTime;
      const progress = Math.min(elapsed / duration, 1);
      
      // Easing function (easeOutQuad)
      const easeProgress = progress * (2 - progress);
      const currentVal = start + easeProgress * (target - start);

      if (isDecimal) {
        element.textContent = currentVal.toFixed(1) + suffix;
      } else {
        element.textContent = Math.floor(currentVal) + suffix;
      }

      if (progress < 1) {
        requestAnimationFrame(updateCount);
      } else {
        if (isDecimal) {
          element.textContent = target.toFixed(1) + suffix;
        } else {
          element.textContent = target + suffix;
        }
      }
    };

    requestAnimationFrame(updateCount);
  };

  const observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        countUp(entry.target);
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.5 });

  statsElements.forEach(element => observer.observe(element));
}

// 4. Portfolio Listing Page DOM Filters
function setupProjectFiltering() {
  const filters = document.querySelectorAll(".filter-btn");
  const cards = document.querySelectorAll(".project-card-item");
  if (filters.length === 0 || cards.length === 0) return;

  filters.forEach(btn => {
    btn.addEventListener("click", () => {
      // Toggle button classes
      filters.forEach(f => f.classList.remove("active", "btn-dark"));
      filters.forEach(f => f.classList.add("btn-outline-dark"));
      
      btn.classList.add("active", "btn-dark");
      btn.classList.remove("btn-outline-dark");

      const category = btn.getAttribute("data-filter");

      cards.forEach(card => {
        const cardCats = card.getAttribute("data-category").split(" ");
        if (category === "all" || cardCats.includes(category)) {
          card.style.display = "block";
          card.style.opacity = "0";
          setTimeout(() => {
            card.style.transition = "opacity 0.4s ease-in-out";
            card.style.opacity = "1";
          }, 50);
        } else {
          card.style.display = "none";
        }
      });
    });
  });
}

// 5. Contact Form Validations & Toasts
function setupContactForm() {
  const form = document.getElementById("contact-agency-form");
  if (!form) return;

  const successMessage = document.getElementById("form-success-toast");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.disabled = true;
    submitBtn.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Sending...`;

    // Simulate Server Request (1.5 seconds delay)
    setTimeout(() => {
      submitBtn.disabled = false;
      submitBtn.innerHTML = originalText;
      
      // Clear form
      form.reset();

      // Show success alert
      if (successMessage) {
        successMessage.classList.add("show");
        // Hide after 5 seconds
        setTimeout(() => {
          successMessage.classList.remove("show");
        }, 5000);
      } else {
        alert("Thank you! Your message has been sent successfully. We will reach out to you within 24 hours.");
      }
    }, 1500);
  });
}

// 6. Scroll Reveal Observer System
function setupScrollReveal() {
  const revealElements = document.querySelectorAll(".reveal, .reveal-left, .reveal-right");
  if (revealElements.length === 0) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add("revealed");
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.15,
    rootMargin: "0px 0px -50px 0px"
  });

  revealElements.forEach(el => observer.observe(el));
}

// 7. Premium Custom Cursor Trail Animation (Lerped Follow)
function setupCustomCursor() {
  const dot = document.querySelector(".custom-cursor-dot");
  const outline = document.querySelector(".custom-cursor-outline");
  if (!dot || !outline) return;

  let mouseX = 0;
  let mouseY = 0;
  let outlineX = 0;
  let outlineY = 0;
  let cursorActive = false;

  document.addEventListener("mousemove", (e) => {
    mouseX = e.clientX;
    mouseY = e.clientY;
    
    if (!cursorActive) {
      dot.style.opacity = "1";
      outline.style.opacity = "1";
      cursorActive = true;
    }
    
    // Immediate dot tracking
    dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate3d(-50%, -50%, 0)`;
  });

  document.addEventListener("mouseleave", () => {
    dot.style.opacity = "0";
    outline.style.opacity = "0";
    cursorActive = false;
  });

  // Lerp tracking animation loop
  function animateOutline() {
    const distX = mouseX - outlineX;
    const distY = mouseY - outlineY;
    
    // Outer circle follows with 15% linear interpolation factor
    outlineX += distX * 0.15;
    outlineY += distY * 0.15;
    
    outline.style.transform = `translate3d(${outlineX}px, ${outlineY}px, 0) translate3d(-50%, -50%, 0)`;
    requestAnimationFrame(animateOutline);
  }
  requestAnimationFrame(animateOutline);

  // Bind hover actions to interactive nodes
  const hoverables = document.querySelectorAll(
    "a, button, .filter-btn, .faq-question, .spinning-badge-wrapper, .pricing-card, .faq-item"
  );
  hoverables.forEach((el) => {
    el.addEventListener("mouseenter", () => {
      outline.style.width = "50px";
      outline.style.height = "50px";
      outline.style.backgroundColor = "rgba(255, 99, 33, 0.05)";
      outline.style.borderColor = "var(--accent)";
      dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate3d(-50%, -50%, 0) scale(0.5)`;
    });
    el.addEventListener("mouseleave", () => {
      outline.style.width = "32px";
      outline.style.height = "32px";
      outline.style.backgroundColor = "transparent";
      outline.style.borderColor = "var(--accent)";
      dot.style.transform = `translate3d(${mouseX}px, ${mouseY}px, 0) translate3d(-50%, -50%, 0) scale(1)`;
    });
  });
}
