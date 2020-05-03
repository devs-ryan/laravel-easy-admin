<?php
namespace Raysirsharp\LaravelEasyAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Raysirsharp\LaravelEasyAdmin\Services\HelperService;
use Raysirsharp\LaravelEasyAdmin\Services\ValidationService;

class AdminController extends Controller
{
    
    /**
     * Helper Service.
     *
     * @var class
     */
    protected $helperService;
    
    /**
     * Validation Service.
     *
     * @var class
     */
    protected $validationService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {  
        $this->helperService = new HelperService;
        $this->validationService = new ValidationService;
    }
    
    /**
     * Display landing page.
     *
     * @return \Illuminate\Http\Response
     */
    public function home()
    {
        $nav_items = $this->helperService->getModelsForNav();
        
        return view('easy-admin::home')
            ->with('nav_items', $nav_items)
            ->with('title', 'Home');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($model)
    {
        //gather info for action
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        $index_columns = $appModel::index();
        $allowed = $appModel::allowed();
      
        //get data
        $check_model = $model_path::first();
        if ($check_model && $check_model->id) 
            $data = $model_path::orderByDesc('id')->paginate(50);
        else if ($check_model && $check_model->create_at) 
            $data = $model_path::orderByDesc('create_at')->paginate(50);
        else $data = $model_path::paginate(50);
          
        return view('easy-admin::index')
            ->with('data', $data)
            ->with('model', $model)
            ->with('model_path', $model_path)
            ->with('nav_items', $nav_items)
            ->with('title', 'Index')
            ->with('index_columns', $index_columns)
            ->with('allowed', $allowed)
            ->with('url_model', $url_model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($model)
    {   
        //gather info for action
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        
        //check allowed
        $allowed = $appModel::allowed();
        if (!in_array('create', $allowed)) abort(403, 'Unauthorized action.');
        
        //create fields
        $fields = $appModel::create();
        $required_fields = $this->validationService->getRequiredFields($model_path);
        
        //return view
        return view('easy-admin::create')
            ->with('model', $model)
            ->with('model_path', $model_path)
            ->with('allowed', $allowed)
            ->with('url_model', $url_model)
            ->with('nav_items', $nav_items)
            ->with('fields', $fields)
            ->with('required_fields', $required_fields);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($model, Request $request)
    {        
        //gather info for view
        $input = $request->except(['_token']);
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        
        //check allowed
        $allowed = $appModel::allowed();
        if (!in_array('create', $allowed)) abort(403, 'Unauthorized action.');
        
        //create
        $message = $this->validationService->createModel($input, $model_path);
        
        //return redirect
        return redirect('/easy-admin/'. $url_model .'/create')
            ->with('message', $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($model, $id)
    {
        //gather info for view
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        $allowed = $appModel::allowed();    
        
        //update fields
        $fields = $appModel::update();
        $required_fields = $this->validationService->getRequiredFields($model_path);
        
        //find model
        $data = $model_path::findOrFail($id);
        
        //return view
        return view('easy-admin::edit')
            ->with('id', $id)
            ->with('model', $model)
            ->with('model_path', $model_path)
            ->with('allowed', $allowed)
            ->with('url_model', $url_model)
            ->with('nav_items', $nav_items)
            ->with('fields', $fields)
            ->with('required_fields', $required_fields)
            ->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($model, Request $request, $id)
    {
        //gather info for action
        $input = $request->except(['_token', '_method']);
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        $allowed = $appModel::allowed();    
        
        //check allowed
        $allowed = $appModel::allowed();
        if (!in_array('update', $allowed)) abort(403, 'Unauthorized action.');
        
        //find model
        $data = $model_path::findOrFail($id);
        
        //update
        $message = $this->validationService->updateModel($input, $data);
        
        //return redirect
        return redirect('/easy-admin/'. $url_model .'/'. $id .'/edit')
            ->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($model, $id)
    {
        //gather info for action
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $nav_items = $this->helperService->getModelsForNav();
        $appModel = "App\\EasyAdmin\\" . $model;
        $allowed = $appModel::allowed();    
        
        //check allowed
        $allowed = $appModel::allowed();
        if (!in_array('delete', $allowed)) abort(403, 'Unauthorized action.');
        
        //find model
        $data = $model_path::findOrFail($id);
        
        //find model
        $message = $this->validationService->deleteModel($data);
        
        //return redirect
        return redirect('/easy-admin/'. $url_model .'/index')
            ->with('message', $message);
    }

}
    
