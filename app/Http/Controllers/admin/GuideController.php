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
        $category = Category::where('status','1')->get();
        return view('admin.selfdiagnosis.add',array('title' => 'Self Diagnosis Add','category'=>$category));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //echo "<pre>";print_r($request->stepfilupload);exit;

        $messages = [
            'guide_step.*.step_title.required' => 'The title field is required.',
            'guide_step.*.step_description.required' => 'The points/description field is required.',
            //'guide_step.*.stepfilupload.*' => 'Please upload jpg,jpeg,png,bmp image',
        ];
        $request->validate([
            'guide_step.*.step_title' => 'required',
            'guide_step.*.step_description' => 'required',
            //'guide_step.*.stepfilupload.*' => 'mimes:jpg,jpeg,png,bmp'
        ],$messages);

        try {            
            return redirect(route('organization.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('organization.list'));
        }
    }

    public function img_upload(Request $request)
    {
        //dd($request->all());
        if($request->unique_id && $request->file('file_image')){
            $file=$request->file('file_image');
            
            $request->validate([
                'file_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = $request->unique_id;
            $imgext =$file->getClientOriginalExtension();
            $path = 'guide/step_media/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('guide/step_media/',$file,$imageName.".".$imgext);
            
            if($fileAdded){
                $guidMediaArr = array();
                $guidMediaArr['step_key'] = $request->unique_id;
                $guidMediaArr['media'] =  $path;
                $media = GuideStepMedia::create($guidMediaArr);
                return Response::json(['status' => true, 'message' => 'Media uploaded.', 'id' => $media->id]);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }

    public function mainImgUpload(Request $request)
    {
        dd($request->all());
        if($request->unique_id && $request->file('file_image')){
            $file=$request->file('file_image');
            
            $request->validate([
                'file_image' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = $request->unique_id;
            $imgext =$file->getClientOriginalExtension();
            $path = 'guide/step_media/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('guide/step_media/',$file,$imageName.".".$imgext);
            
            if($fileAdded){
                $guidMediaArr = array();
                $guidMediaArr['step_key'] = $request->unique_id;
                $guidMediaArr['media'] =  $path;
                $media = GuideStepMedia::create($guidMediaArr);
                return Response::json(['status' => true, 'message' => 'Media uploaded.', 'id' => $media->id]);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }

    public function removeImage(Request $request)
    {
        $media = GuideStepMedia::find($request->imageId);
        //dd($media);
        if($media){

            if(Storage::disk('public')->delete($media->media)){

               $media->delete(); 
               return Response::json(['status' => true, 'message' => 'Media deleted.']);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }
        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
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
