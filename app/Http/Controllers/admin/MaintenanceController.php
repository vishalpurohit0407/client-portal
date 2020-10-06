<?php

namespace App\Http\Controllers\admin;

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
        
        if($request->ajax()){
            return view('admin.maintenance.ajaxlist',array('maintenance'=>$maintenance));
        }else{
            $categorys = Category::where('status','1')->orderBy('name','asc')->get();
            
            return view('admin.maintenance.list',array('title' => 'Maintenance Guide List','categorys'=>$categorys,'maintenance'=>$maintenance));
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

        return view('admin.maintenance.ajaxlist',array('maintenance'=>$maintenance));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $guidArr = array();
        $guidArr['status'] = '3';
        $guidArr['guide_type'] = 'maintenance';
        $guide = Guide::create($guidArr);

        return redirect(route('admin.maintenance.edit',['guide' => $guide->id ])); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {

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
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $path = 'guide/'.$request->guide_id.'/step_media/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('guide/'.$request->guide_id.'/step_media/',$file,$imageName.".".$imgext);
            
            if($fileAdded){
                $getStepId = GuideSteps::where('step_key',$request->unique_id)->first();
                $guidMediaArr = array();
                $guidMediaArr['step_key'] = $request->unique_id;
                $guidMediaArr['step_id'] = ($getStepId)? $getStepId->id : NULL;
                $guidMediaArr['media'] =  $path;
                $media = GuideStepMedia::create($guidMediaArr);
                return Response::json(['status' => true, 'message' => 'Media uploaded.', 'id' => $media->id]);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }

    public function mainImgUpload(Request $request, $id)
    {
        $file = $request->file('file');
        if($file && $id){
        
            $request->validate([
                'file' => 'mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
            
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $path = 'guide/'.$id.'/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('guide/'.$id.'/',$file,$imageName.".".$imgext);
            
            if($fileAdded){
                $guideData = Guide::find($id);
                Storage::disk('public')->delete($guideData->main_image);
                $media = Guide::where('id',$id)->update(['main_image' => $path]);
                return Response::json(['status' => true, 'message' => 'Media uploaded.']);
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

    public function removeStep(Request $request)
    {

        $steps = GuideSteps::where('step_key',$request->step_key)->first();
        
        if($steps){

            $medias = GuideStepMedia::where('step_id', $steps->id)->get();
            foreach ($medias as $media) {
                Storage::deleteDirectory($media->media);
            }
            GuideStepMedia::where('step_id', $steps->id)->delete();
            $steps->delete();    
            return Response::json(['status' => true, 'message' => 'Step deleted.']);
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
        return view('admin.maintenance.detail',array('title'=>'Maintenance Guide Details','maintenance'=>$guide));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function edit(Guide $guide)
    {
        $category = Category::where('status','1')->get();
        $guide_step = GuideSteps::where('guide_id',$guide->id)->with('media')->orderBy('step_no','asc')->get();
        //dd($guide_step);
        $selectedCategory = Guidecategory::where('guide_id',$guide->id)->pluck('category_id')->toArray();
        return view('admin.maintenance.add',array('title' => 'Add Maintenance Guide','category'=> $category, 'guide' => $guide, 'selectedCategory' => $selectedCategory, 'guide_step' => $guide_step));
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
        //dd($request->all());
        if(!$guide){
            abort(404);
        }

        $messages = [
            'guide_step.*.step_title.required' => 'The title field is required.',
            'guide_step.*.step_description.required' => 'The points/description field is required.',
            //'guide_step.*.stepfilupload.*' => 'Please upload jpg,jpeg,png,bmp image',
        ];

        if($request->submit == 'Save As Draft'){
            
            $request->validate([
                'main_title' => 'required'
            ]);

        }else{
            
            $request->validate([
                'main_title' => 'required',
                'description' => 'required',
                'category' => 'required',
                'type' => 'required',
                'duration' => 'required|numeric',
                'cost' => 'required|numeric|between:0,99999999.99',

                //'guide_step.*.step_title' => 'required',
                //'guide_step.*.step_description' => 'required',
                //'guide_step.*.stepfilupload.*' => 'mimes:jpg,jpeg,png,bmp'
            ]);
        }
 
        $guide->main_title = $request->main_title;  
        $guide->description = $request->description; 
        $guide->type = $request->type;    
        $guide->duration = $request->duration;    
        $guide->duration_type = $request->duration_type;   
        $guide->difficulty = $request->difficulty;  
        $guide->cost = $request->cost;    
        $guide->tags = $request->tags;    
        $guide->introduction = $request->introduction;    
        $guide->introduction_video_type = $request->vid_link_type;
        $guide->introduction_video_link = $request->vid_link_url;
        $guide->status = ($request->submit == 'Save As Draft')? '3' : '1';

        if(isset($request->category) && is_array($request->category)){
            Guidecategory::where('guide_id', $guide->id)->whereNotIn('category_id', $request->category)->delete();
            foreach ($request->category as $key => $cate) {
                
                $checkCat = Guidecategory::where('guide_id', $guide->id)->where('category_id', $cate)->count();
                if($checkCat == 0){
                    $cateArr = array();
                    $cateArr['guide_id'] = $guide->id;
                    $cateArr['category_id'] = $cate;
                    Guidecategory::create($cateArr);
                }
            }
        }

        if(isset($request->guide_step) && is_array($request->guide_step)){
            
            foreach ($request->guide_step as $key1 => $step) {
                
                $stepArr = array();
                $stepArr['title'] = $step['step_title'];

                if($step['step_video_media'] != ''){
                    $stepArr['video_type'] = $step['step_video_type'];
                    $stepArr['video_media'] = $step['step_video_media'];
                }

                $stepArr['description'] = $step['step_description'];

                $checkstep = GuideSteps::where('step_key', $step['step_key'])->where('guide_id',  $guide->id)->first();

                if($checkstep){
                    
                    //$stepData = GuideSteps::where('step_key', $step['step_key'])->where('guide_id',  $guide->id)->update($stepArr);
                    $checkstep->update($stepArr);
                    GuideStepMedia::where('step_key',$checkstep->step_key)->update(['step_id' => $checkstep->id]);
                }else{

                    $stepArr['step_key'] = $step['step_key'];
                    $stepArr['guide_id'] = $guide->id;
                    //dd($stepArr);
                    $stepData = GuideSteps::create($stepArr);
                    GuideStepMedia::where('step_key',$step['step_key'])->update(['step_id' => $stepData->id]);
                }
                
            }
        }

        if ($guide->save()) {
            $request->session()->flash('alert-success', 'Maintenance Guide updated successfuly.');
        }
        return redirect(route('admin.maintenance.list'));
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
                $request->session()->flash('alert-success', 'Maintenance Guide deleted successfuly.');
            }
            return redirect(route('admin.maintenance.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.maintenance.list'));
        }
    }
}
