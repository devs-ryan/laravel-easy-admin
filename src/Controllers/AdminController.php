<?php
namespace Raysirsharp\LaravelEasyAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Raysirsharp\LaravelEasyAdmin\Services\HelperService;
use Raysirsharp\LaravelEasyAdmin\Services\ValidationService;
use Auth;

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

        //EasyAdmin Middleware
        $this->middleware(function ($request, $next) {
            if (Auth::check()) {
                if (Auth::user()->is_easy_admin) {
                    return $next($request);
                }
                else {
                    Auth::logout();
                    $request->session()->flush();
                    return redirect('/easy-admin/login')
                        ->with('message', 'Access Denied! Request Easy Admin permission to continue.');
                }

            }
            return redirect('/easy-admin/login');
        });
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index($model, Request $request)
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
            $data = $model_path::orderByDesc('id');
        else if ($check_model && $check_model->create_at)
            $data = $model_path::orderByDesc('create_at');
        else $data = $model_path::query();

        // files
        $file_fields = $appModel::files();

        //apply filters
        foreach($request->all() as $filter => $value) {
            if ($value === null) continue;

            if (strpos($filter, '__from') !== false) { //from comparison
                $filter = str_replace('__from', '', $filter);
                if (!$check_model->$filter) continue;
                $data = $data->where($filter, '>=', date($value));
                continue;
            }
            if (strpos($filter, '__to') !== false) { //from comparison
                $filter = str_replace('__to', '', $filter);
                if (!$check_model->$filter) continue;
                $data = $data->where($filter, '<=', date($value));
                continue;
            }

            // regular comparison
            if (!$check_model->$filter) continue;
            $data = $data->where($filter, 'LIKE', "%$value%");
        }

        //paginate
        $data = $data->paginate(50);

        return view('easy-admin::index')
            ->with('data', $data)
            ->with('model', $model)
            ->with('model_path', $model_path)
            ->with('nav_items', $nav_items)
            ->with('title', 'Index')
            ->with('index_columns', $index_columns)
            ->with('allowed', $allowed)
            ->with('url_model', $url_model)
            ->with('file_fields', $file_fields);
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

        // wysiwyg_editors
        $wysiwyg_editors = $appModel::wysiwyg_editors();

        // files
        $file_fields = $appModel::files();

        //return view
        return view('easy-admin::create')
            ->with('model', $model)
            ->with('model_path', $model_path)
            ->with('allowed', $allowed)
            ->with('url_model', $url_model)
            ->with('nav_items', $nav_items)
            ->with('fields', $fields)
            ->with('required_fields', $required_fields)
            ->with('wysiwyg_fields', $wysiwyg_editors)
            ->with('file_fields', $file_fields);
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
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $appModel = "App\\EasyAdmin\\" . $model;

        //check allowed
        $allowed = $appModel::allowed();
        $file_fields = $appModel::files();
        if (!in_array('create', $allowed)) abort(403, 'Unauthorized action.');

        //create
        $message = $this->validationService->createModel($request, $model_path, $model, $file_fields);

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

        // wysiwyg_editors
        $wysiwyg_editors = $appModel::wysiwyg_editors();

        // files
        $file_fields = $appModel::files();

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
            ->with('data', $data)
            ->with('wysiwyg_fields', $wysiwyg_editors)
            ->with('file_fields', $file_fields);
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
        $url_model = $model;
        $model_path = $this->helperService->convertUrlModel($url_model);
        $model = $this->helperService->stripPathFromModel($model_path);
        $appModel = "App\\EasyAdmin\\" . $model;
        $allowed = $appModel::allowed();

        //check allowed
        $allowed = $appModel::allowed();
        $file_fields = $appModel::files();
        if (!in_array('update', $allowed)) abort(403, 'Unauthorized action.');

        //find model
        $data = $model_path::findOrFail($id);

        //update
        $message = $this->validationService->updateModel($request, $data, $model, $file_fields);

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
        $appModel = "App\\EasyAdmin\\" . $model;
        $allowed = $appModel::allowed();

        //check allowed
        $allowed = $appModel::allowed();
        $file_fields = $appModel::files();
        if (!in_array('delete', $allowed)) abort(403, 'Unauthorized action.');

        //find model
        $data = $model_path::findOrFail($id);

        //do not allow user to delete themselves
        if ($model === 'User' && $data == Auth::user())
            return redirect()->back()->with('message', 'Unauthorized action, cannot delete the currently authenticated user.');

        //delete model
        $message = $this->validationService->deleteModel($data, $model, $file_fields);

        //return redirect
        return redirect('/easy-admin/'. $url_model .'/index')
            ->with('message', $message);
    }

}

