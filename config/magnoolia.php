<?php

return [

    'project' => [
        'name'          => 'Magnoolia Kodud',
        'brand_name'    => 'Magnoolia',
        'slogan'        => 'Ridaelamu mugavus. Eramaja privaatsus.',
        'location'      => 'Vaela küla, Kiili vald, Harjumaa',
        'completion'    => 'Suvi 2027',
        'homes_count'   => 19,
        'energy_class'  => 'A',
        'developer'     => 'Estlanda OÜ',
        'contact_email' => 'diana@estlanda.ee',
        'contact_phone' => '+37258164078',
    ],

    'navigation' => [
        ['id' => 'about',      'label_key' => 'nav.about'],
        ['id' => 'homes',      'label_key' => 'nav.homes'],
        ['id' => 'masterplan', 'label_key' => 'nav.masterplan'],
        ['id' => 'location',   'label_key' => 'nav.location'],
        ['id' => 'contact',    'label_key' => 'nav.contact'],
    ],

    'facts' => [
        ['value' => '19',       'label' => 'kodu',           'icon' => 'home'],
        ['value' => 'A',        'label' => 'energiaklass',   'icon' => 'energy'],
        ['value' => '~129 m²',  'label' => 'elamispind',     'icon' => 'flooring'],
        ['value' => '2027',     'label' => 'valmib suvel',   'icon' => 'calendar'],
        ['value' => '20 min',   'label' => 'Tallinnast',     'icon' => 'location'],
    ],

    // statuses: available | reserved | sold | tbc
    'units' => [
        ['id'=>'M-01','address'=>'Magnoolia tee 1/1','rooms'=>4,'net_area'=>129.6,'terrace_area'=>22.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-02','address'=>'Magnoolia tee 1/2','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-03','address'=>'Magnoolia tee 1/3','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'reserved','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-04','address'=>'Magnoolia tee 1/4','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-05','address'=>'Magnoolia tee 1/5','rooms'=>5,'net_area'=>143.2,'terrace_area'=>24.0,'balcony_area'=>11.5,'storage_area'=>5.0,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-06','address'=>'Magnoolia tee 2/1','rooms'=>4,'net_area'=>129.6,'terrace_area'=>22.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-07','address'=>'Magnoolia tee 2/2','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'reserved','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-08','address'=>'Magnoolia tee 2/3','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-09','address'=>'Magnoolia tee 2/4','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-10','address'=>'Magnoolia tee 2/5','rooms'=>5,'net_area'=>143.2,'terrace_area'=>24.0,'balcony_area'=>11.5,'storage_area'=>5.0,'parking'=>2,'price'=>null,'status'=>'sold','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-11','address'=>'Magnoolia tee 3/1','rooms'=>4,'net_area'=>129.6,'terrace_area'=>22.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-12','address'=>'Magnoolia tee 3/2','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-13','address'=>'Magnoolia tee 3/3','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'reserved','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-14','address'=>'Magnoolia tee 3/4','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-15','address'=>'Magnoolia tee 3/5','rooms'=>5,'net_area'=>143.2,'terrace_area'=>24.0,'balcony_area'=>11.5,'storage_area'=>5.0,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-16','address'=>'Magnoolia tee 4/1','rooms'=>4,'net_area'=>129.6,'terrace_area'=>22.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-17','address'=>'Magnoolia tee 4/2','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'reserved','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-18','address'=>'Magnoolia tee 4/3','rooms'=>4,'net_area'=>129.6,'terrace_area'=>18.0,'balcony_area'=>9.5,'storage_area'=>4.1,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
        ['id'=>'M-19','address'=>'Magnoolia tee 4/4','rooms'=>5,'net_area'=>143.2,'terrace_area'=>24.0,'balcony_area'=>11.5,'storage_area'=>5.0,'parking'=>2,'price'=>null,'status'=>'available','floor_plan_1'=>null,'floor_plan_2'=>null],
    ],

    'statuses' => [
        'available' => 'Vaba',
        'reserved'  => 'Broneeritud',
        'sold'      => 'Müüdud',
        'tbc'       => 'Täpsustamisel',
    ],

    'media' => [
        'hero_video'  => null,
        'hero_poster' => null,
        'logo_light'  => null,
        'logo_dark'   => null,
        'renders'     => [],
        'masterplan'  => null,
    ],

];
