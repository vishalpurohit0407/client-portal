<?php

namespace App\Http\Controllers\admin;

use App\CmsPage;
use Illuminate\Http\Request;
use Response;
use Str;

class CmsPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.cms_pages.list',array('title' => 'CMS Pages List'));
    }

    public function listdata(Request $request){
        // echo "<pre>"; print_r($request->session()->token()); exit();
      $columns = array( 
                        0 => 'id', 
                        1 => 'title',
                        2 => 'url_slug',
                        3 => 'status',
                        4 => 'created_at',
                    );
  
        $totalData = CmsPage::count();
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        if(empty($request->input('search.value')))
        {            
            $posts = CmsPage::offset($start)
                         ->limit($limit)
                         ->orderBy($order,$dir)
                         ->get();
        }
        else {
            $search = $request->input('search.value'); 
            $posts =  CmsPage::where('url_slug','LIKE',"%{$search}%")
                            ->orWhere('title', 'LIKE',"%{$search}%")
                            ->offset($start)
                            ->limit($limit)
                            ->orderBy($order,$dir)
                            ->get();

            $totalFiltered = CmsPage::where('url_slug','LIKE',"%{$search}%")
                            ->orWhere('title', 'LIKE',"%{$search}%")
                            ->count();
        }

        $data = array();
        if(!empty($posts))
        {   $srnumber = 1;
            foreach ($posts as $post)
            {
                $view =  route('cms.pagepreview',$post->url_slug);
                $edit =  route('admin.cms.page.edit',$post->id);
                $token =  $request->session()->token();

                $nestedData['id'] = $post->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['title'] = $post->title;
                $nestedData['url_slug'] = $post->url_slug;
                if($post->status == '0'){
                  $nestedData['status'] = '<span class="badge badge-pill badge-warning">Inactive</span>';
                }elseif($post->status == '1'){ 
                  $nestedData['status'] = '<span class="badge badge-pill badge-success">Active</span>';
                };
                $nestedData['created_at'] = date('d-M-Y',strtotime($post->created_at));
                $nestedData['options'] = "&emsp;<a href='{$edit}' class='btn btn-primary btn-sm mr-0' title='EDIT' >Edit</a>
                                          &emsp;<a href='{$view}' target='_blank' class='btn btn-info btn-sm mr-0' title='VIEW' >View</a>";
                                          // &emsp;<form action='' method='POST' style='display: contents;' id='frm_{$post->id}'> <input type='hidden' name='_method' value='DELETE'> <input type='hidden' name='_token' value='{$token}'> <a type='submit' class='btn btn-danger btn-sm' style='color: white;' onclick='return deleteConfirm(this);' id='{$post->id}'>Delete</a></form>
                $srnumber++;
                $data[] = $nestedData;
            }
        }
          
        $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => intval($totalData),  
                    "recordsFiltered" => intval($totalFiltered), 
                    "data"            => $data   
                    );
            
        echo json_encode($json_data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // return abort(404);
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
            if(CmsPage::create($input))
            {
                $request->session()->flash('alert-success', 'CMS Page created successfuly.');  
            }
            return redirect(route('admin.cms.page.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.cms.page.list'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function show(CmsPage $cmsPage)
    {
        //  
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function edit(CmsPage $cmsPage)
    {
        if (!$cmsPage) {
          return abort(404);
        }
        return view('admin.cms_pages.edit',array('title' => 'Edit CMS Page','page'=>$cmsPage));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CmsPage $cmsPage)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required', 
        ]);
        try {
            if (!$cmsPage) {
              return abort(404);
            }
            $cmsPage->title = $request->title;
            $cmsPage->content = $request->content;
            //$cmsPage->url_slug = Str::slug($request->title, '-');
            if ($request->status != "") {
                $cmsPage->status = '1';
            }else{
                $cmsPage->status = "0";
            }
            if($cmsPage->save())
            {
                $request->session()->flash('alert-success', 'CMS Page updated successfuly.');  
            }
            return redirect(route('admin.cms.page.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.cms.page.list'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CmsPage  $cmsPage
     * @return \Illuminate\Http\Response
     */
    public function destroy(CmsPage $cmsPage)
    {
        try {
            if(!$cmsPage){
                return abort(404) ;
            }
            $cmsPage->status = '0';
            if ($cmsPage->save()) {
                $request->session()->flash('alert-success', 'CMS Page deleted successfuly.');
            }
            return redirect(route('admin.cms.page.list'));
        }catch (ModelNotFoundException $exception) {
            $request->session()->flash('alert-danger', $exception->getMessage()); 
            return redirect(route('admin.cms.page.list'));
        }
    }
}
