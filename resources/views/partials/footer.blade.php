<footer class="main-footer mt-20">
    <div class="main-footer__top">
        <div class="container">
            <div class="row gutter-y-50">
                <div class="col-md-6 col-lg-5 col-xl-4 wow fadeInUp" data-wow-delay="00ms">
                    <div class="footer-widget footer-widget--about">
                        <a href="{{ route('home') }}" class="footer-widget__logo">
                            <img src="{{ asset('assets/images/logo-light.png') }}" width="337" alt="{{ config('app.name') }}">
                        </a>
                        <p class="footer-widget__text">{{ __('footer.description') }}</p>
                        <div class="footer-widget__social-links">
                            <a href="https://facebook.com"><i class="fab fa-facebook-f" aria-hidden="true"></i><span class="sr-only">Facebook</span></a>
                            <a href="https://twitter.com"><i class="fab fa-twitter" aria-hidden="true"></i><span class="sr-only">Twitter</span></a>
                            <a href="https://google.com"><i class="fab fa-google"></i><span class="sr-only">Google</span></a>
                            <a href="https://linkedin.com"><i class="fab fa-linkedin-in" aria-hidden="true"></i><span class="sr-only">LinkedIn</span></a>
                            <a href="https://instagram.com"><i class="fab fa-instagram" aria-hidden="true"></i><span class="sr-only">Instagram</span></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="100ms">
                    <div class="footer-widget footer-widget--links">
                        <h2 class="footer-widget__title">{{ __('footer.our_services') }}</h2>
                        <ul class="list-unstyled footer-widget__links">
                            <li><i class="fas fa-circle"></i><a href="{{ route('services.index') }}">{{ __('menu.services') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('team') }}">{{ __('menu.team') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('contact') }}">{{ __('menu.contact') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('about') }}">{{ __('menu.about') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('faq') }}">{{ __('menu.faq') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('gallery.index') }}">{{ __('menu.gallery') }}</a></li>
                            <li><i class="fas fa-circle"></i><a href="{{ route('blog.index') }}">{{ __('menu.blog') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-3 col-xl-2 wow fadeInUp" data-wow-delay="100ms">
                    <div class="footer-widget footer-widget--links-two">
                        <h2 class="footer-widget__title">{{ __('footer.quick_links') }}</h2>
                        <ul class="list-unstyled footer-widget__links-two">
                            <li><a href="{{ route('faq') }}">{{ __('footer.what_we_do') }}</a></li>
                            <li><a href="{{ route('about') }}">{{ __('footer.about_company') }}</a></li>
                            <li><a href="{{ route('team') }}">{{ __('footer.team_member') }}</a></li>
                            <li><a href="{{ route('gallery.index') }}">{{ __('footer.our_gallery') }}</a></li>
                            <li><a href="{{ route('blog.index') }}">{{ __('footer.latest_news') }}</a></li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl-3 wow fadeInUp" data-wow-delay="200ms">
                    <div class="footer-widget footer-widget--post">
                        <h2 class="footer-widget__title">{{ __('footer.recent_posts') }}</h2>
                        <ul class="list-unstyled">
                            @if(isset($recentPosts) && $recentPosts->count())
                                @foreach($recentPosts as $post)
                                <li class="footer-widget--post__item">
                                    <div class="footer-widget--post__img">
                                        <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}">
                                    </div>
                                    <div class="footer-widget--post__content">
                                        <span class="footer-widget--post__date">
                                            <i class="icon-celemder"></i> {{ $post->created_at->format('d M Y') }}
                                        </span>
                                        <h3 class="footer-widget--post__title">
                                            <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                                        </h3>
                                    </div>
                                </li>
                                @endforeach
                            @else
                                <li class="footer-widget--post__item">
                                    <div class="footer-widget--post__img">
                                        <img src="{{ asset('assets/images/resources/footer-post-1.jpg') }}" alt="post">
                                    </div>
                                    <div class="footer-widget--post__content">
                                        <span class="footer-widget--post__date"><i class="icon-celemder"></i> 20 Nov 2023</span>
                                        <h3 class="footer-widget--post__title">
                                            <a href="{{ route('blog.index') }}">{{ __('footer.recent_post_placeholder') }}</a>
                                        </h3>
                                    </div>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-footer__bottom wow fadeInUp" data-wow-delay="00ms">
        <div class="container">
            <div class="main-footer__bottom__inner">
                <p class="main-footer__copyright">
                    &copy; {{ __('footer.copyright', ['year' => date('Y'), 'name' => config('app.name')]) }}
                </p>
                <ul class="main-footer__bottom__list list-unstyled">
                    <li><a href="{{ route('faq') }}">{{ __('footer.privacy') }}</a></li>
                    <li><a href="{{ route('faq') }}">{{ __('footer.policy') }}</a></li>
                    <li><a href="{{ route('contact') }}">{{ __('common.contact_us') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="main-footer__shape-one">
        <img src="{{ asset('assets/images/resources/footer-1-2.png') }}" alt="image">
    </div>
    <div class="main-footer__shape-two">
        <img src="{{ asset('assets/images/resources/footer-1-1.png') }}" alt="image">
    </div>
</footer>
