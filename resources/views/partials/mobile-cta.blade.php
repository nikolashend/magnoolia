{{-- MAGNOOLIA MOBILE STICKY CTA --}}
<div class="mg-sticky-cta" id="mg-sticky-cta" aria-label="Kiire kontaktmenüü">
    <a href="{{ route('home') }}#hinnad" class="mg-sticky-cta__btn mg-sticky-cta__btn--ghost">
        Kodud &amp; hinnad
    </a>
    <a href="{{ route('home') }}#kontakt" class="mg-sticky-cta__btn">
        Küsi pakkumist
    </a>
</div>
<script>
(function () {
    var bar = document.getElementById('mg-sticky-cta');
    if (!bar) return;
    var hero = document.querySelector('.mg-hero, #mg-hero, .slider-one, section:first-of-type');
    function update() {
        var scrolled = window.scrollY || window.pageYOffset;
        var threshold = hero ? hero.offsetHeight * 0.7 : 400;
        bar.classList.toggle('is-visible', scrolled > threshold);
    }
    window.addEventListener('scroll', update, { passive: true });
    update();
})();
</script>
