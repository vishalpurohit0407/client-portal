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

table.message_list td, table.message_list th{
  word-wrap: break-word !important;
    white-space: break-spaces !important;
}
.message_list .left-message .message_content{
  max-width: 85%;
  float: left;
  background: #ECECEC;
  padding: 15px;
  border-radius: 15px;
  border-bottom-left-radius: 0;
   position: relative;
}
.message_list .right-message .message_content{
  max-width: 85%;
  float: right;
  background: #579FFB;
  color: #fff;
  padding: 15px;
  border-radius: 15px;
  border-bottom-right-radius: 0;
  position: relative;
  min-width: 170px;
}
.message_list .left-message,
.message_list .right-message {
    width: 100%;
    display: inline;
    position: relative;
    float: left;
    margin-top: 30px;
}
.message_list .message_content p{
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
.scroll-wrapper > .message_list.scroll-content {
    width: 100% !important;
    padding: 10px !important;
    box-sizing: border-box !important;
    position: static !important;
}
.message_list {
    width: 100%;
    border: 1px solid #eee;
    flex: 1;
    overflow-y: auto;
    padding: 10px;
    background-color: #fcfcfe;
    background-image: url('assets/img/theme/chat-bg.svg');
    height: 450px;
    display: inline-block;
    padding: 10px !important;
    white-space: normal;
}

.message-info-time-left {
    position: absolute;
    left: 0px;
    color: #000;
    margin-top: 18px;
}
.message-info-time-right
{
    position: absolute;
    bottom: -22px;
    right: 0px;
    color: #000;
}
.message_list tr:last-child > td {
    padding-bottom: 30px !important;
}

.comments-listing-loader .fa.fa-spinner{

    position: relative;
    top: 50%;
}
.message_comment_content{
    float: left;
    width: 100%;
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
            "searching": false,
            responsive: true,
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
                "data": function(d, settings){
                    
                    var api = new $.fn.dataTable.Api(settings);

                     // Convert starting record into page number
                    d.pageNumber = Math.min(
                        Math.max(0, Math.round(d.start / api.page.len())),
                        api.page.info().pages
                    );
                    d._token = "{{ csrf_token() }}";
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
                row.child(formatBlank()).show();
                $('.scrollbar-inner').scrollbar().scrollLock();
                $.ajax({
                    url: "{{ route('user.support.ticket.getcomment',['_token' => csrf_token() ]) }}",
                    data: { ticket_id: row.data().id },
                    type: 'POST',
                    success: function (data) {
                        
                        if(data.status){
                            row.child( format(row.data(),data.comments) ).show();
                            $('.scrollbar-inner').scrollbar().scrollLock();
                        }
                    },
                    error: function (data) {
                        
                    }
                });
                //
                tr.addClass('shown');
                $(this).html('<i class="fas fa-angle-double-down" style="font-size: 20px;"></i>');
                //
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

function formatBlank () {
    //return '<div class="comments-listing-loader"><i class="fa fa-spinner fa-pulse"></i></div><div class="message_list scrollbar-inner"></div>';
    return '<div class="message_list scrollbar-inner" style="padding-left:50px;"><div class="comments-listing-loader"><i class="fa fa-spinner fa-pulse"></i></div>';
}

function format (d, comments) {

    var message_list='<div class="message_list scrollbar-inner message_box_'+d.id+'" style="padding-left:50px;"><div class="comments-listing-loader loader_box_'+d.id+'" style="display:none;"><i class="fa fa-spinner fa-pulse"></i></div><div class="message_comment_content" id="comment_content_'+d.id+'">';

    $.each(comments, function( index, comment ) {
      
      var className = '';
      if(comment.author_type == 'admin'){
        
        message_list+= '<div class="left-message"><div class="message_content"><strong>{{env("APP_NAME")}} Team</strong> <p>'+comment.comment+'</p><span class="message-info-time-left">'+comment.created_at+'</span></div></div>';
      }else{

        message_list+= '<div class="right-message"><div class="message_content"><strong>'+comment.author+'</strong> <p>'+comment.comment+'</p><span class="message-info-time-right">'+comment.created_at+'</span></div></div>';
      }
      
    });

    message_list+= '</div></div><div class="msger-inputarea"><input type="text" class="msger-input" placeholder="Enter your message..." id="ticket_comment_input_'+d.id+'"><button class="msger-send-btn" onclick="return sendComment('+d.id+','+d.requester_id+');">Send</button></div>';

    return message_list;

    /*var message_list='<div class="message_list scrollbar-inner message_box_'+d.id+'" style="padding-left:50px;">';

    message_list+='<div class="left-message">';
    message_list+='<div class="message_content"><strong>User</strong><p>Hi this message from user</p></div>';
    message_list+='</div>';

    message_list+='<div class="right-message">';
    message_list+='<div class="message_content"><strong>Admin</strong><p>Hi this message from admin</p></div>';
    message_list+='</div>';

    message_list+='<div class="right-message">';
    message_list+='<div class="message_content"><strong>User</strong><p>Hi this message from admin</p></div>';
    message_list+='</div>';

    message_list+='<div class="left-message">';
    message_list+='<div class="message_content"><strong>User</strong><p>Hi this message from user</p></div>';
    message_list+='</div>';

    message_list+='</div>';
    return message_list;*/
}

function sendComment(ticket_id, requester_id){

    var commentText = $("#ticket_comment_input_"+ticket_id).val();
    if(commentText != '' && ticket_id != ''){
        $(".loader_box_"+ticket_id).show();
        $.ajax({
            url: "{{ route('user.support.ticket.sendcomment',['_token' => csrf_token() ]) }}",
            data: { ticket_id: ticket_id, commentText:commentText, requester_id:requester_id },
            type: 'POST',
            success: function (data) {
                
                if(data.status){

                    $(".message_box_"+ticket_id).append('<div class="right-message"><div class="message_content"><strong>'+data.sender_name+'</strong><p>'+commentText+'</p><span class="message-info-time-right">'+data.created_at+'</span></div></div>');
                    $('.message_box_'+ticket_id).scrollTop($('#comment_content_'+ticket_id).outerHeight());
                }
                $("#ticket_comment_input_"+ticket_id).val('');
                $(".loader_box_"+ticket_id).hide();
            },
            error: function (data) {
                
            }
        });
    }
}
</script>
@endsection