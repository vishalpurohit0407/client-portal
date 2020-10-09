<?php
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Storage;
  
class CkeditorController extends Controller{
  
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request)
    {
        //dd($request->all());
        if($request->hasFile('upload')) {
            // $originName = $request->file('upload')->getClientOriginalName();
            // $fileName = pathinfo($originName, PATHINFO_FILENAME);
            // $extension = $request->file('upload')->getClientOriginalExtension();
            // $fileName = $fileName.'_'.time().'.'.$extension;
            // $request->file('upload')->move(public_path('images/editor'), $fileName);
            
            $file=$request->file('upload');
            $file_name =$file->getClientOriginalName();
            $fileslug= pathinfo($file_name, PATHINFO_FILENAME);
            $imageName = md5($fileslug.time());
            $imgext =$file->getClientOriginalExtension();
            $fileName = 'ckeditor/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('ckeditor/',$file,$imageName.".".$imgext);

            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = Storage::url($fileName);   
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }
}