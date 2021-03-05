<?php
namespace DevsRyan\LaravelEasyAdmin\Services;

use Illuminate\Support\Facades\DB;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;
use Intervention\Image\Facades\Image;
use Exception;
use Throwable;


class FileService
{

    /**
     * Helper Service.
     *
     * @var class
     */
    protected $helperService;

    /**
     * Template for public model classes
     *
     * @var class
     */
    protected $public_model_template;

    /**
     * Template for app models list
     *
     * @var class
     */
    protected $app_model_list_template;


    /**
     * Image resize
     *
     * @var class
     */
    public $image_sizes = [
        'thumbnail' => '150|auto',
        'small' => '300|auto',
        'medium' => '600|auto',
        'large' => '1200|auto',
        'xtra_large' => '2400|auto',
        'square_thumbnail' => '150|150',
        'square' => '600|600',
        'square_large' => '1200|1200',
        'original' => 'size not altered'
    ];

    /**
     * Image resize
     *
     * @var class
     */
    public $model_types = [
        'page',
        'post',
        'partial'
    ];

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helperService = new HelperService;

        $path = str_replace('/Services', '', __DIR__).'/FileTemplates/PublicModel.template';
        $this->public_model_template = file_get_contents($path) or die("Unable to open file!");

        $path = str_replace('/Services', '', __DIR__).'/FileTemplates/AppModelList.template';
        $this->app_model_list_template = file_get_contents($path) or die("Unable to open file!");
    }

    /**
     * Check if AppModelList is corrupted
     *
     * @return boolean
     */
    public function checkIsModelListCorrupted()
    {
        try {
            $this->helperService->getAllModels();
        }
        catch(Exception $e) {
            return true;
        }
        return false;
    }

    /**
     * Reset AppModelList file
     *
     * @return void
     */
    public function resetAppModelList()
    {
        $write_path = app_path('EasyAdmin/AppModelList.php');
        file_put_contents($write_path, $this->app_model_list_template) or die("Unable to write to file!");
    }

    /**
     * Check if a model has already been added to easy admin
     *
     * @param string $model
     * @return boolean
     */
    public function checkModelExists($model)
    {
        $models = $this->helperService->getAllConvertedModels();
        if (in_array($model, $models)) return true;
        return false;
    }

    /**
     * Check if a public class for this model already exists
     *
     * @param string $model
     * @return boolean
     */
    public function checkPublicModelExists($model_path)
    {
        try {
            $this->helperService->getPublicModel($model_path);
        }
        catch (Exception $e) {
            return false;
        }
        return true;
    }

    /**
     * Add Model into EasyAdmin models list
     *
     * @param string $namespace, $model, $type, $type_target
     * @return void
     */
    public function addModelToList($namespace, $model, $type = 'None', $type_target = null)
    {
        //add model to AppModelList file
        $path = app_path('EasyAdmin/AppModelList.php');

        $package_file = file_get_contents($path) or die("Unable to open file!");

        for($i = 0; $i < strlen($package_file); $i++) {
            //find end of array
            if ($package_file[$i] == ']' && $package_file[$i+1] == ';') {
                $insert = "            '" . rtrim($namespace, '\\') . '.' . $model . "',\n";
                $new_text = substr_replace($package_file, $insert, $i - 8, 0);
                file_put_contents($path, $new_text) or die("Unable to write to file!");
                break;
            }
        }

        // if special type, add to
        if (in_array($type, $this->model_types)) {

            $package_file = file_get_contents($path) or die("Unable to open file!");
            $target = $type . 'Models()';

            $stack = '';
            $target_found = false;
            for($i = 0; $i < strlen($package_file); $i++) {

                if (!$target_found) {
                    if (strlen($stack) + 1 > strlen($target)) $stack = ltrim($stack, $stack[0]) . $package_file[$i];
                    else $stack .= $package_file[$i];
                    if ($stack == $target) $target_found = true;
                }
                else {
                    //find end of array
                    if ($package_file[$i] == ']' && $package_file[$i+1] == ';') {
                        switch($type) {
                            case 'page':
                            case 'post':
                                $insert = "            '" . $model . "',\n";
                                break;
                            case 'partial':
                                if ($type_target === null)
                                    throw new Exception('Invaled type target for model type: ' . $type);
                                    $insert = "            '" . $type_target . '.' . $model . "',\n";
                                break;
                        }

                        $new_text = substr_replace($package_file, $insert, $i - 8, 0);
                        file_put_contents($path, $new_text) or die("Unable to write to file!");
                        break;
                    }
                }

            }
        }
    }

    /**
     * Remove Model from EasyAdmin models list
     *
     * @param string $namespace, $model
     * @return void
     */
    public function removeModelFromList($namespace, $model)
    {
        $path = app_path('EasyAdmin/AppModelList.php');
        $input_lines = file_get_contents($path) or die("Unable to open file!");
        $overwrite_string = preg_replace('/^.*(\.)?'.$model.'\',\n/m', '', $input_lines);
        file_put_contents($path, $overwrite_string) or die("Unable to write to file!");
    }

    /**
     * Add Model into app Models
     *
     * @param string $model_path
     * @return void
     */
    public function addPublicModel($model_path)
    {
        $model = $this->helperService->stripPathFromModel($model_path);
        $write_path = app_path() . '/EasyAdmin/' . $model . '.php';

        //get attributes
        $record = new $model_path;
        $table = $record->getTable();

        $fields = '';
        $columns = DB::select('SHOW COLUMNS FROM ' . $table);
        foreach($columns as $column) {
            $fields .= "'$column->Field',\n            ";
        }

        //comment out fields
        $text = str_replace("{{form_model_fields}}", $this->formFilter($fields), $this->public_model_template);
        $text = str_replace("{{index_model_fields}}", $this->indexFilter($fields), $text);
        $text = str_replace("{{model_name}}", $model, $text);
        $this->createAppDirectory(); //if doesnt exist create public directory
        file_put_contents($write_path, $text) or die("Unable to write to file!");
    }

    /**
     * Remove Model from app Models
     *
     * @param string $model_path
     * @return void
     */
    public function removePublicModel($model_path)
    {
        $model = $this->helperService->stripPathFromModel($model_path);
        $write_path = app_path() . '/EasyAdmin/' . $model . '.php';
        unlink($write_path);
    }

    /////////////////////////////////////
    //FILTER FUNCTIONS FOR ABOVE METHOD//
    /////////////////////////////////////
    private function formFilter($fields)
    {
        $fields = trim($fields);
        $fields = str_replace('\'id', '//\'id', $fields);
        $fields = str_replace('\'remember_token', '//\'remember_token', $fields);
        $fields = str_replace('\'email_verified_at', '//\'email_verified_at', $fields);
        $fields = str_replace('\'created_at', '//\'created_at', $fields);
        $fields = str_replace('\'updated_at', '//\'updated_at', $fields);

        return $fields;
    }
    private function indexFilter($fields)
    {
        $fields = trim($fields);
        $fields = str_replace('\'password', '//\'password', $fields);
        $fields = str_replace('\'remember_token', '//\'remember_token', $fields);
        $fields = str_replace('\'email_verified_at', '//\'email_verified_at', $fields);
        $fields = str_replace('\'created_at', '//\'created_at', $fields);
        $fields = str_replace('\'updated_at', '//\'updated_at', $fields);

        return $fields;
    }


    /**
     * Remove the App/EasyAdmin directory
     *
     * @return void
     */
    public function removeAppDirectory() {
        $dir = app_path() . '/EasyAdmin';

        $it = new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new \RecursiveIteratorIterator($it,
                     \RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }

    /**
     * Create the App/EasyAdmin directory
     *
     * @return void
     */
    public function createAppDirectory() {
        $dir = app_path() . '/EasyAdmin';
        if (!file_exists($dir)) {
            mkdir($dir);
        }
    }

    /**
     * Store an uploaded file and save the filename in DB
     *
     * @param Request $request
     * @param Model $record
     * @param string $field_name
     * @param string $model
     * @return void
     */
    public function storeUploadedFile($request, $record, $field_name, $model) {
        $file = $request->file($field_name);
        $filename = sha1(time()) . '.' . $file->extension();

        // set file name in DB
        $record->$field_name = $filename;

        // check if file is not an image
        $image_info = @getimagesize($file);
        if($image_info == false) {
            $original_path = public_path() . '/devsryan/LaravelEasyAdmin/storage/files/' . $model . '-' .  $field_name;
            $file->move($original_path, $filename);
            return;
        }


        // save original image
        $original_path = public_path() . '/devsryan/LaravelEasyAdmin/storage/img/' . $model . '-' .  $field_name . '/original';
        $file->move($original_path, $filename);

        foreach($this->image_sizes as $name => $size) {
            if ($name == 'original') continue;

            $width = explode("|", $size)[0];
            $height = explode("|", $size)[1];

            $image_resize = Image::make($original_path . '/' . $filename);

            if ($height != 'auto') {
                $image_resize->fit($width, $height);
            }
            else {
                // resize the image to a width of 300 and constrain aspect ratio (auto height)
                $image_resize->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $path = public_path() . '/devsryan/LaravelEasyAdmin/storage/img/' . $model . '-' .  $field_name . '/' . $name;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            $image_resize->save($path . '/' . $filename);
        }
    }

    /**
     * Unlink files from field
     *
     * @param model $model
     * @param string $model_name
     * @param array $file_fields
     * @return void
     */
    public function unlinkFiles($model, $model_name, $file_fields, $target = null) {
        $attributes = $model->attributesToArray();

        foreach($attributes as $field_name => $value) {

            if ($target !== null && $field_name != $target) continue; // Do not erase file when targetting a specific file that needs erased

            if (in_array($field_name, $file_fields)) {

                // unlink all file paths
                $path = public_path() . '/devsryan/LaravelEasyAdmin/storage/files/' . $model_name . '-' .  $field_name;
                if (file_exists($path . '/' . $value)) {
                    unlink($path . '/' . $value);
                }


                // unlink all image paths
                foreach($this->image_sizes as $name => $size) {
                    $path = public_path() . '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/' . $name;
                    if (file_exists($path . '/' . $value)) {
                        unlink($path . '/' . $value);
                    }
                }
            }
        }
    }

    /**
     * Undocumented function
     *
     * @param string $model_name
     * @param string $field_name
     * @param string $value
     * @return void
     */
    public static function getFileLink($model_name, $field_name, $value) {

        // check if is file
        $path = public_path() . '/devsryan/LaravelEasyAdmin/storage/files/' . $model_name . '-' .  $field_name;
        if (file_exists($path . '/' . $value)) {
            return '/devsryan/LaravelEasyAdmin/storage/files/' . $model_name . '-' .  $field_name . '/' . $value;
        }

        // check
         // unlink all image paths
         $path = public_path() . '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/original';
         if (file_exists($path . '/' . $value)) {
             return '/devsryan/LaravelEasyAdmin/storage/img/' . $model_name . '-' .  $field_name . '/original/' . $value;
         }

         return null;
    }
}





































