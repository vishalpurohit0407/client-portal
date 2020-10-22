<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')

@section('content')
      <div class="header bg-primary pb-6">
        <div class="container-fluid">
          <div class="header-body">
            <div class="row align-items-center py-4">
              <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                  </ol>
                </nav>
              </div>
              <!-- <div class="col-lg-6 col-5 text-right">
                <a href="{{route('admin.category.create')}}" class="btn btn-sm btn-neutral">Add New</a>
              </div> -->
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
      <div class="row">
        <div class="col">
          <div class="card">
            <!-- Card header -->
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-7">
                  <!-- Title -->
                  <h5 class="h3 mb-0">{{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="table-responsive py-4">
              <div class="col-sm-12 col-md-6 col-xl-6 col-lg-6 pl-4 pb-3 row">
                  <span class="col-lg-3 pb-3 pr-0">Filter By Status :</span>
                  <span class="col-lg-4 pb-3">
                    <select class="form-control form-control-sm" name="filter_status" id="filter_status" data-toggle="select" data-minimum-results-for-search="Infinity">
                      <option value="">All</option>
                      <option value="0">INITIAL</option>
                      <option value="1">Admin Reply</option>
                      <option value="2">Request</option>
                      <option value="3">Approved</option>
                      <option value="4">Declined</option>
                    </select>
                  </span>
              </div>  
              <table class="table table-flush" id="datatable-warranty">
                <thead class="thead-light">
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">User Name</th>
                    <th>Unique Key</th>
                    <th>Status</th>
                    <th>Updated At</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-100">User Name</th>
                    <th>Unique Key</th>
                    <th>Status</th>
                    <th>Updated At</th>
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
fetch_data();
function fetch_data(status = ''){
  var table = $('#datatable-warranty').DataTable({
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
        "url": "{{ route('admin.warrantyextension.listdata',['_token' => csrf_token() ]) }}",
        "dataType": "json",
        "type": "POST",
         data: {
          status:status
        }
      },
      'columnDefs': [{
          "targets": 0,
          "orderable": false
      },{
          "targets": 5,
          "orderable": false
      }],
      "columns": [
          { "data": "srnumber" },
          { "data": "name" },
          { "data": "key" },
          { "data": "status" },
          { "data": "updated_at" },
          { "data": "options" }
      ]  
  });

  table.order( [[ 1, 'asc' ]] ).draw();
}
function deleteConfirm(event){
  var id = $(event).attr('id');
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
$('#filter_status').change(function(){
  var status = $('#filter_status').val();
  $('#datatable-warranty').DataTable().destroy();
  fetch_data(status);
});
</script>
@endsection