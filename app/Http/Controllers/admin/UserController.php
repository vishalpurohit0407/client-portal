<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use App\User;
use Response;
use Hash;
use Storage;
use App\Common;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        return view('admin.user.list',array('title' => 'Users List','status'=>$request->status));
    }

    public function listdata(Request $request)
    {
      // echo "<pre>"; print_r($request->session()->token()); exit();
      $columns = array( 
                        0 => 'id', 
                        1 => 'name',
                        2 => 'email',
                        3 => 'status',
                        4 => 'created_at',
                    );
  
        $totalData = User::where('status','!=','3')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = User::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->where('status','!=','3')
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $posts =  User::where('status','!=','3')
                            ->where(function ($query)  use ($search) {
                                $query->where('email','LIKE',"%{$search}%")
                                    ->orWhere('name', 'LIKE',"%{$search}%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = User::where('status','!=','3')
                            ->where(function ($query) use ($search){
                                $query->where('email','LIKE',"%{$search}%")
                                    ->orWhere('name', 'LIKE',"%{$search}%");
                            })
                            ->count();
        }

        $data = array();
        if(!empty($posts))
        {   $srnumber = 1;
            foreach ($posts as $post)
            {
                $destroy =  route('admin.user.destroy',$post->id);
                $edit =  route('admin.user.edit',$post->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $post->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['name'] = '<td class="table-user"> <img src="'.$post->user_image_url.'" class="avatar rounded-circle mr-3"> <b>'.ucfirst($post->name).'</b> </td>';
                $nestedData['email'] = $post->email;
                if($post->status == '0'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Inactive</span>';
                }elseif($post->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Active</span>';
                }elseif($post->status == '2'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-info">Pending</span>';
                }else{
                  $nestedData['status'] = '<span class="badge badge-pill badge-danger">Deleted</span>';
                };
                $nestedData['created_at'] = date('d-M-Y',strtotime($post->created_at));
                $nestedData['options'] = "&emsp;<a href='{$edit}' class='btn btn-primary btn-sm mr-0' title='EDIT' >Edit</a> 
                                          &emsp;<form action='{$destroy}' method='POST' style='display: contents;' id='frm_{$post->id}'> <input type='hidden' name='_method' value='DELETE'> <input type='hidden' name='_token' value='{$token}'> <a type='submit' class='btn btn-danger btn-sm' style='color: white;' onclick='return deleteConfirm(this);' id='{$post->id}'>Delete</a></form>";
                
                $srnumber++;
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        return view('admin.user.add',array('title' => 'Create User'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,',
            'password' => 'min:8|required_with:confirmpass|same:confirmpass', 
            'confirmpass' => 'min:8',
        ]);
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'status' => $request->status ? '1' : '0',
                'password' => Hash::make($request->password),
            ]); 
            if($user){
                $file=$request->file('profile_img');
                if($file){
                    $request->validate([
                        'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                    $file_name =$file->getClientOriginalName();
                    $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
                    $imageName = md5($fileslug.time());
                    $imgext =$file->getClientOriginalExtension();
                    $path = 'userprofile/'.$user->id.'/'.$imageName.".".$imgext;
                    $fileAdded = Storage::disk('public')->putFileAs('userprofile/'.$user->id.'/',$file,$imageName.".".$imgext);
                    $user->profile_img = $path;
                    $user->save();
                }
                $request->session()->flash('alert-success', 'User Created successfuly.');  
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.user.list'));
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
        // echo "<pre>";print_r($user->user_image_url);exit();
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
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
        try {
            if (!$user) {
              return abort(404);
            }
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status ? '1' : '0';
            if ($request->password) {
                $request->validate([
                    'password' => 'min:8', 
                ]);
                $user->password = Hash::make($request->password);
            }
            $file=$request->file('profile_img');
            if($file){
                $request->validate([
                    'profile_img' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
                ]);
                if ($user->user_image_url) {
                    Storage::disk('public')->delete($user->profile_img);
                }
                $file_name =$file->getClientOriginalName();
                $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
                $imageName = md5($fileslug.time());
                $imgext =$file->getClientOriginalExtension();
                $path = 'userprofile/'.$user->id.'/'.$imageName.".".$imgext;
                $fileAdded = Storage::disk('public')->putFileAs('userprofile/'.$user->id.'/',$file,$imageName.".".$imgext);
                $user->profile_img = $path;
            }
            if($user->save())
            {
                $request->session()->flash('alert-success', 'User updated successfuly.');  
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.user.list'));
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
                $request->session()->flash('alert-success', 'User deleted successfuly.');
            }
            return redirect(route('admin.user.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.user.list'));
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
