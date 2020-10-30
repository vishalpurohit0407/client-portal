<?php

namespace App\Http\Controllers\admin;

use App\WarrantyExtension;
use Illuminate\Http\Request;
use Storage;
use Response;
use App\User;


class WarrantyExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.warrantyextension.list',array('title' => 'Warranty Extension List'));
    }


    public function listdata(Request $request)
    {
        //echo "<pre>"; print_r($request->all()); exit();
        $columns = array(  
            0 => 'warranty_extension.id',
            1 => 'users.name',
            2 => 'warranty_extension.unique_key',
            3 => 'warranty_extension.updated_at',
        );
  
         

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $totalData = WarrantyExtension::distinct('unique_key')->count();     
        $totalFiltered = $totalData;
            
        if(empty($request->input('search.value')))
        {            
            $extensions = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                         ->select('warranty_extension.*','users.name')
                         ->offset($start)
                         ->limit($limit)
                         //->distinct('warranty_extension.unique_key')
                         ->groupBy('warranty_extension.unique_key')
                         ->orderBy($order,$dir)
                         ->get();
        }else{
            $search = $request->input('search.value'); 

            $extensions =  WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                            ->where('users.name', 'LIKE',"%{$search}%")
                            ->orWhere('unique_key', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            //->distinct('warranty_extension.unique_key')
                            ->groupBy('warranty_extension.unique_key')
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                            ->where('users.name', 'LIKE',"%{$search}%")
                            ->orWhere('unique_key', 'LIKE',"%{$search}%")
                            ->groupBy('warranty_extension.unique_key')
                            //->distinct('warranty_extension.unique_key')
                            ->count();
        }
        //dd($extensions);
        $data = array();
        if(!empty($extensions))
        {   $srnumber = 1;
            foreach ($extensions as $extension)
            {
                if($extension->status == '3' || $extension->status == '4'){
                    $edit =  route('admin.warrantyextension.history',$extension->unique_key);
                }else {
                    $edit =  route('admin.warrantyextension.edit',$extension->id);
                }
                
                $token =  $request->session()->token();

                $nestedData['id'] = $extension->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['name'] = '<img src="'.asset('storage/'.$extension->user->profile_img).'" class="avatar rounded-circle mr-3"> <b>'.ucfirst($extension->user->name).'</b>';
                $nestedData['key'] = $extension->unique_key;

                $extension_latest = WarrantyExtension::where('unique_key',$extension->unique_key)->latest()->select('updated_at')->first();
                
                $nestedData['updated_at'] = date('d-M-Y',strtotime($extension_latest->updated_at));
                $nestedData['options'] = "&emsp;<a href='{$edit}' class='btn btn-success btn-sm mr-0'>View</a>";
                
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


    public function requestListData(Request $request)
    {
        if($request->ajax()){
            
            //echo "<pre>"; print_r($request->all()); exit();
            $columns = array(  
                0 => 'warranty_extension.id',
                1 => 'users.name',
                2 => 'warranty_extension.unique_key',
                3 => 'warranty_extension.updated_at',
            );
            //dd($columns[$request->input('order.0.column')]);
            $limit = $request->input('length');
            $start = $request->input('start');
            $order = $columns[$request->input('order.0.column')];
            $dir = $request->input('order.0.dir');

            $totalData = WarrantyExtension::distinct('unique_key')->whereIn('warranty_extension.status',['0','1','2'])->count();     
            $totalFiltered = $totalData;
                
            if(empty($request->input('search.value')))
            {            
                $extensions = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                             ->select('warranty_extension.*','users.name')
                             ->whereIn('warranty_extension.status',['0','1','2'])
                             ->offset($start)
                             ->limit($limit)
                             ->groupBy('warranty_extension.unique_key')
                             //->distinct('warranty_extension.unique_key')
                             ->orderBy($order,$dir)
                             ->get();

            }else{
                $search = $request->input('search.value'); 

                $extensions =  WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                                ->whereIn('warranty_extension.status',['0','1','2'])
                                ->where('users.name', 'LIKE',"%{$search}%")
                                ->orWhere('unique_key', 'LIKE',"%{$search}%")
                                ->offset($start)
                                ->limit($limit)
                                //->distinct('warranty_extension.unique_key')
                                ->groupBy('warranty_extension.unique_key')
                                ->orderBy($order,$dir)
                                ->get();

                $totalFiltered = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                                ->whereIn('warranty_extension.status',['0','1','2'])
                                ->where('users.name', 'LIKE',"%{$search}%")
                                ->orWhere('unique_key', 'LIKE',"%{$search}%")
                                //->distinct('warranty_extension.unique_key')
                                ->groupBy('warranty_extension.unique_key')
                                ->count();
            }

            //dd($extensions);
            $data = array();
            if(!empty($extensions))
            {   $srnumber = 1;
                foreach ($extensions as $extension)
                {
                    $edit =  route('admin.warrantyextension.edit',$extension->id);
                    $token =  $request->session()->token();

                    $nestedData['id'] = $extension->id;
                    $nestedData['srnumber'] = $srnumber;
                    $nestedData['name'] = '<img src="'.$extension->user->user_image_url.'" class="avatar rounded-circle mr-3"> <b>'.ucfirst($extension->user->name).'</b>';
                    $nestedData['key'] = $extension->unique_key;

                    $extension_latest = WarrantyExtension::where('unique_key',$extension->unique_key)->latest()->select('updated_at')->first();

                    $nestedData['updated_at'] = date('d-M-Y',strtotime($extension_latest->updated_at));
                    $nestedData['options'] = "&emsp;<a href='{$edit}' class='btn btn-success btn-sm mr-0' title='EDIT' >View</a>";
                    
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
                
            echo json_encode($json_data);die();
        }

        return view('admin.warrantyextension.list-request',array('title' => 'Warranty Extension Request List'));
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
     * Display the specified resource.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function show(WarrantyExtension $warrantyExtension)
    {
        return view('admin.warrantyextension.detail',array('title' => 'Warranty Extension Details','warrantyExtension'=>$warrantyExtension));
    }

    public function warrantyExtensionHistory(Request $request,$unique_key)
    {
        $warrantyExtension = WarrantyExtension::where('unique_key',$unique_key)->orderBy('next_warranty_valid_date','asc')->get();
        if (!$warrantyExtension) {
            abort('404');
        }
        // echo "<pre>";print_r($warrantyExtension);exit();
        return view('admin.warrantyextension.history',array('title' => 'Warranty Extension History','warrantyExtension'=>$warrantyExtension));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function edit(WarrantyExtension $warrantyExtension)
    {
        
        return view('admin.warrantyextension.edit',array('title' => 'Warranty Extension View','warrantyExtension'=>$warrantyExtension));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarrantyExtension $warrantyExtension)
    {
        $request->validate([
            'warranty_valid_date' => 'required',
            'warranty_main_image' => [function ($attribute, $value, $fail) {
                if ($warrantyExtension->picture_by_admin == '') {
                    $fail(':Picture is required.');
                }
            }],
        ]);

        $user = User::find($warrantyExtension->user_id);

        if(isset($request->approve) || isset($request->decline)){
            
            $request->validate([
                'next_warranty_valid_date' => 'required'
            ]);

            if(isset($request->approve)){
                $warrantyExtension->status = '3';
                $messageNoti = 'Your warranty extension request has been approved successfuly.';
            }else{
                $warrantyExtension->status = '4';
                 $messageNoti = 'Your warranty extension request has been Declined. Please contact to Administration.';
            }

            if($request->next_warranty_valid_date != ''){
                $warrantyExtension->next_warranty_valid_date = $request->next_warranty_valid_date;
            }

            if($warrantyExtension->save())
            {
                if($user){
                    WarrantyExtension::sendWarrantyNotification($user->email, $user->name, $messageNoti, route('user.warranty_extension.list'));
                }
                $request->session()->flash('alert-success', 'Warranty Extension updated successfuly.');  
            }
            return redirect(route('admin.warrantyextension.list'));
        }

        $input = $request->all();
        
        if($warrantyExtension->status == '0'){

            if($user && $warrantyExtension->status == '0'){
                $messageNoti = 'Your warranty extension request detail has been filled by Administration, Please check and submit require detail as per instruction.';
                WarrantyExtension::sendWarrantyNotification($user->email, $user->name, $messageNoti, route('user.warranty_extension.list'));
            }

            if ($request->admin_vid_link_url) {
              Storage::disk('public')->delete($warrantyExtension->picture_by_admin);
              $warrantyExtension->picture_by_admin = NULL;
            }
            $warrantyExtension->warranty_valid_date = $request->warranty_valid_date;
            $warrantyExtension->admin_vid_link_type = $request->admin_vid_link_type == 'youtube' ? 'youtube' : 'vimeo';
            $warrantyExtension->admin_vid_link_url = $request->admin_vid_link_url;
            $warrantyExtension->status = '1';
            
        }else{
            if ($request->admin_vid_link_url) {
              Storage::disk('public')->delete($warrantyExtension->picture_by_admin);
              $warrantyExtension->picture_by_admin = NULL;
            }

            $warrantyExtension->admin_vid_link_type = $request->admin_vid_link_type == 'youtube' ? 'youtube' : 'vimeo';
            $warrantyExtension->admin_vid_link_url = $request->admin_vid_link_url;
            $warrantyExtension->warranty_valid_date = $request->warranty_valid_date;
            $warrantyExtension->voltage = $request->voltage;
            $warrantyExtension->temperat = $request->temperat;

            $warrantyExtension->thing_on = (isset($request->thing_on) && $request->thing_on == 'on')? 'yes' : 'no';;
            $warrantyExtension->do_something = (isset($request->do_something))? 'true' : 'false';
        }
          
        if($warrantyExtension->save())
        {
            $request->session()->flash('alert-success', 'Warranty Extension updated successfuly.');  
        }
        return redirect(route('admin.warrantyextension.list'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarrantyExtension $warrantyExtension)
    {
        //
    }

    public function machineImgUpload(Request $request, $id)
    {
        $file = $request->file('file');
        if($file && $id){
        
            $request->validate([
                'file' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $path = 'warranty_extension/'.$id.'/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('warranty_extension/'.$id.'/',$file,$imageName.".".$imgext);
            
            if($fileAdded){

                $guideData = WarrantyExtension::find($id);
                Storage::disk('public')->delete($guideData->picture_by_admin);
                $media = WarrantyExtension::where('id',$id)->update(['picture_by_admin' => $path,'admin_vid_link_url'=>NULL,'admin_vid_link_type'=>NULL]);
                return Response::json(['status' => true, 'message' => 'Media uploaded.']);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }
}
