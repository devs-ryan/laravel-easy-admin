<?php
namespace Raysirsharp\LaravelEasyAdmin\Services;

use Illuminate\Support\Facades\DB;
use Raysirsharp\LaravelEasyAdmin\Services\HelperService;
use Exception;


class FileService
{
    
    /**
     * Helper Service.
     *
     * @var class
     */
    protected $helperService;

    public function __construct()
    {  
        $this->helperService = new HelperService;
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
     * Add Model into East Admin models list
     *
     * @param string $model
     * @return void
     */
    public function addModelToList($namespace, $model)
    {
        //add model to AppModelsList file
        $path = str_replace('/Services', '', __DIR__).'/AppModelsList.php';

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
}





































