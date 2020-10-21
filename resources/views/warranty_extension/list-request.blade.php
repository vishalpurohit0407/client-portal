<!-- Admin Dashboard Page -->
@extends('layouts.app')

@section('content')
      <div class="header bg-primary pb-6">
        <div class="container-fluid">
          <div class="header-body">
            <div class="row align-items-center py-4">
              <div class="col-12">
                <h6 class="h2 text-white d-inline-block mb-0">{{$title}}</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{route('user.warranty_extension.list')}}">Warranty Extension</a></li>
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
                <div class="col-8">
                  <!-- Title -->
                  <h5 class="h3 mb-0">{{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="table-responsive py-4">
              <table class="table table-flush" id="datatable-warranty">
                <thead class="thead-light">
                  <tr>
                    <th class="w-10">No.</th>
                    <th>Unique Key</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Options</th>
                  </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                  <tr>
                    <th class="w-10">No.</th>
                    <th>Unique Key</th>
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
        var table = $('#datatable-warranty').DataTable({
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
              "url": "{{ route('user.warranty_extension.listreqest.data',['_token' => csrf_token() ]) }}",
              "dataType": "json",
              "type": "POST",
               data: {
              
              }
            },
            'columnDefs': [{
                "targets": 0,
                "orderable": false
            },{
                "targets": 4,
                "orderable": false
            }],
            "columns": [
                { "data": "srnumber" },
                { "data": "key" },
                { "data": "status" },
                { "data": "created_at" },
                { "data": "options" }
            ]  

        });

        table.order( [[ 1, 'asc' ]] ).draw();
    });
</script>
@endsection