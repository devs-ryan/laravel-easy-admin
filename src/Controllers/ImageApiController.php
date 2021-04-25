<?php
namespace DevsRyan\LaravelEasyAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use Auth;
use Exception;

class ImageApiController extends Controller
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
    protected $fileService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->helperService = new HelperService;
        $this->fileService = new FileService;

        //EasyAdmin ImageApi Middleware
        $this->middleware(function ($request, $next) {
            $key = (substr(env('APP_KEY'), 0, 7) === "base64:") ? substr(env('APP_KEY'), 7) : env('APP_KEY');

            if ($request->has('token') && $key === $request->token) { // token needs to be URL encoded
                return $next($request);
            }
            return abort(403, 'You are not authorized to perform this action');
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return 'todo';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return 'todo';
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return 'todo';
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return 'todo';
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return 'todo';
    }

}

