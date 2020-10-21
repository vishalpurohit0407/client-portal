<?php

namespace App\Http\Controllers;
use App\GuideCompletion;
use Zendesk;
use Auth;
use App\WarrantyExtension;
use App\Guide;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if(Auth::user()->zendesk_id != null){
             $tickets = Zendesk::users(Auth::user()->zendesk_id)->tickets()->requested();
             $totalSupportTicket = $tickets->count;
        }else{
            $totalSupportTicket = 0;
        }
        
        $totalWarrantyRequest = WarrantyExtension::where('user_id',Auth::user()->id)->groupBy('unique_key')->count();
        $totalSelfDiagnosis = GuideCompletion::leftJoin('guide', 'guide_completion.guide_id', '=', 'guide.id')
        ->where('guide_completion.user_id',Auth::user()->id)->where('guide.guide_type','self-diagnosis')->count();
        $totalMaintenance = GuideCompletion::leftJoin('guide', 'guide_completion.guide_id', '=', 'guide.id')
        ->where('guide_completion.user_id',Auth::user()->id)->where('guide.guide_type','maintenance')->count();

        return view('dashboard',array('totalSupportTicket' => $totalSupportTicket, 'totalWarrantyRequest' => $totalWarrantyRequest, 'totalSelfDiagnosis' => $totalSelfDiagnosis, 'totalMaintenance' => $totalMaintenance)); 
    }
}
