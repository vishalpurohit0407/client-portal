<?php

namespace App\Http\Controllers\admin;

use App\Documentgroupassign;
use Illuminate\Http\Request;
use App\User;
use App\UserDetail;
use App\Typeofdocument;
use App\Groupofdocument;
use Response;
use Hash;

class DocumentgroupassignController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.documentgroupassigned.list',array('title' => 'Document Group Assigned List'));
    }

    public function listdata(Request $request){
        $result = array();
        //dd($request->sort);
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $documentgroups = Documentgroupassign::
                join('group_of_documents', 'group_of_documents.id' , '=', 'document_group_assigned.document_group_id')
                ->join('type_of_documents', 'type_of_documents.id', '=', 'document_group_assigned.document_type_id')
                ->groupBy('document_group_assigned.document_group_id');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $documentgroups=$documentgroups->where(function ($query) use ($keyword) {
                $query->where('document_group_assigned.id', 'like', '%'.$keyword.'%')
                      ->orWhere('group_of_documents.name', 'like', '%'.$keyword.'%')
                      ->orWhere('type_of_documents.name', 'like', '%'.$keyword.'%');
            });
        }
        
        if($field == 'document_group'){
            $field = 'group_of_documents.name';
        }
        //orderBy($field, $sort)->
        $documentgroups=$documentgroups->orderBy($field, $sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($documentgroups[0]);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $documentgroups->lastPage(),
            'perpage' => $perpage,
            'total' => $documentgroups->total(),
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

        if($documentgroups){
            $count = $pageCal;
            foreach ($documentgroups as $key => $documentgroup) {

                $getTypeId = Documentgroupassign::where('document_group_id',$documentgroup->document_group_id)->pluck('document_type_id')->toArray();
                $typeName = Typeofdocument::whereIn('id',$getTypeId)->pluck('name')->toArray();
                $result['data'][] = array(
                    "id" => $documentgroup->id,
                    "document_group_id" => $documentgroup->document_group_id,
                    "number" => $count,
                    "document_group"=>$documentgroup->group_of_document->name,
                    "document_type" => implode(',',$typeName),
                    "created_at"=> date('d-M-Y', strtotime( $documentgroup->created_at)),
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
        $groupofdocument = Groupofdocument::get();
        $typeofdocument = Typeofdocument::get();
        $addedId = Documentgroupassign::pluck('document_group_id')->unique()->toArray();
        
        return view('admin.documentgroupassigned.create',array('title' => 'Create New Document Group','groupofdocuments'=>$groupofdocument,'typeofdocuments'=>$typeofdocument, 'addedId' => $addedId));
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
            'groupofdocument' => 'required',
            'typeofdocument' => 'required',
        ]);
        try {
            foreach ($request->typeofdocument as $key => $documenttype_value) {
                $success = Documentgroupassign::create([
                    'document_group_id' => $request->groupofdocument,
                    'document_type_id' => $documenttype_value,
                ]);
            }
            if ($success) {   
                $request->session()->flash('alert-success', 'Document group added successfuly.');
            }
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('document.group.assigned.add'));
        } 
        return redirect(route('document.group.assigned.list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Documentgroupassign  $documentgroupassign
     * @return \Illuminate\Http\Response
     */
    public function show(Documentgroupassign $documentgroupassign)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documentgroupassign  $documentgroupassign
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dd($id);
        $groupofdocument = Groupofdocument::get();
        $typeofdocument = Typeofdocument::get();
        $type_id = Documentgroupassign::where('document_group_id',$id)->pluck('document_type_id')->toArray();
        //echo "<pre>";print_r($type_id);exit();
        return view('admin.documentgroupassigned.edit',array('title' => 'Edit Document Group','document_type_id'=>$id,'groupofdocuments'=>$groupofdocument,'typeofdocuments'=>$typeofdocument,'typeofdocumentselected'=>$type_id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documentgroupassign  $documentgroupassign
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'typeofdocument' => 'required',
        ]);

        try {
          
            foreach ($request->typeofdocument as $key => $typedocument_value) {
                $typegroupassigned = Documentgroupassign::where('document_group_id',$id)->where('document_type_id',$typedocument_value)->first();
                if($typegroupassigned){   
                    $typegroupassigned->document_type_id = $typedocument_value;
                    $typegroupassigned->document_group_id = $id;
                    $savedata = $typegroupassigned->save();
                }else{
                    $savedata = Documentgroupassign::create([
                        'document_type_id' => $typedocument_value,
                        'document_group_id' =>  $id,
                    ]);
                }
            }
          
          if($savedata)
          {
              $request->session()->flash('alert-success', 'Document group assigned updated successfuly.');  
          }
          return redirect(route('document.group.assigned.list'));

        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('document.group.assigned.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documentgroupassign  $documentgroupassign
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            
            Documentgroupassign::where('document_group_id',$id)->delete();
            $request->session()->flash('alert-success', 'Document group assigned deleted successfuly.');
            return redirect(route('document.group.assigned.list'));
            
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('document.group.assigned.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documentgroupassign  $documentgroupassign
     * @return \Illuminate\Http\Response
     */
    public function typeTagDelete(Request $request)
    {
        try {
          $Documentgroupassign = Documentgroupassign::where('document_group_id',$request->group_id)->where('document_type_id',$request->tagId)->first();
            if(!$Documentgroupassign){
                return abort(404) ;
            }
            if ($Documentgroupassign->delete()) {
                return response()->json();
            }
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return response()->json();
        }
    }
}
