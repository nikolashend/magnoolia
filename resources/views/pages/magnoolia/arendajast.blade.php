@extends('layouts.app')

@php
    $loc = app()->getLocale();
    $T = [
        'et' => [
            'title'    => 'Arendajast',
            'eyebrow'  => 'Estlanda kinnisvara ja ehitus',
            'lead'     => 'Arendame ja ehitame ise.',
            'body'     => [
                'Estlanda on 2009. aastast edukalt kinnisvaraarenduse- ja ehituse valdkonnas tegutsev ettevõte. Sõna „Estlanda" võib mõtestada eelkõige kui eestimaist, aga laiemas kontekstis tähendab nutikaid ja tänapäevaseid ehituslahendusi läbi terve hoone elukaare.',
                'Arendame ja ehitame oma kinnisvara ise ning see võimaldab arvestada kliendi soove kogu protsessi jooksul, alustades kavandamisest kuni viimse detailini läbi mõeldud ehituse lõpliku valmimiseni.',
            ],
            'quality'  => 'Kvaliteedis me järeleandmisi ei tee!',
            'contact'  => 'Objekti vaatamiseks võtke meiega ühendust. Meie eksperdid vastavad hea meelega kõigile teie küsimustele ja leiavad teile parima võimaliku lahenduse.',
            'cta'      => 'Võta ühendust',
            'projects_title' => 'Teised Estlanda projektid',
            'note'     => 'Pildid on illustratiivsed, võivad sisaldada ebatäpsusi ning erineda tegelikkusest.',
        ],
        'ru' => [
            'title'    => 'О застройщике',
            'eyebrow'  => 'Estlanda — недвижимость и строительство',
            'lead'     => 'Мы сами разрабатываем и строим.',
            'body'     => [
                'Estlanda — компания, успешно работающая в сфере девелопмента и строительства недвижимости с 2009 года. Слово «Estlanda» можно понимать прежде всего как «эстонское», но в более широком смысле оно означает умные и современные строительные решения на протяжении всего жизненного цикла здания.',
                'Мы сами разрабатываем и строим свою недвижимость, что позволяет учитывать пожелания клиента на всех этапах — от проектирования до продуманной до мельчайших деталей окончательной сдачи.',
            ],
            'quality'  => 'В качестве мы не идём на компромиссы!',
            'contact'  => 'Чтобы посмотреть объект, свяжитесь с нами. Наши специалисты с радостью ответят на все вопросы и найдут для вас наилучшее решение.',
            'cta'      => 'Связаться',
            'projects_title' => 'Другие проекты Estlanda',
            'note'     => 'Изображения иллюстративные, могут содержать неточности и отличаться от действительности.',
        ],
        'en' => [
            'title'    => 'About the developer',
            'eyebrow'  => 'Estlanda real estate & construction',
            'lead'     => 'We develop and build ourselves.',
            'body'     => [
                'Estlanda has been operating successfully in real-estate development and construction since 2009. The word “Estlanda” can be understood first of all as “Estonian”, but in a broader sense it stands for smart, modern building solutions across the whole life cycle of a building.',
                'We develop and build our properties ourselves, which lets us take the client’s wishes into account throughout the process — from design to a completion thought through to the last detail.',
            ],
            'quality'  => 'We make no compromises on quality!',
            'contact'  => 'To view an object, get in touch with us. Our experts are happy to answer all your questions and find the best possible solution for you.',
            'cta'      => 'Get in touch',
            'projects_title' => 'Other Estlanda projects',
            'note'     => 'Images are illustrative, may contain inaccuracies and differ from reality.',
        ],
    ];
    $t = $T[$loc] ?? $T['et'];

    // Sister projects — per the brief, the "Magnoolia" link is replaced with Raime.
    $projects = [
        ['name' => 'Keila Park Residence', 'url' => 'https://keilaresidence.estlanda.ee/'],
        ['name' => 'Nõmmeliiva kodud',     'url' => 'https://nommeliiva.ee/'],
        ['name' => 'Raime ridaelamud',     'url' => 'https://raime-ridaelamud.estlanda.ee/'],
    ];
@endphp

@section('title', $t['title'] . ' — Magnoolia')

@section('content')
<div class="mg-page-hero">
    <div class="container" style="max-width:900px;">
        <div class="mg-page-hero__eyebrow">{{ $t['eyebrow'] }}</div>
        <h1 class="mg-page-hero__title">{{ $t['title'] }}</h1>
        <p class="mg-page-hero__lead">{{ $t['lead'] }}</p>
    </div>
</div>

<section class="section-space" style="padding-top:14px;">
    <div class="container" style="max-width:900px;">
        @foreach($t['body'] as $para)
            <p style="font-size:16px;color:#4a4540;line-height:1.8;margin:0 0 18px;">{{ $para }}</p>
        @endforeach

        <div style="border-left:4px solid #c89443;background:#fbf8f3;border-radius:0 12px 12px 0;padding:20px 26px;margin:26px 0;">
            <p style="margin:0;font-size:20px;font-weight:800;color:#1d2430;">{{ $t['quality'] }}</p>
        </div>

        <p style="font-size:15px;color:#6f6a61;line-height:1.7;margin:0 0 24px;">{{ $t['contact'] }}</p>
        <a href="{{ lroute('magnoolia.contact') }}#kontaktivorm" class="zoomvilla-btn">{{ $t['cta'] }} <i class="icon-angle-small-right"></i></a>

        {{-- Other Estlanda projects --}}
        <div style="margin-top:48px;">
            <h2 style="font-size:22px;color:#1d2430;margin:0 0 18px;">{{ $t['projects_title'] }}</h2>
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(240px,1fr));gap:16px;">
                @foreach($projects as $pr)
                    <a href="{{ $pr['url'] }}" target="_blank" rel="noopener noreferrer"
                       style="display:flex;align-items:center;justify-content:space-between;gap:12px;border:1px solid #e7e9ee;border-radius:12px;padding:18px 20px;text-decoration:none;color:#1d2430;font-weight:700;font-size:15px;transition:border-color .15s;"
                       onmouseover="this.style.borderColor='#c89443'" onmouseout="this.style.borderColor='#e7e9ee'">
                        {{ $pr['name'] }}
                        <span style="color:#c89443;">↗</span>
                    </a>
                @endforeach
            </div>
        </div>

        <p style="font-size:12.5px;color:#9a948a;border-top:1px solid #eee;padding-top:16px;margin-top:36px;">{{ $t['note'] }}</p>
    </div>
</section>
@endsection
