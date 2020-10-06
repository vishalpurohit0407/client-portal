<?php

namespace App\Http\Controllers;

use App\Guide;
use App\Category;
use Illuminate\Http\Request;
use Storage;
use App\GuideStepMedia;
use App\Guidecategory;
use App\GuideSteps;
use Response;

class MaintenanceController extends Controller
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
        $maintenance = Guide::with('guide_category','guide_category.category')->where('guide_type','=','maintenance')->where('main_title','!=','')->where('status','!=','2')->orderBy('created_at', 'desc')->paginate($this->getrecord);
        // echo "<pre>";print_r($maintenance);exit();
        if($request->ajax()){
            return view('maintenance.ajaxlist',array('maintenances'=>$maintenance));
        }else{
            $categorys = Category::where('status','1')->orderBy('name','asc')->get();
            
            return view('maintenance.list',array('title' => 'Maintenance Guide List','categorys'=>$categorys,'maintenances'=>$maintenance));
        }
    }

    public function search(Request $request){

        $maintenance=Guide::with('guide_category','guide_category.category')->where('guide_type','=','maintenance')->where('main_title','!=','')->where('status','!=','2');
        //->where('status','!=','2')
        if(isset($request->search) && !empty($request->search)){
            $maintenance=$maintenance->where('main_title','LIKE','%'.$request->search.'%');
        }
        if(isset($request->category_id) && !empty($request->category_id)){
            $category_id=$request->category_id;
            $maintenance=$maintenance->whereHas('guide_category', function ($query) use ($category_id) {
                    $query->where('category_id', $category_id);
                });
        }
        $maintenance=$maintenance->orderBy('created_at', 'desc')->paginate($this->getrecord);

        return view('maintenance.ajaxlist',array('maintenances'=>$maintenance));
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
        return view('maintenance.detail',array('title'=>'Maintenance Guide Details','maintenance'=>$guide));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function edit(Guide $guide)
    {
        //
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
        //
    }
}
