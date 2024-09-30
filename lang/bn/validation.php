<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | নিম্নলিখিত ভাষা লাইনগুলি ডিফল্ট ত্রুটি বার্তা ধারণ করে যা
    | ভ্যালিডেটর ক্লাস দ্বারা ব্যবহৃত হয়। কিছু নিয়মের একাধিক সংস্করণ রয়েছে
    | যেমন সাইজ নিয়ম। এখানে এই বার্তাগুলি পরিবর্তন করতে নির্দ্বিধায়।
    |
    */

    'accepted' => ':attribute গ্রহণ করতে হবে।',
    'accepted_if' => ':other :value হলে :attribute গ্রহণ করতে হবে।',
    'active_url' => ':attribute একটি বৈধ URL হতে হবে।',
    'after' => ':attribute :date এর পরে একটি তারিখ হতে হবে।',
    'after_or_equal' => ':attribute :date এর পরে বা সমান তারিখ হতে হবে।',
    'alpha' => ':attribute শুধুমাত্র অক্ষর থাকতে হবে।',
    'alpha_dash' => ':attribute শুধুমাত্র অক্ষর, সংখ্যা, ড্যাশ এবং আন্ডারস্কোর থাকতে হবে।',
    'alpha_num' => ':attribute শুধুমাত্র অক্ষর এবং সংখ্যা থাকতে হবে।',
    'array' => ':attribute একটি অ্যারে হতে হবে।',
    'ascii' => ':attribute শুধুমাত্র একক-বাইট অক্ষর এবং প্রতীক থাকতে হবে।',
    'before' => ':attribute :date এর আগে একটি তারিখ হতে হবে।',
    'before_or_equal' => ':attribute :date এর আগে বা সমান একটি তারিখ হতে হবে।',
    'between' => [
        'array' => ':attribute :min এবং :max আইটেমের মধ্যে থাকতে হবে।',
        'file' => ':attribute :min এবং :max কিলোবাইটের মধ্যে হতে হবে।',
        'numeric' => ':attribute :min এবং :max এর মধ্যে হতে হবে।',
        'string' => ':attribute :min এবং :max অক্ষরের মধ্যে হতে হবে।',
    ],
    'boolean' => ':attribute ক্ষেত্রটি সত্য বা মিথ্যা হতে হবে।',
    'can' => ':attribute অননুমোদিত মান অন্তর্ভুক্ত করেছে।',
    'confirmed' => ':attribute নিশ্চিতকরণ মেলেনি।',
    'current_password' => 'পাসওয়ার্ডটি সঠিক নয়।',
    'date' => ':attribute একটি বৈধ তারিখ হতে হবে।',
    'date_equals' => ':attribute :date এর সমান একটি তারিখ হতে হবে।',
    'date_format' => ':attribute :format ফরম্যাটের সাথে মেলেনি।',
    'decimal' => ':attribute :decimal দশমিক স্থান থাকতে হবে।',
    'declined' => ':attribute প্রত্যাখ্যান করতে হবে।',
    'declined_if' => ':other :value হলে :attribute প্রত্যাখ্যান করতে হবে।',
    'different' => ':attribute এবং :other ভিন্ন হতে হবে।',
    'digits' => ':attribute :digits সংখ্যা হতে হবে।',
    'digits_between' => ':attribute :min এবং :max সংখ্যার মধ্যে হতে হবে।',
    'dimensions' => ':attribute অকার্যকর ইমেজ মাত্রা আছে।',
    'distinct' => ':attribute ক্ষেত্রে একটি সদৃশ মান আছে।',
    'doesnt_end_with' => ':attribute নিম্নলিখিতগুলির মধ্যে একটি দিয়ে শেষ হতে পারবে না: :values।',
    'doesnt_start_with' => ':attribute নিম্নলিখিতগুলির মধ্যে একটি দিয়ে শুরু হতে পারবে না: :values।',
    'email' => ':attribute একটি বৈধ ইমেইল ঠিকানা হতে হবে।',
    'ends_with' => ':attribute নিম্নলিখিতগুলির মধ্যে একটি দিয়ে শেষ হতে হবে: :values।',
    'enum' => 'নির্বাচিত :attribute অবৈধ।',
    'exists' => 'নির্বাচিত :attribute অবৈধ।',
    'extensions' => ':attribute নিম্নলিখিত এক্সটেনশনের একটি হতে হবে: :values।',
    'file' => ':attribute একটি ফাইল হতে হবে।',
    'filled' => ':attribute ক্ষেত্রটি পূরণ করা আবশ্যক।',
    'gt' => [
        'array' => ':attribute :value আইটেমের চেয়ে বেশি থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বেশি হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বেশি হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে বেশি হতে হবে।',
    ],
    'gte' => [
        'array' => ':attribute :value আইটেম বা তার বেশি থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে বেশি বা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে বেশি বা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে বেশি বা সমান হতে হবে।',
    ],
    'hex_color' => ':attribute একটি বৈধ হেক্সাডেসিমেল রঙ হতে হবে।',
    'image' => ':attribute একটি চিত্র হতে হবে।',
    'in' => 'নির্বাচিত :attribute অবৈধ।',
    'in_array' => ':attribute ক্ষেত্রটি :other এ উপস্থিত হতে হবে।',
    'integer' => ':attribute একটি পূর্ণসংখ্যা হতে হবে।',
    'ip' => ':attribute একটি বৈধ আইপি ঠিকানা হতে হবে।',
    'ipv4' => ':attribute একটি বৈধ IPv4 ঠিকানা হতে হবে।',
    'ipv6' => ':attribute একটি বৈধ IPv6 ঠিকানা হতে হবে।',
    'json' => ':attribute একটি বৈধ JSON স্ট্রিং হতে হবে।',
    'lowercase' => ':attribute ছোট হাতের অক্ষর হতে হবে।',
    'lt' => [
        'array' => ':attribute :value আইটেমের চেয়ে কম থাকতে হবে।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে কম হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে কম হতে হবে।',
    ],
    'lte' => [
        'array' => ':attribute :value আইটেমের চেয়ে বেশি থাকতে পারবে না।',
        'file' => ':attribute :value কিলোবাইটের চেয়ে কম বা সমান হতে হবে।',
        'numeric' => ':attribute :value এর চেয়ে কম বা সমান হতে হবে।',
        'string' => ':attribute :value অক্ষরের চেয়ে কম বা সমান হতে হবে।',
    ],
    'mac_address' => ':attribute একটি বৈধ MAC ঠিকানা হতে হবে।',
    'max' => [
        'array' => ':attribute :max আইটেমের চেয়ে বেশি থাকতে পারবে না।',
        'file' => ':attribute :max কিলোবাইটের চেয়ে বেশি হতে পারবে না।',
        'numeric' => ':attribute :max এর চেয়ে বেশি হতে পারবে না।',
        'string' => ':attribute :max অক্ষরের চেয়ে বেশি হতে পারবে না।',
    ],
    'max_digits' => ':attribute :max সংখ্যার চেয়ে বেশি থাকতে পারবে না।',
    'mimes' => ':attribute একটি ফাইল হতে হবে যার প্রকার: :values।',
    'mimetypes' => ':attribute একটি ফাইল হতে হবে যার প্রকার: :values।',
    'min' => [
        'array' => ':attribute অন্তত :min আইটেম থাকতে হবে।',
        'file' => ':attribute অন্তত :min কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অন্তত :min হতে হবে।',
        'string' => ':attribute অন্তত :min অক্ষর হতে হবে।',
    ],
    'min_digits' => ':attribute অন্তত :min সংখ্যা থাকতে হবে।',
    'missing' => ':attribute অনুপস্থিত থাকতে হবে।',
    'missing_if' => ':other :value হলে :attribute অনুপস্থিত থাকতে হবে।',
    'missing_unless' => ':other :value না হলে :attribute অনুপস্থিত থাকতে হবে।',
    'missing_with' => ':values উপস্থিত হলে :attribute অনুপস্থিত থাকতে হবে।',
    'missing_with_all' => ':values উপস্থিত থাকলে :attribute অনুপস্থিত থাকতে হবে।',
    'multiple_of' => ':attribute :value এর একটি গুণিতক হতে হবে।',
    'not_in' => 'নির্বাচিত :attribute অবৈধ।',
    'not_regex' => ':attribute ফরম্যাট অবৈধ।',
    'numeric' => ':attribute একটি সংখ্যা হতে হবে।',
    'password' => [
        'letters' => ':attribute অন্তত একটি অক্ষর থাকতে হবে।',
        'mixed' => ':attribute অন্তত একটি বড় হাতের এবং একটি ছোট হাতের অক্ষর থাকতে হবে।',
        'numbers' => ':attribute অন্তত একটি সংখ্যা থাকতে হবে।',
        'symbols' => ':attribute অন্তত একটি প্রতীক থাকতে হবে।',
        'uncompromised' => 'প্রদত্ত :attribute একটি ডেটা লিক-এ দেখা গেছে। অনুগ্রহ করে একটি ভিন্ন :attribute নির্বাচন করুন।',
    ],
    'present' => ':attribute ক্ষেত্রটি উপস্থিত থাকতে হবে।',
    'present_if' => ':other :value হলে :attribute উপস্থিত থাকতে হবে।',
    'present_unless' => ':other :value না হলে :attribute উপস্থিত থাকতে হবে।',
    'prohibited' => ':attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_if' => ':other :value হলে :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibited_unless' => ':other :value না হলে :attribute ক্ষেত্রটি নিষিদ্ধ।',
    'prohibits' => ':attribute :other উপস্থিত থাকতে বাধা দেয়।',
    'regex' => ':attribute ফরম্যাট অবৈধ।',
    'required' => ':attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_array_keys' => ':attribute ক্ষেত্রটি অবশ্যই :values এর এন্ট্রি থাকতে হবে।',
    'required_if' => ':other :value হলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_if_accepted' => ':attribute ক্ষেত্রটি প্রয়োজনীয় যদি :other গৃহীত হয়।',
    'required_unless' => ':attribute ক্ষেত্রটি প্রয়োজনীয় যদি :other :values এ না থাকে।',
    'required_with' => ':values উপস্থিত হলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_with_all' => ':values উপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without' => ':values অনুপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'required_without_all' => ':values অনুপস্থিত থাকলে :attribute ক্ষেত্রটি প্রয়োজনীয়।',
    'same' => ':attribute এবং :other অবশ্যই মেলানো উচিত।',
    'size' => [
        'array' => ':attribute অবশ্যই :size আইটেম থাকতে হবে।',
        'file' => ':attribute অবশ্যই :size কিলোবাইট হতে হবে।',
        'numeric' => ':attribute অবশ্যই :size হতে হবে।',
        'string' => ':attribute অবশ্যই :size অক্ষর হতে হবে।',
    ],
    'starts_with' => ':attribute নিম্নলিখিতগুলির মধ্যে একটি দিয়ে শুরু হতে হবে: :values।',
    'string' => ':attribute একটি স্ট্রিং হতে হবে।',
    'timezone' => ':attribute একটি বৈধ সময় অঞ্চল হতে হবে।',
    'unique' => ':attribute ইতিমধ্যেই নেওয়া হয়েছে।',
    'uploaded' => ':attribute আপলোড করতে ব্যর্থ হয়েছে।',
    'uppercase' => ':attribute বড় হাতের অক্ষর হতে হবে।',
    'url' => ':attribute একটি বৈধ URL হতে হবে।',
    'ulid' => ':attribute একটি বৈধ ULID হতে হবে।',
    'uuid' => ':attribute একটি বৈধ UUID হতে হবে।',

    /*
    |--------------------------------------------------------------------------
    | কাস্টম ভ্যালিডেশন ল্যাঙ্গুয়েজ লাইন
    |--------------------------------------------------------------------------
    |
    | এখানে আপনি কাস্টম ভ্যালিডেশন বার্তা নির্দিষ্ট করতে পারেন
    | আপনি বৈশিষ্ট্যগুলি ব্যবহার করে
    | উদাহরণস্বরূপ 'অ্যাট্রিবিউট-নিয়ম' কনভেনশন ব্যবহার করে। এটি আমাদের
    | দ্রুত নির্দিষ্ট বার্তা তৈরি করতে দেয়।
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | কাস্টম ভ্যালিডেশন অ্যাট্রিবিউট
    |--------------------------------------------------------------------------
    |
    | নিম্নলিখিত ভাষা লাইনগুলি আমাদেরকে ব্যবহার করতে দেয়
    | কিছুটা আরও পাঠযোগ্য কিছু আমাদের
    | স্থানাঙ্ক নামের জন্য যেমন "ই-মেইল" এর পরিবর্তে "ই-মেইল ঠিকানা"।
    |
    */

    'attributes' => [],

];
