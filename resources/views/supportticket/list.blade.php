<!-- Admin Dashboard Page -->
@extends('layouts.app')

@section('content')
      <div class="header bg-primary pb-6">
        <div class="container-fluid">
          <div class="header-body">
            <div class="row align-items-center py-4">
              <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">{{$title}}</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                  </ol>
                </nav>
              </div>
              <div class="col-lg-6 col-5 text-right">
                
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Table -->
      @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has('alert-' . $msg))
              <div class="alert alert-custom alert-{{ $msg }} alert-dismissible fade show mb-2" role="alert">                           
                  <div class="alert-text">{{ Session::get('alert-' . $msg) }}</div>
                  <div class="alert-close">
                      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">×</span>
                      </button>
                  </div>
              </div>
          @endif 
      @endforeach
      <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0">Add New Support Ticket</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <!-- Form groups used in grid -->
            <form method="post" action="{{route('user.support.ticket.store')}}"> 
                @csrf
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group @if($errors->has('subject')) has-danger @endif ">
                            <label class="form-control-label" for="subject">Subject&nbsp;<strong class="text-danger">*</strong></label>
                            <input type="text" class="form-control  @if($errors->has('subject')) is-invalid @endif maxlength" name="subject" id="subject" placeholder="Subject">
                            @if($errors->has('subject'))
                                <span class="form-text text-danger">{{ $errors->first('subject') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <label class="form-control-label" for="priority">Priority&nbsp;<strong class="text-danger">*</strong></label>
                        <div class="form-group">
                            <select class="form-control @if($errors->has('priority')) is-invalid @endif" name="priority" id="priority">
                                <option value="low">Low</option>
                                <option value="normal">Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                            @if($errors->has('priority'))
                                <span class="form-text text-danger">{{ $errors->first('priority') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-control-label" for="priority">Department&nbsp;<strong class="text-danger">*</strong></label>
                            <select class="form-control @if($errors->has('department')) is-invalid @endif" name="department" id="department">
                                <option value="support">Support</option>
                                <option value="billing">Billing</option>
                                <option value="sales">Sales</option>
                            </select>
                            @if($errors->has('department'))
                              <span class="form-text text-danger">{{ $errors->first('department') }}</span>
                            @endif
                        </div>

                        <div class="form-group">
                            <label class="form-control-label @if($errors->has('comment')) has-danger @endif" for="comment">Comment <strong class="text-danger">*</strong></label>
                            <textarea class="form-control @if($errors->has('comment')) is-invalid @endif" id="comment" name="comment" rows="15">{{old('comment')}}</textarea>
                            @if($errors->has('comment'))
                                <span class="form-text text-danger">{{ $errors->first('comment') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <hr>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </div>

      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <!-- Title -->
                  <h5 class="h3 mb-0">{{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-ticket">
                <thead class="thead-light">
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">Subject</th>
                    <th>Department</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Options</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
<style type="text/css">

td.details-control {
    /*background: url('assets/img/icons/details_open.png') no-repeat center center;*/
    cursor: pointer;
    text-align: center;
}
tr.shown td.details-control {
    /*background: url('assets/img/icons/details_close.png') no-repeat center center;*/
}
.message_list{
  width: 100%;
  border: 1px solid #eee;
}
table.message_list td, table.message_list th{
  word-wrap: break-word !important;
    white-space: break-spaces !important;
}
table.message_list .left-message .message-info{
  max-width: 85%;
  float: left;
  background: #ECECEC;
  padding: 15px;
  border-radius: 15px;
  border-bottom-left-radius: 0;
}
table.message_list .right-message .message-info{
  max-width: 85%;
  float: right;
  background: #579FFB;
  color: #fff;
  padding: 15px;
  border-radius: 15px;
  border-bottom-right-radius: 0;
}
table.message_list td{
  border-top: none;
}
table.message_list .message-info p{
  margin-bottom:0;
}



.msger-inputarea {
    display: flex;
    padding: 10px;
    border-top: var(--border);
    background: #eee;
}
.msger-input {
    flex: 1;
    background: #ddd;
}
.msger-inputarea * {
    padding: 10px;
    border: none;
    border-radius: 3px;
    font-size: 1em;
}
.msger-send-btn {
    margin-left: 10px;
    background: rgb(0, 196, 65);
    color: #fff;
    font-weight: bold;
    cursor: pointer;
    transition: background 0.23s;
}
.message_list {

    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background-color: #fcfcfe;
    background-image: url('assets/img/theme/chat-bg.svg');
}
</style>
@endsection
@section('pagewise_js')
<script>
    $(document).ready(function () {
        var table = $('#datatable-ticket').DataTable({
            "processing": true,
            "serverSide": true,
            "destroy": true,
            language: {
              paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
              }
            },
            "ajax":{
              "url": "{{ route('user.support.ticket.listdata') }}",
              "dataType": "json",
              "type": "POST",
               data: {
              "_token": "{{ csrf_token() }}",
              }
            },
            'columnDefs': [{
                "targets": 0,
                "orderable": false
            }],
            "columns": [
                { "data": "srnumber" },
                { "data": "subject" },
                { "data": "department" },
                { "data": "status" },
                { "data": "priority" },
                {
                  "className":      'details-control',
                  "orderable":      false,
                  "data":           null,
                  "defaultContent": '<i class="fas fa-angle-double-right" style="font-size: 20px;"></i>'
                },
            ]  

        });

        table.order( [[ 1, 'asc' ]] ).draw();

        $('#datatable-ticket tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = table.row( tr );
     
            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
                $(this).html('<i class="fas fa-angle-double-right" style="font-size: 20px;"></i>');
            }
            else {
                // Open this row
                row.child( format(row.data()) ).show();
                tr.addClass('shown');
                $(this).html('<i class="fas fa-angle-double-down" style="font-size: 20px;"></i>');
            }
        });
    });
function deleteConfirm(event){
  var id = $(event).attr('id');
  console.log(id);
  swal({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: !0,
      buttonsStyling: !1,
      confirmButtonClass: "btn btn-danger",
      confirmButtonText: "Yes, delete it!",
      cancelButtonClass: "btn btn-secondary"
  }).then((result) => {
    if (result.value) {
      $("#frm_"+id).submit();
    }
  });
}

function format ( d ) {
    // `d` is the original data object for the row

    var message_list='<table class="message_list message_box_'+d.id+'" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

    $.each(d.comments, function( index, comment ) {
      
      var className = '';
      if(comment.author_type == 'admin'){
        
        message_list+= '<tr class="left-message"><td><div class="message-info"><strong>{{env("APP_NAME")}} Team</strong> <p>'+comment.comment+'</p></td></div></tr>';
      }else{
        message_list+= '<tr class="right-message"><td><div class="message-info"><strong>'+comment.author+'</strong> <p>'+comment.comment+'</p></td></div></tr>';
      }
      
    });

    message_list+= '</table><div class="msger-inputarea"><input type="text" class="msger-input" placeholder="Enter your message..." id="ticket_comment_input_'+d.id+'"><button class="msger-send-btn" onclick="return sendComment('+d.id+','+d.requester_id+');">Send</button></div>';

    return message_list;
        /*'<tr class="left-message">'+
            '<td><div class="message-info"><strong>User</strong> <p>Hi this message from user</p></td></div>'+
        '</tr>'+
        '<tr class="right-message">'+
            '<td><div class="message-info"><strong>Admin</strong><p>Hi this message from admin</p></td></div>'+
        '</tr>'+
        '<tr class="right-message">'+
            '<td><div class="message-info"><strong>Admin</strong><p>Hi this message from admin</p></td></div>'+
        '</tr>'+
        '<tr class="left-message">'+
            '<td><div class="message-info"><strong>User</strong><p>Hi this message from user</p></td></div>'+
        '</tr>'+
    '</table>'+
    '<form class="msger-inputarea">'+
      '<input type="text" class="msger-input" placeholder="Enter your message...">'+
      '<button type="submit" class="msger-send-btn">Send</button>'+
    '</form>';*/

    /*var message_list='<div class="message_list">';

    message_list+='<div class="left-message">';
    message_list+='<strong>User</strong><p>Hi this message from user</p>';
    message_list+='</div>';

    message_list+='<div class="right-message">';
    message_list+='<strong>Admin</strong><p>Hi this message from admin</p>';
    message_list+='</div>';

    message_list+='<div class="right-message">';
    message_list+='<strong>User</strong><p>Hi this message from admin</p>';
    message_list+='</div>';

    message_list+='<div class="left-message">';
    message_list+='<strong>User</strong><p>Hi this message from user</p>';
    message_list+='</div>';

    message_list+='</div>';*/
    //return message_list;
}

function sendComment(ticket_id, requester_id){

    var commentText = $("#ticket_comment_input_"+ticket_id).val();
    if(commentText != '' && ticket_id != ''){

        $.ajax({
            url: "{{ route('user.support.ticket.sendcomment',['_token' => csrf_token() ]) }}",
            data: { ticket_id: ticket_id, commentText:commentText, requester_id:requester_id },
            type: 'POST',
            success: function (data) {
                
                if(data.status){

                    $(".message_box_"+ticket_id).append('<tr class="right-message"><td><div class="message-info"><strong>'+data.sender_name+'</strong><p>'+commentText+'</p></td></div></tr>');
                }
                $("#ticket_comment_input_"+ticket_id).val('')
            },
            error: function (data) {
                
            }
        });
    }
}
</script>
@endsection