<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\EcommerceBanner;
use App\Http\Controllers\Controller;
use App\Store;
use App\Traits\ImageTraits;
use App\VBAuthor;
use App\VlogBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;

class BlogAuthorController extends BaseController
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
        $this->setPageTitle('Class & Vlog Vendor', 'Class & Vlog Vendor');
        return view('blog.author.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $author = VBAuthor::select('*');
        return Datatables::of($author)
            ->rawColumns(['actions', 'status'])
            ->editColumn('status', function ($author) {
                if ($author->status == 1) {
                    return '<span class="badge badge-success">Enabled</span>';
                } else {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
            })
            ->editColumn('actions', function ($author) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('blogauthor_update')) {
                    $editUrl = \URL::route('admin.blog-author.edit', $author->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                if ($currentUser->hasPermissionTo('blogauthor_delete')) {
                    $deleteUrl = \URL::route('admin.blog-author.destroy', $author->id);
                    $count = VlogBlog::where('author_id', $author->id)->count();
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
        //Get all roles and pass it to the view
        $this->setPageTitle('Class & Vlog Vendor', 'Create Class & Vlog Vendor');
        $imageSize = config('globalconstants.imageSize')['blogAuthor'];
        return view('blog.author.create', compact('imageSize'));
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
                'vendor_name' => 'required|max:120',
                'email' => 'required',
                'phone_number' => 'required'
            ]);

            $currentUser = $request->user();
            $imgPath = "";
            if ($request->hasFile('profile_image')) {
                $imageSize = config('globalconstants.imageSize')['blogAuthor'];
                $request->profile_image = $this->singleImage($request->file('profile_image'), $imageSize['path'], 'blogAuthor');
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['blogAuthor'];
                $request->cover_image = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'blogAuthor');
            }
            $banner = VBAuthor::create([
                'vendor_name' => $request->vendor_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'profile_image' => $request->profile_image,
                'cover_image' => $request->cover_image,
                'description' => $request->description,
                'status' => $request->status,
                'by_user_id' => $currentUser->id,
            ]);

            //Redirect to the users.index view and display message
            alert()->success('Blog Author successfully added.', 'Added');
            return redirect()->route('admin.blog-author.index');
        } catch (\Exception $e) {
            alert()->error('Unable to add blog author.', 'Added');
            return redirect()->route('admin.blog-author.index');
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
        return redirect('blog-author');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VBAuthor $blog_author)
    {
        try {
            $this->setPageTitle('Class & Vlog Vendor', 'Update Class & Vlog Vendor');
            $blogAuthor = VBAuthor::find($blog_author->id);
            $imageSize = config('globalconstants.imageSize')['blogAuthor'];
            return view('blog.author.edit', compact('blogAuthor', 'imageSize')); //pass user and roles data to view
        } catch (\Exception $e) {
            alert()->error('Unable to update blog author.', 'Updated');
            return redirect()->route('admin.blog-author.index');
        }
    }

    public function destroy($id)
    {
        try {
            $category = VBAuthor::where('id', $id)->delete();
            if ($category) {
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
    public function update(Request $request, VBAuthor $blog_author)
    {
        try {
            $blogAuthor = VBAuthor::find($blog_author->id);
            $this->validate($request, [
                'vendor_name' => 'required|max:120',
                'email' => 'required',
                'phone_number' => 'required'
            ]);
            $input = $request->all();

            if ($request->hasFile('profile_image')) {
                $imageSize = config('globalconstants.imageSize')['blogAuthor'];
                $input['profile_image'] = $this->singleImage($request->file('profile_image'), $imageSize['path'], 'blogAuthor');
                if (!empty($input['profile_image'])) {
                    $path = config('globalconstants.imageSize.blogAuthor')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $blogAuthor->getAttributes()['profile_image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $blogAuthor->profile_image);
                    }
                }
            }
            if ($request->hasFile('cover_image')) {
                $imageSize = config('globalconstants.imageSize')['blogAuthor'];
                $input['cover_image'] = $this->singleImage($request->file('cover_image'), $imageSize['path'], 'blogAuthor');
                if (!empty($input['cover_image'])) {
                    $path = config('globalconstants.imageSize.blogAuthor')['path'] . '/';
                    if (!env('CDN_ENABLED', false)) {
                        \Storage::delete($path . $blogAuthor->getAttributes()['cover_image']);
                    } else {
                        \Storage::disk('s3')->delete(env('CDN_FILE_DIR', 'dev/upl/') . $path . $blogAuthor->cover_image);
                    }
                }
            }
            $input['vendor_name'] = $request->vendor_name;
            $input['email'] = $request->email;
            $input['phone_number'] = $request->phone_number;
            $input['description'] = $request->description;
            $input['status'] = $request->status;
            $input['by_user_id'] = $request->user()->id;
            $blogAuthor->fill($input)->save();

            alert()->success('Blog Author details successfully updated.', 'Updated');
            return redirect()->route('admin.blog-author.index');
        } catch (\Exception $e) {
            alert()->error('Unable to update blog author.', 'Updated');
            return redirect()->route('admin.blog-author.index');
        }
    }
}
