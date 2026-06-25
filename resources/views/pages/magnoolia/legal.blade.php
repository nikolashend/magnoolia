@extends('layouts.app')

@php
    $loc = app()->getLocale();
    $T = [
        'et' => [
            'privacy_title' => 'Privaatsuspoliitika',
            'terms_title'   => 'Kasutustingimused',
            'updated'       => 'Viimati uuendatud',
            'draft'         => 'See on esialgne versioon. Lõpliku õigusteksti kinnitab arendaja Estlanda Ehitus OÜ.',
            'privacy' => [
                ['Vastutav töötleja', 'Magnoolia kodude arendaja Estlanda Ehitus OÜ. Küsimustes andmete kohta võta ühendust: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
                ['Milliseid andmeid kogume', 'Kontaktivormi kaudu: nimi, e-posti aadress, telefon ja sõnum. Lisaks tehnilised andmed (nt IP-aadress, brauseri tüüp) turvalisuse ja veebilehe toimimise tagamiseks.'],
                ['Miks andmeid kasutame', 'Et vastata sinu päringule, anda infot kodude, hindade ja saadavuse kohta ning sind soovi korral tagasi kutsuda. Andmeid ei müüda ega edastata kolmandatele isikutele turunduslikel eesmärkidel.'],
                ['Õiguslik alus', 'Sinu nõusolek (kontaktivormi saatmisel) ja meie õigustatud huvi päringutele vastata.'],
                ['Kui kaua andmeid säilitame', 'Andmeid säilitatakse nii kaua, kui see on vajalik päringu menetlemiseks ja seadusest tulenevate kohustuste täitmiseks.'],
                ['Sinu õigused', 'Sul on õigus tutvuda oma andmetega, neid parandada või nõuda nende kustutamist. Selleks kirjuta diana@estlanda.ee. Samuti on sul õigus pöörduda Andmekaitse Inspektsiooni poole.'],
                ['Küpsised', 'Veebileht kasutab ainult tööks vajalikke seansiküpsiseid. Turundus- ja jälgimisküpsiseid praegu ei kasutata. Kui need lisatakse, küsime selleks eraldi nõusoleku.'],
            ],
            'terms' => [
                ['Sisu eesmärk', 'Selle veebilehe sisu on informatiivne ja mõeldud Magnoolia ridaelamukodude tutvustamiseks. Pildid, renderdused ja korruseplaanid on illustratiivsed.'],
                ['Hinnad ja saadavus', 'Hinnad, plaanid, pindalad ja saadavus võivad muutuda. Siduvad andmed kinnitatakse broneerimis- ja müügilepingus.'],
                ['Materjalid', 'Arendajal on õigus kasutada veebilehel näidatud toodete ja materjalidega samaväärseid lahendusi. Lõplik viimistlus täpsustatakse lepingus.'],
                ['Kontakt', 'Täpse info saamiseks konkreetse kodu kohta võta ühendust: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
            ],
        ],
        'ru' => [
            'privacy_title' => 'Политика конфиденциальности',
            'terms_title'   => 'Условия использования',
            'updated'       => 'Последнее обновление',
            'draft'         => 'Это предварительная версия. Окончательный юридический текст утверждает застройщик Estlanda Ehitus OÜ.',
            'privacy' => [
                ['Ответственный за обработку данных', 'Застройщик домов Magnoolia — Estlanda Ehitus OÜ. По вопросам о данных: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
                ['Какие данные мы собираем', 'Через контактную форму: имя, e-mail, телефон и сообщение. Также технические данные (например, IP-адрес, тип браузера) для безопасности и работы сайта.'],
                ['Зачем мы используем данные', 'Чтобы ответить на ваш запрос, предоставить информацию о домах, ценах и наличии. Данные не продаются и не передаются третьим лицам в маркетинговых целях.'],
                ['Правовое основание', 'Ваше согласие (при отправке формы) и наш законный интерес отвечать на запросы.'],
                ['Срок хранения', 'Данные хранятся столько, сколько необходимо для обработки запроса и выполнения требований закона.'],
                ['Ваши права', 'Вы вправе ознакомиться со своими данными, исправить или потребовать их удаления — напишите на diana@estlanda.ee. Также вы можете обратиться в Инспекцию по защите данных.'],
                ['Cookie-файлы', 'Сайт использует только необходимые сессионные cookie. Маркетинговые cookie сейчас не используются; при их добавлении мы запросим отдельное согласие.'],
            ],
            'terms' => [
                ['Назначение содержимого', 'Содержимое сайта носит информационный характер и представляет дома Magnoolia. Изображения, рендеры и планы являются иллюстративными.'],
                ['Цены и наличие', 'Цены, планы, площади и наличие могут меняться. Обязывающие данные фиксируются в договоре бронирования и купли-продажи.'],
                ['Материалы', 'Застройщик вправе использовать решения, равноценные показанным на сайте. Окончательная отделка уточняется в договоре.'],
                ['Контакт', 'Для точной информации о конкретном доме: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
            ],
        ],
        'en' => [
            'privacy_title' => 'Privacy Policy',
            'terms_title'   => 'Terms of Use',
            'updated'       => 'Last updated',
            'draft'         => 'This is a preliminary version. The final legal text is confirmed by the developer Estlanda Ehitus OÜ.',
            'privacy' => [
                ['Data controller', 'The developer of Magnoolia homes, Estlanda Ehitus OÜ. For data questions contact: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
                ['What data we collect', 'Through the contact form: name, email, phone and message. Also technical data (e.g. IP address, browser type) for security and site operation.'],
                ['Why we use data', 'To answer your enquiry and provide information about homes, prices and availability. Data is not sold or shared with third parties for marketing.'],
                ['Legal basis', 'Your consent (when submitting the form) and our legitimate interest in answering enquiries.'],
                ['Retention', 'Data is kept for as long as necessary to handle the enquiry and to meet legal obligations.'],
                ['Your rights', 'You may access, correct or request deletion of your data — write to diana@estlanda.ee. You may also contact the Estonian Data Protection Inspectorate.'],
                ['Cookies', 'The site uses only essential session cookies. Marketing/tracking cookies are not used at present; if added, we will ask for separate consent.'],
            ],
            'terms' => [
                ['Purpose of content', 'The content of this website is informational and presents the Magnoolia row houses. Images, renders and floor plans are illustrative.'],
                ['Prices and availability', 'Prices, plans, areas and availability may change. Binding details are confirmed in the reservation and sales contract.'],
                ['Materials', 'The developer may use solutions equivalent to those shown on the website. Final finishing is specified in the contract.'],
                ['Contact', 'For exact information about a specific home: Diana Tali, diana@estlanda.ee, +372 58 16 40 78.'],
            ],
        ],
    ];
    $t = $T[$loc] ?? $T['et'];
    $isPrivacy = ($doc ?? 'privacy') === 'privacy';
    $title = $isPrivacy ? $t['privacy_title'] : $t['terms_title'];
    $items = $isPrivacy ? $t['privacy'] : $t['terms'];
@endphp

@section('title', $title . ' — Magnoolia')

@section('content')
<section class="mg-page-hero" style="padding:80px 0 30px;">
    <div class="container" style="max-width:860px;">
        <h1 class="mg-page-hero__title">{{ $title }}</h1>
        <p style="color:#9a948a;font-size:13px;margin-top:8px;">{{ $t['updated'] }}: 2026</p>
    </div>
</section>

<section class="section-space" style="padding-top:10px;">
    <div class="container" style="max-width:860px;">
        @foreach($items as [$h, $body])
            <div style="margin-bottom:26px;">
                <h2 style="font-size:19px;color:#1d2430;margin:0 0 8px;">{{ $h }}</h2>
                <p style="font-size:15px;color:#5b5446;line-height:1.7;margin:0;">{{ $body }}</p>
            </div>
        @endforeach

        <p style="font-size:12.5px;color:#9a948a;border-top:1px solid #eee;padding-top:16px;margin-top:8px;">{{ $t['draft'] }}</p>
    </div>
</section>
@endsection
