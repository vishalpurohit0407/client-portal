<?php

namespace App\Http\Controllers\admin\admin;

use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Selfdiagnosis;
use App\Guidecategory;
use Response;
use Hash;
use Auth;
use Storage;

class SelfDiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $selfdiagnosis = Selfdiagnosis::with('guide_category','guide_category.category')->orderBy('created_at', 'desc')->paginate(6);
        // Selfdiagnosis::with('guide_category','guide_category.category')
        
        if($request->ajax()){
            return view('admin.selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
        }else{
            $categorys = Category::all();
            //print_r($categorys);
            return view('admin.selfdiagnosis.list',array('title' => 'Self Diagnosis List','categorys'=>$categorys,'selfdiagnosis'=>$selfdiagnosis));
        }
    }

    public function search(Request $request){

        $selfdiagnosis=Selfdiagnosis::with('guide_category','guide_category.category')
            ->orderBy('created_at', 'desc');
        if(isset($request->search) && !empty($request->search)){
            $selfdiagnosis=$selfdiagnosis->where('main_title','LIKE','%'.$request->search."%");
        }
        if(isset($request->category_id) && !empty($request->category_id)){
            $category_id=$request->category_id;
            $selfdiagnosis=$selfdiagnosis->whereHas('guide_category', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
        }
        $selfdiagnosis=$selfdiagnosis->paginate(6);

        return view('admin.selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
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

        $messages = [
            'guide_step.*.step_title.required' => 'The title field is required.',
            'guide_step.*.step_description.required' => 'The points/description field is required.',
        ];
        $request->validate([
            'guide_step.*.step_title' => 'required',
            'guide_step.*.step_description' => 'required'
        ],$messages);

        try {            
            return redirect(route('organization.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.list'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Selfdiagnosis $selfdiagnosis, Request $request)
    {
        echo "<pre>";print_r($selfdiagnosis);exit();
        try {
            if(!$selfdiagnosis){
                return abort(404) ;
            }
            $selfdiagnosis->status = '3';
            if ($selfdiagnosis->save()) {
            }
            return redirect(route('admin.selfdiagnosis.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.selfdiagnosis.list'));
        }
    }



    
}
