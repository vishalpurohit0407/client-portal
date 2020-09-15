<?php

namespace App\Http\Controllers\admin;

use App\Cmspag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;

class CmspagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cms_pages.list',array('title' => 'CMS Page List'));    
    }

    public function listdata(Request $request){
        $result = array();
        $perpage= (isset($request->pagination['perpage']))? $request->pagination['perpage'] : 10;
        $page= (isset($request->pagination['page']))? $request->pagination['page'] : 1;
        $sort= (isset($request->sort['sort']))? $request->sort['sort'] : 'desc';
        $field= (isset($request->sort['field']))? $request->sort['field'] : 'created_at';
        $CmsPage = Cmspag::orderBy('created_at','desc');
        if(isset($request['query']['generalSearch']) && !empty(isset($request['query']['generalSearch']))){
            $keyword=$request['query']['generalSearch'];
            $CmsPage=$CmsPage->where(function ($query) use ($keyword) {
                $query->where('title', 'like', '%'.$keyword.'%')
                      ->orWhere('url_slug', 'like', '%'.$keyword.'%')
                      ->orWhere('content', 'like', '%'.$keyword.'%');
            });
        }
        $CmsPage=$CmsPage->orderBy($field,$sort)->paginate($perpage,['*'],'page',$page);
        // echo "<pre>";print_r($CmsPage);exit;
        $result['meta']=array(
            'page' => $page,
            'pages' => $CmsPage->lastPage(),
            'perpage' => $perpage,
            'total' => $CmsPage->total(),
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

        if($CmsPage){
            $count = $pageCal;
            foreach ($CmsPage as $key => $page) {

                $result['data'][] = array(
                    "id" => $page->id,
                    "number" => $count,
                    "title"=>$page->title,
                    "content" => $page->content,
                    "url_slug"=> $page->url_slug,                    
                    "status"=> $page->status,
                    "created_at"=> date('d-M-Y', strtotime($page->created_at)),
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
        return view('admin.cms_pages.create',array('title' => 'CMS Page Create'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required', 
        ]);
        try {
            $input = $request->all();
            $input['title'] = $request->title;
            $input['content'] = $request->content;
            $input['url_slug'] = Str::slug($request->title, '-');
            $input['status'] =(isset($input['status']))?$input['status']:'0';
            if(Cmspag::create($input))
            {
                $request->session()->flash('alert-success', 'User updated successfuly.');  
            }
            return redirect(route('cms.pages.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('cms.pages.list'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CmsPage  $CmsPage
     * @return \Illuminate\Http\Response
     */
    public function show(Cmspag $Cmspag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CmsPage  $CmsPage
     * @return \Illuminate\Http\Response
     */
    public function edit(Cmspag $cmspag)
    {
        if (!$cmspag) {
          return abort(404);
        }
        return view('admin.cms_pages.edit',array('title' => 'Edit CMS Page','page'=>$cmspag));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CmsPage  $CmsPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Cmspag $cmspag)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required', 
        ]);
        try {
            if (!$cmspag) {
              return abort(404);
            }
            $cmspag->title = $request->title;
            $cmspag->content = $request->content;
            $cmspag->url_slug = Str::slug($request->title, '-');
            if ($request->status != "") {
                $cmspag->status = $request->status;
            }else{
                $cmspag->status = "0";
            }
            if($cmspag->save())
            {
                $request->session()->flash('alert-success', 'CMS Page updated successfuly.');  
            }
            return redirect(route('cms.pages.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('cms.pages.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CmsPage  $CmsPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cmspag $cmspag)
    {
        try {
            if(!$cmspag){
                return abort(404) ;
            }
            $cmspag->status = '0';
            if ($cmspag->save()) {
                $request->session()->flash('alert-success', 'CMS Page deleted successfuly.');
            }
            return redirect(route('cms.pages.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('cms.pages.list'));
        }
    }
}
