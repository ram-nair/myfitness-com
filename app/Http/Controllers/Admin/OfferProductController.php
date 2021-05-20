<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Brand;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\OfferBrand;
use App\Offers;
use App\Store;
use App\Traits\ImageTraits;
use App\VBAuthor;
use App\VlogBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class OfferProductController extends BaseController
{
    use ImageTraits;

    public function __construct()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->setPageTitle('Offer Detail', 'Offer Detail');
        return view('offers.offer.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $offers = Offers::with('brandDetail')->select('*');
        return Datatables::of($offers)
            ->rawColumns(['actions', 'status'])
            ->editColumn('status', function ($offers) {
                if ($offers->status == 1) {
                    return '<span class="badge badge-success">Enabled</span>';
                } else {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
            })
            ->editColumn('redeem_text', function ($blog) {
                return Str::limit(strip_tags($blog->redeem_text) . "\n", $limit = 20, $end = '...');
            })
            ->editColumn('actions', function ($offers) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('offer_update')) {
                    $editUrl = \URL::route('admin.offers.edit', $offers->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('offer_delete')) {
                    $deleteUrl = \URL::route('admin.offers.destroy', $offers->id);
                    $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';

                }
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
        $this->setPageTitle('Offer Detail', 'Create Offer');
        $imageSize = config('globalconstants.imageSize')['offers'];
        //$purchase_validity = [];
//        for ($i = 1; $i <= 30; $i++) {
//            $purchase_validity[] = $i;
//        }
        $brands = OfferBrand::where('status',1)->get();

        return view('offers.offer.create', compact('imageSize','brands'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        try {
            $this->validate($request, [
                'brand_id' => 'required',
                'title' => 'required',
                'redeem_text' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            $currentUser = $request->user();
            $imgPath = "";

            if ($request->hasFile('image')) {
                $imageSize = config('globalconstants.imageSize')['offers'];
                $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'offers');
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['offers'];
                $request->cover_image = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'offers');
            }
            $brand = Offers::create([
                'brand_id' => $request->brand_id,
                'title' => $request->title,
                'description' => $request->description,
                'image' => $request->image,
                'cover_image' => $request->cover_image,
                'redeem_text' => $request->redeem_text,
                'start_date' => date('Y-m-d',strtotime($request->start_date)),
                'end_date' => date('Y-m-d',strtotime($request->end_date)),
                'status' => $request->status,
                'by_user_id' => $currentUser->id,
            ]);

            //Redirect to the users.index view and display message
            alert()->success('Offer detail successfully added.', 'Added');
            return redirect()->route('admin.offers.index');
        } catch (\Exception $e) {
            Log::error('add brand', ['Exception' => $e->getMessage()]);
            alert()->error('Unable to add Offer detail.', 'Added');
            return redirect()->route('admin.offers.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('offers');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Offers $offers,$id)
    {
        try {

            $this->setPageTitle('Offer Detail', 'Update Offer Detail');
            $offer = Offers::find($id);
            $imageSize = config('globalconstants.imageSize')['offers'];
//            $purchase_validity = [];
//            for ($i = 1; $i <= 30; $i++) {
//                $purchase_validity[] = $i;
//            }
            $brands = OfferBrand::where('status',1)->get();

            return view('offers.offer.edit', compact('offer', 'imageSize','brands')); //pass user and roles data to view
        } catch (\Exception $e) {
            alert()->error('Unable to update brand detail.', 'Updated');
            return redirect()->route('admin.offer-brand.index');
        }
    }

    public function destroy($id)
    {
        try {
            $offer = Offers::where('id', $id)->delete();
            if ($offer) {
                return response()->json(["status" => true, "message" => 'Successfully deleted']);
            } else {
                return response()->json(["status" => false, "message" => 'Offer Not Found']);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offers $offers)
    {
        try {

            $offer = Offers::find($request->id);
            $this->validate($request, [
                'brand_id' => 'required',
                'title' => 'required',
                'redeem_text' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

            $input = $request->all();

            if ($request->hasFile('image')) {
                $imageSize = config('globalconstants.imageSize')['offers'];
                $input['image'] = $this->singleImage($request->file('image'), $imageSize['path'], 'offers');
                if (!empty($input['image'])) {
                    $path = config('globalconstants.imageSize.offers')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $offer->getAttributes()['image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $offer->image);
                    }
                }
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['offers'];
                $input['cover_image'] = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'offers');
                if (!empty($input['cover_image'])) {
                    $path = config('globalconstants.imageSize.offers')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $offer->getAttributes()['cover_image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $offer->cover_image);
                    }
                }
            }

            $input['title'] = $request->title;
            $input['brand_id'] = $request->brand_id;
            $input['description'] = $request->description;
            $input['redeem_text'] = $request->redeem_text;
            $input['start_date'] = date('Y-m-d',strtotime($request->start_date));
            $input['end_date'] = date('Y-m-d',strtotime($request->end_date));
            $input['status'] = $request->status;
            $input['by_user_id'] = $request->user()->id;
            $offer->fill($input)->save();

            alert()->success('Offer details successfully updated.', 'Updated');
            return redirect()->route('admin.offers.index');
        } catch (\Exception $e) {
            Log::error('update offer', ['Exception' => $e->getMessage()]);
            alert()->error('Unable to update offer detail.', 'Updated');
            return redirect()->route('admin.offers.index');
        }
    }
}
