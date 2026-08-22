/**
 * MedicEdu Global — Main JavaScript File
 * Powered by GSAP (GreenSock Animation Platform) + ScrollTrigger
 * Off-Canvas Mobile Drawer, Hero Slider, FAQ Accordions, and Lead Handlers
 */

document.addEventListener('DOMContentLoaded', () => {

  // 1. Dynamic Copyright Year
  document.querySelectorAll('[data-year]').forEach(el => {
    el.textContent = new Date().getFullYear();
  });

  // 2. Off-Canvas Drawer Menu Controls
  const mobileToggle = document.querySelector('.mobile-toggle');
  const offcanvasDrawer = document.querySelector('.offcanvas-drawer');
  const offcanvasOverlay = document.querySelector('.offcanvas-overlay');
  const offcanvasClose = document.querySelector('.offcanvas-close');
  const offcanvasDropdownBtn = document.querySelector('.offcanvas-dropdown-btn');
  const offcanvasSubnav = document.querySelector('.offcanvas-subnav');

  function openOffcanvas() {
    if (offcanvasDrawer && offcanvasOverlay) {
      offcanvasDrawer.classList.add('active');
      offcanvasOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
      
      if (window.gsap) {
        gsap.fromTo('.offcanvas-nav > li', 
          { x: 20, opacity: 0 },
          { x: 0, opacity: 1, stagger: 0.04, duration: 0.25, ease: 'power2.out', clearProps: 'all' }
        );
      }
    }
  }

  function closeOffcanvas() {
    if (offcanvasDrawer && offcanvasOverlay) {
      offcanvasDrawer.classList.remove('active');
      offcanvasOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }
  }

  if (mobileToggle) mobileToggle.addEventListener('click', openOffcanvas);
  if (offcanvasClose) offcanvasClose.addEventListener('click', closeOffcanvas);
  if (offcanvasOverlay) offcanvasOverlay.addEventListener('click', closeOffcanvas);

  // Toggle subnav in off-canvas drawer
  if (offcanvasDropdownBtn && offcanvasSubnav) {
    offcanvasDropdownBtn.addEventListener('click', (e) => {
      e.preventDefault();
      offcanvasSubnav.classList.toggle('active');
      const icon = offcanvasDropdownBtn.querySelector('.ri-arrow-down-s-line, .ri-arrow-up-s-line');
      if (icon) {
        icon.classList.toggle('ri-arrow-up-s-line');
        icon.classList.toggle('ri-arrow-down-s-line');
      }
    });
  }

  // 3. Dynamic Hero Slider
  const slides = document.querySelectorAll('.hero-slider .slide');
  const dots = document.querySelectorAll('.slider-dots button');
  const prevBtn = document.querySelector('.slider-arrows .prev-slide');
  const nextBtn = document.querySelector('.slider-arrows .next-slide');
  let currentSlide = 0;
  let slideInterval = null;

  function showSlide(index) {
    if (!slides.length) return;
    slides.forEach((slide, i) => {
      slide.classList.toggle('active', i === index);
    });
    dots.forEach((dot, i) => {
      dot.classList.toggle('active', i === index);
    });
    currentSlide = index;

    if (window.gsap) {
      const activeSlide = slides[index];
      if (activeSlide) {
        gsap.fromTo(activeSlide.querySelectorAll('.hero-copy > *'), 
          { opacity: 0, y: 20 },
          { opacity: 1, y: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out', clearProps: 'all' }
        );
        gsap.fromTo(activeSlide.querySelector('.hero-photo'),
          { opacity: 0.85, scale: 1.03 },
          { opacity: 1, scale: 1, duration: 0.6, ease: 'power2.out', clearProps: 'all' }
        );
      }
    }
  }

  function nextSlide() {
    let next = (currentSlide + 1) % slides.length;
    showSlide(next);
  }

  function prevSlide() {
    let prev = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prev);
  }

  if (nextBtn) nextBtn.addEventListener('click', () => { nextSlide(); resetTimer(); });
  if (prevBtn) prevBtn.addEventListener('click', () => { prevSlide(); resetTimer(); });

  dots.forEach((dot, i) => {
    dot.addEventListener('click', () => {
      showSlide(i);
      resetTimer();
    });
  });

  function startTimer() {
    if (slides.length > 1) {
      slideInterval = setInterval(nextSlide, 6500);
    }
  }

  function resetTimer() {
    clearInterval(slideInterval);
    startTimer();
  }

  const sliderWrap = document.querySelector('.hero-slider-wrap');
  if (sliderWrap) {
    sliderWrap.addEventListener('mouseenter', () => clearInterval(slideInterval));
    sliderWrap.addEventListener('mouseleave', startTimer);
  }
  startTimer();

  // 4. Interactive FAQ Accordion
  const faqQuestions = document.querySelectorAll('.faq-question');
  faqQuestions.forEach(btn => {
    btn.addEventListener('click', () => {
      const item = btn.parentElement;
      const isActive = item.classList.contains('active');
      
      document.querySelectorAll('.faq-item').forEach(other => {
        if (other !== item) {
          other.classList.remove('active');
          const answer = other.querySelector('.faq-answer');
          if (answer) answer.style.maxHeight = null;
        }
      });

      item.classList.toggle('active', !isActive);
      const answer = item.querySelector('.faq-answer');
      if (answer) {
        if (!isActive) {
          answer.style.maxHeight = answer.scrollHeight + 30 + 'px';
        } else {
          answer.style.maxHeight = null;
        }
      }
    });
  });

  // Open the first FAQ by default
  const firstFaq = document.querySelector('.faq-item.active .faq-answer');
  if (firstFaq) {
    firstFaq.style.maxHeight = firstFaq.scrollHeight + 30 + 'px';
  }

  // 5. Interactive Form Submission Handlers
  document.querySelectorAll('.demo-form').forEach(form => {
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const status = form.querySelector('.form-status');
      const submitBtn = form.querySelector('button[type="submit"]');
      
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="ri-loader-4-line ri-spin"></i> Submitting...';
      }

      setTimeout(() => {
        if (status) {
          status.style.color = '#10B981';
          status.innerHTML = '<i class="ri-checkbox-circle-fill"></i> Thank you! Your request has been received. Our senior counsellor will call you at +91 94106 24320 shortly.';
        }
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = '<i class="ri-check-line"></i> Submitted Successfully';
        }
        form.reset();
      }, 1000);
    });
  });

  // 6. Robust GSAP + ScrollTrigger Animations (Never Blocks Visibility)
  if (window.gsap && window.ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);

    // Floating badges gentle bobbing
    if (document.querySelector('.hero-badge-1')) {
      gsap.to('.hero-badge-1', {
        y: -6,
        duration: 2.2,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut'
      });
    }
    if (document.querySelector('.hero-badge-2')) {
      gsap.to('.hero-badge-2', {
        y: 6,
        duration: 2.5,
        repeat: -1,
        yoyo: true,
        ease: 'sine.inOut'
      });
    }

    // Gentle scroll triggers with clearProps so elements never get stuck
    gsap.utils.toArray('.section-head').forEach(head => {
      gsap.fromTo(head,
        { y: 24, opacity: 0 },
        {
          scrollTrigger: {
            trigger: head,
            start: 'top 90%',
            once: true
          },
          y: 0,
          opacity: 1,
          duration: 0.6,
          ease: 'power2.out',
          clearProps: 'transform,opacity'
        }
      );
    });

    gsap.utils.toArray('.grid-3, .grid-4, .process-grid, .timeline').forEach(grid => {
      if (grid && grid.children && grid.children.length > 0) {
        gsap.fromTo(grid.children,
          { y: 25, opacity: 0 },
          {
            scrollTrigger: {
              trigger: grid,
              start: 'top 90%',
              once: true
            },
            y: 0,
            opacity: 1,
            duration: 0.5,
            stagger: 0.08,
            ease: 'power2.out',
            clearProps: 'transform,opacity'
          }
        );
      }
    });

    gsap.utils.toArray('.table-responsive, .form-card, .about-image').forEach(el => {
      gsap.fromTo(el,
        { y: 20, opacity: 0 },
        {
          scrollTrigger: {
            trigger: el,
            start: 'top 92%',
            once: true
          },
          y: 0,
          opacity: 1,
          duration: 0.6,
          ease: 'power2.out',
          clearProps: 'transform,opacity'
        }
      );
    });

    // Refresh ScrollTrigger when images load
    window.addEventListener('load', () => {
      ScrollTrigger.refresh();
    });
  }

});
