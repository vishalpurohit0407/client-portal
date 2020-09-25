<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Response;

class CategoryController extends Controller
{

    public function __construct() {
      $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.category.list1',array('title' => 'Organization Type List'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listdata(Request $request)
    {
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'asc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'name';
        
        $categories = Category::where('status','!=', '0')->orderBy($field,$sort);
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $categories=$categories->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%'.$keyword.'%');
            });
        }
        $categories=$categories->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($request['query']);exit();
        $result['meta']=array(
            'page' => $page,
            "pages" => $categories->lastPage(),
            'perpage' => $perpage,
            'total' => $categories->total(),
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

        if(count($categories) > 0){
            $count = $pageCal;
            foreach ($categories as $key => $category) {

                $result['data'][] = array(
                    "id" => $category->id,
                    "number" => $count,
                    "name" => $category->name,
                    "status" => $category->status,
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
        $document_group_assigned = Documentgroupassign::orderBy('created_at','desc')->pluck('document_group_id')->toArray();
        $groupofdocument = Groupofdocument::whereIn('id',array_unique($document_group_assigned))->get();
        return view('admin.category.add',array('title' => 'Organization Type Add','groupofdocuments'=>$groupofdocument));
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
            'groupofdocument' => 'required',
        ]);
        $input = $request->all();
        $input['status']=(isset($input['status']))?$input['status']:'2';
        
        try {
            $category = Category::create($input);
            if($category){
                foreach ($request->groupofdocument as $key => $groupdocument_value) {
                    Organizationtypegroupassigned::create([
                        'organization_type_id' => $category->id,
                        'document_group_assign_id' => $groupdocument_value,
                    ]);
                }
            }
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('category.add'));
        }
        $request->session()->flash('alert-success', 'Organization Type Added successfuly.'); 
        return redirect(route('category.list'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {    
      //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {    

        $document_group_id = Documentgroupassign::orderBy('created_at','desc')->pluck('document_group_id')->toArray();
        $groupofdocument = Groupofdocument::whereIn('id',array_unique($document_group_id))->get();
        $Organizationtypegroupassigned = Organizationtypegroupassigned::where('organization_type_id',$category->id)->pluck('document_group_assign_id')->toArray();
        return view('admin.category.edit',array('title' => 'Organization Type Edit','categorydata'=>$category,'groupofdocuments'=>$groupofdocument,'Organizationtypegroupassigned'=>$Organizationtypegroupassigned));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category, Request $request)
    {
        $request->validate([
            'name' => 'required',
            'groupofdocument' => 'required',
        ]);

        try {
          
          if(!$category){
            return abort(404) ;
          }
         
          $category->name = $request->name;
          $category->status = (isset($request->status))?$request->status:'2';
            foreach ($request->groupofdocument as $key => $groupdocument_value) {
                $typegroupassigned = Organizationtypegroupassigned::where('organization_type_id',$category->id)->where('document_group_assign_id',$groupdocument_value)->first();
                if($typegroupassigned){   
                    $typegroupassigned->organization_type_id = $category->id;
                    $typegroupassigned->document_group_assign_id = $groupdocument_value;
                    $typegroupassigned->save();
                }else{
                    Organizationtypegroupassigned::create([
                        'organization_type_id' => $category->id,
                        'document_group_assign_id' => $groupdocument_value,
                    ]);
                }
            }
          
          if($category->save())
          {
              $request->session()->flash('alert-success', 'Organization Type updated successfuly.');  
          }
          return redirect(route('category.list'));

        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('category.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Request $request)
    {
        try {
          
            if(!$category){
                return abort(404) ;
            }
            $category->status = '0';
            if ($category->save()) {
                // Storage::deleteDirectory(storage_path('app\category/'.$id));
                $request->session()->flash('alert-success', 'Organization Type deleted successfuly.');
            }
            return redirect(route('category.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('category.list'));
        }
    }

    public function orgTagDelete(Request $request)
    {
        try {
          $organizationtypegroupassigned = Organizationtypegroupassigned::where('organization_type_id',$request->categoryId)->where('document_group_assign_id',$request->tagId)->first();
            if(!$organizationtypegroupassigned){
                return abort(404) ;
            }
            if ($organizationtypegroupassigned->delete()) {
                return response()->json();
            }
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return response()->json();
        }
    }
}
