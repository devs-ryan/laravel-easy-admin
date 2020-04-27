<?php
namespace Raysirsharp\LaravelEasyAdmin\Services;
use Illuminate\Support\Facades\DB;
use Raysirsharp\LaravelEasyAdmin\AppModelsList;
use Exception;


class HelperService
{
    /**
     * Convert the URL model to the app model
     *
     * @return Array
     */
    public function convertUrlModel($url_model) 
    {
        $model = '';
        $pieces = explode('-', $url_model);
        foreach ($pieces as $piece) {
            $model .= ucfirst($piece);
        }

        $app_models = $this->getAllModels();
        
        foreach ($app_models as $app_model) {
            //parse model
            $pieces = explode('.', $app_model);
            if (count($pieces) != 2) {
                throw new Exception('Parse error in AppModelsList');
            }
            //check form match
            $app_model = $pieces[1];
            $name_space = $pieces[0];
            
            if ($app_model == $model) {
                return $name_space . '\\' . $model;
            }
            if ($app_model == rtrim($model, 's')) {
                return $name_space . '\\' . rtrim($model, 's');
            }
        }
        
        throw new Exception('Model not found: ' . $url_model);
    }
    
    /**
     * Return all models added to admin area (without full path)
     *
     * @return Array
     */
    public function getModelsForNav()
    {
        $models = [];
        $all_models = $this->getAllModels();
        
        foreach ($all_models as $model) {
            //parse model
            $pieces = explode('.', $model);
            if (count($pieces) != 2) {
                throw new Exception('Parse error in AppModelsList');
            }
            //check form match
            $app_model = $pieces[1];
            $name_space = $pieces[0];
            $models[$this->convertModelToLink($app_model)] = $app_model;
        }
        return $models;
    }
    
    /**
     * Strip the model away from the models full path
     *
     * @return Array
     */
    public function stripPathFromModel($model)
    {
        $pieces = explode('\\', $model);
        $length = count($pieces); 

        return $pieces[$length - 1];
    }

    /**
     * Return all models added to admin area
     *
     * @return Array
     */
    public function getAllModels()
    {
        return AppModelsList::models();
    }
    
    /**
     * Convert Model to Link
     *
     * @return string
     */
    public function convertModelToLink($model)
    {
        $link = $model;

        for ($i = 1; $i < strlen($model); $i++) {
            if (ctype_upper($model[$i])) {
                $link = substr_replace($model, '-' . strtolower($model[$i]), $i, 1);
            }
        }
        return strtolower($link);
    }    
}

