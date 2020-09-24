<?php

namespace App\Http\Controllers\admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Category;
use Response;
class CategoryController extends Controller
{

    public function __construct() {
        $this->middleware(['web','admin']);
        $this->messages = [
            'required' => 'The :attribute is required.',
            'mime_types' => 'Only excel file allowed.'
        ];

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.category.list',array('title' => 'Category List'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function listdata(Request $request)
    {
      // echo "<pre>"; print_r($request->session()->token()); exit();
      $columns = array( 
                            0 =>'id', 
                            1 =>'name',
                            2=> 'status',
                            3=> 'created_at',
                        );
  
        $totalData = Category::where('status','!=','2')->count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = Category::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->where('status','!=','2')
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 

            $posts =  Category::orWhere('name', 'LIKE',"%{$search}%")
                            ->where('status','!=','2')
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = Category::where('status','!=','2')
                            ->orWhere('name', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($posts))
        {   $srnumber = 1;
            foreach ($posts as $post)
            {
                $destroy =  route('admin.category.destroy',$post->id);
                $edit =  route('admin.category.edit',$post->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $post->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['name'] = ucfirst($post->name);
                if($post->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Active</span>';
                }elseif($post->status == '0'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Inactive</span>';
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
        return view('admin.category.add',array('title' => 'Category Add'));
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
        ]);
        $input = $request->all();
        $input['status']=(isset($input['status']))?'1':'0';
        
        try {
            $category = Category::create($input);
            if($category){ 
              $request->session()->flash('alert-success', 'Category Added successfuly.');
            }
        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.category.add'));
        } 
        return redirect(route('admin.category.list'));
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
        return view('admin.category.edit',array('title' => 'Category Edit','categorydata'=>$category));
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
        ]);

        try {
          
          if(!$category){
            return abort(404) ;
          }
         
          $category->name = $request->name;
          $category->status = (isset($request->status))?'1':'0';
          
          if($category->save())
          {
              $request->session()->flash('alert-success', 'Category updated successfuly.');  
          }
          return redirect(route('admin.category.list'));

        } catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.category.list'));
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
            $category->status = '2';
            if ($category->save()) {
                $request->session()->flash('alert-success', 'Category deleted successfuly.');
            }
            return redirect(route('admin.category.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.category.list'));
        }
    }
}
