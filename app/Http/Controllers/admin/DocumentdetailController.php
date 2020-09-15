<?php

namespace App\Http\Controllers\admin;

use App\Documentdetail;
use Illuminate\Http\Request;
use App\User;
use Response;

class DocumentdetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.document.list',array('title' => 'Document Detail List'));    
    }

    public function listdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $userdata = User::with('user_detail')->where('status','!=','3')->where('user_type','user');
        if(isset($request['generalSearch']) && !empty(isset($request['generalSearch']))){
            $keyword=$request['generalSearch'];
            $userdata=$userdata->where(function ($query) use ($keyword) {
                $query->where('user_detail.first_name', 'like', '%'.$keyword.'%')
                      ->orWhere('user_detail.last_name', 'like', '%'.$keyword.'%')
                      ->orWhere('email', 'like', '%'.$keyword.'%')
                      ->orWhere('id', 'like', '%'.$keyword.'%');
            });
        }
        $userdata=$userdata->orderBy($field,$sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($userdata);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $userdata->lastPage(),
            'perpage' => $perpage,
            'total' => $userdata->total(),
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

        if($userdata){
            $count = $pageCal;
            foreach ($userdata as $key => $user) {

                $result['data'][] = array(
                    "id" => $user->id,
                    "number" => $count,
                    "dibil_number"=>$user->dibil_number,
                    "name" => $user->user_detail ? $user->user_detail->name :'N/A',
                    "email"=> $user->email,
                    "mobile"=> $user->mobile,
                    "status"=> $user->status,
                    "created_at"=> date('d-M-Y', strtotime( $user->created_at)),
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
     * @param  \App\Documentdetail  $documentdetail
     * @return \Illuminate\Http\Response
     */
    public function show(Documentdetail $documentdetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Documentdetail  $documentdetail
     * @return \Illuminate\Http\Response
     */
    public function edit(Documentdetail $documentdetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Documentdetail  $documentdetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Documentdetail $documentdetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Documentdetail  $documentdetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(Documentdetail $documentdetail)
    {
        //
    }
}
