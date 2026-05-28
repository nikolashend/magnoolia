@php
    $project = config('magnoolia.project', []);
  $canonicalRaw = config('magnoolia.seo.canonical_base');
  $canonicalBase = rtrim($canonicalRaw ?: config('app.url', url('/')), '/');
    $heroImage = asset(config('magnoolia.seo.og_image', 'assets/images/magnoolia/Cam001.0000.jpg'));
    $masterplanImage = asset('assets/images/magnoolia/magnoolia_cam09.jpg');
@endphp
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebSite",
      "@@id": "{{ $canonicalBase }}/#website",
      "url": "{{ $canonicalBase }}",
      "name": "{{ $project['name'] ?? 'Magnoolia Kodud' }}",
      "inLanguage": "et-EE",
      "publisher": {
        "@@id": "{{ $canonicalBase }}/#organization"
      }
    },
    {
      "@@type": "Organization",
      "@@id": "{{ $canonicalBase }}/#organization",
      "name": "{{ $project['developer'] ?? 'Estlanda OÜ' }}",
      "url": "{{ $canonicalBase }}",
      "email": "{{ $project['contact_email'] ?? 'diana@estlanda.ee' }}",
      "telephone": "{{ $project['contact_phone'] ?? '+37258164078' }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      },
      "contactPoint": {
        "@@type": "ContactPoint",
        "contactType": "sales",
        "email": "{{ $project['contact_email'] ?? 'diana@estlanda.ee' }}",
        "telephone": "{{ $project['contact_phone'] ?? '+37258164078' }}",
        "availableLanguage": ["et", "ru", "en"]
      }
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ $canonicalBase }}/#project",
      "name": "{{ $project['name'] ?? 'Magnoolia Kodud' }}",
      "url": "{{ $canonicalBase }}",
      "description": "A-energiaklassi ridaelamukodud Vaela külas, Kiili vallas. Arenduses on 19 kodu. I etapp valmib kevad 2027, II etapp kevad 2028.",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      },
      "image": [
        "{{ $heroImage }}",
        "{{ $masterplanImage }}"
      ],
      "amenityFeature": [
        { "@@type": "LocationFeatureSpecification", "name": "A-energiaklass", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Maasoojuspump", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Soojustagastusega ventilatsioon", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Põrandaküte", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "EV-laadimise valmidus", "value": true }
      ]
    },
    {
      "@@type": "Place",
      "@@id": "{{ $canonicalBase }}/#place",
      "name": "Vaela küla, Kiili vald",
      "address": {
        "@@type": "PostalAddress",
        "addressLocality": "Vaela küla",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    },
    {
      "@@type": "ImageObject",
      "@@id": "{{ $canonicalBase }}/#hero-image",
      "contentUrl": "{{ $heroImage }}",
      "caption": "Magnoolia A-energiaklassi ridaelamud"
    },
    {
      "@@type": "BreadcrumbList",
      "@@id": "{{ $canonicalBase }}/#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Avaleht",
          "item": "{{ $canonicalBase }}"
        }
      ]
    },
    {
      "@@type": "FAQPage",
      "@@id": "{{ $canonicalBase }}/#faq",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kus asub Magnoolia?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia asub Vaela külas, Kiili vallas, Harjumaal — ligikaudu 20 minuti kaugusel Tallinnast."
          }
        },
        {
          "@@type": "Question",
          "name": "Mitu kodu on arenduses?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Arenduses on 19 A-energiaklassi ridaelamukodu."
          }
        },
        {
          "@@type": "Question",
          "name": "Millal valmib I ja II etapp?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "I etapp (Magnoolia tee 1 ja 3) valmib kevad 2027. II etapp (Magnoolia tee 5 kuni 11) valmib kevad 2028."
          }
        },
        {
          "@@type": "Question",
          "name": "Kuidas küsida hinda ja saadavust?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Hinnad ja saadavus on täpsustamisel. Vali sobiv kodu ja saada päring kontaktivormi kaudu — Diana Tali täpsustab hinna, plaani ning järgmise broneerimissammu."
          }
        },
        {
          "@@type": "Question",
          "name": "Kas igal kodul on privaatne hooviala?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Jah, igal Magnoolia kodul on oma piiratud ja maastikuehitusega hooviala. See annab eramaja privaatsuse ridaelamu mugavuse ja uusarenduse kindlusega."
          }
        },
        {
          "@@type": "Question",
          "name": "Mis teeb Magnoolia ridaelamu eriliseks?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia ühendab A-energiaklassi lahendused (maasoojuspump, põrandaküte, soojustagastusega ventilatsioon), privaatse hooviala, rõdu ja terrassi ning rahulikku asukohta Tallinna lähedal ühes läbimõeldud koduprojektis."
          }
        },
        {
          "@@type": "Question",
          "name": "Kas kodudel on terrass ja rõdu?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Jah. Iga Magnoolia kodu juurde kuulub isiklik terrass (18–24 m²) esimesel korrusel ja rõdu (9,5–11,5 m²) teisel korrusel."
          }
        },
        {
          "@@type": "Question",
          "name": "Millised tehnosüsteemid on Magnoolia kodus?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia kodudes on maasoojuspump, veesoojendusega põrandaküte igas toas ja vannitoas, soojustagastusega ventilatsioon ning EV-laadimise ja päikesepaneelide valmidus."
          }
        },
        {
          "@@type": "Question",
          "name": "Kui suured on Magnoolia kodud?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia pakub kahte plaanitüüpi: 4-toaline Plaan A (~129,6 m²) ja 5-toaline Plaan B (~143,2 m²). Kõigi juurde kuulub laoruum ja 2 parkimiskohta."
          }
        }
      ]
    }
  ]
}
</script>
