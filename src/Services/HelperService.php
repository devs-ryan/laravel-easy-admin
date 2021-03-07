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
     * Find the form input type
     *
     * @param integer $parent_id
     * @param string $model
     * @return HTML
     */
    public static function makePartialBreadcrums($parent_id, $model, $nav_items)
    {
        $html = '<a href="/easy-admin">HOME</a>';
        $helper = new HelperService;
        $full_path_models = $helper->getAllConvertedModels();
        $partial_models = $helper->getAllPartialModels();
        $partial_models_stripped = $helper->stripParentFromPartials($partial_models);
        $initial_loop = true;
        $prev_parent_id = $parent_id;
        $prev_url_model = '';

        while(in_array($model, $partial_models_stripped)) {

            // find partial and parent
            foreach ($partial_models as $partial_model) {
                $pieces = explode('.', $partial_model);
                $parent = $pieces[0];
                $partial = $pieces[1];

                // partial and parent found
                if ($model === $partial) {
                    if ($parent === 'Global') return $html; // no need to do anything more for global partials}

                    // find parent_id if not set from initial loop
                    if (!$initial_loop) {
                        $column_name = $helper->findParentIdColumnName($parent, $nav_items);

                        foreach($full_path_models as $full_path_model) {
                            if (strpos($full_path_model, $model) !== false) {
                                $find_parent_for = $full_path_model::findOrFail($parent_id);
                                $parent_id = $find_parent_for->$column_name;

                                if ($parent_id === null) throw new Exception("`$column_name` expected in $model database table");
                                break;
                            }
                        }
                    }

                    // find parent nav string
                    $found = false;
                    foreach($nav_items as $url_string => $model_name) {
                        if ($model_name === $parent) {
                            $url_model = $url_string;
                            $found = true;
                            break;
                        }
                    }

                    if (!$found) throw New Exception('Failed to find partials parent details');

                    //check for last iteration
                    $parent_append = '?parent_id=' . $parent_id;

                    if (!$initial_loop) {

                        // add edit page
                        $add_to_html = ' / ';
                        $add_to_html .= '<a href="/easy-admin/' . $prev_url_model . '/' . $prev_parent_id . '/edit' . $parent_append . '">'
                            . strtoupper($partial) . ' #' . $prev_parent_id
                            . '</a>';
                        $html = str_replace('<a href="/easy-admin">HOME</a>', '<a href="/easy-admin">HOME</a>' . $add_to_html, $html);

                        // add index page
                        $add_to_html = ' / ';
                        $add_to_html .= '<a href="/easy-admin/' . $prev_url_model . '/index' . $parent_append . '">'
                            . strtoupper($partial) . ' - INDEX'
                            . '</a>';
                        $html = str_replace('<a href="/easy-admin">HOME</a>', '<a href="/easy-admin">HOME</a>' . $add_to_html, $html);
                    }

                    $model = $parent;
                    $initial_loop = false;
                    $prev_parent_id = $parent_id;
                    $prev_url_model = $url_model;
                    break;
                }
            }
        }

        // final iteration
        // add edit page
        $add_to_html = ' / ';
        $add_to_html .= '<a href="/easy-admin/' . $url_model . '/' . $parent_id . '/edit">'
            . strtoupper($parent) . ' #' . $parent_id
            . '</a>';
        $html = str_replace('<a href="/easy-admin">HOME</a>', '<a href="/easy-admin">HOME</a>' . $add_to_html, $html);

        // add index page
        $add_to_html = ' / ';
        $add_to_html .= '<a href="/easy-admin/' . $url_model . '/index">'
            . strtoupper($parent) . ' - INDEX'
            . '</a>';
        $html = str_replace('<a href="/easy-admin">HOME</a>', '<a href="/easy-admin">HOME</a>' . $add_to_html, $html);

        return $html;
    }

    /**
     * Find relationship column name for a parent model
     *
     * @param string $parent
     * @param Array $nav_items
     * @return mixed
     */
    public function findParentIdColumnName($parent, $nav_items) {
        // find field to get parent ID
        foreach($nav_items as $url_string => $model_name) {
            if ($model_name === $parent) {
                return str_replace('-', '_', $url_string) . '_id';
            }
        }
        throw new Exception('Missing parent model in nav items');
    }

    /**
     * Finds a models parent model
     *
     * @param string $child_model
     * @return mixed
     */
    public function findParent($child_model) {
        $partial_models = $this->getAllPartialModels();

        foreach ($partial_models as $partial_model) {
            $pieces = explode('.', $partial_model);
            $parent = $pieces[0];
            $partial = $pieces[1];

            if ($partial === $child_model) {
                return $parent;
            }

        }
        throw new Exception('Unable to find parent model');
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
     * Strip the Global/Parent away from partials
     *
     * @return Array
     */
    public function stripParentFromPartials($partials)
    {
        $output = [];

        foreach($partials as $partial) {
            $pieces = explode('.', $partial);
            $output[] = $pieces[1];
        }

        return $output;
    }

    /**
     * Get Partials that belong to a specific Model
     *
     * @return Array
     */
    public function getPartials($model)
    {
        $output = [];

        $partials = $this->getAllPartialModels();

        foreach($partials as $partial) {
            $pieces = explode('.', $partial);

            if ($pieces[0] == $model)
                $output[] = $pieces[1];
        }

        return $output;
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
     * Return all partial models added to admin area
     * Format Model
     *
     * @return Array
     */
    public function getAllPartialModels()
    {
        try {
            return AppModelList::partialModels();
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

        for ($i = 1; $i < strlen($link); $i++) {
            if (ctype_upper($link[$i])) {
                $link = substr_replace($link, '-' . strtolower($link[$i]), $i, 1);
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


















