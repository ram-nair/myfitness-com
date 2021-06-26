<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Gift;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;
use Auth;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class GiftsController extends Controller
{
    use ImageTraits;
    public function __construct()
    {
        $this->authorizeResource(Gift::class, 'gift');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gifts = Gift::all();
        return view('admin.gifts.index', compact('gifts'));
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $gifts = gift::select('*')->orderBy('created_at', 'asc');
        return Datatables::of($gifts)
            ->rawColumns(['actions'])
            ->editColumn('created_at', function ($gifts) {
                return $gifts->created_at->format('F d, Y h:ia');
            })->editColumn('actions', function ($gifts) use ($currentUser) {
                $b = '';
                $b .= '<a href="' . URL::route('admin.gifts.edit', $gifts->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                $b .= ' <a href="' . URL::route('admin.gifts.destroy', $gifts->id) . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                return $b;
            })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Get all roles and pass it to the view
        $imageSize = config('globalconstants.imageSize')['category'];
        return view('admin.gifts.create',compact('imageSize'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'expire_at' => 'required',
            'balance_amt' => 'required',
        ]);
       
        $validator->validate();
        $image ="";
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
              $image = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
                if (!empty($input['image'])) {
                    $path = config('globalconstants.imageSize.category')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $vlog_blog->getAttributes()['image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $image);
                    }
                }
        }

        gift::create([
            'code'=>$this->coupon(10),
            'balance_amt' => $request->balance_amt,
            'expire_at' => $request->expire_at,
            'status' => $request->status,
            'is_redeem' => $request->is_redeem,
            'image'=>$image,
            
        ]);
        alert()->success('gift successfully added.', 'Added');
        return redirect()->route('admin.gifts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('gifts');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(gift $gift)
    {
        $imageSize = config('globalconstants.imageSize')['category'];
        return view('admin.gifts.edit', compact('gift','imageSize'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, gift $gift)
    {
        $validator = Validator::make(request()->all(), [
            'expire_at' => 'required',
            'balance_amt' => 'required',
        ]);

        $image =$gift->image;
        if ($request->hasFile('image')) {
            $imageSize = config('globalconstants.imageSize')['category'];
              $image = $this->singleImage($request->file('image'), $imageSize['path'], 'category');
                if (!empty($input['image'])) {
                    $path = config('globalconstants.imageSize.category')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $vlog_blog->getAttributes()['image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $image);
                    }
                }
        }

        $expire_at = $request->expire_at;
        $validator->validate();
        $gift->balance_amt = $request->balance_amt;
        $gift->expire_at = $expire_at;
        $gift->status = $request->status;
        $gift->is_redeem = $request->is_redeem;
        $gift->image =$image;
        $gift->save();
        alert()->success('gift details successfully updated.', 'Updated');
        return redirect()->route('admin.gifts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id)
    {
        gift::destroy($id);
        return response()->json(["status" => true, "message" => 'Successfully deleted']);
    }

   public function coupon($l){
        $coupon = "MF".substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',$l-2)),0,$l-2);
        return $coupon;
	}

}
