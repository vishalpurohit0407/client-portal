<?php

namespace App\Http\Controllers;

use App\WarrantyExtension;
use Illuminate\Http\Request;
use Storage;
use Response;
use Auth;

class WarrantyExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index()
    {
        return view('warranty_extension.list',array('title' => 'Warranty Extension List'));
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
  
        $totalData = WarrantyExtension::groupBy('unique_key')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $extensions = WarrantyExtension::where('user_id', Auth::user()->id)
                         ->offset($start)
                         ->limit($limit)
                         ->groupBy('unique_key')
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $extensions =  WarrantyExtension::where('user_id', Auth::user()->id)
                            ->where('unique_key', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->groupBy('unique_key')
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = WarrantyExtension::where('user_id', Auth::user()->id)
                            ->where('unique_key', 'LIKE',"%{$search}%")
                            ->groupBy('unique_key')
                            ->count();
        }
        //dd($extensions);
        $data = array();
        if(!empty($extensions))
        {   $srnumber = 1;
            foreach ($extensions as $extension)
            {
                $show =  route('user.warranty_extension.show',$extension->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $extension->id;
                $nestedData['srnumber'] = $srnumber;
                //$nestedData['name'] = '<img src="'.$extension->user->user_image_url.'" class="avatar rounded-circle mr-3"> <b>'.ucfirst($extension->user->name).'</b>';
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
                $nestedData['options'] = "&emsp;<a href='{$show}' class='btn btn-success btn-sm mr-0' title='View' >View</a>";
                
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
        return view('warranty_extension.add',array('title' => 'Add Warranty Extension'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // echo "<pre>";print_r($request->all());exit();  
        $request->validate([
            'unique_key' => 'required',
        ]);
        try {
            $warrantyExtension = new WarrantyExtension;
            $warrantyExtension->unique_key = $request->unique_key;
            $warrantyExtension->user_id = Auth::user()->id;
            if($warrantyExtension->save())
            {
                $request->session()->flash('alert-success', 'Warranty Extension added successfuly.');  
            }
            return redirect(route('user.warranty_extension.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.warranty_extension.list'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function show(WarrantyExtension $warrantyExtension)
    {
        return view('warranty_extension.detail',array('title' => 'Warranty Extension Details','warrantyExtension'=>$warrantyExtension));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function edit(WarrantyExtension $warrantyExtension)
    {
        return view('warranty_extension.edit',array('title' => 'Warranty Extension','warranty'=>$warrantyExtension));
    }

    public function userImgUpload(Request $request, $id)
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
                Storage::disk('public')->delete($guideData->picture_by_user);
                $media = WarrantyExtension::where('id',$id)->update(['picture_by_user' => $path]);
                return Response::json(['status' => true, 'message' => 'Media uploaded.']);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
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
            'voltage' => 'required',
            'temperat' => 'required',
            'thing_on' => 'required',
        ]);
        try {
            if (!$warrantyExtension) {
                return abort(404);
            }
            if ($request->vid_link_url) {
                Storage::disk('public')->delete($warrantyExtension->picture_by_user);
                $warrantyExtension->picture_by_user = '';
            }
            $warrantyExtension->voltage = $request->voltage;
            $warrantyExtension->temperat = $request->temperat;
            $warrantyExtension->thing_on = $request->thing_on;
            $warrantyExtension->vid_link_type = $request->vid_link_type == 'youtube' ? 'youtube' : 'vimeo';
            $warrantyExtension->vid_link_url = $request->vid_link_url;
            $warrantyExtension->do_something = $request->do_something ? 'true' : 'false';
            $warrantyExtension->status =  '2';
            if($warrantyExtension->save())
            {
                $request->session()->flash('alert-success', 'Warranty Extension updated successfuly.');  
            }
            return redirect(route('user.warranty_extension.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.warranty_extension.list'));
        }
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
}
