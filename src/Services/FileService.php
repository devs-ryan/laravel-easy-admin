<?php
namespace Raysirsharp\LaravelEasyAdmin\Services;

use Illuminate\Support\Facades\DB;
use Raysirsharp\LaravelEasyAdmin\Services\HelperService;
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
        'thumbnail' => '150|150',
        'small' => '300|auto',
        'medium' => '600|auto',
        'large' => '1200|auto',
        'xtra_large' => '2400|auto',
        'square' => '600|600',
        'square_large' => '1200|1200',
        'original' => 'size not altered'
    ];

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helperService = new HelperService;

        $path = str_replace('/Services', '', __DIR__).'/FileTemplates/PublicModelTemplate.template';
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
     * @param string $namespace, $model
     * @return void
     */
    public function addModelToList($namespace, $model)
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
        $handle = fopen($path, "r") or  die("Unable to open file!");
        $remove = rtrim($namespace, '\\') . '.' . $model . "',\n";
        $overwrite_string = "";

        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                if (strpos($line, $remove) === false) {
                    $overwrite_string .= $line;
                }
            }
            fclose($handle);
        }
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
}





































