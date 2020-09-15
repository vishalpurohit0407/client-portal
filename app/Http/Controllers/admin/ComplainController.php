<?php

namespace App\Http\Controllers\admin;

use App\Complain;
use App\Dibilrequest;
use App\UserDetail;
use App\Document;
use App\Documentmedia;
use App\Pushnotification;
use Illuminate\Http\Request;
use Response;

class ComplainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.complain.list',array('title' => 'Complain List'));
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
        $complains =  Complain::join('user_details','user_details.user_id','=','complains.user_id')
        ->select('user_details.name','complains.*')
        ->with('dibilrequest','user','userdetail');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $complains=$complains->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%'.$keyword.'%')
                      ->orWhere('description', 'like', '%'.$keyword.'%')
                      ->orWhere('name', 'like', '%'.$keyword.'%')
                      ->orWhere('admin_review', 'like', '%'.$keyword.'%');
            });
        }
        
        if($field == 'user'  || $field == 'recipient'){
            $field = 'user_details.name';
        }

        $complains=$complains->orderBy($field, $sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($complains);exit();
        $result['meta']=array(
            'page' => $page,
            "pages" => $complains->lastPage(),
            'perpage' => $perpage,
            'total' => $complains->total(),
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

        if(count($complains) > 0){
            $count = $pageCal;
            foreach ($complains as $key => $complain) {

                $result['data'][] = array(
                    "id" => $complain->id,
                    "number" => $count,
                    "user" => $complain->userdetail->name,
                    "userid" => $complain->userdetail->user_id,
                    "userimage" => $complain->userdetail->image,
                    "email" => $complain->user->email,
                    "recipientimage" => $complain->dibilrequest ? $complain->dibilrequest->recipientdetail->image : '',
                    "recipient" => $complain->dibilrequest ? $complain->dibilrequest->recipientdetail->name : "N/A",
                    "recipientemail" => $complain->dibilrequest ? $complain->dibilrequest->recipient->email : "N/A",
                    "recipientid" => $complain->dibilrequest ? $complain->dibilrequest->recipient->id : "N/A",
                    "title" => $complain->title,
                    "description" => $complain->description,
                    "admin_review" => $complain->admin_review,
                    "status" => $complain->status,
                    "created_at"=> date('d-M-Y', strtotime($complain->created_at)),
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
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function show(Complain $complain)
    {
        $dibilerequest = Dibilrequest::where('id',$complain->request_id)->first();
        if (!$dibilerequest) {
            abort(404);
        }
        $document = Document::where('id',$dibilerequest->document_id)->first();
        $document_media = Documentmedia::where('document_id',$dibilerequest->document_id)->get();
        // echo "<pre>";print_r($document_media);exit();
        return view('admin.complain.detail',array('title' => 'Complain Detail','data'=>$complain,'document_media'=>$document_media,'document'=>$document,'dibilerequest'=>$dibilerequest));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function edit(Complain $complain)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Complain $complain)
    {
        try {
            if (!$complain) {
              return abort(404);
            }
            $complain->admin_review = $request->admin_review ? $request->admin_review : NULL;
            $complain->status = '1';            
            if($complain->save())
            {
                $payload = array("conversation_id"=>0,"type"=>"6","badge"=>"1");
                $inpute = $request->all();
                $inpute['user_id'] = $complain->dibilrequest->user->id;
                $inpute['title'] = 'Complaint review';
                $inpute['message'] = 'Dear user admin has ben reviewed your complain.';
                $inpute['payload'] = $payload;
                $notification = Pushnotification::create($inpute);
                $request->session()->flash('alert-success', 'Review added successfuly.');  
            }
            return redirect(route('complain.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('complain.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Complain  $complain
     * @return \Illuminate\Http\Response
     */
    public function destroy(Complain $complain, Request $request)
    {
        //
    }
}
