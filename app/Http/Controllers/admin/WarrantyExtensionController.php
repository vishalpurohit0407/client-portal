<?php

namespace App\Http\Controllers\admin;

use App\WarrantyExtension;
use Illuminate\Http\Request;
use Storage;
use Response;

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
            0 =>'name',
            1 =>'unique_key',
            2 => 'status',
            3 => 'created_at',
        );
  
        $totalData = WarrantyExtension::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $extensions = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                         ->select('warranty_extension.*','users.name')
                         ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $extensions =  WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                            ->where('users.name', 'LIKE',"%{$search}%")
                            ->orWhere('unique_key', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = WarrantyExtension::join('users', 'users.id', '=', 'warranty_extension.user_id')
                            ->where('users.name', 'LIKE',"%{$search}%")
                            ->orWhere('unique_key', 'LIKE',"%{$search}%")
                            ->count();
        }
        //dd($extensions);
        $data = array();
        if(!empty($extensions))
        {   $srnumber = 1;
            foreach ($extensions as $extension)
            {
                $edit =  route('admin.maintenance.warrantyextension.edit',$extension->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $extension->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['name'] = '<img src="'.$extension->user->user_image_url.'" class="avatar rounded-circle mr-3"> <b>'.ucfirst($extension->user->name).'</b>';
                $nestedData['key'] = $extension->unique_key;

                if($extension->status == '0'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Initial</span>';
                }else if($extension->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-primary">Admin Reply</span>';
                }elseif($extension->status == '2'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Request</span>';
                }elseif($extension->status == '3'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Approved</span>';
                }elseif($extension->status == '4'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-danger">Declined</span>';
                }
                
                $nestedData['created_at'] = date('d-M-Y',strtotime($extension->created_at));
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
            
        echo json_encode($json_data);
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
        //
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

        if(isset($request->approve) || isset($request->decline)){
            
            if(isset($request->approve)){
                $warrantyExtension->status = '3';
            }else{
                $warrantyExtension->status = '4';
            }

            if($warrantyExtension->save())
            {
                $request->session()->flash('alert-success', 'Warranty Extension updated successfuly.');  
            }
            return redirect(route('admin.maintenance.warrantyextension.list'));
        }

        $input = $request->all();
        $warrantyExtension->warranty_valid_date = $request->warranty_valid_date;
        $warrantyExtension->status = $request->status;
        $warrantyExtension->voltage = $request->voltage;
        $warrantyExtension->temperat = $request->temperat;

        $warrantyExtension->thing_on = (isset($request->thing_on) && $request->thing_on == 'on')? 'yes' : 'no';;
        $warrantyExtension->do_something = (isset($request->do_something))? 'true' : 'false';
          
        if($warrantyExtension->save())
        {
            $request->session()->flash('alert-success', 'Warranty Extension updated successfuly.');  
        }
        return redirect(route('admin.maintenance.warrantyextension.list'));

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
                $media = WarrantyExtension::where('id',$id)->update(['picture_by_admin' => $path]);
                return Response::json(['status' => true, 'message' => 'Media uploaded.']);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }
}
