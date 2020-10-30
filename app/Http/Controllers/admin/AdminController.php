<?php
// New controller for Admin profile and change password page of admin

namespace App\Http\Controllers\admin;
use App\Http\Requests;
use App\Http\Controllers\admin\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\Admin;
use App\User;
use App\WarrantyExtension;
use App\Guide;
use Auth;
use Session;
use Hash;
use File;
use App;
use Illuminate\Support\Facades\Storage;
use URL;
use DB;

class AdminController extends Controller {
    
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
      $this->middleware(['admin']);
    }

    public function index() {
      
      $totalUser = User::where('status', '!=', '3')->count();
      $totalWarrantyRequest = WarrantyExtension::whereIn('status',['0','1','2'])->count();
      $totalSelfDiagnosis = Guide::where('guide_type','self-diagnosis')->where('status','!=','3')->count();
      $totalMaintenance = Guide::where('guide_type','maintenance')->where('status','!=','3')->count();

      $extensions = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
         ->select('warranty_extension.*','users.name')
         ->whereIn('warranty_extension.status',['0','1','2'])
         ->limit(5)
         ->groupBy('warranty_extension.unique_key')
         ->orderBy('created_at','desc')
         ->get();

      return view('admin.dashboard',array('totalUser' => $totalUser, 'totalWarrantyRequest' => $totalWarrantyRequest, 'totalSelfDiagnosis' => $totalSelfDiagnosis, 'totalMaintenance' => $totalMaintenance, 'extensions' => $extensions)); 
    }

    public function getChangePass() {
    	return view('admin.changepass',array('title' => 'Change Password'));
    }
	
  	public function changePass(Request $request) {
  		$messages = [
      	'currentpass.required' => 'The Current Password field is required.',
  			'newpass.required' => 'The New Password field is required.',
  			'newpass.min' => 'The New Password must be at least 6 characters.',
  			'newpass.confirmed' => 'The New Password and Confirm Password does not match.',
  			'newpass_confirmation.required' => 'The Confirm Password field is required.',
  		];

      $request->validate([           
        'currentpass' => 'required',
        'newpass' => 'required|min:6|confirmed',
        'newpass_confirmation' => 'required|min:6',
      ], $messages);

  		$userData = Admin::find(Auth::guard('admin')->user()->id);
  		if(!Hash::check($request->get('currentpass'),$userData->password)){
  			$request->session()->flash('alert-danger','Please enter valid current password.');
  			return redirect(route('admin.editprofile'));
  		}

  		$userData->password = Hash::make($request->get('newpass'));
  		if($userData->save()){
  			$request->session()->flash('alert-success','Password changed successfully.');
  		}

  		return redirect(route('admin.editprofile'));
    }
	
  	public function profile() {
      
  		$userData = Admin::find(Auth::guard('admin')->user()->id);
      return view('admin.profile.edit',array('title' => 'Edit Profile','userData' => $userData));
    }
	
  	public function postprofile(Request $request) {
      $request->validate([           
        'name' => 'required|max:255',
        'username' => 'required|max:255',
        'email' => 'required|email|max:255',
      ]);

  		$userData = Admin::find(Auth::guard('admin')->user()->id);
  		$userData->name = $request->name;
  		$userData->username = $request->username;
  		$userData->email = $request->email;

      $file=$request->file('profile_img');
        if($file){
          $request->validate([
              'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
          ]);
          // echo "<pre>";print_r($userData->profile_img);exit();
            if (is_file($userData->profile_img)) { 
                unlink($userData->profile_img);
            }
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $path = 'adminprofile/'.$userData->id.'/'.$imageName.".".$imgext;
            Storage::disk('public')->putFileAs('adminprofile/'.$userData->id,$file,$imageName.".".$imgext);
            
            $userData->profile_img = 'storage/'.$path;
        }else{

            if($request->profile_avatar_remove){
              unlink($userData->profile_img);
              $userData->profile_img = NULL;
            }else{
              unset($userData->profile_img);
            }
        }
        
  		if($userData->save()){
  			$request->session()->flash('alert-success','Profile updated successfully.');
  		}
  		return redirect(route('admin.editprofile'));
    }
}