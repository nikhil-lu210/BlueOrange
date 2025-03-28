<?php

return [
    'accepted' => ':attribute को स्वीकार करना अनिवार्य है।',
    'accepted_if' => ':other :value होने पर :attribute को स्वीकार करना अनिवार्य है।',
    'active_url' => ':attribute एक मान्य URL होना चाहिए।',
    'after' => ':attribute में :date के बाद की तिथि होनी चाहिए।',
    'after_or_equal' => ':attribute में :date के बराबर या उसके बाद की तिथि होनी चाहिए।',
    'alpha' => ':attribute में केवल अक्षर होने चाहिए।',
    'alpha_dash' => ':attribute में केवल अक्षर, संख्याएँ, डैश और अंडरस्कोर हो सकते हैं।',
    'alpha_num' => ':attribute में केवल अक्षर और संख्याएँ हो सकती हैं।',
    'array' => ':attribute एक array होना चाहिए।',
    'ascii' => ':attribute में केवल single-byte अक्षरांकीय अक्षर और प्रतीक हो सकते हैं।',
    'before' => ':attribute में :date से पहले की तिथि होनी चाहिए।',
    'before_or_equal' => ':attribute में :date के बराबर या उससे पहले की तिथि होनी चाहिए।',
    'between' => [
        'array' => ':attribute में :min और :max आइटम्स के बीच होना चाहिए।',
        'file' => ':attribute का आकार :min और :max किलोबाइट्स के बीच होना चाहिए।',
        'numeric' => ':attribute :min और :max के बीच होना चाहिए।',
        'string' => ':attribute :min और :max अक्षरों के बीच होना चाहिए।',
    ],
    'boolean' => ':attribute फ़ील्ड true या false होना चाहिए।',
    'can' => ':attribute में unauthorized मान है।',
    'confirmed' => ':attribute की पुष्टि मेल नहीं खाती।',
    'current_password' => 'पासवर्ड गलत है।',
    'date' => ':attribute मान्य तिथि होनी चाहिए।',
    'date_equals' => ':attribute :date के बराबर की तिथि होनी चाहिए।',
    'date_format' => ':attribute :format प्रारूप के अनुरूप होना चाहिए।',
    'decimal' => ':attribute में :decimal दशमलव स्थान होने चाहिए।',
    'declined' => ':attribute अस्वीकार होना चाहिए।',
    'declined_if' => ':other :value होने पर :attribute अस्वीकार होना चाहिए।',
    'different' => ':attribute और :other अलग-अलग होने चाहिए।',
    'digits' => ':attribute में :digits अंक होने चाहिए।',
    'digits_between' => ':attribute :min और :max अंकों के बीच होना चाहिए।',
    'dimensions' => ':attribute की छवि के आयाम अमान्य हैं।',
    'distinct' => ':attribute में एक डुप्लिकेट मान है।',
    'doesnt_end_with' => ':attribute निम्न में से किसी एक से समाप्त नहीं होना चाहिए: :values।',
    'doesnt_start_with' => ':attribute निम्न में से किसी एक से प्रारंभ नहीं होना चाहिए: :values।',
    'email' => ':attribute एक मान्य ईमेल पता होना चाहिए।',
    'ends_with' => ':attribute निम्न में से किसी एक से समाप्त होना चाहिए: :values।',
    'enum' => 'चयनित :attribute अमान्य है।',
    'exists' => 'चयनित :attribute अमान्य है।',
    'extensions' => ':attribute में निम्नलिखित एक्सटेंशन में से कोई एक होना चाहिए: :values।',
    'file' => ':attribute एक फाइल होनी चाहिए।',
    'filled' => ':attribute में मान होना चाहिए।',
    'gt' => [
        'array' => ':attribute में :value से अधिक आइटम्स होने चाहिए।',
        'file' => ':attribute का आकार :value किलोबाइट्स से बड़ा होना चाहिए।',
        'numeric' => ':attribute :value से अधिक होना चाहिए।',
        'string' => ':attribute :value अक्षरों से अधिक होना चाहिए।',
    ],
    'gte' => [
        'array' => ':attribute में :value आइटम्स या उससे अधिक होने चाहिए।',
        'file' => ':attribute का आकार :value किलोबाइट्स या उससे अधिक होना चाहिए।',
        'numeric' => ':attribute :value या उससे अधिक होना चाहिए।',
        'string' => ':attribute :value अक्षरों या उससे अधिक होना चाहिए।',
    ],
    'hex_color' => ':attribute एक मान्य हेक्साडेसिमल रंग होना चाहिए।',
    'image' => ':attribute एक छवि होनी चाहिए।',
    'in' => 'चयनित :attribute अमान्य है।',
    'in_array' => ':attribute :other में मौजूद होना चाहिए।',
    'integer' => ':attribute एक पूर्णांक होना चाहिए।',
    'ip' => ':attribute एक मान्य IP पता होना चाहिए।',
    'ipv4' => ':attribute एक मान्य IPv4 पता होना चाहिए।',
    'ipv6' => ':attribute एक मान्य IPv6 पता होना चाहिए।',
    'json' => ':attribute एक मान्य JSON स्ट्रिंग होनी चाहिए।',
    'lowercase' => ':attribute को लोअरकेस में होना चाहिए।',
    'lt' => [
        'array' => ':attribute में :value से कम आइटम्स होने चाहिए।',
        'file' => ':attribute का आकार :value किलोबाइट्स से कम होना चाहिए।',
        'numeric' => ':attribute :value से कम होना चाहिए।',
        'string' => ':attribute :value अक्षरों से कम होना चाहिए।',
    ],
    'lte' => [
        'array' => ':attribute में :value से अधिक आइटम्स नहीं होने चाहिए।',
        'file' => ':attribute का आकार :value किलोबाइट्स से अधिक नहीं होना चाहिए।',
        'numeric' => ':attribute :value से अधिक नहीं होना चाहिए।',
        'string' => ':attribute :value अक्षरों से अधिक नहीं होना चाहिए।',
    ],
    'mac_address' => ':attribute एक मान्य MAC पता होना चाहिए।',
    'max' => [
        'array' => ':attribute में :max से अधिक आइटम्स नहीं हो सकते।',
        'file' => ':attribute का आकार :max किलोबाइट्स से अधिक नहीं हो सकता।',
        'numeric' => ':attribute :max से अधिक नहीं हो सकता।',
        'string' => ':attribute :max अक्षरों से अधिक नहीं हो सकता।',
    ],
    'max_digits' => ':attribute में :max से अधिक अंक नहीं होने चाहिए।',
    'mimes' => ':attribute में निम्नलिखित प्रकार की फाइल होनी चाहिए: :values।',
    'mimetypes' => ':attribute में निम्नलिखित प्रकार की फाइल होनी चाहिए: :values।',
    'min' => [
        'array' => ':attribute में कम से कम :min आइटम्स होने चाहिए।',
        'file' => ':attribute का आकार कम से कम :min किलोबाइट्स होना चाहिए।',
        'numeric' => ':attribute कम से कम :min होना चाहिए।',
        'string' => ':attribute कम से कम :min अक्षरों का होना चाहिए।',
    ],
    'min_digits' => ':attribute में कम से कम :min अंक होने चाहिए।',
    'missing' => ':attribute अनुपस्थित होना चाहिए।',
    'missing_if' => ':other :value होने पर :attribute अनुपस्थित होना चाहिए।',
    'missing_unless' => ':other :value होने पर :attribute अनुपस्थित होना चाहिए।',
    'missing_with' => ':values उपस्थित होने पर :attribute अनुपस्थित होना चाहिए।',
    'missing_with_all' => ':values उपस्थित होने पर :attribute अनुपस्थित होना चाहिए।',
    'multiple_of' => ':attribute :value का गुणक होना चाहिए।',
    'not_in' => 'चयनित :attribute अमान्य है।',
    'not_regex' => ':attribute का प्रारूप अमान्य है।',
    'numeric' => ':attribute एक संख्या होनी चाहिए।',
    'password' => [
        'letters' => ':attribute में कम से कम एक अक्षर होना चाहिए।',
        'mixed' => ':attribute में कम से कम एक अपरकेस और एक लोअरकेस अक्षर होना चाहिए।',
        'numbers' => ':attribute में कम से कम एक संख्या होनी चाहिए।',
        'symbols' => ':attribute में कम से कम एक प्रतीक होना चाहिए।',
        'uncompromised' => 'दिया गया :attribute डेटा लीक में पाया गया है। कृपया एक अलग :attribute चुनें।',
    ],
    'present' => ':attribute फ़ील्ड उपस्थित होना चाहिए।',
    'present_if' => ':other :value होने पर :attribute फ़ील्ड उपस्थित होना चाहिए।',
    'present_unless' => ':other :value होने पर :attribute फ़ील्ड उपस्थित होना चाहिए।',
    'present_with' => ':values उपस्थित होने पर :attribute फ़ील्ड उपस्थित होना चाहिए।',
    'present_with_all' => ':values उपस्थित होने पर :attribute फ़ील्ड उपस्थित होना चाहिए।',
    'prohibited' => ':attribute फ़ील्ड निषिद्ध है।',
    'prohibited_if' => ':other :value होने पर :attribute फ़ील्ड निषिद्ध है।',
    'prohibited_unless' => ':attribute फ़ील्ड निषिद्ध है जब तक कि :other :values में न हो।',
    'prohibits' => ':attribute :other को उपस्थित होने से रोकता है।',
    'regex' => ':attribute प्रारूप अमान्य है।',
    'required' => ':attribute फ़ील्ड आवश्यक है।',
    'required_if' => ':other :value होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_unless' => ':other :values में न हो तब :attribute फ़ील्ड आवश्यक है।',
    'required_with' => ':values उपस्थित होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_with_all' => ':values उपस्थित होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_without' => ':values अनुपस्थित होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_without_all' => ':values में से कोई भी उपस्थित न होने पर :attribute फ़ील्ड आवश्यक है।',
    'required_array_keys' => ':attribute फ़ील्ड में :values के लिए प्रविष्टियाँ होनी चाहिए।',
    'same' => ':attribute और :other मेल खाना चाहिए।',
    'size' => [
        'array' => ':attribute में :size आइटम्स होने चाहिए।',
        'file' => ':attribute का आकार :size किलोबाइट्स होना चाहिए।',
        'numeric' => ':attribute :size होना चाहिए।',
        'string' => ':attribute :size अक्षरों का होना चाहिए।',
    ],
    'starts_with' => ':attribute निम्न में से किसी एक से शुरू होना चाहिए: :values।',
    'string' => ':attribute एक स्ट्रिंग होनी चाहिए।',
    'timezone' => ':attribute एक मान्य क्षेत्र होना चाहिए।',
    'unique' => ':attribute पहले से ही लिया जा चुका है।',
    'uploaded' => ':attribute अपलोड करने में विफल रहा।',
    'uppercase' => ':attribute अपरकेस होना चाहिए।',
    'url' => ':attribute एक मान्य URL होना चाहिए।',
    'ulid' => ':attribute एक मान्य ULID होना चाहिए।',
    'uuid' => ':attribute एक मान्य UUID होना चाहिए।',

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    'attributes' => [],
];
