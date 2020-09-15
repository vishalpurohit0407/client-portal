<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use App\UserDetail;
use App\Dibilrequest;
use App\Document;
use App\Category;
use App\Common;
use App\Pushnotification;
use Response;
use Hash;
use QrCode;
use Auth;
use Storage;

class OrganizationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        return view('admin.organization.list',array('title' => 'Organization List'));
    }

    public function listdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $userdata = User::join('user_details', 'users.id', '=', 'user_details.user_id')->select('users.*','user_details.name','user_details.organization_name','user_details.profile_img')->where('status','!=','3')->where('user_type','organization');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_details.name', 'like', '%'.$keyword.'%')
                      ->orWhere('user_details.organization_name', 'like', '%'.$keyword.'%')
                      ->orWhere('users.email', 'like', '%'.$keyword.'%')
                      ->orWhere('users.mobile', 'like', '%'.$keyword.'%')
                      ->orWhere('users.id', 'like', '%'.$keyword.'%');
            });
        }

        if($field == 'full_name'){
            $field = 'user_details.name';
        }

        $userdata=$userdata->orderBy($field,$sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($userdata);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $userdata->lastPage(),
            'perpage' => $perpage,
            'total' => $userdata->total(),
            'sort' => $sort,
            'field' => $field
        );
        $result['data']=array();

         $pageCal = '';
        $countbase = 1;
        $gePage = $page - 1;
        if($gePage == 0){
            $pageCal=$countbase;
        }else{
           $pageCal= $gePage.$countbase;
        }

        if($userdata){
            $count = $pageCal;
            foreach ($userdata as $key => $organize) {

                $result['data'][] = array(
                    "id" => $organize->id,
                    "number" => $count,
                    "dibil_number"=>$organize->dibil_number,
                    "full_name" => $organize->name,
                    "profile_img" => $organize->user_detail->image,
                    "organization_name" => $organize->organization_name,
                    "email"=> $organize->email,
                    "mobile"=> $organize->mobile,
                    "status"=> $organize->status,
                    "created_at"=> date('d-M-Y', strtotime( $organize->created_at)),
                    "Actions" => null        
                );
                $count++;
            }
        }
        return Response::json($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $organization_type = Category::where('status','1')->get();
        return view('admin.organization.create',array('title' => 'Organization Add','organization_type'=>$organization_type));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'password.required' => 'The MPIN field is required.',
            'password.digits' => 'The MPIN must be only 4 digits.',
        ];
        $request->validate([
            'name' => 'required|max:500',
            'organization_name' => 'required',
            'organization_type' => 'required',
            'mobile' => 'required|numeric|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|digits:4|numeric',
        ],$messages);
        try {
            $saveuser = User::create([
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => $request->status != '' ? '1' : '2' ,
                'user_type' => 'organization',
            ]);
            if ($saveuser) {
                $user_detail = new UserDetail;
                $user_detail->user_id = $saveuser->id;
                $user_detail->name = $request->name;
                $user_detail->organization_name = $request->organization_name;
                $user_detail->organization_type = $request->organization_type;
                $user_detail->birth_date = $request->birth_date ? date("Y-m-d", strtotime($request->birth_date)) : NULL;
                $user_detail->full_address = $request->full_address;
                $user_detail->city = $request->city;
                $user_detail->gendar = $request->gender != '' && $request->gender == 'male' ? 'male' : 'female' ;
                
                $file=$request->file('profile_img');
                if($file){
                    $request->validate([
                        'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                    if ($saveuser->image) { 
                        Common::deleteImage($fileurl = $user_detail->profile_img);
                    }
                    $path = Common::storeImage($file=$file,$type='profile',$user_id=$saveuser->id);
                    $user_detail->profile_img = $path;
                }

                if($user_detail->save())
                {
                    $request->session()->flash('alert-success', 'Organization Added successfuly.');  
                }
            }
            return redirect(route('organization.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.list'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        if (!$user) {
          return abort(404);
        }
        $document = Document::where('user_id',$user->id)->where('status','1')->with('document_media','document_type')->get();
        $shereNo = $document->where('shared_number','!=','0')->count();
        // $shereNo = array();
        // foreach ($document as $key => $value) {
        //     $shereNo[] = $value->shared_number;
        // }
        $receivedDoc = Dibilrequest::join('users', 'users.id','=','dibil_requests.user_id')
        ->join('documents', 'documents.id', '=','dibil_requests.document_id')
        ->select('users.email','dibil_requests.*','documents.document_name')
        ->where('recipient_id',$user->id)
        ->where('dibil_requests.status','1')->count();
        // echo "<pre>";print_r($receivedDoc);exit();
        return view('admin.user.detail',array('title' => 'Organization Detail','userdata'=>$user,'documents'=>'','receivedDoc'=>$receivedDoc,'shereNo'=>$shereNo));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (!$user) {
          return abort(404);
        }
        $organization_type = Category::where('status','1')->get();
        return view('admin.organization.edit',array('title' => 'Organization Edit','userdata'=>$user,'organization_type'=>$organization_type));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(User $user, Request $request)
    {
        // echo "<pre>";print_r($request->all());exit();
        $request->validate([
            'name' => 'required|max:500',
            'organization_name' => 'required',
            'organization_type' => 'required',
            'mobile' => 'required|numeric|unique:users,mobile,'.$user->id,
            'email' => 'required|email|unique:users,email,'.$user->id,  
        ]);
        try {
            if (!$user) {
              return abort(404);
            }
            $user_detail = UserDetail::where('user_id',$user->id)->first();
            if (!$user_detail) {
              return abort(404);
            }
            $user_detail->name = $request->name;
            $user_detail->organization_name = $request->organization_name;
            $user_detail->organization_type = $request->organization_type;
            $user_detail->birth_date = $request->birth_date ? date("Y/m/d", strtotime($request->birth_date)) : NULL;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
            $user->user_type = 'organization';
            $user_detail->full_address = $request->full_address;
            $user_detail->city = $request->city;
            if ($request->password) {
                $messages = [
                    'password.digits' => 'The MPIN must be only 4 digits.',
                ];
                $request->validate([
                    'password' => 'required|digits:4',
                ],$messages);
                $user->password = Hash::make($request->password);
            }
            if ($request->gender != "" && $request->gender == 'male') {
                $user_detail->gendar = $request->gender;
            }else{
                $user_detail->gendar = "female";
            }
            if ($request->status != "") {
                $user->status = $request->status;
            }else{
                $user->status = "2";
            }
            $file=$request->file('profile_img');
            if($file){
                $request->validate([
                    'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
                if ($user->image) { 
                    Common::deleteImage($fileurl = $user_detail->profile_img);
                }
                $path = Common::storeImage($file=$file,$type='profile',$user_id=$user->id);
                $user_detail->profile_img = $path;
            }else{

                if($request->profile_avatar_remove){
                    if ($user->image) { 
                        Common::deleteImage($fileurl = $user_detail->profile_img);
                    }
                  $user_detail->profile_img = NULL;
                }else{
                  unset($user_detail->profile_img);
                }
            }
            if($user->save() && $user_detail->save())
            {
                if ($user->status == '2') {
                    // echo "<pre>";print_r($user->id);exit();
                    $payload = array("conversation_id"=>0,"type"=>"6","badge"=>"1");
                    $inpute = $request->all();
                    $inpute['user_id'] = $user->id;
                    $inpute['title'] = 'account inactive';
                    $inpute['message'] = 'Dear organization your account has been inactive by admin please contact administrator';
                    $inpute['payload'] = $payload;
                    $notification = Pushnotification::create($inpute);
                }
                $request->session()->flash('alert-success', 'Organization updated successfuly.');  
            }
            return redirect(route('organization.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Request $request)
    {
        try {
            if(!$user){
                return abort(404) ;
            }
            $user->status = '3';
            if ($user->save()) {
                $payload = array("conversation_id"=>0,"type"=>"6","badge"=>"1");
                $inpute = $request->all();
                $inpute['user_id'] = $user->id;
                $inpute['title'] = 'Account Has Block';
                $inpute['message'] = 'Dear organization your account has been block by admin please contact administrator';
                $inpute['payload'] = $payload;
                $notification = Pushnotification::create($inpute);
            }
            return redirect(route('organization.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.list'));
        }
    }



    public function deletedUser(){
        return view('admin.organization.deletedlist',array('title' => 'Organization Deleted List'));
    }

    public function deletedUserlistdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $userdata = User::join('user_details', 'users.id', '=', 'user_details.user_id')->select('users.*','user_details.name','user_details.organization_name','user_details.profile_img')->where('status','3')->where('user_type','organization');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_details.name', 'like', '%'.$keyword.'%')
                      ->orWhere('user_details.organization_name', 'like', '%'.$keyword.'%')
                      ->orWhere('users.email', 'like', '%'.$keyword.'%')
                      ->orWhere('users.mobile', 'like', '%'.$keyword.'%')
                      ->orWhere('users.id', 'like', '%'.$keyword.'%');
            });
        }
        
        if($field == 'full_name'){
            $field = 'user_details.name';
        }

        $userdata=$userdata->orderBy($field,$sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($userdata);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $userdata->lastPage(),
            'perpage' => $perpage,
            'total' => $userdata->total(),
            'sort' => $sort,
            'field' => $field
        );
        $result['data']=array();

        $pageCal = '';
        $countbase = 1;
        $gePage = $page - 1;
        if($gePage == 0){
            $pageCal=$countbase;
        }else{
           $pageCal= $gePage.$countbase;
        }

        if($userdata){
            $count = $pageCal;
            foreach ($userdata as $key => $organize) {

                $result['data'][] = array(
                    "id" => $organize->id,
                    "number" => $count,
                    "dibil_number"=>$organize->dibil_number,
                    "full_name" => $organize->name,
                    "profile_img" => $organize->user_detail->image,
                    "organization_name" => $organize->organization_name,
                    "email"=> $organize->email,
                    "mobile"=> $organize->mobile,
                    "status"=> $organize->status,
                    "created_at"=> date('d-M-Y', strtotime( $organize->created_at)),
                    "Actions" => null        
                );
                $count++;
            }
        }
        return Response::json($result);
    }

    public function userRestore(Request $request, $id){
        $userdata = User::where('id',$id)->first();
        try {
            if (!$userdata) {
              return abort(404);
            }
            $userdata->status = '1';
            if($userdata->save())
            {
                $request->session()->flash('alert-success', 'Organization restored successfuly.');  
            }
            return redirect(route('organization.deleted.list'));
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.deleted.list'));
        }
    }

    public function pendingUser(){
        return view('admin.organization.pendinglist',array('title' => 'Pending Organization List'));
    }

    public function pendingUserlistdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $userdata = User::join('user_details', 'users.id', '=', 'user_details.user_id')->select('users.*','user_details.name','user_details.organization_name','user_details.profile_img')->where('status','0')->where('user_type','organization');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_details.name', 'like', '%'.$keyword.'%')
                      ->orWhere('user_details.organization_name', 'like', '%'.$keyword.'%')
                      ->orWhere('users.email', 'like', '%'.$keyword.'%')
                      ->orWhere('users.mobile', 'like', '%'.$keyword.'%')
                      ->orWhere('users.id', 'like', '%'.$keyword.'%');
            });
        }
        
        if($field == 'full_name'){
            $field = 'user_details.name';
        }

        $userdata=$userdata->orderBy($field,$sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($userdata);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $userdata->lastPage(),
            'perpage' => $perpage,
            'total' => $userdata->total(),
            'sort' => $sort,
            'field' => $field
        );
        $result['data']=array();

        $pageCal = '';
        $countbase = 1;
        $gePage = $page - 1;
        if($gePage == 0){
            $pageCal=$countbase;
        }else{
           $pageCal= $gePage.$countbase;
        }

        if($userdata){
            $count = $pageCal;
            foreach ($userdata as $key => $organize) {

                $result['data'][] = array(
                    "id" => $organize->id,
                    "number" => $count,
                    "dibil_number"=>$organize->dibil_number,
                    "full_name" => $organize->name,
                    "profile_img" => $organize->user_detail->image,
                    "organization_name" => $organize->organization_name,
                    "email"=> $organize->email,
                    "mobile"=> $organize->mobile,
                    "status"=> $organize->status,
                    "created_at"=> date('d-M-Y', strtotime( $organize->created_at)),
                    "Actions" => null        
                );
                $count++;
            }
        }
        return Response::json($result);
    }

    public function approveOrDecline(Request $request, $id, $status){
        $userdata = User::where('id',$id)->first();
        try {
            if (!$userdata) {
              return abort(404);
            }
            if ($status != 'decline') {
                $userdata->status = '1';
            }else{
                $userdata->status = '2';
            }
            if($userdata->save())
            {
                $request->session()->flash('alert-success', 'Organization '.ucfirst($status).' successfuly.');  
            }
            return redirect(route('organization.pending.list'));
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.pending.list'));
        }
    }
}
