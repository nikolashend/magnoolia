{{-- ══════════════════════════════════════════════════════════════
    BREADCRUMB PARTIAL — Phase 14
    Usage: @include('partials.seo.breadcrumb', ['items' => [
        ['label' => 'Avaleht', 'url' => route('home')],
        ['label' => 'Kodud ja hinnad'],
    ]])
    Last item has no URL (current page).
    Schema: BreadcrumbList injected inline.
    ══════════════════════════════════════════════════════════════ --}}
@php
    $items = $items ?? [];
@endphp

{{-- Visual breadcrumb --}}
<nav aria-label="Leivapururada" style="padding:12px 0;margin-bottom:4px;">
    <ol style="list-style:none;padding:0;margin:0;display:flex;flex-wrap:wrap;align-items:center;gap:4px;font-size:13px;color:#9a9490;">
        @foreach($items as $i => $item)
            @if(!$loop->last)
                <li>
                    <a href="{{ $item['url'] }}"
                       style="color:#c89443;text-decoration:none;font-weight:500;transition:opacity .2s;"
                       onmouseover="this.style.opacity='.7'" onmouseout="this.style.opacity='1'">
                        {{ $item['label'] }}
                    </a>
                </li>
                <li aria-hidden="true" style="color:rgba(29,36,48,.25);font-size:11px;">›</li>
            @else
                <li aria-current="page" style="color:#6f6a61;">{{ $item['label'] }}</li>
            @endif
        @endforeach
    </ol>
</nav>

{{-- BreadcrumbList JSON-LD --}}
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@type": "BreadcrumbList",
  "itemListElement": [
    @foreach($items as $i => $item)
    {
      "@@type": "ListItem",
      "position": {{ $i + 1 }},
      "name": "{{ $item['label'] }}"@if(isset($item['url'])),
      "item": "{{ $item['url'] }}"@endif
    }{{ !$loop->last ? ',' : '' }}
    @endforeach
  ]
}
</script>
