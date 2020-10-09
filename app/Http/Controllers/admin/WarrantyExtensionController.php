<?php

namespace App\Http\Controllers\admin;

use App\WarrantyExtension;
use Illuminate\Http\Request;

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
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Request</span>';
                }else if($extension->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-primary">Submit For Approval</span>';
                }elseif($extension->status == '2'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Approved</span>';
                }elseif($extension->status == '3'){
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
        //
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
