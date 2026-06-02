{{-- MAGNOOLIA MOBILE STICKY CTA --}}
<div class="mg-sticky-cta" id="mg-sticky-cta" aria-label="{{ __('magnoolia.nav.mobile_menu') }}">
    <a href="{{ route('home') }}#hinnad" class="mg-sticky-cta__btn mg-sticky-cta__btn--ghost">
        {{ __('magnoolia.footer.nav_homes') }}
    </a>
    <a href="{{ route('home') }}#kontakt" class="mg-sticky-cta__btn">
        {{ __('magnoolia.footer.cta') }}
    </a>
</div>
<script>
(function () {
    var bar = document.getElementById('mg-sticky-cta');
    if (!bar) return;
    var hero    = document.querySelector('.main-slider-two, .slider-one, section:first-of-type');
    var contact = document.getElementById('kontakt');
    var footer  = document.getElementById('footer');
    function update() {
        var scrolled  = window.scrollY || window.pageYOffset;
        var threshold = hero ? hero.offsetHeight * 0.7 : 400;
        var nearBottom = false;
        if (contact) {
            var rect = contact.getBoundingClientRect();
            nearBottom = rect.top < window.innerHeight * 0.85;
        }
        if (!nearBottom && footer) {
            var fr = footer.getBoundingClientRect();
            nearBottom = fr.top < window.innerHeight;
        }
        bar.classList.toggle('is-visible', scrolled > threshold && !nearBottom);
    }
    window.addEventListener('scroll', update, { passive: true });
    update();
})();
</script>
