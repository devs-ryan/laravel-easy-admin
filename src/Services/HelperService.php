<?php
namespace DevsRyan\LaravelEasyAdmin\Services;

use App\EasyAdmin\AppModelList;
use Illuminate\Support\Facades\DB;
use Exception;
use Throwable;


class HelperService
{
    /**
     * Find the form input type
     *
     * @param string $field
     * @param string $model
     * @return string
     */
    public static function inputType($field, $model)
    {
        $record = new $model;
        $table = $record->getTable();
        $column_type = '';

        //find column type
        $columns = DB::select('SHOW COLUMNS FROM ' . $table);
        foreach($columns as $column) {
            if ($column->Field == $field) {
                $column_type = $column->Type;
                break;
            }
        }

        //convert to options:
        //int, float, boolean, date, timestamp, password, text (default)

        //check password
        if ($field == 'password') {
            return 'password';
        }
        //check boolean
        if ($column_type == 'tinyint(1)') {
            return 'boolean';
        }
        //check integer
        if (strpos($column_type, 'int') !== false) {
            return 'integer';
        }
        //check decimal
        foreach(['double', 'decimal', 'float'] as $check) {
            if (strpos($column_type, $check) !== false) {
                return 'decimal';
            }
        }
        //check timestamp
        if (strpos($column_type, 'timestamp') !== false) {
            return 'timestamp';
        }
        //check date
        if (strpos($column_type, 'date') !== false) {
            return 'date';
        }

        //default to text
        return 'text';
    }

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
                throw new Exception('Parse error in AppModelList');
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
                throw new Exception('Parse error in AppModelList');
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
     * Format Namespace.Model
     *
     * @return Array
     */
    public function getAllModels()
    {
        try {
            return AppModelList::models();
        }
        catch (Throwable $t) {
            throw new Exception('Parse Error: AppModelList.php has been corrupted.');
        }
    }

    /**
     * Return all page models added to admin area
     * Format Model
     *
     * @return Array
     */
    public function getAllPageModels()
    {
        try {
            return AppModelList::pageModels();
        }
        catch (Throwable $t) {
            throw new Exception('Parse Error: AppModelList.php has been corrupted.');
        }
    }

    /**
     * Return all post models added to admin area
     * Format Model
     *
     * @return Array
     */
    public function getAllPostModels()
    {
        try {
            return AppModelList::postModels();
        }
        catch (Throwable $t) {
            throw new Exception('Parse Error: AppModelList.php has been corrupted.');
        }
    }

    /**
     * Return all section models added to admin area
     * Format Model
     *
     * @return Array
     */
    public function getAllSectionModels()
    {
        try {
            return AppModelList::sectionModels();
        }
        catch (Throwable $t) {
            throw new Exception('Parse Error: AppModelList.php has been corrupted.');
        }
    }

    /**
     * Get public model file
     *
     * @return Array
     */
    public function getPublicModel($model_path)
    {
        $model = $this->stripPathFromModel($model_path);
        $app_model = "App\\EasyAdmin\\" . $model;

        try {
            if (class_exists($app_model)) {
                return $app_model;
            }
            throw new Exception('Error: Public model does not exist.');
        }
        catch (Throwable $t) {
            throw new Exception('Error: Public model does not exist.');
        }
    }

    /**
     * Return all models added to admin area
     * Format Namespace\Model
     *
     * @return Array
     */
    public function getAllConvertedModels()
    {
        $models = $this->getAllModels();
        $converted = [];
        foreach ($models as $model) {
            array_push($converted, str_replace('.', '\\', $model));
        }
        return $converted;
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

    /**
     * Check if model has ID field
     *
     * @return boolean
     */
    public function checkModelHasId($model_path)
    {
        $record = new $model_path;
        $table = $record->getTable();

        $columns = DB::select('SHOW COLUMNS FROM ' . $table);

        foreach($columns as $column_data) {
            if ($column_data->Field == 'id') {
                return true;
            }
        }
        return false;
    }
}


















