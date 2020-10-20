<?php

namespace App\Http\Controllers\admin;

use App\Flowchart;
use App\Flowchartnode;
use Illuminate\Http\Request;
use Response;
use Str;
use App\Guide;
use App\GuideFlowchart;

class FlowchartController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.flowchart.list',array('title' => 'Flowchart List'));
    }

    public function listdata(Request $request){
        // echo "<pre>"; print_r($request->session()->token()); exit();
      $columns = array( 
                        0 => 'id', 
                        1 => 'title',
                        2 => 'status',
                        3 => 'created_at',
                    );
  
        $totalData = Flowchart::where('status','!=','3')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $flowchart = Flowchart::where('status','!=','3')
            			 ->offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $flowchart =  Flowchart::where('status','!=','3')
            				->where(function ($query) use ($search){
                                $query->orWhere('description','LIKE',"%{$search}%")
                            		  ->orWhere('title', 'LIKE',"%{$search}%");
                            })
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Flowchart::where('status','!=','3')
            				->where(function ($query) use ($search){
                                $query->orWhere('description','LIKE',"%{$search}%")
                            		  ->orWhere('title', 'LIKE',"%{$search}%");
                            })
                            ->count();
        }

        $data = array();
        if(!empty($flowchart))
        {   $srnumber = 1;
            foreach ($flowchart as $chart)
            {
                $view =  route('admin.flowchart.show',$chart->id);
                $edit =  route('admin.flowchart.edit',$chart->id);
                $delete =  route('admin.flowchart.destroy',$chart->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $chart->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['title'] = $chart->title;
                if($chart->status == '0'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-info">Draft</span>';
                }elseif($chart->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Active</span>';
                }elseif($chart->status == '2'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Inactive</span>';
                }elseif($chart->status == '3'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-danger">Deleted</span>';
                };
                $nestedData['created_at'] = date('d-M-Y',strtotime($chart->created_at));
                $nestedData['options'] = "&emsp;<a href='{$edit}' class='btn btn-primary btn-sm mr-0' title='EDIT' >Edit</a>
                                          &emsp;<a href='{$view}' class='btn btn-info btn-sm mr-0' title='VIEW' >View</a>
                                          &emsp;<form action='{$delete}' method='POST' style='display: contents;' id='frm_{$chart->id}'> <input type='hidden' name='_method' value='DELETE'> <input type='hidden' name='_token' value='{$token}'> <a type='submit' class='btn btn-danger btn-sm' style='color: white;' onclick='return deleteConfirm(this);' id='{$chart->id}'>Delete</a></form>";
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
        // return abort(404);
        // return view('admin.flowchart.create',array('title' => 'Flowchart Create'));
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
            'title' => 'required|max:255', 
        ]);
        try {
            $input = $request->all();
            $input['title'] = $request->title;
            $input['description'] = $request->description;
            $input['status'] ='0';
            $flowchart = Flowchart::create($input);
            if($flowchart)
            {
                $request->session()->flash('alert-success', 'Flowchart created successfuly.');  
            }
            return redirect(route('admin.flowchart.edit',$flowchart->id));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.flowchart.list'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Flowchart  $flowchart
     * @return \Illuminate\Http\Response
     */
    public function show(Flowchart $flowchart)
    {
       	return view('admin.flowchart.preview',array('title' => 'Flowchart Preview','flowchart'=>$flowchart));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Flowchart  $flowchart
     * @return \Illuminate\Http\Response
     */
    public function edit(Flowchart $flowchart)
    {
        if (!$flowchart) {
          return abort(404);
        }

        $childNode = Flowchartnode::where('flowchart_id',$flowchart->id)->orderBy('created_at','desc')->get();
        
        $maintenance = Guide::where('guide_type','maintenance')->where('status','1')->get();
        $self_diagnosis = Guide::where('guide_type','self-diagnosis')->where('status','1')->get();
        $guideflowchart_guideid = GuideFlowchart::where('flowchart_id',$flowchart->id)->pluck('guide_id')->toArray();
        return view('admin.flowchart.edit',array('title' => 'Edit Flowchart','flowchart'=>$flowchart, 'childNode' => $childNode,'self_diagnosis'=>$self_diagnosis,'maintenance'=>$maintenance, 'guide_id_array'=>$guideflowchart_guideid));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Flowchart  $flowchart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Flowchart $flowchart)
    {
        //dd($request->all());
        // echo "<pre>";print_r($request->all());exit();
        if (!$flowchart) {
            return abort(404);
        }
        if ($request->flag != 'flowchart_details' && $request->flag != 'flowchart_addnode') {
            return abort(404);
        }

        try {
        	if ($request->flag == 'flowchart_details') {
        		$request->validate([
			        'title' => 'required|max:255',
			    ]);   
	            $flowchart['title'] = $request->title;
	            $flowchart['description'] = $request->description;
	            if ($request->flowchart_details_submit == 'Publish') {
	            	$flowchart['status'] = '1';
	            }else if($request->flowchart_details_submit == 'save as a draft'){
	            	$flowchart['status'] = '0';
	            }

	            if ($request->guide_id) {
	            	$guideflowid = GuideFlowchart::where('flowchart_id',$flowchart->id)->whereNotIn('guide_id',$request->guide_id)->delete();
	            	foreach ($request->guide_id as $guidekey => $guide_id) {
	            		$guideflowchart = GuideFlowchart::updateOrCreate([
	            			'guide_id'=>$guide_id,
	            			'flowchart_id'=>$flowchart->id,
	            		]);
	            	}
	            }else{
	            	GuideFlowchart::where('flowchart_id',$flowchart->id)->delete();
	            }
	            if($flowchart->save())
	            {
	                $request->session()->flash('alert-success', 'Flowchart details updated successfuly.');  
	            }

        	}

        	if ($request->flag == 'flowchart_addnode') {

                $nodelabel = Flowchartnode::where('flowchart_id',$flowchart->id)->where('label',$request->lable)->first();
                // echo "<pre>";print_r($nodelabel);exit();
                if ($nodelabel) {
                    $labelvalidation = 'required|unique:flowchart_node,label';
                }else{
                    $labelvalidation = 'required';
                }
        		$validationArr = [
		            'lable' => $labelvalidation,
		            'type' => 'required', 
		            'text' => 'required'
		        ];

		        if($request->type == 'decision'){
		            //$validationArr['dicision_yes'] = 'required';
		            //$validationArr['dicision_no'] = 'required';
		        }

		        if($request->type == 'process'){
		            //$validationArr['process_next'] = 'required';
		        }

		        if(isset($request->add_link) && $request->type != 'decision'){
		            $validationArr['link_text'] = 'required';
		            $validationArr['link_url'] = 'required|url';
		        }

		        if(isset($request->add_tip) && $request->type != 'decision'){
		            $validationArr['tip_title'] = 'required';
		            $validationArr['tip_text'] = 'required';
		        }
		        $request->validate($validationArr);

        		$flowchartnodeArr = array();    
	            $flowchartnodeArr['flowchart_id'] = $flowchart->id;
	            $flowchartnodeArr['label'] = str_replace(' ', '-', $request->lable);
	            $flowchartnodeArr['type'] = $request->type;
	            $flowchartnodeArr['text'] = $request->text;

	            if($request->type == 'decision'){
	                $flowchartnodeArr['yes'] = $request->dicision_yes;
	                $flowchartnodeArr['no'] = $request->dicision_no;
	            }

	            if($request->type == 'process'){
	                $flowchartnodeArr['next'] = $request->process_next;
	            }

	            if(isset($request->add_link) && $request->type != 'decision'){
	                $flowchartnodeArr['link_text'] = $request->link_text;
	                $flowchartnodeArr['link_url'] = $request->link_url;
	                $flowchartnodeArr['link_target'] = $request->link_target;
	            }

	            if(isset($request->add_tip) && $request->type != 'decision'){
	                $flowchartnodeArr['tips_title'] = $request->tip_title;
	                $flowchartnodeArr['tips_text'] = $request->tip_text;
	            }
	            
	            if(isset($request->change_orient) && $request->type == 'decision'){
	                $flowchartnodeArr['orient_yes'] = $request->orient_yes;
	                $flowchartnodeArr['orient_no'] = $request->orient_no;
	            }

	            if(Flowchartnode::create($flowchartnodeArr))
	            {
	                $request->session()->flash('alert-success', 'Flowchart node added successfuly.');  
	            }

        	}
        	if ($request->submit == 'Preview') {
        		$redirect = [$flowchart->id,'#flowchart_preview'];
        	}else{
        		$redirect = $flowchart->id;
        	}
            return redirect(route('admin.flowchart.edit', $redirect));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.flowchart.edit',$flowchart->id));
        }
    }

    public function nodeUpdate(Request $request)
    {
        // dd($request->all());
        $flowchartnode = Flowchartnode::find($request->id);
        if (!$flowchartnode) {
            return abort(404);
        }

        $nodelabel = Flowchartnode::where('flowchart_id',$request->flowchart_id)->where('label',$request->lable)->where('id','!=',$request->id)->count();
                //echo "<pre>";print_r($nodelabel);exit();
                if ($nodelabel > 0) {
                    $labelvalidation = 'required|unique:flowchart_node,label';
                }else{
                    $labelvalidation = 'required';
                }
        try {
            
            $validationArr = [
                'lable' => $labelvalidation,
                'type' => 'required', 
                'text' => 'required'
            ];

            if($request->type == 'decision'){
                //$validationArr['dicision_yes'] = 'required';
                //$validationArr['dicision_no'] = 'required';
            }

            if($request->type == 'process'){
                //$validationArr['process_next'] = 'required';
            }

            if(isset($request->add_link) && $request->type != 'decision'){
                $validationArr['link_text'] = 'required';
                $validationArr['link_url'] = 'required|url';
            }

            if(isset($request->add_tip) && $request->type != 'decision'){
                $validationArr['tip_title'] = 'required';
                $validationArr['tip_text'] = 'required';
            }
            $request->validate($validationArr);

            
            $flowchartnode['label'] = str_replace(' ', '-', $request->lable);
            $flowchartnode['type'] = $request->type;
            $flowchartnode['text'] = $request->text;

            if($request->type == 'decision'){
                $flowchartnode['yes'] = $request->dicision_yes;
                $flowchartnode['no'] = $request->dicision_no;
            }

            if($request->type == 'process'){
                $flowchartnode['next'] = $request->process_next;
            }

            if(isset($request->add_link) && $request->type != 'decision'){
                $flowchartnode['link_text'] = $request->link_text;
                $flowchartnode['link_url'] = $request->link_url;
                $flowchartnode['link_target'] = $request->link_target;
            }

            if(isset($request->add_tip) && $request->type != 'decision'){
                $flowchartnode['tips_title'] = $request->tip_title;
                $flowchartnode['tips_text'] = $request->tip_text;
            }
            
            if(isset($request->change_orient) && $request->type == 'decision'){
                $flowchartnode['orient_yes'] = $request->orient_yes;
                $flowchartnode['orient_no'] = $request->orient_no;
            }

            if($flowchartnode->save())
            {
                $request->session()->flash('alert-success', 'Node updated successfuly.');
                return Response::json(['status' => true, 'message' => 'Node updated successfuly.']);
            }
            
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
            
        }catch (ModelNotFoundException $exception) {
            return Response::json(['status' => false, 'message' => $exception->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Flowchart  $flowchart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Flowchart $flowchart)
    {
        try {
            if(!$flowchart){
                return abort(404) ;
            }
            $flowchart->status = '3';
            if ($flowchart->save()) {
                $request->session()->flash('alert-success', 'Flowchart deleted successfuly.');
            }
            return redirect(route('admin.flowchart.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.flowchart.list'));
        }
    }

    public function removeNode(Request $request, $id)
    {
        
        try {


            $node = Flowchartnode::find($id);
            $flowchartId = $node->flowchart_id;

            if(!$node){
                return abort(404) ;
            }
            
            if ($node->delete()) {
                Flowchartnode::where('yes',$id)->update(['yes'=>NULL,]);
                Flowchartnode::where('no',$id)->update(['no'=>NULL,]);
                Flowchartnode::where('next',$id)->update(['next'=>NULL,]);
                $request->session()->flash('alert-success', 'Flowchart node deleted successfuly.');
            }
            return redirect(route('admin.flowchart.edit', $flowchartId));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.flowchart.list'));
        }
    }
}
