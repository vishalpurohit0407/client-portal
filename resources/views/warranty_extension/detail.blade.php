@extends('layouts.app')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-9">
              <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions Details</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('user.warranty_extension.list')}}">Warranty Extensions List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-3 text-right">
              <a href="{{route('user.warranty_extension.list')}}" class="btn btn-sm btn-neutral">Back</a>
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
              <h3 class="mb-0">{{$title}}</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <!-- Form groups used in grid -->
                <form class="form">
                    <div class="row row-example">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="name">Unique Key: </label>
                                <span>{{$warrantyExtension->unique_key}}</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="warranty_valid_date">Warranty Valid Until</label>
                                <span>{{$warrantyExtension->warranty_valid_date ? $warrantyExtension->warranty_valid_date : 'N/A'}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 machine-img">
                          <div class="form-group">
                            <label class="form-control-label">Picture By Admin</label>
                            <img class="dz-preview-img"  style="height: 344px;" src="{{$warrantyExtension->image_by_admin}}">
                          </div>
                        </div>
                        @if($warrantyExtension->vid_link_url)
                          <div class="col-md-6 machine-img">
                              <div class="form-group">
                                  <label class="form-control-label">{{$warrantyExtension->vid_link_type ? $warrantyExtension->vid_link_type : ''}}</label>
                                  <div class="embed-responsive embed-responsive-4by3 rounded">
                                    <iframe class="embed-responsive-item" src="{{asset($warrantyExtension->vid_link_url)}}"></iframe>
                                  </div>
                              </div>
                          </div>
                        @else
                          <div class="col-md-6 machine-img">
                            <div class="form-group">
                                <label class="form-control-label">Picture By User</label>
                                <img class="dz-preview-img"  style="height: 344px;" src="{{$warrantyExtension->image_by_user}}">
                            </div>
                          </div>
                        @endif
                    </div>

                    <div class="row row-example">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="name">Voltage</label>
                                <span>{{$warrantyExtension->voltage ? $warrantyExtension->voltage : 'N/A'}}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="temperat">temperature</label>
                                <span>{{$warrantyExtension->temperat ? $warrantyExtension->temperat : 'N/A'}}</span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="name">Is the thing on &nbsp; &nbsp;</label><br>
                                @if($warrantyExtension->thing_on)
                                  <span class="badge badge-{{$warrantyExtension->thing_on == 'yes' ? 'success' : ''}}">Yes</span>
                                  <span class="badge badge-{{$warrantyExtension->thing_on == 'no' ? 'danger' : ''}}">No</span>
                                @else
                                N/A
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-control-label" for="temperat">Did you do something</label><br>
                                @if($warrantyExtension->do_something)
                                <span class="badge badge-{{$warrantyExtension->do_something == 'true' ? 'success' : ''}}">True</span>
                                <span class="badge badge-{{$warrantyExtension->do_something == 'false' ? 'danger' : ''}}">False</span>
                                @else
                                N/A
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-control-label">Status</label>
                            <div class="form-group">
                                <label class="form-control-label">
                                @if($warrantyExtension->status == '0') 
                                    <span class="badge badge-pill badge-warning">Initial</span>
                                @elseif($warrantyExtension->status == '1')
                                    <span class="badge badge-pill badge-primary">Admin Reply</span>
                                @elseif($warrantyExtension->status == '2')
                                    <span class="badge badge-pill badge-success">Request</span>
                                @elseif($warrantyExtension->status == '3')
                                    <span class="badge badge-pill badge-success">Approved</span>
                                @elseif($warrantyExtension->status == '4')
                                    <span class="badge badge-pill badge-danger">Declined</span>
                                @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                </form>
            </div>
        </div>
    </div>
<style type="text/css">
  .machine-img .dz-default.dz-message{
    height: 344px;
    padding: 8rem 1rem;
  }
  .picture-by-user{
    height: 300px;
    border-radius: .375rem;
  }
</style>
@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function() {
    @if($warrantyExtension->picture_by_admin) 
        $(".dz-preview.dz-preview-single").html('<div class="dz-preview-cover dz-processing dz-image-preview dz-success dz-complete"><img class="dz-preview-img" src="{{asset($warrantyExtension->image_by_admin)}}"></div>');
        $(".dropzone.dropzone-single").addClass('dz-clickable dz-max-files-reached');
    @endif
});
</script>
@endsection