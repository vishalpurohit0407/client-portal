<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CmsPage;

class PagepreviewController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    public function pagepreview(Request $request,$url_slug)
    {
        $cmspage = CmsPage::where('url_slug',$url_slug)->first();
        if(!$cmspage){
            return abort(404);
        }
        return view('pagepreview',array('title' => 'CMS Page List','page'=>$cmspage));
    }
}
