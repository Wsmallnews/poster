<?php
return [
    /*
    |--------------------------------------------------------------------------
    | Make poster default config
    |--------------------------------------------------------------------------
    |
    |
    */

    // Text default config
    'text' => [
        // default font file
        'font_file' => storage_path('app/assets').'/pingfang.ttf',

        'font_size' => 24,

        // default horizontal text alignment relative to given basepoint. Possible values are left, right and center
        'align' => 'left',

        // default vertical text alignment relative to given basepoint. Possible values are top, bottom and middle
        'valign' => 'top',
    ],


    // Image default config
    'image' => [
        // Set a position where image will be inserted
        'position' => 'top-left'
    ]
];
