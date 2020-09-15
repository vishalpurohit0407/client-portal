<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Typeofdocument;
use App\Common;
use Response;

class TypeOfDocumentController extends Controller
{

    public function __construct() {
      $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.document_type.list',array('title' => 'Document Type List'));
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
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';

        if(isset($request['query']) && isset($request['query']['category_id'])){
            $parent_id=$request['query']['category_id'];
        }else{
            $parent_id=0;
        }
        
        $categories = Typeofdocument::where('parent_id',$parent_id)->where('status','1')->orderBy($field,$sort);
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
                    "product_img" => ($category->icon)?asset($category->icon_path):'',
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
        $parentCategory = Typeofdocument::where('parent_id',0)->where('status','1')->get();
        //dd($parentCategory[0]->icon_path);
        return view('admin.document_type.add',array('title' => 'Document Type Add','parentCategory'=>$parentCategory));
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
            'category_img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        
        try {

            $catArr = array();
            $catArr['name'] = $request->name;
            $catArr['parent_id'] = $request->parent_id;
            $file=$request->file('category_img');
            if($file){
                
                $path = Common::storeImage($file=$file,$type='document_type',NULL);
                $catArr['icon']  = $path;
            }

            $category = Typeofdocument::create($catArr);
            
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('documenttype.add'));
        }
        $request->session()->flash('alert-success', 'Document Type Added successfuly.'); 
        return redirect(route('documenttype.list'));
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
    public function edit(Typeofdocument $typeofdocument)
    {    
        $parentCategory = Typeofdocument::where('parent_id',0)->where('status','1')->get();
        return view('admin.document_type.edit',array('title' => 'Document Type Edit','parentCategory'=>$parentCategory,'typeofdocument'=>$typeofdocument));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Typeofdocument $typeofdocument, Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        try {
          
          if(!$typeofdocument){
            return abort(404) ;
          }

          $typeofdocument->name = $request->name;
          $typeofdocument->parent_id = $request->parent_id;
          $file=$request->file('category_img');
          if($file){
              $request->validate([
                  'category_img' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048'
              ]);
              if($typeofdocument->icon) { 
                  Common::deleteImage($fileurl = $typeofdocument->icon_path);
              }

              $path = Common::storeImage($file=$file,$type='document_type',NULL);
              $typeofdocument->icon  = $path;
          }
          
          if($typeofdocument->save())
          {
              $request->session()->flash('alert-success', 'Document Type updated successfuly.');  
          }
          return redirect(route('documenttype.list'));

        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('documenttype.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Typeofdocument $typeofdocument, Request $request)
    {
        //dd($typeofdocument);
        try {
          
            if(!$typeofdocument){
                return abort(404) ;
            }
            $typeofdocument->status = '2';
            if ($typeofdocument->save()) {
                Typeofdocument::where('parent_id',$typeofdocument->id)->update(['status' => '2']);
                $request->session()->flash('alert-success', 'Organization Type deleted successfuly.');
            }
            return redirect(route('documenttype.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('documenttype.list'));
        }
    }
}
