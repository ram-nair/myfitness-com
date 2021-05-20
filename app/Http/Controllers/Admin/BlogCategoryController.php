<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\Store;
use App\Traits\ImageTraits;
use App\VBCategory;
use App\VlogBlog;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;

class BlogCategoryController extends BaseController
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
        $this->setPageTitle('Blog Category', 'Blog Category');
        return view('blog.category.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $category = VBCategory::select('*');
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
                if ($currentUser->hasPermissionTo('blogcategory_update')) {
                    $editUrl = \URL::route('admin.blog-category.edit', $category->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('blogcategory_delete')) {
                    $deleteUrl = \URL::route('admin.blog-category.destroy', $category->id);
                    $count = VlogBlog::where('category_id', $category->id)->count();
                    if ($count >= 1) {
                        $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs no-delete"><i class="fa fa-trash"></i></a>';
                    } else {
                        $b .= ' <a href="' . $deleteUrl . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                    }
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
        $this->setPageTitle('Blog Categories', 'Create Blog Category');
        return view('blog.category.create');
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
            $category = VBCategory::create([
                'name' => $request->name,
                'status' => $request->status,
                'by_user_id' => $currentUser->id
            ]);

            alert()->success('Blog Category successfully added.', 'Added');
            return redirect()->route('admin.blog-category.index');
        } catch (\Exception $e) {
            alert()->error('Unable to add blog category.', 'Added');
            return redirect()->route('admin.blog-category.index');
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
        return redirect('blog-category');
    }

    public function destroy($id)
    {
        try {
            $category = VBCategory::where('id', $id)->delete();
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
    public function edit(VBCategory $blog_category)
    {
        try {
            $blogCategory = VBCategory::find($blog_category->id);
            $this->setPageTitle('Blog Categories', 'Edit : ' . $blog_category->name);

            return view('blog.category.edit', compact('blogCategory'));
        } catch (\Exception $e) {
            alert()->error('Unable to update blog category.', 'Updated');
            return redirect()->route('admin.blog-category.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VBCategory $blog_category)
    {
        try {
            $blogCategory = VBCategory::find($blog_category->id);
            $this->validate($request, [
                'name' => 'required|max:120',
            ]);
            $input = $request->all();

            $blogCategory->fill($input)->save();

            alert()->success('Blog Category successfully updated.', 'Updated');
            return redirect()->route('admin.blog-category.index');
        } catch (\Exception $e) {
            alert()->error('Unable to update blog category.', 'Updated');
            return redirect()->route('admin.blog-category.index');
        }

    }
}
