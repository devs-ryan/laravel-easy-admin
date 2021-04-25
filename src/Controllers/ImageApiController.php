<?php
namespace DevsRyan\LaravelEasyAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use DevsRyan\LaravelEasyAdmin\Services\HelperService;
use DevsRyan\LaravelEasyAdmin\Services\FileService;
use Illuminate\Support\Facades\DB;
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
            }return $next($request);
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
        $query = DB::table('easy_admin_images');

        // model filter
        if ($request->has('model_filter')) {
            $filter = $request->model_filter;
            $model = $filter != 'all' ? $filter : null;
            if ($model) $query = $query->where('model', $model);
        }

        // date filter
        if ($request->has('date_filter')) {
            $filter = $request->date_filter;
            $date = $filter != 'all' ? $filter : null;
            if ($date) {
                $pieces = explode('|', $date);
                $month = $pieces[0]; $year = $pieces[1];
                $query = $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }
        }

        if ($request->has('search')) {
            // TODO
        }

        return response()->json($query->paginate(24), 200);
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
        $record = DB::table('easy_admin_images')->where('id', $id)->first();
        if (!$record) return response()->json('Image record not found', 404);
        return response()->json($record, 200);
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
        $validated = $request->validate([
            'alt' => 'nullable|max:255',
            'title' => 'nullable|max:255',
            'description' => 'nullable|max:4000'
        ]);

        $record = DB::table('easy_admin_images')->where('id', $id)->get();
        if (!count($record)) return response()->json('Image record not found', 404);

        DB::table('easy_admin_images')->where('id', $id)->update([
            'alt' => $validated['alt'],
            'title' => $validated['title'],
            'description' => $validated['description']
        ]);

        return response()->json('Record updated successfully', 200);
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

