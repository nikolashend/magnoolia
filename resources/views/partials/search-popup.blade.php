<div class="search-popup">
    <div class="search-popup__overlay search-toggler"></div>
    <div class="search-popup__content">
        <form role="search" method="GET" action="{{ route('search') }}" class="search-popup__form">
            <input type="text" name="q" id="search" placeholder="{{ __('common.search_placeholder') }}">
            <button type="submit" aria-label="{{ __('common.search') }}" class="zoomvilla-btn">
                <span><i class="icon-search"></i></span>
            </button>
        </form>
    </div>
</div>
