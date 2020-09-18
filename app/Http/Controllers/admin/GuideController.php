<?php

namespace App\Http\Controllers\admin;

use App\Guide;
use App\Category;
use Illuminate\Http\Request;

class GuideController extends Controller
{

    public function __construct()
    {
        $this->getrecord = '12';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $selfdiagnosis = Guide::with('guide_category','guide_category.category')->where('status','!=','2')->orderBy('created_at', 'desc')->paginate($this->getrecord);
        
        if($request->ajax()){
            return view('admin.selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
        }else{
            $categorys = Category::all();
            //print_r($categorys);
            return view('admin.selfdiagnosis.list',array('title' => 'Self Diagnosis List','categorys'=>$categorys,'selfdiagnosis'=>$selfdiagnosis));
        }
    }

    public function search(Request $request){

        $selfdiagnosis=Guide::with('guide_category','guide_category.category')->where('status','!=','2');
        //->where('status','!=','2')
        if(isset($request->search) && !empty($request->search)){
            $selfdiagnosis=$selfdiagnosis->where('main_title','LIKE','%'.$request->search.'%');
        }
        if(isset($request->category_id) && !empty($request->category_id)){
            $category_id=$request->category_id;
            $selfdiagnosis=$selfdiagnosis->whereHas('guide_category', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
        }
        $selfdiagnosis=$selfdiagnosis->orderBy('created_at', 'desc')->paginate($this->getrecord);

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function show(Guide $guide)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function edit(Guide $guide)
    {
        echo "<pre>";print_r($guide);exit();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guide $guide)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guide $guide, Request $request)
    {
        try {
            if(!$guide){
                return abort(404) ;
            }
            $guide->status = '2';
            if ($guide->save()) {
                $request->session()->flash('alert-success', 'Selfdiagnosis deleted successfuly.');
            }
            return redirect(route('admin.selfdiagnosis.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.selfdiagnosis.list'));
        }
    }
}
