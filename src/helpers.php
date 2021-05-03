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

        return rtrim(env('APP_URL', ''), "/") . '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/' . $size . '/' . $file_name;
    }
}

/**
 * Helper function to get image file paths
 */
if (! function_exists('easyImgDetails')) {
    function easyImgDetails($model_name, $field_name, $file_name) {

        if ($field_name === null) $field_name = 'general_storage';

        $data = [
            'file_name' => $file_name,
            'title' => null,
            'alt' => null,
            'description' => null,
            'width' => null,
            'height' => null,
            'size' => null,
            'model' => $model_name,
            'field' => $field_name,
            'created_at' => null,
            'updated_at' => null
        ];

        $sizes = [];
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

        foreach($image_sizes as $size) {
            $sizes[$size] = rtrim(env('APP_URL', ''), "/") . '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/' . $size . '/' . $file_name;
        }

        $image_details = \DB::table('easy_admin_images')->where('file_path', $sizes['original'])->first();
        if ($image_details) {
            $data['title'] = $image_details->title;
            $data['alt'] = $image_details->alt;
            $data['description'] = $image_details->description;
            $data['width'] = $image_details->width;
            $data['height'] = $image_details->height;
            $data['size'] = $image_details->size;
            $data['created_at'] = $image_details->created_at;
            $data['updated_at'] = $image_details->updated_at;
        }

        $data['sizes'] =  $sizes;

        return $data;
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

/**
 * Helper function to strip any HTML away from text for wysiwyg fields
 */
if (! function_exists('easySafeText')) {
    function easySafeText($html) {
        $html = preg_replace("/<[^>]+>/i", "", $html);
        return $html;
    }
}
