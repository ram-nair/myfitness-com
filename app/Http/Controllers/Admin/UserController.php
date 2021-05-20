<?php
namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\BaseController;
use URL;
use App\User;
//use App\Models\Withdraw;
//use App\Models\Currency;
use Illuminate\Support\Facades\Input;
use Validator;

class UserController extends Controller
{
	public function __construct()
	    {
	        $this->middleware('auth:admin');
	    }

	    //*** JSON Request
	    public function datatables()
	    {
	         $datas = User::orderBy('id')->get();
	         //--- Integrating This Collection Into Datatables
             return Datatables::of($datas)
                               ->rawColumns(['actions'])
                               ->editColumn('created_at', function ($datas) {
                                   return $datas->created_at->format('F d, Y h:ia');
                                })
	                            ->editColumn('actions', function(User $data) {
                                    $b = '';
                                    $class = $data->ban == 0 ? 'drop-success' : 'drop-danger';
                                    $s = $data->ban == 1 ? 'selected' : '';
                                    $ns = $data->ban == 0 ? 'selected' : '';
                                   /* $ban = '<select class="process select droplinks '.$class.'">'.
                '<option data-val="0" value="'.  URL::route('admin.users.ban',['id1' => $data->id, 'id2' => 1]).'" '.$s.'>Block</option>'.
                '<option data-val="1" value="'.  URL::route('admin.users.ban',['id1' => $data->id, 'id2' => 0]).'" '.$ns.'>UnBlock</option></select>';*/
                           $b .= '<a href="' . URL::route('admin.users.edit', $data->id) . '" class="btn btn-outline-primary btn-xs"><i class="fa fa-edit"></i></a>';
                           $b .= ' <a href="' . URL::route('admin.users.show', $data->id) . '" class="btn btn-outline-danger btn-xs"><i class="fa fa-eye"></i></a>';
                           $b .= ' <a href="' . URL::route('admin.users.destroy', $data->id) . '" class="btn btn-outline-danger btn-xs destroy"><i class="fa fa-trash"></i></a>';
                           return $b;
                                /*    return '<div class="action-list"><a href="' . route('admin.users.show',$data->id) . '" > 
                                    <i class="fas fa-eye"></i></a><a data-href="' . 
                                    route('admin.users.edit',$data->id) . '" 
                                    class="edit" data-toggle="modal" data-target="#modal1">
                                     <i class="fas fa-edit"></i></a><a href="javascript:;" class="send"
                                      data-email="'. $data->email .'" data-toggle="modal" 
                                      data-target="#vendorform"><i class="fas fa-envelope"></i></a>'
                                      .$ban.'<a href="javascript:;" data-href="' .
                                       route('admin.users.destroy',$data->id) . '"
                                        data-toggle="modal" data-target="#confirm-delete" 
                                        class="delete"><i class="fas fa-trash-alt"></i></a></div>';*/
                                })->make(true);
                                //--- Returning Json Data To Client Side
	    }

	    //*** GET Request
	    public function index()
	    {
	        return view('admin.user.index');
        }
        

        public function create()
        {
            return view('admin.user.create');
        }
    
       
    
        
        public function store(Request $request)
        {
            $this->validate($request, [
                'first_name' => 'required|max:120',
                'email' => 'required|email|unique:users',
                'phone' => 'required|max:10',
            ]);
            //--- Validation Section Ends
    
                //$gs = Generalsetting::findOrFail(1);
                $user = new User;
                $input = $request->all();        
               
                $token = md5(time().$request->name.$request->email);
                $input['verification_link'] = $token;
                $input['affilate_code'] = md5($request->first_name.$request->email);
                $input['status'] =1;
               
               // $input['invoice_address'] =$request->invoice_address;
               
               
                $user->fill($input)->save();
    
                 /* if(!empty($request->vendor))
                  {
                    $user->is_vendor = 1;
                    $user->update();
                  }*/
    
              /*  if($gs->is_verification_email == 0)
                {
                    $user->email_verified = 'Yes';
                    $user->update();
                    $notification = new Notification;
                    $notification->user_id = $user->id;
                    $notification->save();
                    $msg = 'New Data Added Successfully.';
                    return response()->json($msg);    
                    
                }
                else {
                $to = $request->email;
                $subject = 'Verify your email address.';
                $msg = "Dear Customer,Welcome <br> 
                Your user name and password is : <br> 
                user name: ".$request->email."<br/>
                password: ".$request->phone."<br/>
                We noticed that you need to verify your email address. <a href=".url('user/register/verify/'.$token).">Simply click here to verify. </a>";
                //Sending Email To Customer
                if($gs->is_smtp == 1)
                {
                $data = [
                    'to' => $to,
                    'subject' => $subject,
                    'body' => $msg,
                ];
    
                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);
                }
                else
                {
                $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
                mail($to,$subject,$msg,$headers);
                }
                return response()->json('We need to verify your email address. We have sent an email to '.$to.' to verify your email address. Please click link in that email to continue.');
                }  */ 
            //--- Redirect Section Ends    

            alert()->success('Customer successfully added.', 'Added');
        return redirect()->route('admin.users.index');
        }
    


	    //*** GET Request
	    public function show($id)
	    {
	        $data = User::findOrFail($id);
	        return view('admin.user.show',compact('data'));
	    }

        //*** GET Request
        public function ban($id1,$id2)
        {
            $user = User::findOrFail($id1);
            $user->ban = $id2;
            $user->update();

        }

	    //*** GET Request    
	    public function edit($id)
	    {
	        $user = User::findOrFail($id);
	        return view('admin.user.edit',compact('user'));
	    }

	    //*** POST Request
	    public function update(Request $request, $id)
	    {
	       

	        $user = User::findOrFail($id);
	      
            $this->validate($request, [
                'first_name' => 'required|max:120',
                'email' => 'required|email|unique:users,email,' . $id,
               
            ]);
            $input = $request->all();    
           if ($file = $request->file('photo')){
                    $name = time().$file->getClientOriginalName();
                    $file->move('assets/images/users',$name);
                    if($user->photo != null)
                    {
                        if (file_exists(public_path().'/assets/images/users/'.$user->photo)) {
                            unlink(public_path().'/assets/images/users/'.$user->photo);
                        }
                    }
                    $input['photo'] = $name;
	        }
	        $user->fill($input)->save();
	        alert()->success('Customer Information Updated Successfully..', 'Updated');
        return redirect()->route('admin.users.index'); 
	    }

	    //*** GET Request Delete
		public function destroy($id)
		{
		$user = User::findOrFail($id);

        if($user->ratings->count() > 0)
        {
            foreach ($user->ratings as $gal) {
                $gal->delete();
            }
        }

        if($user->notifications->count() > 0)
        {
            foreach ($user->notifications as $gal) {
                $gal->delete();
            }
        }

        if($user->wishlists->count() > 0)
        {
            foreach ($user->wishlists as $gal) {
                $gal->delete();
            }
        }

        if($user->withdraws->count() > 0)
        {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }

        if($user->socialProviders->count() > 0)
        {
            foreach ($user->socialProviders as $gal) {
                $gal->delete();
            }
        }

        if($user->conversations->count() > 0)
        {
            foreach ($user->conversations as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
            if($gal->notifications->count() > 0)
            {
                foreach ($gal->notifications as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }
        if($user->comments->count() > 0)
        {
            foreach ($user->comments as $gal) {
            if($gal->replies->count() > 0)
            {
                foreach ($gal->replies as $key) {
                    if($key->subreplies->count() > 0)
                    {
                        foreach ($key->subreplies as $key1) {
                            $key1->delete();
                        }
                    }
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }

            if($user->replies->count() > 0)
            {
                foreach ($user->replies as $gal) {
                    if($gal->subreplies->count() > 0)
                    {
                        foreach ($gal->subreplies as $key) {
                            $key->delete();
                        }
                    }
                    $gal->delete();
                }
            }


        if($user->favorites->count() > 0)
        {
            foreach ($user->favorites as $gal) {
                $gal->delete();
            }
        }


        if($user->subscribes->count() > 0)
        {
            foreach ($user->subscribes as $gal) {
                $gal->delete();
            }
        }

        if($user->services->count() > 0)
        {
            foreach ($user->services as $gal) {
                if (file_exists(public_path().'/assets/images/services/'.$gal->photo)) {
                    unlink(public_path().'/assets/images/services/'.$gal->photo);
                }
                $gal->delete();
            }
        }


        if($user->withdraws->count() > 0)
        {
            foreach ($user->withdraws as $gal) {
                $gal->delete();
            }
        }


        if($user->products->count() > 0)
        {

// PRODUCT

            foreach ($user->products as $prod) {
                if($prod->galleries->count() > 0)
                {
                    foreach ($prod->galleries as $gal) {
                            if (file_exists(public_path().'/assets/images/galleries/'.$gal->photo)) {
                                unlink(public_path().'/assets/images/galleries/'.$gal->photo);
                            }
                        $gal->delete();
                    }

                }
                if($prod->ratings->count() > 0)
                {
                    foreach ($prod->ratings as $gal) {
                        $gal->delete();
                    }
                }
                if($prod->wishlists->count() > 0)
                {
                    foreach ($prod->wishlists as $gal) {
                        $gal->delete();
                    }
                }

		        if($prod->clicks->count() > 0)
		        {
		            foreach ($prod->clicks as $gal) {
		                $gal->delete();
		            }
		        }

                if($prod->comments->count() > 0)
                {
                    foreach ($prod->comments as $gal) {
                    if($gal->replies->count() > 0)
                    {
                        foreach ($gal->replies as $key) {
                            $key->delete();
                        }
                    }
                        $gal->delete();
                    }
                }

		        if (file_exists(public_path().'/assets/images/products/'.$prod->photo)) {
		            unlink(public_path().'/assets/images/products/'.$prod->photo);
		        }

                $prod->delete();
            }


// PRODUCT ENDS

        }
// OTHER SECTION 



        if($user->senders->count() > 0)
        {
            foreach ($user->senders as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }


        if($user->recievers->count() > 0)
        {
            foreach ($user->recievers as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }


        if($user->conversations->count() > 0)
        {
            foreach ($user->conversations as $gal) {
            if($gal->messages->count() > 0)
            {
                foreach ($gal->messages as $key) {
                    $key->delete();
                }
            }
                $gal->delete();
            }
        }


        if($user->vendororders->count() > 0)
        {
            foreach ($user->vendororders as $gal) {
                $gal->delete();
            }
        }

        if($user->notivications->count() > 0)
        {
            foreach ($user->notivications as $gal) {
                $gal->delete();
            }
        }


// OTHER SECTION ENDS


		    //If Photo Doesn't Exist
		    if($user->photo == null){
		        $user->delete();
			    //--- Redirect Section     
			    $msg = 'Data Deleted Successfully.';
			    return response()->json($msg);      
			    //--- Redirect Section Ends 
		    }
		    //If Photo Exist
		    if (file_exists(public_path().'/assets/images/users/'.$user->photo)) {
		            unlink(public_path().'/assets/images/users/'.$user->photo);
		         }
		    $user->delete();
		    //--- Redirect Section     
		    $msg = 'Data Deleted Successfully.';
		    return response()->json($msg);      
		    //--- Redirect Section Ends    
		}

	    //*** JSON Request
	    public function withdrawdatatables()
	    {
	         $datas = Withdraw::where('type','=','user')->orderBy('id','desc')->get();
	         //--- Integrating This Collection Into Datatables
	         return Datatables::of($datas)
	                            ->addColumn('email', function(Withdraw $data) {
	                            	$email = $data->user->email;
	                            	return $email;
	                            }) 
	                            ->addColumn('phone', function(Withdraw $data) {
	                            	$phone = $data->user->phone;
	                            	return $phone;
	                            }) 
	                            ->editColumn('status', function(Withdraw $data) {
	                            	$status = ucfirst($data->status);
	                            	return $status;
	                            }) 
	                            ->editColumn('amount', function(Withdraw $data) {
	                            	$sign = Currency::where('is_default','=',1)->first();
	                            	$amount = $sign->sign.round($data->amount * $sign->value , 2);
	                            	return $amount;
	                            }) 
	                            ->addColumn('action', function(Withdraw $data) {
	                            	$action = '<div class="action-list"><a data-href="' . route('admin-withdraw-show',$data->id) . '" class="view details-width" data-toggle="modal" data-target="#modal1"> <i class="fas fa-eye"></i> Details</a>';
	                            	if($data->status == "pending") {
	                            	$action .= '<a data-href="' . route('admin-withdraw-accept',$data->id) . '" data-toggle="modal" data-target="#confirm-delete"> <i class="fas fa-check"></i> Accept</a><a data-href="' . route('admin-withdraw-reject',$data->id) . '" data-toggle="modal" data-target="#confirm-delete1"> <i class="fas fa-trash-alt"></i> Reject</a>';
	                            	}
	                            	$action .= '</div>';
	                                return $action;
	                            }) 
	                            ->rawColumns(['name','action'])
	                            ->toJson(); //--- Returning Json Data To Client Side
	    }

	    //*** GET Request
	    public function withdraws()
	    {
	        return view('admin.user.withdraws');
	    }

	    //*** GET Request	    
	    public function withdrawdetails($id)
	    {
	    	$sign = Currency::where('is_default','=',1)->first();
	        $withdraw = Withdraw::findOrFail($id);
	        return view('admin.user.withdraw-details',compact('withdraw','sign'));
	    }

	    //*** GET Request	
	    public function accept($id)
	    {
	        $withdraw = Withdraw::findOrFail($id);
	        $data['status'] = "completed";
	        $withdraw->update($data);
		    //--- Redirect Section     
		    $msg = 'Withdraw Accepted Successfully.';
		    return response()->json($msg);      
		    //--- Redirect Section Ends   
	    }

	    //*** GET Request	
	    public function reject($id)
	    {
	        $withdraw = Withdraw::findOrFail($id);
	        $account = User::findOrFail($withdraw->user->id);
	        $account->affilate_income = $account->affilate_income + $withdraw->amount + $withdraw->fee;
	        $account->update();
	        $data['status'] = "rejected";
	        $withdraw->update($data);
		    //--- Redirect Section     
		    $msg = 'Withdraw Rejected Successfully.';
		    return response()->json($msg);      
		    //--- Redirect Section Ends   
        }
        public function profile()
        {
            return view('admin.users.profile', ['user' => Auth::guard('admin')->user()]);
        }

}