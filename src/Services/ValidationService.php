<?php
namespace DevsRyan\LaravelEasyAdmin\Services;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use DevsRyan\LaravelEasyAdmin\Services\FileService;
use Exception;


class ValidationService
{

    /**
     * Helper Service.
     *
     * @var class
     */
    protected $fileService;

    /**
     * Create a new service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->file_service = new FileService;
    }

    /**
     * Create a new record, after validation
     *
     * @param array $input
     * @param string $model
     * @param array $file_fields
     * @return void
     */
    public function createModel($request, $model_path, $model, $file_fields = [])
    {
        try {
            $record = new $model_path;
            $input = $request->except(['_token', 'partial_redirect_easy_admin', 'easy_admin_submit_with_parent_id']);

            foreach($input as $key => $attribute) {
                if ($key == 'password') {
                    $record->$key = Hash::make($attribute);
                }
                else if (in_array($key, $file_fields)) {
                    if ($request->hasFile($key)) {
                        $this->file_service->storeUploadedFile($request, $record, $key, $model);
                    }
                }
                else {
                    $record->$key = $attribute;
                }
            }
            $record->save();
        }
        catch(Exception $e) {
            return [
                'message' => 'Error:' . $e->getMessage(),
                'record' => null
            ];
        }
        return [
            'message' => 'Success: A new record was created!',
            'record' => $record
        ];
    }

    /**
     * Update a record, after validation
     *
     * @return string (success/failure message)
     */
    public function updateModel($request, $record, $model, $file_fields = [])
    {
        try {
            $input = $request->except(['_token', '_method', 'easy_admin_submit_with_parent_id']);

            foreach($input as $key => $attribute) {
                if ($key == 'password') {
                    if ($attribute != '**********')
                        $record->$key = Hash::make($attribute);
                }
                else if (in_array($key, $file_fields)) {
                    if ($request->hasFile($key)) {
                        $this->file_service->unlinkFiles($record, $model, $file_fields, $key);
                        $this->file_service->storeUploadedFile($request, $record, $key, $model);
                    }
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
    public function deleteModel($record, $model, $file_fields)
    {
        try {
            // delete any assosiated files
            $this->file_service->unlinkFiles($record, $model, $file_fields);

            // delete record
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





































