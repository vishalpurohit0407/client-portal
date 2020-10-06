<?php

namespace App\Http\Controllers;

use App\Guide;
use App\Category;
use Illuminate\Http\Request;
use Auth;
use App\GuideCompletion;
use PDF;
use Zendesk;
use Response;

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
        return view('supportticket.list',array('title' => 'Support Ticket List'));
    }

    public function listdata(Request $request)
    {
        
        if(Auth::user()->zendesk_id == NULL){
            
            $json_data = array(
                    "draw"            => intval($request->input('draw')),  
                    "recordsTotal"    => 0,  
                    "recordsFiltered" => 0, 
                    "data"            => []   
                    );
            echo json_encode($json_data);die();    
        }
        
        // echo "<pre>"; print_r($request->session()->token()); exit();
        $columns = array( 
            0 =>'id', 
            1 =>'subject',
            2=> 'department',
            3=> 'status',
            4=> 'priority',
            5=> 'action',
        );

        $pageNumber = $request->pageNumber + 1;
         
        $tickets = Zendesk::users(Auth::user()->zendesk_id)->tickets()->requested(['page' => $pageNumber, 'per_page' => $request->length, 'sort_by' => 'created_at', 'sort_order' => 'desc']);
        
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
                $nestedData['id'] = $ticket->id;
                $nestedData['srnumber'] = $srnumber;
                $nestedData['requester_id'] = $ticket->requester_id;
                $nestedData['subject'] = ucfirst($ticket->subject);
                $nestedData['department'] = ucfirst($ticket->custom_fields[0]->value);
                $nestedData['status'] = ucfirst($ticket->status);
                $nestedData['priority'] = ucfirst($ticket->priority);
                //$nestedData['comments'] = $commentsArr;
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
            'custom_fields' => array("id" => env('ZENDESK_CUSTOM_FIELD_ID'), "value" => $request->department)
        ]);
        
        if($newticker){
            $user = Auth::user();
            if($user->zendesk_id == NULL){
                $user->zendesk_id = (string)$newticker->ticket->requester_id;
                $user->save();
            }
            $request->session()->flash('alert-success','New support ticket created successfully.');
        }

        return redirect(route('user.support.ticket.list'));
    }

    public function getcomment(Request $request)
    {
        //dd($request->all());
        if($request->ticket_id != ''){

            $ticketComments = Zendesk::tickets($request->ticket_id)->comments()->sideload(['users'])->findAll();
            $commentsArr = array();
            if($ticketComments){
                foreach ($ticketComments->comments as $key => $comment) {
                    
                    $key = array_search($comment->author_id, array_column($ticketComments->users, 'id'));
                    $commentsArr[] = ['comment' => $comment->body, 'created_at' => date('d M Y H:i A',strtotime($comment->created_at)), 'author' => $ticketComments->users[$key]->name, 'author_type' => $ticketComments->users[$key]->role];
                }
            }

            return Response::json(['status' => true, 'message' => 'Comment fetch successfully.', 'comments' => $commentsArr]);
            
        }
        return Response::json(['status' => false, 'message' => 'Something went wrong.']);
    }

    public function sendComment(Request $request)
    {
        if($request->ticket_id != '' && $request->commentText != '' && $request->requester_id != ''){
            $comment = Zendesk::tickets()->update($request->ticket_id, [
                
                'comment'  => [
                    'body' => $request->commentText,
                    "author_id" => $request->requester_id
                ],
            ]);
            //dd($comment);
            if($comment){

                return Response::json(['status' => true, 'message' => 'Comment send successfully.', 'sender_name' => $comment->audit->via->source->to->name, 'created_at' => date('d M Y H:i A',strtotime($comment->ticket->created_at))]);
            }
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
