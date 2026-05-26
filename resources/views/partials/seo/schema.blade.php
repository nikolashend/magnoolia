<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebSite",
      "@@id": "{{ url('/') }}/#website",
      "url": "{{ url('/') }}",
      "name": "Magnoolia Kodud",
      "description": "A-energiaklassi ridaelamud Tallinna lÃ¤hedal, Vaela kÃ¼las, Kiili vallas",
      "inLanguage": "et-EE",
      "publisher": {
        "@@id": "{{ url('/') }}/#organization"
      }
    },
    {
      "@@type": "Organization",
      "@@id": "{{ url('/') }}/#organization",
      "name": "Magnoolia Kodud",
      "url": "{{ url('/') }}",
      "logo": "{{ asset('assets/images/magnoolia/Cam001.0000.jpg') }}",
      "contactPoint": {
        "@@type": "ContactPoint",
        "email": "diana@estlanda.ee",
        "telephone": "+37258164078",
        "contactType": "sales",
        "areaServed": "EE",
        "availableLanguage": ["Estonian", "Russian", "English"]
      },
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela kÃ¼la",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ url('/') }}/#project",
      "name": "Magnoolia Kodud",
      "description": "Magnoolia on 19 uue A-energiaklassi koduga ridaelamuarendus Vaela kÃ¼las, Kiili vallas, ligikaudu 20 minuti kaugusel Tallinnast. Privaatsed hoovialad, rÃµdud, terrassid ja lÃ¤bimÃµeldud energialahendused.",
      "url": "{{ url('/') }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela kÃ¼la",
        "addressRegion": "Harjumaa",
        "postalCode": "75401",
        "addressCountry": "EE"
      },
      "geo": {
        "@@type": "GeoCoordinates",
        "latitude": "59.3560",
        "longitude": "24.8720"
      },
      "numberOfRooms": "4-5",
      "floorSize": {
        "@@type": "QuantitativeValue",
        "value": "129",
        "unitCode": "MTK"
      },
      "amenityFeature": [
        { "@@type": "LocationFeatureSpecification", "name": "A-energiaklass", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Privaatne hooviala", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Maasoojuspump", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Ventilatsioon", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "EV laadimise valmidus", "value": true },
        { "@@type": "LocationFeatureSpecification", "name": "Terrass ja rÃµdu", "value": true }
      ]
    },
    {
      "@@type": "BreadcrumbList",
      "@@id": "{{ url()->current() }}/#breadcrumb",
      "itemListElement": [
        {
          "@@type": "ListItem",
          "position": 1,
          "name": "Magnoolia Kodud",
          "item": "{{ url('/') }}"
        }
      ]
    },
    {
      "@@type": "FAQPage",
      "@@id": "{{ url('/') }}/#faq",
      "mainEntity": [
        {
          "@@type": "Question",
          "name": "Kus asub Magnoolia?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia asub Vaela kÃ¼las, Kiili vallas, Harjumaal, ligikaudu 20 minuti kaugusel Tallinnast."
          }
        },
        {
          "@@type": "Question",
          "name": "Millal Magnoolia kodud valmivad?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia kodude planeeritud valmimisaeg on suvi 2027."
          }
        },
        {
          "@@type": "Question",
          "name": "Mitu kodu Magnoolia arenduses on?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Arenduses on 19 uut A-energiaklassi ridaelamukodu."
          }
        },
        {
          "@@type": "Question",
          "name": "Millised on Magnoolia kodude suurused?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Kodud on ligikaudu 129 mÂ² suurused, 4â€“5-toalised, rÃµdu ja terrassiga."
          }
        },
        {
          "@@type": "Question",
          "name": "Kas igal kodul on privaatne hooviala?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Jah, igal kodul on oma privaatne hooviala, mis loob eramaja tunnetuse."
          }
        },
        {
          "@@type": "Question",
          "name": "Mis teeb Magnoolia eriliseks?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia Ã¼hendab ridaelamu mugavuse, eramaja privaatsuse, A-energiaklassi, oma hooviala ja uusarenduse kindluse Tallinna lÃ¤hedal."
          }
        }
      ]
    }
  ]
}
</script>

