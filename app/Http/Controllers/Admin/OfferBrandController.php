<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\OfferBrand;
use App\OfferCategory;
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
class OfferBrandController extends BaseController
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
        $this->setPageTitle('Merchant Detail', 'Merchant Detail');
        return view('offers.brand.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $brands = OfferBrand::select('*');
        return Datatables::of($brands)
            ->rawColumns(['actions', 'status'])
            ->editColumn('status', function ($brands) {
                if ($brands->status == 1) {
                    return '<span class="badge badge-success">Enabled</span>';
                } else {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
            })
            ->editColumn('actions', function ($brands) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('offerbrand_update')) {
                    $editUrl = \URL::route('admin.offer-brand.edit', $brands->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('offerbrand_delete')) {
                    $deleteUrl = \URL::route('admin.offer-brand.destroy', $brands->id);
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
        $this->setPageTitle('Merchant Detail', 'Create Merchant');
        $imageSize = config('globalconstants.imageSize')['offerBrand'];
        $categories = OfferCategory::where('status',1)->get();
        return view('offers.brand.create', compact('imageSize','categories'));
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
            $validator = Validator::make(request()->all(), [
                'name' => 'required|max:120',
                'category_id' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
                'start_at' => 'required',
                'end_at' => 'required',
                'location' => 'required',
            ]);

            $start = $request->start_at;
            $end = $request->end_at;
            $validator->after(function ($validator) use ($start, $end) {
                if (empty($start)) {
                    $validator->errors()->add("start_at", 'Please select start time');
                }
                if (empty($end)) {
                    $validator->errors()->add("end_at", 'Please select end time');
                }
                if (!empty($start) && !empty($end)) {
                    $startHour = Carbon::parse($start);
                    $endHour = Carbon::parse($end);
                    if ($startHour->addMinute()->gt($endHour)) {
                        $validator->errors()->add("end_at", 'End time should be greater than start time.');
                    }
                }
            });
            $validator->validate();

            $currentUser = $request->user();
            $imgPath = "";
            if ($request->hasFile('image')) {
                $imageSize = config('globalconstants.imageSize')['offerBrand'];
                $request->image = $this->singleImage($request->file('image'), $imageSize['path'], 'offerBrand');
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['offerBrand'];
                $request->cover_image = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'offerBrand');
            }
            $brand = OfferBrand::create([
                'name' => $request->name,
                'category_id' => $request->category_id,
                'phone_number' => $request->phone_number,
                'email' => $request->email,
                'working_hour' => $request->start_at . "-" . $request->end_at,
                'working_start_hour' => $request->start_at,
                'working_end_hour' => $request->end_at,
                'image' => $request->image,
                'cover_image' => $request->cover_image,
                'description' => $request->description,
                'location' => $request->location,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'status' => $request->status,
                'by_user_id' => $currentUser->id,
            ]);

            //Redirect to the users.index view and display message
            alert()->success('Brand detail successfully added.', 'Added');
            return redirect()->route('admin.offer-brand.index');
        } catch (\Exception $e) {
            alert()->error('Unable to add brand detail.', 'Added');
            return redirect()->route('admin.offer-brand.index');
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
        return redirect('offer-brand');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OfferBrand $offer_brand)
    {
        try {
            $this->setPageTitle('Merchant Detail', 'Update Merchant Detail');
            $offerBrand = OfferBrand::find($offer_brand->id);
            $imageSize = config('globalconstants.imageSize')['offerBrand'];
            $categories = OfferCategory::where('status',1)->get();
            return view('offers.brand.edit', compact('offerBrand', 'imageSize','categories')); //pass user and roles data to view
        } catch (\Exception $e) {
            alert()->error('Unable to update brand detail.', 'Updated');
            return redirect()->route('admin.offer-brand.index');
        }
    }

    public function destroy($id)
    {
        try {
            $brand = OfferBrand::where('id', $id)->delete();
            if ($brand) {
                return response()->json(["status" => true, "message" => 'Successfully deleted']);
            } else {
                return response()->json(["status" => false, "message" => 'Author Not Found']);
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
    public function update(Request $request, OfferBrand $offer_brand)
    {
        try {

            $offerBrand = OfferBrand::find($offer_brand->id);
            $validator = Validator::make(request()->all(), [
                'name' => 'required|max:120',
                'category_id' => 'required',
                'phone_number' => 'required',
                'email' => 'required',
                'start_at' => 'required',
                'end_at' => 'required',
                'location' => 'required',
            ]);
            $input = $request->all();

            $start = $request->start_at;
            $end = $request->end_at;
            $validator->after(function ($validator) use ($start, $end) {

                if (!empty($start) && !empty($end)) {
                    $startHour = Carbon::parse($start);
                    $endHour = Carbon::parse($end);
                    if ($startHour->addMinute()->gt($endHour)) {
                        $validator->errors()->add("end_at", 'End time should be greater than start time.');
                    }
                }
            });
            $validator->validate();

            if ($request->hasFile('image')) {
                $imageSize = config('globalconstants.imageSize')['offerBrand'];
                $input['image'] = $this->singleImage($request->file('image'), $imageSize['path'], 'offerBrand');
                if (!empty($input['image'])) {
                    $path = config('globalconstants.imageSize.offerBrand')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $offerBrand->getAttributes()['image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $offerBrand->image);
                    }
                }
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['offerBrand'];
                $input['cover_image'] = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'offerBrand');
                if (!empty($input['cover_image'])) {
                    $path = config('globalconstants.imageSize.offerBrand')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $offerBrand->getAttributes()['cover_image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $offerBrand->cover_image);
                    }
                }
            }
            $input['name'] = $request->name;
            $input['category_id'] = $request->category_id;
            $input['phone_number'] = $request->phone_number;
            $input['email'] = $request->email;
            $input['working_hour'] = $request->start_at . "-" . $request->end_at;
            $input['working_start_hour'] = $request->start_at;
            $input['working_end_hour'] = $request->end_at;
            $input['location'] = $request->location;
            $input['latitude'] = $request->latitude;
            $input['longitude'] = $request->longitude;
            $input['description'] = $request->description;
            $input['status'] = $request->status;
            $input['by_user_id'] = $request->user()->id;
            $offerBrand->fill($input)->save();

            alert()->success('Brand details successfully updated.', 'Updated');
            return redirect()->route('admin.offer-brand.index');
        } catch (\Exception $e) {
            Log::error('update brand', ['Exception' => $e->getMessage()]);
            alert()->error('Unable to update brand detail.', 'Updated');
            return redirect()->route('admin.offer-brand.index');
        }
    }
}
