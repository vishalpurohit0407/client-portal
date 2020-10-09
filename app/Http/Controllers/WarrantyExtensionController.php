<?php

namespace App\Http\Controllers;

use App\WarrantyExtension;
use Illuminate\Http\Request;
use Storage;
use Response;

class WarrantyExtensionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warranty_extension.add',array('title' => 'Warranty Extension'));
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
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function show(WarrantyExtension $warrantyExtension)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function edit(WarrantyExtension $warrantyExtension)
    {
        return view('warranty_extension.add',array('title' => 'Warranty Extension','warranty'=>$warrantyExtension));
    }

    public function userImgUpload(Request $request, $id)
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
            $path = 'warranty_extension/'.$id.'/'.$imageName.".".$imgext;
            $fileAdded = Storage::disk('public')->putFileAs('warranty_extension/'.$id.'/',$file,$imageName.".".$imgext);
            
            if($fileAdded){
                $guideData = WarrantyExtension::find($id);
                Storage::disk('public')->delete($guideData->picture_by_user);
                $media = WarrantyExtension::where('id',$id)->update(['picture_by_user' => $path]);
                return Response::json(['status' => true, 'message' => 'Media uploaded.']);
            }
            return Response::json(['status' => false, 'message' => 'Something went wrong.']);
        }

        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WarrantyExtension $warrantyExtension)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\WarrantyExtension  $warrantyExtension
     * @return \Illuminate\Http\Response
     */
    public function destroy(WarrantyExtension $warrantyExtension)
    {
        //
    }
}
