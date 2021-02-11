<?php

/**
 * Helper function to get image file paths
 */
if (! function_exists('easyImg')) {
    function easyImg($model_name, $field_name, $file_name, $size = 'original') {

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

        return '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/' . $size . '/' . $file_name;
    }
}

/**
 * Helper function to get non-image file paths
 */
if (! function_exists('easyFile')) {
    function easyFile($model_name, $field_name, $file_name) {
        return '/devsryan/LaravelEasyAdmin/storage/files/' . $model_name . '-' .  $field_name . '/' . $file_name;
    }
}
