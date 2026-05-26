<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@@graph": [
    {
      "@@type": "WebSite",
      "@@id": "{{ url('/') }}/#website",
      "url": "{{ url('/') }}",
      "name": "Magnoolia Kodud",
      "description": "A-energiaklassi ridaelamukodud Tallinna lähedal, Vaela külas, Kiili vallas",
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
        "addressLocality": "Vaela küla",
        "addressRegion": "Harjumaa",
        "addressCountry": "EE"
      }
    },
    {
      "@@type": "ApartmentComplex",
      "@@id": "{{ url('/') }}/#project",
      "name": "Magnoolia Kodud",
      "description": "Magnoolia on 19 uue A-energiaklassi koduga ridaelamuarendus Vaela külas, Kiili vallas, ligikaudu 20 minuti kaugusel Tallinnast. Privaatsed hoovialad, rõdud, terrassid ja läbimõeldud energialahendused.",
      "url": "{{ url('/') }}",
      "address": {
        "@@type": "PostalAddress",
        "streetAddress": "Magnoolia tee",
        "addressLocality": "Vaela küla",
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
        { "@@type": "LocationFeatureSpecification", "name": "Terrass ja rõdu", "value": true }
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
          "name": "Kus Magnoolia asub?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia asub Vaela külas, Kiili vallas, Harjumaal — ligikaudu 20 minutit Tallinnast."
          }
        },
        {
          "@@type": "Question",
          "name": "Millal Magnoolia kodud valmivad?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "I etapp (Magnoolia tee 1 ja 3) valmib kevad 2027. II etapp (Magnoolia tee 5 kuni 11) valmib kevad 2028."
          }
        },
        {
          "@@type": "Question",
          "name": "Kui palju kodusid on ja mis suurused need on?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Arenduses on 19 uut A-energiaklassi ridaelamukodu. 4-toaline kodu on ~129,6 m², 5-toaline ~143,2 m². Kõigi juurde kuulub terrass, rõdu ja 2 parkimiskohta."
          }
        },
        {
          "@@type": "Question",
          "name": "Mis on hind?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Hinnad on täpsustamisel. Võtke ühendust ning saadame teile hinnainfo esimesena."
          }
        },
        {
          "@@type": "Question",
          "name": "Kas igal kodul on privaatne hooviala?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Jah, igal Magnoolia kodul on oma piiratud ja maastikuehitusega hooviala. See loob eramaja tunnetuse ja privaatsuse."
          }
        },
        {
          "@@type": "Question",
          "name": "Mis teeb Magnoolia ridaelamu eriliseks?",
          "acceptedAnswer": {
            "@@type": "Answer",
            "text": "Magnoolia uhendab ridaelamu mugavuse, eramaja privaatsuse, A-energiaklassi maasoojuspumbaga, soojusvahestiga ventilatsiooni ja Tallinna laheduse uhes uues kodus."
          }
        }
      ]
    }
  ]
}
</script>
