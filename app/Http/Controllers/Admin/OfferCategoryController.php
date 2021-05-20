<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\OfferCategory;
use App\Store;
use App\Traits\ImageTraits;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;

class OfferCategoryController extends BaseController
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
        $this->setPageTitle('Offer Category', 'Offer Category');
        return view('offers.category.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $category = OfferCategory::select('*');
        return Datatables::of($category)
            ->rawColumns(['actions', 'status'])
            ->editColumn('status', function ($category) {
                if ($category->status == 1) {
                    return '<span class="badge badge-success">Enabled</span>';
                } else {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
            })
            ->editColumn('actions', function ($category) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('offercategory_update')) {
                    $editUrl = \URL::route('admin.offer-category.edit', $category->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('offercategory_delete')) {
                    $deleteUrl = \URL::route('admin.offer-category.destroy', $category->id);
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
        $this->setPageTitle('Offer Categories', 'Create Offer Category');
        return view('offers.category.create');
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
                'name' => 'required|max:120',
            ]);

            $currentUser = $request->user();
            $category = OfferCategory::create([
                'name' => $request->name,
                'status' => $request->status,
                'by_user_id' => $currentUser->id
            ]);

            alert()->success('Offer Category successfully added.', 'Added');
            return redirect()->route('admin.offer-category.index');
        } catch (\Exception $e) {
            alert()->error('Unable to add offer category.', 'Added');
            return redirect()->route('admin.offer-category.index');
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
        return redirect('offer-category');
    }

    public function destroy($id)
    {
        try {
            $category = OfferCategory::where('id', $id)->delete();
            if ($category) {
                return response()->json(["status" => true, "message" => 'Successfully deleted']);
            } else {
                return response()->json(["status" => false, "message" => 'Category Not Found']);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(OfferCategory $offer_category)
    {
        try {
            $offerCategory = OfferCategory::find($offer_category->id);
            $this->setPageTitle('Offer Categories', 'Edit : ' . $offer_category->name);

            return view('offers.category.edit', compact('offerCategory'));
        } catch (\Exception $e) {
            alert()->error('Unable to update offer category.', 'Updated');
            return redirect()->route('admin.offer-category.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OfferCategory $offer_category)
    {
        try {
            $blogCategory = OfferCategory::find($offer_category->id);
            $this->validate($request, [
                'name' => 'required|max:120',
            ]);
            $input = $request->all();

            $blogCategory->fill($input)->save();

            alert()->success('Offer Category successfully updated.', 'Updated');
            return redirect()->route('admin.offer-category.index');
        } catch (\Exception $e) {
            alert()->error('Unable to update offer category.', 'Updated');
            return redirect()->route('admin.offer-category.index');
        }

    }
}
