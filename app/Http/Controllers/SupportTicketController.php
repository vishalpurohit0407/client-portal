<?php

namespace App\Http\Controllers;

use App\Guide;
use App\Category;
use Illuminate\Http\Request;
use Auth;
use App\GuideCompletion;
use PDF;
use Zendesk;

class SupportTicketController extends Controller
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
        //$tickets = Zendesk::users(900868125783)->me();
        //$params = array('query' => 'testalax@gmail.com');
        //$tickets = Zendesk::users()->search($params);
        /*$tickets = Zendesk::tickets()->update(8, [
            //'priority' => 'urgent',
            'comment'  => [
                'body' => 'We have changed your ticket priority to Urgent and will keep you up-to-date asap.',
                "author_id" => 900868125783
            ],
        ]);*/
        /*$tickets = Zendesk::tickets(8)->comments()->sideload(['users'])->findAll();*/
        //dd($tickets);
        return view('supportticket.list',array('title' => 'Support Ticket List'));
    }

    public function listdata(Request $request)
    {
        // echo "<pre>"; print_r($request->session()->token()); exit();
        $columns = array( 
            0 =>'id', 
            1 =>'subject',
            2=> 'department',
            3=> 'status',
            4=> 'priority',
            5=> 'action',
        );
        
        $tickets = Zendesk::tickets()->findAll();
        $totalData = $tickets->count;
            
        $totalFiltered = $totalData; 

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
            
        
        $data = array();
        if(!empty($tickets->tickets))
        {   $srnumber = 1;
            foreach ($tickets->tickets as $ticket)
            {
                //$ticketComments = Zendesk::tickets($ticket->id)->comments()->sideload(['users'])->findAll();
                $nestedData['id'] = $ticket->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['subject'] = ucfirst($ticket->subject);
                $nestedData['department'] = ucfirst($ticket->custom_fields[0]->value);
                $nestedData['status'] = ucfirst($ticket->status);
                $nestedData['priority'] = ucfirst($ticket->priority);
                //$nestedData['comments'] =
                $nestedData['options'] = "";

                
                
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

    public function search(Request $request){
        $selfdiagnosis=Guide::with('guide_category','guide_category.category')->where('main_title','!=','')->where('status','=','1');
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

        return view('selfdiagnosis.ajaxlist',array('selfdiagnosis'=>$selfdiagnosis));
    }

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
        $request->validate([           
            'subject' => 'required',
            'priority' => 'required',
            'department' => 'required',
            'comment' => 'required',
        ]);
        //dd($request->all());
        $newticker = Zendesk::tickets()->create([
            'subject'  => $request->subject,
            'comment'  => array(
                'body' => $request->comment
            ),
            'requester' => array(
                'user_id' => Auth::user()->id,
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
            ),
            'priority' => $request->priority,
            'custom_fields' => array("id" => 900006262866, "value" => $request->department)
        ]);
        dd($newticker);
        if($newticker){
            $request->session()->flash('alert-success','New support ticket created successfully.');
        }

        return redirect(route('user.support.ticket.list'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function show(Guide $guide)
    {
        return view('selfdiagnosis.detail',array('title'=>'Self Diagnosis Details','selfdiagnosis'=>$guide));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Guide  $guide
     * @return \Illuminate\Http\Response
     */
    public function edit(Guide $guide)
    {
        // echo "<pre>";print_r($guide);exit();
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
        //
    }
}
