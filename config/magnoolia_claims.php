<?php

/**
 * Magnoolia Claim-to-Proof Matrix
 *
 * Purpose: Track every public claim, its verification status, risk level,
 * and approved safe wording. Used to audit copy across blades/lang files
 * and ensure no unverifiable absolute claims appear publicly.
 *
 * Status values:
 *   client_provided   — told to us by client, not independently verified
 *   design_intent     — based on architectural drawings / design brief
 *   confirmed_config  — fixed in config/data, cross-checked internally
 *   pending_client    — awaiting client confirmation before publishing
 *   illustrative      — explicitly marked as illustrative on the site
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Project basics
    |--------------------------------------------------------------------------
    */

    'project_name' => [
        'claim_et'            => 'Magnoolia Kodud',
        'claim_en'            => 'Magnoolia Kodud',
        'status'              => 'confirmed_config',
        'proof_type'          => 'brand_name',
        'risk'                => 'none',
        'safe_public_wording' => 'Magnoolia Kodud',
    ],

    'unit_count' => [
        'claim_et'            => '19 kodu',
        'claim_en'            => '19 homes',
        'status'              => 'confirmed_config',
        'proof_type'          => 'unit_array_count',
        'risk'                => 'Low — unit array in config/magnoolia.php has exactly 19 entries. Must be re-verified if units change.',
        'safe_public_wording' => '19 ridaelamukodu / 19 homes',
    ],

    'address' => [
        'claim_et'            => 'Magnoolia tee, Vaela küla, Kiili vald, Harjumaa',
        'claim_en'            => 'Magnoolia tee, Vaela village, Kiili municipality, Harju County',
        'status'              => 'confirmed_config',
        'proof_type'          => 'land_registry_address',
        'risk'                => 'Low — street address visible in public land registry.',
        'safe_public_wording' => 'Magnoolia tee, Vaela küla, Kiili vald',
    ],

    'energy_class' => [
        'claim_et'            => 'A-energiaklassi ridaelamukodud',
        'claim_en'            => 'Energy class A townhouses',
        'status'              => 'client_provided',
        'proof_type'          => 'technical_description',
        'risk'                => 'Medium — official energy certificate is not uploaded. Claim is based on design intent and client description.',
        'safe_public_wording_et' => 'Projekt on kavandatud A-energiaklassi nõuete kohaselt. Täpne energiamärgis kinnitatakse ehituse lõpus.',
        'safe_public_wording_en' => 'The development is designed to energy class A requirements. The official energy certificate will be confirmed on completion.',
    ],

    'phase_1_completion' => [
        'claim_et'            => 'I etapp: Magnoolia tee 1 ja 3 — kevad 2027',
        'claim_en'            => 'Phase I: Magnoolia tee 1 and 3 — spring 2027',
        'status'              => 'client_provided',
        'proof_type'          => 'construction_schedule',
        'risk'                => 'Medium — depends on building permits and construction progress. Exact date not guaranteed.',
        'safe_public_wording_et' => 'I etapp (Magnoolia tee 1 ja 3, 7 kodu) on kavandatud valmima 2027. aasta kevadel. Täpne kuupäev sõltub ehitusloa saamisest ja tööde kulust.',
        'safe_public_wording_en' => 'Phase I (Magnoolia tee 1 and 3, 7 homes) is planned for completion in spring 2027. The exact date depends on building permits and construction schedule.',
    ],

    'phase_2_completion' => [
        'claim_et'            => 'II etapp: Magnoolia tee 5–11 — kevad 2028',
        'claim_en'            => 'Phase II: Magnoolia tee 5–11 — spring 2028',
        'status'              => 'client_provided',
        'proof_type'          => 'construction_schedule',
        'risk'                => 'Medium — same caveats as phase 1.',
        'safe_public_wording_et' => 'II etapp (Magnoolia tee 5, 7, 9 ja 11, 12 kodu) on kavandatud valmima 2028. aasta kevadel.',
        'safe_public_wording_en' => 'Phase II (Magnoolia tee 5–11, 12 homes) is planned for completion in spring 2028.',
    ],

    'private_yard' => [
        'claim_et'            => 'Igal kodul privaatne hooviala',
        'claim_en'            => 'Private yard for every home',
        'status'              => 'design_intent',
        'proof_type'          => 'site_plan_design',
        'risk'                => 'Low — confirmed in site plan. Exact m² per unit is pending final survey.',
        'safe_public_wording_et' => 'Igal Magnoolia kodul on privaatne hooviala. Täpne pindala fikseeritakse müügilepingus.',
        'safe_public_wording_en' => 'Every Magnoolia home has its own private yard. The exact area is fixed in the sales contract.',
    ],

    'townhouse_format' => [
        'claim_et'            => 'Ridaelamu mugavus + eramaja privaatsus',
        'claim_en'            => 'Townhouse comfort + private house privacy',
        'status'              => 'design_intent',
        'proof_type'          => 'marketing_positioning',
        'risk'                => 'Low — supported by separated units, private yards and carports between homes.',
        'safe_public_wording' => 'Ridaelamu mugavus kohtab eramaja privaatsust.',
    ],

    'carport_separation' => [
        'claim_et'            => 'Autovarjualused eraldavad kodusid',
        'claim_en'            => 'Homes separated by carports',
        'status'              => 'design_intent',
        'proof_type'          => 'architectural_drawings',
        'risk'                => 'Medium — do not claim "no shared walls" unless verified per unit pair.',
        'safe_public_wording_et' => 'Autovarjualused asuvad kodude vahel, tagades täiendava privaatsuse.',
    ],

    'ground_source_heat_pump' => [
        'claim_et'            => 'Maasoojuspump',
        'claim_en'            => 'Ground source heat pump',
        'status'              => 'design_intent',
        'proof_type'          => 'technical_specification',
        'risk'                => 'Medium — technical spec not yet officially confirmed in signed contract.',
        'safe_public_wording_et' => 'Kavandatud on maasoojuspump. Täpne tehniline lahendus fikseeritakse projektis.',
        'safe_public_wording_en' => 'A ground source heat pump is planned. Exact technical specification is confirmed in the project documentation.',
    ],

    'heat_recovery_ventilation' => [
        'claim_et'            => 'Soojustagastusega sundventilatsioon',
        'claim_en'            => 'Heat-recovery mechanical ventilation',
        'status'              => 'design_intent',
        'proof_type'          => 'technical_specification',
        'risk'                => 'Low — standard in A-class Estonian new builds.',
        'safe_public_wording_et' => 'Kavandatud on soojustagastusega sundventilatsioon.',
    ],

    'solar_panel_readiness' => [
        'claim_et'            => 'Päikesepaneelide valmidus',
        'claim_en'            => 'Solar panel readiness',
        'status'              => 'design_intent',
        'proof_type'          => 'technical_specification',
        'risk'                => 'Low — described as "readiness/preparation", not installed panels.',
        'safe_public_wording_et' => 'Katusele on ette valmistatud päikesepaneelide hilisemaks paigalduseks.',
    ],

    'ev_charging_readiness' => [
        'claim_et'            => 'EV laadimise valmidus',
        'claim_en'            => 'EV charging readiness',
        'status'              => 'design_intent',
        'proof_type'          => 'technical_specification',
        'risk'                => 'Low — described as readiness at parking spaces, not installed charger.',
        'safe_public_wording_et' => 'Mõlema parkimiskoha juurde on EV-laadimispunkti ettevalmistus.',
    ],

    'balcony_and_terrace' => [
        'claim_et'            => 'Rõdu + terrass',
        'claim_en'            => 'Balcony + terrace',
        'status'              => 'design_intent',
        'proof_type'          => 'architectural_drawings',
        'risk'                => 'Low — shown in floor plans. Exact m² depend on unit type.',
        'safe_public_wording_et' => 'Iga kodu juurde kuulub terrass ja rõdu vastavalt plaani tüübile.',
    ],

    'two_parking_spaces' => [
        'claim_et'            => '2 parkimiskohta',
        'claim_en'            => '2 parking spaces',
        'status'              => 'design_intent',
        'proof_type'          => 'site_plan',
        'risk'                => 'Low — 2 spaces per unit visible in site plan.',
        'safe_public_wording_et' => '2 parkimiskohta iga kodu juures.',
    ],

    'travel_time_tallinn' => [
        'claim_et'            => '~20 minutit Tallinnast',
        'claim_en'            => '~20 minutes from Tallinn',
        'status'              => 'confirmed_config',
        'proof_type'          => 'mapping_distance',
        'risk'                => 'Low — use "~20" or "approximately 20" with tilde/approximately qualifier. Actual time varies with traffic.',
        'safe_public_wording_et' => 'Ligikaudu 20–30 minutit Tallinna kesklinnast sõltuvalt liiklusest.',
        'safe_public_wording_en' => 'Approximately 20–30 minutes from Tallinn city centre depending on traffic.',
    ],

    'low_running_costs' => [
        'claim_et'            => 'Madalad kõrvalkulud',
        'claim_en'            => 'Low running costs',
        'status'              => 'pending_client',
        'proof_type'          => 'energy_calculation',
        'risk'                => 'High — do not use absolute claim. Only reference A-energy-class systems as the basis.',
        'safe_public_wording_et' => 'A-energiaklassi lahendused (maasoojuspump, ventilatsioon, põrandaküte) on suunatud energiakulu vähendamisele.',
        'safe_public_wording_en' => 'A-class energy systems (heat pump, ventilation, underfloor heating) are designed to minimise energy costs.',
    ],

    'price' => [
        'claim_et'            => 'Hind',
        'claim_en'            => 'Price',
        'status'              => 'pending_client',
        'proof_type'          => 'price_list',
        'risk'                => 'High — do not publish specific prices until client-confirmed price list received. Show "Küsi hinnainfot" / price TBC.',
        'safe_public_wording_et' => 'Hinnad täpsustuvad. Küsi Diana käest.',
    ],

    'floor_plans' => [
        'claim_et'            => 'Korrusplaanid',
        'claim_en'            => 'Floor plans',
        'status'              => 'illustrative',
        'proof_type'          => 'architectural_drawings',
        'risk'                => 'Low — always accompanied by disclaimer that plans are illustrative.',
        'safe_public_wording_et' => 'Korrusplaanid on illustratiivsed. Täpne plaan kinnitatakse konkreetse aadressi põhjal.',
    ],

    'site_plan' => [
        'claim_et'            => 'Asendiplaan',
        'claim_en'            => 'Site plan',
        'status'              => 'illustrative',
        'proof_type'          => 'design_render',
        'risk'                => 'Low — shown as illustrative. Interactive hotspot map pending EXR/SVG confirmation.',
        'safe_public_wording_et' => 'Asendiplaan on illustratiivne. Interaktiivne kaart lisatakse pärast EXR/SVG-kaardistuse kinnitamist.',
    ],

    'reservation_process' => [
        'claim_et'            => 'Broneerimisprotsess',
        'claim_en'            => 'Reservation process',
        'status'              => 'client_provided',
        'proof_type'          => 'sales_process_description',
        'risk'                => 'Low — process described in general terms without specific fee amounts.',
        'safe_public_wording_et' => 'Broneerimine algab Diana Taliga ühendust võttes. Tingimused kinnitatakse broneerimislepingus.',
    ],

];
