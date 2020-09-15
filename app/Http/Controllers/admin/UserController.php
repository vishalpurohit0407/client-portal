<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use App\UserDetail;
use App\Pushnotification;
use App\Documentmedia;
use App\Dibilrequest;
use App\Document;
use Response;
use Hash;
use Storage;
use App\Common;
use QrCode;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){

        //backgroundColor(255, 0, 0)->
        /*$file = QrCode::margin(1)->format('png')->size(300)->generate('123456789');
        $qrpath = Common::storeImage($file,'qrcode','test');*/
       // echo "<pre>";print_r($request->status);exit();
        return view('admin.user.list',array('title' => 'User List','status'=>$request->status));
    }

    public function listdata(Request $request, $status){
        $result = array();
        // echo "<pre>";print_r($status);exit();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';

        $userdata = User::join('user_details', 'users.id', '=', 'user_details.user_id')->select('users.*','user_details.name','user_details.profile_img')->where('user_type','user');
        if ($status == '1') {
            $userdata = $userdata->where('status','1');
        }else{
            $userdata = $userdata->where('status','!=','3');
        }
        if(isset($request['query']['generalSearch']) && !empty($request['query']['generalSearch'])){
            $keyword=$request['query']['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_details.name', 'like', '%'.$keyword.'%')
                      ->orWhere('users.email', 'like', '%'.$keyword.'%')
                      ->orWhere('users.mobile', 'like', '%'.$keyword.'%')
                      ->orWhere('users.id', 'like', '%'.$keyword.'%');
            });
        }
        if($field == 'full_name'  || $field == 'number'){
            $field = 'user_details.name';
        }

        $userdata=$userdata->orderBy($field, $sort)->paginate($perpage,['*'],'page',$page);
        //echo "<pre>";print_r($userdata);exit;
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
            foreach ($userdata as $key => $user) {

                $result['data'][] = array(
                    "id" => $user->id,
                    "number" => $count,
                    "dibil_number"=>$user->dibil_number,
                    "full_name" => $user->name,
                    "profile_img" => $user->user_detail->image,
                    "email"=> $user->email,
                    "mobile"=> $user->mobile,
                    "status"=> $user->status,
                    "created_at"=> date('d-M-Y', strtotime( $user->created_at)),
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
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        // echo "<pre>";print_r(array_sum($shereNo));exit();
        $receivedDoc = Dibilrequest::join('users', 'users.id','=','dibil_requests.user_id')
        ->join('documents', 'documents.id', '=','dibil_requests.document_id')
        ->select('users.email','dibil_requests.*','documents.document_name')
        ->where('recipient_id',$user->id)
        ->where('dibil_requests.status','1')->count();
        return view('admin.user.detail',array('title' => 'User Detail','userdata'=>$user,'documents'=>$document,'shereNo'=>$shereNo,'receivedDoc'=>$receivedDoc));
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
        return view('admin.user.edit',array('title' => 'Edit User','userdata'=>$user));
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
        // echo "<pre>";print_r($user);exit();
        $request->validate([
            'name' => 'required',
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

            $user_detail->educational = $request->educational;
            $user_detail->full_address = $request->full_address;
            $user_detail->birth_date = $request->birth_date ? date("Y/m/d", strtotime($request->birth_date)) : NULL;
            $user->mobile = $request->mobile;
            $user->email = $request->email;
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
            if ($request->password) {
                $messages = [
                    'password.digits' => 'The MPIN must be only 4 digits.',
                ];
                $request->validate([
                    'password' => 'digits:4'
                ],$messages);
                $user->password = Hash::make($request->password);
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
                    $inpute['message'] = 'Dear user your account has been inactive by admin please contact administrator';
                    $inpute['payload'] = $payload;
                    $notification = Pushnotification::create($inpute);
                }
                $request->session()->flash('alert-success', 'User updated successfuly.');  
            }
            return redirect(route('user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.list'));
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
                $inpute['message'] = 'Dear user your account has been block by admin please contact administrator';
                $inpute['payload'] = $payload;
                $notification = Pushnotification::create($inpute);
                $request->session()->flash('alert-success', 'User deleted successfuly.');
            }
            return redirect(route('user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.list'));
        }
    }



    public function deletedUser(){
        return view('admin.user.deletedlist',array('title' => 'Deleted User List'));
    }

    public function deletedUserlistdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $userdata = User::join('user_details', 'users.id', '=', 'user_details.user_id')->select('users.*','user_details.name','user_details.profile_img')->where('status','3')->where('user_type','user');
        if(isset($request['query']['generalSearch']) && !empty($request['query']['generalSearch'])){
            $keyword=$request['query']['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_details.name', 'like', '%'.$keyword.'%')
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
            foreach ($userdata as $key => $user) {

                $result['data'][] = array(
                    "id" => $user->id,
                    "number" => $count,
                    "dibil_number"=>$user->dibil_number,
                    "full_name" => $user->name,
                    "profile_img" => $user->user_detail->image,
                    "email"=> $user->email,
                    "mobile"=> $user->mobile,
                    "status"=> $user->status,
                    "created_at"=> date('d-M-Y', strtotime( $user->created_at)),
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
                $request->session()->flash('alert-success', 'User restored successfuly.');  
            }
            return redirect(route('user.deleted.list'));
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.deleted.list'));
        }
    }

    public function userDocumentVerify(Request $request, $id, $flag){
        $document = Document::find($id);
        try {
            if (!$document) {
              return abort(404);
            }
            if ($flag == 'verify') {
                $document->scanit_verify = '1';
            }else{
                $document->scanit_verify = '0';
            }
            if($document->save())
            {
                if ($document->scanit_verify == '1') {
                    $request->session()->flash('alert-success', 'User document verified successfuly.'); 
                }else{
                    $request->session()->flash('alert-success', 'User document unverified successfuly.');
                }
                 
            }
            return redirect()->back();
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect()->back();
        }
    }

    public function userDocumentDetail(Request $request, $document_id){
        $document = Document::find($document_id);
        if (!$document) {
            abort(404);
        }
        $dibilrequest = Dibilrequest::where('document_id',$document->id)->select('recipient_id','created_at')->get();
        // echo "<pre>";print_r($dibilrequest->pluck('recipient_id'));exit();

        $shereuser = UserDetail::whereIn('user_id',$dibilrequest->pluck('recipient_id'))->get();
       return view('admin.user.documentdetail',array('title' => 'User Document Details','document'=>$document,'shereuser'=>$shereuser, 'dibilrequest'=>$dibilrequest));
    }

    public function userDocumentDelete(Request $request, $document_id){
        try {
            $document = Document::find($document_id);
            $user_id = $document->user_id;
            if (!$document) {
                abort(404);
            }
            if ($document->delete()) {
                $document_media = Documentmedia::where('document_id',$document->id)->get();
                foreach ($document_media as $key => $media_value) {
                    Common::deleteImage($media_value->document_path);
                    $media_value->delete();
                }
                
            }
            return redirect(route('user.show',$user_id));
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.show',$user_id));
        }
    }
}
