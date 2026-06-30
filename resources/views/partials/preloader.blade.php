<div class="custom-cursor__cursor"></div>
<div class="custom-cursor__cursor-two"></div>

<div class="preloader" style="background-color:#fbfaf7;">
    {{-- On-brand Magnoolia loader: cream background + the transparent Magnoolia logo. --}}
    <div class="preloader__image" style="background-image: url({{ asset('assets/magnoolia/logos/magnoolia-dark.webp') }});background-size:170px auto;"></div>
</div>
<script>
/* Hide the loader as soon as the page is usable. zoomvilla.js fades it on window
   "load" (after every image), which is slow; this dismisses it shortly after the
   DOM is ready so lazy images / the hero video can never keep the loader up. */
(function () {
    var hidden = false;
    function hide() {
        if (hidden) return; hidden = true;
        var p = document.querySelector('.preloader');
        if (!p) return;
        p.style.transition = 'opacity .4s ease';
        p.style.opacity = '0';
        setTimeout(function () { p.style.display = 'none'; }, 420);
    }
    if (document.readyState === 'complete') { hide(); return; }
    window.addEventListener('load', hide);
    document.addEventListener('DOMContentLoaded', function () { setTimeout(hide, 1000); });
})();
</script>
