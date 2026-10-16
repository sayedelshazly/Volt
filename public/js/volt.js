(() => {
  const root = document.documentElement, $ = (s, c = document) => c.querySelector(s);
  // الثيم: يتذكر اختيار المستخدم أو يتبع إعداد الجهاز
  const saved = localStorage.getItem('theme') || (matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
  root.dataset.theme = saved;
  document.addEventListener('DOMContentLoaded', () => {
    const tb = $('#theme'); const paint = () => tb && (tb.textContent = root.dataset.theme === 'dark' ? '☀️' : '🌙'); paint();
    tb?.addEventListener('click', () => { root.dataset.theme = root.dataset.theme === 'dark' ? 'light' : 'dark'; localStorage.setItem('theme', root.dataset.theme); paint(); });
    $('#burger')?.addEventListener('click', () => $('#menu').classList.toggle('open'));
    window.AOS && AOS.init({ duration: 650, once: true, offset: 40 });
    // حركة الهيرو (GSAP) - مرة واحدة عند التحميل
    if (window.gsap && $('.hero')) {
      gsap.from('.hero h1,.hero p,.hero .btns', { y: 28, opacity: 0, duration: .8, stagger: .12, ease: 'power3.out' });
      gsap.to('.orb', { y: -14, duration: 2.4, yoyo: true, repeat: -1, ease: 'sine.inOut', stagger: .35 });
    }
    // إضافة للسلة بدون إعادة تحميل
    const toast = m => { let t = $('.toast') || document.body.appendChild(Object.assign(document.createElement('div'), { className: 'toast' })); t.textContent = m; t.classList.add('show'); setTimeout(() => t.classList.remove('show'), 2200); };
    document.addEventListener('submit', async e => {
      const f = e.target.closest('form.js-add'); if (!f) return; e.preventDefault();
      const r = await fetch(f.action, { method: 'POST', headers: { Accept: 'application/json', 'X-CSRF-TOKEN': $('meta[name=csrf-token]').content }, body: new FormData(f) });
      if (!r.ok) return toast('⚠️'); const d = await r.json();
      const b = $('#cartCount'); b.textContent = d.count; b.hidden = false; window.gsap && gsap.fromTo(b, { scale: 1.8 }, { scale: 1, duration: .4 }); toast('✓ ' + d.msg);
    });
    // فلترة فورية: أي تغيير في الفلاتر يرسل الفورم تلقائيًا، والبحث بعد توقف الكتابة
    const ff = $('#filters'); if (ff) { ff.addEventListener('change', () => ff.submit()); let t; ff.querySelector('[name=q]')?.addEventListener('input', () => { clearTimeout(t); t = setTimeout(() => ff.submit(), 600); }); }
    // تنسيق حقول الكارت
    $('#card_number')?.addEventListener('input', e => e.target.value = e.target.value.replace(/\D/g, '').slice(0, 16).replace(/(.{4})/g, '$1 ').trim());
    $('#card_exp')?.addEventListener('input', e => { let v = e.target.value.replace(/\D/g, '').slice(0, 4); e.target.value = v.length > 2 ? v.slice(0, 2) + '/' + v.slice(2) : v; });
    document.querySelectorAll('input[name=payment_method]').forEach(r => r.addEventListener('change', () => $('#cardBox').hidden = r.value !== 'card' || !r.checked));
  });
})();
