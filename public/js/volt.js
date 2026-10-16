(() => {
  const root = document.documentElement;
  const $ = (s, c = document) => c.querySelector(s);
  const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

  // 1. Theme Management (Remember preference & auto-detect OS preference)
  const savedTheme = localStorage.getItem('theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  root.dataset.theme = savedTheme;

  const updateThemeButton = () => {
    const isDark = root.dataset.theme === 'dark';
    $$('.theme-toggle-btn').forEach(btn => {
      btn.textContent = isDark ? '☀️' : '🌙';
      btn.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
      btn.title = isDark ? 'Switch to light mode' : 'Switch to dark mode';
    });
    const tb = $('#theme');
    if (tb) {
      tb.textContent = isDark ? '☀️' : '🌙';
      tb.setAttribute('aria-label', isDark ? 'Switch to light mode' : 'Switch to dark mode');
      tb.title = isDark ? 'Switch to light mode' : 'Switch to dark mode';
    }
  };

  const toggleTheme = () => {
    const nextTheme = root.dataset.theme === 'dark' ? 'light' : 'dark';
    root.dataset.theme = nextTheme;
    localStorage.setItem('theme', nextTheme);
    updateThemeButton();
  };

  document.addEventListener('DOMContentLoaded', () => {
    updateThemeButton();

    // Theme toggle events
    $('#theme')?.addEventListener('click', (e) => {
      e.preventDefault();
      toggleTheme();
    });
    $$('.theme-toggle-btn').forEach(btn => btn.addEventListener('click', (e) => {
      e.preventDefault();
      toggleTheme();
    }));

    // 2. Mobile Drawer & Backdrop Controller
    const drawer = $('#mobileDrawer');
    const burger = $('#burger');
    const backdrop = $('#menuBackdrop') || $('.menu-backdrop');
    const drawerClose = $('#drawerClose') || $('.drawer-close');

    const openDrawer = () => {
      if (!drawer) return;
      drawer.classList.add('open');
      drawer.setAttribute('aria-hidden', 'false');
      backdrop?.classList.add('open');
      document.body.style.overflow = 'hidden';
    };

    const closeDrawer = () => {
      if (!drawer) return;
      drawer.classList.remove('open');
      drawer.setAttribute('aria-hidden', 'true');
      backdrop?.classList.remove('open');
      document.body.style.overflow = '';
    };

    burger?.addEventListener('click', (e) => {
      e.preventDefault();
      e.stopPropagation();
      drawer?.classList.contains('open') ? closeDrawer() : openDrawer();
    });

    drawerClose?.addEventListener('click', (e) => {
      e.preventDefault();
      closeDrawer();
    });

    backdrop?.addEventListener('click', (e) => {
      e.preventDefault();
      closeDrawer();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && drawer?.classList.contains('open')) {
        closeDrawer();
      }
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth > 860 && drawer?.classList.contains('open')) {
        closeDrawer();
      }
    }, { passive: true });

    // 3. Toast Notifications
    const toast = (message, isError = false) => {
      let t = $('.toast');
      if (!t) {
        t = document.createElement('div');
        t.className = 'toast';
        document.body.appendChild(t);
      }
      t.innerHTML = message;
      t.style.borderColor = isError ? 'var(--err)' : 'var(--blue)';
      t.classList.add('show');
      setTimeout(() => t.classList.remove('show'), 2600);
    };

    // 4. AJAX Add to Cart
    document.addEventListener('submit', async (e) => {
      const f = e.target.closest('form.js-add');
      if (!f) return;
      e.preventDefault();

      const submitBtn = f.querySelector('button[type=submit], button.btn');
      const originalText = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '⏳ ...';
      }

      try {
        const csrfToken = $('meta[name=csrf-token]')?.content || '';
        const r = await fetch(f.action, {
          method: 'POST',
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: new FormData(f)
        });

        if (!r.ok) {
          toast('⚠️ ' + (r.statusText || 'Error'), true);
          return;
        }

        const data = await r.json();
        const badge = $('#cartCount');
        if (badge) {
          badge.textContent = data.count;
          badge.hidden = false;
          if (window.gsap) {
            gsap.fromTo(badge, { scale: 1.8 }, { scale: 1, duration: 0.35, ease: 'back.out(2)' });
          }
        }
        toast('✅ ' + (data.msg || 'Added to cart'));
      } catch (err) {
        toast('⚠️ Connection error', true);
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalText;
        }
      }
    });

    // 5. Quantity Steppers (+ / -)
    document.addEventListener('click', (e) => {
      const btn = e.target.closest('.qty-btn');
      if (!btn) return;
      e.preventDefault();

      const stepper = btn.closest('.qty-stepper');
      if (!stepper) return;

      const input = stepper.querySelector('input[type=number]');
      if (!input) return;

      const min = parseInt(input.getAttribute('min') || '1', 10);
      const max = parseInt(input.getAttribute('max') || '99', 10);
      let val = parseInt(input.value || '1', 10);

      if (btn.classList.contains('qty-plus')) {
        if (val < max) val++;
      } else if (btn.classList.contains('qty-minus')) {
        if (val > min) val--;
      }

      input.value = val;
      input.dispatchEvent(new Event('change', { bubbles: true }));

      // If in cart table/card, auto-submit update
      const autoForm = stepper.closest('form.qty-auto-submit');
      if (autoForm) autoForm.submit();
    });

    // 6. Shop Filters: live debounce search & auto submit
    const filtersForm = $('#filters');
    if (filtersForm) {
      filtersForm.querySelectorAll('select').forEach(sel => {
        sel.addEventListener('change', () => filtersForm.submit());
      });

      let debounceTimer;
      filtersForm.querySelector('input[name=q]')?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => filtersForm.submit(), 500);
      });
    }

    // 7. Mobile Filter Modal Toggle
    const mobileFilterBtn = $('#mobileFilterToggle');
    const filterAside = $('.filters');
    if (mobileFilterBtn && filterAside) {
      mobileFilterBtn.addEventListener('click', () => {
        filterAside.classList.toggle('show-mobile');
      });
    }

    // 8. Payment Method Card Toggle
    const cardBox = $('#cardBox');
    $$('input[name=payment_method]').forEach(r => {
      r.addEventListener('change', () => {
        if (cardBox) {
          cardBox.hidden = r.value !== 'card' || !r.checked;
        }
      });
    });

    // Format Credit Card Number & Expiry
    const cardNum = $('#card_number');
    if (cardNum) {
      cardNum.addEventListener('input', (e) => {
        let v = e.target.value.replace(/\D/g, '').slice(0, 16);
        e.target.value = v.replace(/(.{4})/g, '$1 ').trim();
      });
    }

    const cardExp = $('#card_exp');
    if (cardExp) {
      cardExp.addEventListener('input', (e) => {
        let v = e.target.value.replace(/\D/g, '').slice(0, 4);
        e.target.value = v.length > 2 ? v.slice(0, 2) + '/' + v.slice(2) : v;
      });
    }

    // 9. Back to Top Button
    const backToTop = $('#backToTop');
    if (backToTop) {
      window.addEventListener('scroll', () => {
        if (window.scrollY > 320) {
          backToTop.classList.add('visible');
        } else {
          backToTop.classList.remove('visible');
        }
      }, { passive: true });

      backToTop.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }

    // 10. AOS & GSAP Animations
    if (window.AOS) {
      AOS.init({ duration: 600, once: true, offset: 30 });
    }

    if (window.gsap && $('.hero')) {
      gsap.from('.hero h1, .hero p, .hero .btns, .hero-pill', {
        y: 24,
        opacity: 0,
        duration: 0.75,
        stagger: 0.1,
        ease: 'power3.out'
      });
      gsap.to('.orb', {
        y: -12,
        duration: 2.6,
        yoyo: true,
        repeat: -1,
        ease: 'sine.inOut',
        stagger: 0.35
      });
    }
  });
})();
