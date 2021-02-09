<?php

if (! function_exists('easyImg')) {
    function easyImg($model_name, $field_name, $size = 'original') {

        $image_sizes = [
            'thumbnail',
            'small',
            'medium',
            'large',
            'xtra_large',
            'square_thumbnail',
            'square',
            'square_large',
            'original'
        ];

        if (!in_array($size, $image_sizes)) $size = 'original';

        return '/raysirsharp/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/' . $size;
    }
}
