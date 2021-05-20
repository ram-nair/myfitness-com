<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use App\Store;
use App\Traits\ImageTraits;
use App\VBCategory;
use App\VBImages;
use App\VlogBlog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\BlogCategoryCollection;
use App\Http\Resources\BlogCollection;
use Validator;
class BlogController extends BaseController
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
        $this->setPageTitle('Blog', 'Blog');
        return view('blog.vlog_blog.index');
    }

    public function datatable(Request $request)
    {
        $currentUser = $request->user();
        $blog = VlogBlog::select('*');
        return Datatables::of($blog)
            ->rawColumns(['actions', 'status'])
           
            ->editColumn('category_id', function ($blog) {
                return ($blog->category)?$blog->category->name:'';
            })
            ->editColumn('description', function ($blog) {
                return Str::limit(strip_tags($blog->description) . "\n", $limit = 70, $end = '...');
            })
            ->editColumn('status', function ($blog) {
                if ($blog->status == 1) {
                    return '<span class="badge badge-success">Enabled</span>';
                } else {
                    return '<span class="badge badge-danger">Disabled</span>';
                }
            })
            ->editColumn('actions', function ($blog) use ($currentUser) {
                $b = '';
                if ($currentUser->hasPermissionTo('vlogblog_update')) {
                    $editUrl = \URL::route('admin.blog.edit', $blog->id);
                    $b .= '<a href="' . $editUrl . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                }
                $imageUrl = \URL::to('admin/blog/manage-images/'.$blog->id);
                $b .= ' <a href="'.$imageUrl.'" class="btn btn-outline-danger btn-xs"><i class="fa fa-image"></i></a>';
                if ($currentUser->hasPermissionTo('vlogblog_delete')) {
                    $deleteUrl = \URL::route('admin.blog.destroy', $blog->id);
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
        try {
            //Get all roles and pass it to the view
            $this->setPageTitle('Blog', 'Create Blog');
            $imageSize = config('globalconstants.imageSize')['vlogBlog'];
            $categories = VBCategory::where('status', 1)->get();
            
            return view('blog.vlog_blog.create', compact( 'categories','imageSize'));
        } catch (\Exception $e) {
            alert()->error($e->getMessage(), 'Added');
            return redirect()->route('admin.blog.index');
        }
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
                'category_id' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
            $image ="";
            $currentUser = $request->user();
            if ($request->hasFile('blog_image')) {
                $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                $image = $this->singleImage($request->file('blog_image'), $imageSize['path'], 'vlogBlog');
                
            }
            $banner = VlogBlog::create([
                'category_id' => $request->category_id,
                'author_id' => $currentUser->id,
                'title' => $request->title,
                'description' => $request->description,
                'image' =>$image,
                'status' => $request->status,
                'by_user_id' => $currentUser->id,
            ]);

            //Redirect to the users.index view and display message
            alert()->success('Blog successfully added.', 'Added');
            return redirect()->route('admin.blog.index');
        } catch (\Exception $e) {
            Log::error('store-blog', ['Exception' => $e->getMessage()]);
            alert()->error("Unable to add blog", 'Added');
            return redirect()->route('admin.blog.index');
        }
    }

    public function destroy($id)
    {
        try {
            $category = VlogBlog::where('id', $id)->delete();
            if ($category) {
                return response()->json(["status" => true, "message" => 'Successfully deleted']);
            } else {
                return response()->json(["status" => false, "message" => 'Blog Not Found']);
            }
        } catch (\Exception $e) {
            return response()->json(["status" => false, "message" => $e->getMessage()]);
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
        return redirect('blog');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(VlogBlog $vlog_blog,$id)
    {
        try {
            $this->setPageTitle('Blog', 'Update Blog');
            $vlogBlog = VlogBlog::find($id);
            $imageSize = config('globalconstants.imageSize')['vlogBlog'];
            $categories = VBCategory::where('status', 1)->get();
            return view('blog.vlog_blog.edit', compact('vlogBlog', 'categories','imageSize')); //pass user and roles data to view
        } catch (\Exception $e) {
            alert()->error('Unable to update blog.', 'Updated');
            return redirect()->route('admin.blog.index');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VlogBlog $vlog_blog)
    {

        try {
            $vlogBlog = VlogBlog::find($request->id);
            $this->validate($request, [
                'category_id' => 'required',
                'title' => 'required',
                'description' => 'required',
            ]);
            
            $image = $vlogBlog->image;
            $currentUser = $request->user();
            if ($request->hasFile('blog_image')) {
                $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                $image = $this->singleImage($request->file('blog_image'), $imageSize['path'], 'vlogBlog');
                
            }
            $input = $request->all();
            $input['image'] =$image;
            $input['author_id'] = $request->user()->id;
            $input['by_user_id'] = $request->user()->id;
            $vlogBlog->fill($input)->save();
            alert()->success('Blog details successfully updated.', 'Updated');
            return redirect()->route('admin.blog.index');
        } catch (\Exception $e) {
            alert()->error('Unable to update blog.', 'Updated');
            return redirect()->route('admin.blog.index');
        }
    }
    public function manage_images($blog_id)
    {
        try {
            $imageSize = config('globalconstants.imageSize')['vlogBlog'];
            $this->setPageTitle('Vlog Blog Images', 'Update Vlog Blog Images');
            $vlog_blog = VlogBlog::find($blog_id);
            return view('blog.vlog_blog.manage_images', compact('vlog_blog','imageSize','blog_id'));
        } catch (\Exception $e) {
            alert()->error('Unable to update blog image and video.', 'Updated');
            return redirect()->route('admin.blog.index');
        }
    }
    public function post_images(Request $request)
    {
        try {
            $action_type = $request->action_type;
            $blog_id = $request->blog_id;
            $image = "";
            $cover_image = "";
              if($action_type == "image"){
                  if ($request->hasFile('blog_image')) {
                      $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                      $image = $this->singleImage($request->file('blog_image'), $imageSize['path'], 'vlogBlog');
                      $cover_image = "";
                  }
              }elseif($action_type == "video"){
                  if ($request->hasFile('blog_video')) {
                      $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                      $image = $this->singleImage($request->file('blog_video'), $imageSize['path'], 'vlogBlog');
                  }
                  if ($request->hasFile('blog_video_thumbnail')) {
                      $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                      $cover_image  = $this->singleImage($request->file('blog_video_thumbnail'), $imageSize['path'], 'vlogBlog');
                  }
              }elseif($action_type == "video_url"){
                  $image = $request->video_url;
                  if ($request->hasFile('blog_video_url_thumbnail')) {
                      $imageSize = config('globalconstants.imageSize')['vlogBlog'];
                      $cover_image  = $this->singleImage($request->file('blog_video_url_thumbnail'), $imageSize['path'], 'vlogBlog');
                  }
              }else{
                  alert()->error('Unable to update blog image and video.', 'Updated');
                  return redirect()->back();
              }

            VBImages::create([
                'vb_id' => $blog_id,
                'image' => $image,
                'cover_image' => $cover_image,
                'upload_type' => $action_type
            ]);

            alert()->success('Blog image details successfully updated.', 'Updated');
            return redirect()->back();


        } catch (\Exception $e) {
            Log::error('post_images', ['Exception' => $e->getMessage()]);
            alert()->error('Unable to update blog image and video.', 'Updated');
            return redirect()->back();
        }
    }

    //api
    public function vlogBlog(Request $request)
    {
        try {
            $user = auth('api')->user();
            if(!$user){
                return errorResponse(trans('blog.blog.user_not_found'));
            }
            //categories
            $categories = VBCategory::where('status', 1)->get();

//            // all blogs
//            $all_blogs = VlogBlog::where('status', 1);
//            if($request->category_id){
//                $all_blogs = $all_blogs->where('category_id',$request->category_id);
//            }
//            $all_blogs = $all_blogs->orderBy('created_at','desc')->get();

            //top vlogs
            $top_vlogs = VlogBlog::where('status', 1)->where('blog_type','vlog')->withCount(['getFollowingAuthor as following_count' => function($query){
                $query->select(DB::raw('count(id)'));
            }]);
            if($request->category_id){
                $top_vlogs = $top_vlogs->where('category_id',$request->category_id);
            }
            $top_vlogs = $top_vlogs->orderBy('following_count','desc')->skip(0)->take(2)->get();

            // top blogs
            $top_blogs = VlogBlog::where('status', 1)->where('blog_type','blog')->withCount(['getFollowingAuthor as following_count' => function($query){
                $query->select(DB::raw('count(id)'));
            }]);
            if($request->category_id){
                $top_blogs = $top_blogs->where('category_id',$request->category_id);
            }
            $top_blogs = $top_blogs->orderBy('following_count','desc')->skip(0)->take(2)->get();

            //following author blogs
            $following_author_blogs = VlogBlog::where('status', 1)->whereIn('author_id',function($query) use($user){
                $query->where('user_id',$user->id)->select('author_id')->from('vlog_blog_author_followers');
            })->skip(0)->take(2)->get();



            return [
                'statusCode' => 200,
                'message' => trans('blog.blog.list'),
                'data' => [
                    'categories' => $categories->isEmpty() ? null : new BlogCategoryCollection($categories),
                    //'blogs' => $all_blogs->isEmpty() ? null : new BlogCollection($all_blogs),
                    'following_author_blogs' => $following_author_blogs->isEmpty() ? null : new BlogCollection($following_author_blogs),
                    'top_vlogs' => $top_vlogs->isEmpty() ? null : new BlogCollection($top_vlogs),
                    'top_blogs' => $top_blogs->isEmpty() ? null : new BlogCollection($top_blogs)
                ]
            ];
        }catch(\Exception $e){
            Log::error('vlog-blog', ['Exception' => $e->getMessage()]);
            return errorResponse(trans('blog.blog.something_wrong'));
        }
     }
    public function searchVlogBlog(Request $request)
    {
        try {
            $rules = [
                'keyword' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }

            $user = auth('api')->user();
            if(!$user){
                return errorResponse(trans('blog.blog.user_not_found'));
            }

            //top vlogs
            $top_vlogs = VlogBlog::where('title','LIKE', '%' . $request->keyword . '%')
                ->orWhere(function ($query) use ($request) {
                    $query->whereHas('category', function ($q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->keyword . '%');
                });

            })->where('status', 1)->where('blog_type','vlog');
            $top_vlogs = $top_vlogs->skip(0)->take(2)->get();

            // top blogs
            $top_blogs = VlogBlog::where('title','LIKE', '%' . $request->keyword . '%')
                ->orWhere(function ($query) use ($request) {
                    $query->whereHas('category', function ($q) use ($request) {
                        $q->where('name', 'LIKE', '%' . $request->keyword . '%');
                    });

                })->where('status', 1)->where('blog_type','blog');
            $top_blogs = $top_blogs->skip(0)->take(2)->get();

            return [
                'statusCode' => 200,
                'message' => trans('blog.blog.list'),
                'data' => [
                    'top_vlogs' => $top_vlogs->isEmpty() ? null : new BlogCollection($top_vlogs),
                    'top_blogs' => $top_blogs->isEmpty() ? null : new BlogCollection($top_blogs)
                ]
            ];
        }catch(\Exception $e){
            Log::error('searchVlogBlog', ['Exception' => $e->getMessage()]);
            return errorResponse(trans('blog.blog.something_wrong'));
        }
    }
    public function viewAllvlogBlog(Request $request)
    {
        try {
            $limit = $request->limit ?? 20;
            $rules = [
                'view_all_type' => 'required',
                'keyword' => 'required_if:view_all_type,==,search_vlog,view_all_type,==,search_blog',
                'author_id' => 'required_if:view_all_type,==,latest_vlogs',

            ];
            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }
            $user = auth('api')->user();
            if(!$user){
                return errorResponse(trans('blog.blog.user_not_found'));
            }
            $view_all_data = null;
            if($request->view_all_type == "following_blog"){
                $following_author_blogs = VlogBlog::where('status', 1)->whereIn('author_id',function($query) use($user){
                    $query->where('user_id',$user->id)->select('author_id')->from('vlog_blog_author_followers');
                })->paginate($limit);
                $view_all_paginate_data = $following_author_blogs;
                $view_all_data = $following_author_blogs->isEmpty() ? null : new BlogCollection($following_author_blogs);

            }elseif($request->view_all_type == "top_vlog"){
                $top_vlogs = VlogBlog::where('status', 1)->where('blog_type','vlog')->withCount(['getFollowingAuthor as following_count' => function($query){
                    $query->select(DB::raw('count(id)'));
                }]);
                if($request->category_id){
                    $top_vlogs = $top_vlogs->where('category_id',$request->category_id);
                }
                if($request->author_id){
                    $top_vlogs = $top_vlogs->where('author_id',$request->author_id);
                }
                $top_vlogs = $top_vlogs->orderBy('following_count','desc')->paginate($limit);
                $view_all_paginate_data = $top_vlogs;
                $view_all_data = $top_vlogs->isEmpty() ? null : new BlogCollection($top_vlogs);


            }elseif($request->view_all_type == "top_blog"){
                $top_blogs = VlogBlog::where('status', 1)->where('blog_type','blog')->withCount(['getFollowingAuthor as following_count' => function($query){
                    $query->select(DB::raw('count(id)'));
                }]);
                if($request->category_id){
                    $top_blogs = $top_blogs->where('category_id',$request->category_id);
                }
                $top_blogs = $top_blogs->orderBy('following_count','desc')->paginate($limit);
                $view_all_paginate_data = $top_blogs;
                $view_all_data = $top_blogs->isEmpty() ? null : new BlogCollection($top_blogs);

            }elseif($request->view_all_type == "search_vlog"){

                $top_vlogs = VlogBlog::where('title','LIKE', '%' . $request->keyword . '%')
                    ->orWhere(function ($query) use ($request) {
                        $query->whereHas('category', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->keyword . '%');
                        });

                    })->where('status', 1)->where('blog_type','vlog')->paginate($limit);
                $view_all_paginate_data = $top_vlogs;
                $view_all_data = $top_vlogs->isEmpty() ? null : new BlogCollection($top_vlogs);

            }elseif($request->view_all_type == "search_blog"){
                $top_blogs = VlogBlog::where('title','LIKE', '%' . $request->keyword . '%')
                    ->orWhere(function ($query) use ($request) {
                        $query->whereHas('category', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->keyword . '%');
                        });

                    })->where('status', 1)->where('blog_type','blog')->paginate($limit);
                $view_all_paginate_data = $top_blogs;
                $view_all_data = $top_blogs->isEmpty() ? null : new BlogCollection($top_blogs);

            }elseif($request->view_all_type == "latest_vlogs"){
                $latest_vlogs = VlogBlog::where('status', 1)->where('author_id',$request->author_id)->where('blog_type','vlog')->orderBy('created_at','desc')->paginate($limit);
                $view_all_paginate_data = $latest_vlogs;
                $view_all_data = $latest_vlogs->isEmpty() ? null : new BlogCollection($latest_vlogs);

            }else{
                return errorResponse(trans('blog.blog.valid_view_type'));
            }

            return [
                'statusCode' => 200,
                'message' => trans('blog.blog.list'),
                'data' => [
                    'view_all_data' => $view_all_data
                ],
                "links" => $view_all_paginate_data->isEmpty() ? null : [
                    "first" => $view_all_paginate_data->url(1),
                    "last" => $view_all_paginate_data->url($view_all_paginate_data->lastPage()),
                    "prev" => $view_all_paginate_data->previousPageUrl(),
                    "next" => $view_all_paginate_data->nextPageUrl(),
                ],
                "meta" => $view_all_paginate_data->isEmpty() ? null : [
                    "current_page" => $view_all_paginate_data->currentPage(),
                    "from" => $view_all_paginate_data->firstItem(),
                    "last_page" => $view_all_paginate_data->lastPage(),
                    "path" => null,
                    "per_page" => $view_all_paginate_data->perPage(),
                    "to" => $view_all_paginate_data->firstItem(),
                    "total" => $view_all_paginate_data->total(),
                ],
            ];
        }catch(\Exception $e){
            Log::error('viewAllvlogBlog', ['Exception' => $e->getMessage()]);
            return errorResponse(trans('blog.blog.something_wrong'));
        }
    }
    public function authorFollowing(Request $request)
    {
        try {
            $rules = [
                'author_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }
            $user = auth('api')->user();
            if(!$user){
                return errorResponse(trans('blog.blog.user_not_found'));
            }
            $is_exists_following = VBAuthorFollower::where('author_id',$request->author_id)->where('user_id',$user->id)->exists();
            if($is_exists_following){
                VBAuthorFollower::where('author_id',$request->author_id)->where('user_id',$user->id)->delete();
                $message = trans('blog.blog.un_following_user');
                $is_following = 0;
            }else{
                  VBAuthorFollower::create([
                    'author_id' => $request->author_id,
                    'user_id' => $user->id
                ]);
                $message = trans('blog.blog.following_user');
                $is_following = 1;
            }
            return [
                'statusCode' => 200,
                'message' => $message,
                'data' => [
                    'is_following'=>$is_following
                ]
            ];
        }catch(\Exception $e){
            Log::error('authorFollowing', ['Exception' => $e->getMessage()]);
            return errorResponse(trans('blog.blog.something_wrong'));
        }
    }
    public function authorDetail(Request $request){
        try {
            $rules = [
                'author_id' => 'required',
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $errorMessage = $validator->messages();
                return errorResponse(trans('api.required_fields'), $errorMessage);
            }
            $user = auth('api')->user();
            if(!$user){
                return errorResponse(trans('blog.blog.user_not_found'));
            }
            $author_detail = VBAuthor::where('id',$request->author_id)->first();
            $is_exists_following = VBAuthorFollower::where('author_id',$request->author_id)->where('user_id',$user->id)->exists();

            $author_detail->is_folowing = ($is_exists_following)?1:0;
            //latest vlogs
            $latest_vlogs = VlogBlog::where('status', 1)->where('blog_type','vlog')->where('author_id',$request->author_id)->orderBy('created_at','desc')->skip(0)->take(2)->get();

            //top vlogs
            $top_vlogs = VlogBlog::where('status', 1)->where('blog_type','vlog')->where('author_id',$request->author_id)->withCount(['getFollowingAuthor as following_count' => function($query){
                $query->select(DB::raw('count(id)'));
            }]);
            $top_vlogs = $top_vlogs->orderBy('following_count','desc')->skip(0)->take(2)->get();



            return [
                'statusCode' => 200,
                'message' => trans('blog.author.detail'),
                'data' => [
                    'author_detail'=>$author_detail,
                    'latest_vlog'=>$latest_vlogs->isEmpty() ? null : new BlogCollection($latest_vlogs),
                    'top_vlogs' => $top_vlogs->isEmpty() ? null : new BlogCollection($top_vlogs),
                ]
            ];
        }catch(\Exception $e){
            Log::error('authorDetail', ['Exception' => $e->getMessage()]);
            return errorResponse(trans('blog.blog.something_wrong'));
        }
    }



}
