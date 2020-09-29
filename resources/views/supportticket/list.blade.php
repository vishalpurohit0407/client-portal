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
              <table class="table table-flush" id="datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">Name</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Options</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('pagewise_js')
<script>
    $(document).ready(function () {
        /*var table = $('#datatable-basic').DataTable({
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
              "url": "{{ route('admin.category.listdata') }}",
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
                { "data": "name" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "options" }
            ]  

        });

        table.order( [[ 1, 'asc' ]] ).draw();*/
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
</script>
@endsection