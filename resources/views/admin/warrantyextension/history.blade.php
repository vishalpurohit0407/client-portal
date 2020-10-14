@extends('layouts.adminapp')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-9">
              <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.warrantyextension.list')}}">All Warranty Extensions</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-3 text-right">
              <a href="{{route('admin.warrantyextension.list')}}" class="btn btn-sm btn-neutral">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col">
                  <h3 class="mb-0">Warranty Valid Until : {{$warrantyExtension->last()->next_warranty_valid_date ? date("d-m-Y", strtotime($warrantyExtension->last()->next_warranty_valid_date)) : 'N/A'}} </h3>
                </div>
                
              </div>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <h5 class="h3 card-title mb-2">Warranty extension history</h5>
                <ul class="list-group list-group-flush">
                    @if($warrantyExtension)
                    @php 
                    $color = array('warning','primary','success','success','danger');
                    @endphp
                        @foreach($warrantyExtension as $warranty_key => $warranty)
                            <li class="checklist-entry list-group-item flex-column align-items-start py-4 px-4">
                                <div class="checklist-item checklist-item-{{$color[$warranty->status]}}">
                                    <div class="checklist-info">
                                        <h4 class="checklist-title mb-0">{{$warranty->warranty_valid_date ? date("d-m-Y", strtotime($warranty->warranty_valid_date)) : 'N/A'}}</h4>
                                        <small>
                                            @if($warranty->status == '0') 
                                                <span class="badge badge-pill badge-warning">Initial</span>
                                            @elseif($warranty->status == '1')
                                                <span class="badge badge-pill badge-primary">Admin Reply</span>
                                            @elseif($warranty->status == '2')
                                                <span class="badge badge-pill badge-success">Request</span>
                                            @elseif($warranty->status == '3')
                                                <span class="badge badge-pill badge-success">Approved</span>
                                            @elseif($warranty->status == '4')
                                                <span class="badge badge-pill badge-danger">Declined</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div>
                                        <div class="custom-control custom-checkbox custom-checkbox-success">
                                          @if($warranty->status == 3 || $warranty->status == 4)
                                            <a href="{{route('admin.warrantyextension.show',$warranty->id)}}" class="btn btn-info">Details</a>
                                          @else
                                            <a href="{{route('admin.warrantyextension.edit',$warranty->id)}}" class="btn btn-info">Edit</a>
                                          @endif
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
    </div>
@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function() {

});
function deleteConfirm(event){
  swal({
      title: "Are you sure?",
      text: "Add new warranty extension!",
      type: "warning",
      showCancelButton: !0,
      buttonsStyling: !1,
      confirmButtonClass: "btn btn-success",
      confirmButtonText: "Yes, Add new!",
      cancelButtonClass: "btn btn-secondary"
  }).then((result) => {
    if (result.value) {
        $("#frmaddnew").submit();
    }
  });
}
</script>
@endsection