<?php
namespace Raysirsharp\LaravelEasyAdmin\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Exception;


class ValidationService
{
    /**
     * Create a new record, after validation
     *
     * @return string (success/failure message)
     */
    public function createModel($input, $model) 
    {
        try {
            $record = new $model;
            foreach($input as $key => $attribute) {
                if ($key == 'password') {
                    $record->$key = Hash::make($attribute);
                }
                else {
                    $record->$key = $attribute;
                }
            }
            $record->save();
        }
        catch(Exception $e) {
            return 'Error:' . $e->getMessage();
        }
        return 'Success: A new record was created!';
    }
    
    /**
     * Update a record, after validation
     *
     * @return string (success/failure message)
     */
    public function updateModel($input, $record) 
    {
        try {
            foreach($input as $key => $attribute) {
                if ($key == 'password') {
                    if ($attribute != '**********')
                        $record->$key = Hash::make($attribute);
                }
                else {
                    $record->$key = $attribute;
                }
            }
            $record->save();
        }
        catch(Exception $e) {
            return 'Error:' . $e->getMessage();
        }
        return 'Success: The record was updated!';
    }
    
    /**
     * Delete a record, after validation
     *
     * @return string (success/failure message)
     */
    public function deleteModel($record)
    {
        try {
            $record->delete();
        }
        catch(Exception $e) {
            return 'Error:' . $e->getMessage();
        }
        return 'Success: The record was removed!';
    }
    
    /**
     * Get the list of required fields
     *
     * @return array
     */
    public function getRequiredFields($model)
    {
        $record = new $model;
        $table = $record->getTable();
        $required = [];
    
        $columns = DB::select('SHOW COLUMNS FROM ' . $table);

        foreach($columns as $column_data) {
            if ($column_data->Null == 'NO') {
                array_push($required, $column_data->Field);
            }
        }
        return $required;
    }
}





































