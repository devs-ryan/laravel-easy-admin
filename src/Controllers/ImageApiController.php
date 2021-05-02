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
     * General storage name
     *
     * @var class
     */
    protected $general_storage_name = 'general_storage';

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
        $image_query = DB::table('easy_admin_images')->orderBy('id', 'desc');
        $dates = DB::table('easy_admin_images')
            ->select(DB::raw('YEAR(created_at) year, MONTH(created_at) month, MONTHNAME(created_at) month_name'))
            ->distinct()
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');

        // model filter
        if ($request->has('model_filter')) {
            $filter = $request->model_filter;
            $model = $filter != 'all' ? $filter : null;
            if ($model) {
                $dates = $dates->where('model', $model);
                $image_query = $image_query->where('model', $model);
            }
        }

        // field filter
        if ($request->has('field_filter')) {
            $filter = $request->field_filter;
            $field = $filter != 'general_storage' ? $filter : null;
            if ($field) {
                $image_query = $image_query->where('field', $field);
                $dates = $dates->where('field', $field);
            }
        }

        // date filter
        if ($request->has('date_filter')) {
            $filter = $request->date_filter;
            $date = $filter != 'all' ? $filter : null;
            if ($date) {
                $pieces = explode('|', $date);
                $month = $pieces[0]; $year = $pieces[1];
                $image_query = $image_query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month);
            }
        }

        // search filter
        if ($request->has('search')) {
            $filter = $request->search;
            $search = $filter != '' ? $filter : null;

            if ($search) {
                $keywords = explode(" ", $search);
                $image_query = $image_query->where(function ($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('title', 'like', '%' . $word . '%');
                        $q->orWhere('alt', 'like', '%' . $word . '%');
                        $q->orWhere('description', 'like', '%' . $word . '%');
                    }
                });
            }
        }

        $data = [
            'image_results' => $image_query->paginate(24),
            'dates' => $dates->get()
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|max:255',
            'img' => 'required|file' //!! if this name changes fileService->storeUploadedFile needs updated
        ]);

        $file = $request->file('img');

        // get file dimensions
        $file_name = $file->getClientOriginalName();
        $dimensions = $this->fileService->getImageDimensions($file);
        $width = $dimensions['width'];
        $height = $dimensions['height'];

        //get file size
        $filesize = $this->fileService->getFileSize($file);
        $field = $request->has('field') ? $request->field : $this->general_storage_name;
        $file_details = $this->fileService->storeUploadedFile($request, null, $request->model, $field, true);

        $date_time = new \DateTime();
        $insert_data = [
            'title' => $file_name,
            'model' => $request->model,
            'field' => $field,
            'file_name' => $file_details['file_name'],
            'file_path' => $file_details['file_path'],
            'width' => $width,
            'height' => $height,
            'size' => $filesize,
            'created_at' => $date_time->format('Y-m-d H:i:s'),
            'updated_at' => $date_time->format('Y-m-d H:i:s')
        ];

        DB::table('easy_admin_images')->insert($insert_data);
        return response()->json('Image record created successfully', 200);
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

        return response()->json('Image record updated successfully', 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $record = DB::table('easy_admin_images')->where('id', $id)->first();
        if (!$record) return response()->json('Image record not found', 404);

        $field = $request->has('field') ? $request->field : $this->general_storage_name;
        $this->fileService->unlinkFile($record->model, $field, $record->file_name);
        DB::table('easy_admin_images')->where('id', $id)->delete();
        return response()->json('Image record deleted successfully', 200);
    }

}

