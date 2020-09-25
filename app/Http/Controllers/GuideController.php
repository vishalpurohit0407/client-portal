<?php

namespace App\Http\Controllers;

use App\Guide;
use App\Category;
use Illuminate\Http\Request;
use Auth;
use App\GuideCompletion;
use PDF;

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
        $selfdiagnosis = Guide::with('guide_category','guide_category.category')->where('main_title','!=','')->where('status','=','1')->orderBy('created_at', 'desc')->paginate($this->getrecord);
        
        if($request->ajax()){
            return view('selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
        }else{
            $categorys = Category::where('status','1')->orderBy('name','asc')->get();
            //print_r($categorys);
            return view('selfdiagnosis.list',array('title' => 'Self Diagnosis List','categorys'=>$categorys,'selfdiagnosis'=>$selfdiagnosis));
        }
    }

    public function search(Request $request){
        $selfdiagnosis=Guide::with('guide_category','guide_category.category')->where('main_title','!=','')->where('status','=','1');
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

        return view('selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function completedGuide(Request $request, $guide_id)
    {
        try {
            if (!$guide_id) {
              return abort(404);
            }
            $completed_guide = new GuideCompletion;
            $completed_guide->guide_id = $guide_id;
            $completed_guide->user_id = Auth::user()->id;
            if($completed_guide->save())
            {
                $request->session()->flash('alert-success', 'Guide completed successfuly.');  
            }
            return redirect(route('user.selfdiagnosis.show',$guide_id));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('user.selfdiagnosis.show',$guide_id));
        };
    }

    public function createPDF(Request $request ,$guide_id) {
        // retreive all records from db
        $selfdiagnosis = Guide::find($guide_id);
        if (!$selfdiagnosis) {
            return abort(404);
        }
        // share data to view
        view()->share('selfdiagnosis',$selfdiagnosis);
        // return view('selfdiagnosis.pdf_view', $selfdiagnosis);
        $pdf = PDF::loadView('selfdiagnosis.pdf_view', $selfdiagnosis);
        $pdf->setPaper('A4', 'landscape'); 
        return $pdf->download('pdf_file.pdf');
    }

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
        return view('selfdiagnosis.detail',array('title'=>'Self Diagnosis Details','selfdiagnosis'=>$guide));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function edit(Guide $guide)
    {
        // echo "<pre>";print_r($guide);exit();
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
