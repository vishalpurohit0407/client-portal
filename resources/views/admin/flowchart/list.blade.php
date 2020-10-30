<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')

@section('content')
      <div class="header bg-primary pb-6">
        <div class="container-fluid">
          <div class="header-body">
            <div class="row align-items-center py-4">
              <div class="col-lg-6 col-7">
                <h6 class="h2 text-white d-inline-block mb-0">Flowchart</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                  </ol>
                </nav>
              </div>
              <div class="col-lg-6 col-5 text-right">
                <button class="btn btn-sm btn-neutral" data-toggle="modal" data-target="#modal-form">New Flowchart</button>
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
                    <th class="w-100">Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="w-10">No.</th>
                    <th class="w-70">Title</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Options</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
        <div class="modal fade" id="modal-form" tabindex="-2" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
          <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
            <div class="modal-content">
              <div class="modal-body p-0">
                <div class="card bg-secondary border-0 mb-0">
                  <div class="card-header bg-transparent">
                      <div class="text-muted text-center">Add New Flowchart</div>
                  </div>
                  <div class="card-body">
                      <form role="form" action="{{route('admin.flowchart.store')}}" method="post" id="addflowchart">
                        @csrf
                          <div class="form-group mb-3">
                              <div class="input-group">
                                  <input class="form-control" name="title" id="title" placeholder="Title*" type="text">
                              </div>
                              <span class="form-text text-danger" id="errortitle" style="display: none;">The title field is required.</span>
                          </div>
                          <div class="form-group">
                              <div class="input-group">
                                  <textarea class="form-control" name="description" id="description" rows="10" placeholder="Description"></textarea>
                              </div>
                              <span class="form-text text-danger" id="errordesc" style="display: none;">The description field is required.</span>
                          </div>
                          <div class="text-center">
                              <button type="submit" class="btn btn-primary my-4">Save</button>
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                          </div>
                      </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
@endsection
@section('pagewise_js')
<script>
  $(document).ready(function () {
      $('#datatable-basic').DataTable({
          "processing": true,
          "serverSide": true,
          "destroy": true,
          responsive: true,
          language: {
            paginate: {
              previous: "<i class='fas fa-angle-left'>",
              next: "<i class='fas fa-angle-right'>"
            }
          },
          "ajax":{
            "url": "{{ route('admin.flowchart.listdata') }}",
            "dataType": "json",
            "type": "POST",
             data: {
            "_token": "{{ csrf_token() }}",
            }
          },
          'columnDefs': [{
              "targets": -1,
              "orderable": false
          }],
          "columns": [
              { "data": "srnumber" },
              { "data": "title" },
              { "data": "status" },
              { "data": "created_at" },
              { "data": "options" }
          ]  
      });

      $('#addflowchart').submit(function () {
          var title = document.getElementById('title');
          var errortitle = document.getElementById("errortitle");
          // Check if empty of not
          if ($.trim($('#title').val())  === '') {
              errortitle.style.display = "block";
              title.classList.add("is-invalid");
              return false;
          }
      });
  });

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
</script>
@endsection