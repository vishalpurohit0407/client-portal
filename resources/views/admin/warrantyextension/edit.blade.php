@extends('layouts.adminapp')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.maintenance.warrantyextension.list')}}">Warranty Extensions List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-5 text-right">
              <a href="{{route('admin.category.list')}}" class="btn btn-sm btn-neutral">Back</a>
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
                <form class="form" action="{{ route('admin.maintenance.warrantyextension.update', $warrantyExtension->id) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-control-label" for="name">Unique Key: </label>
                                <h4>{{$warrantyExtension->unique_key}}</h4>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('warranty_valid_date')) has-danger @endif ">
                                <label class="form-control-label" for="warranty_valid_date">Warranty Valid Until&nbsp;<strong class="text-danger">*</strong></label>
                                <input class="form-control" type="date" name="warranty_valid_date" value="{{old('warranty_valid_date',$warrantyExtension->warranty_valid_date)}}">
                                @if($errors->has('warranty_valid_date'))
                                    <span class="form-text text-danger">{{ $errors->first('warranty_valid_date') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 machine-img">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Picture By Admin&nbsp;<strong class="text-danger">*</strong></label>
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('admin.maintenance.warrantyextension.imgupload',['_token' => csrf_token(), 'id' => $warrantyExtension->id])}}">
                                  <div class="fallback">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="warranty_main_image">
                                      <label class="custom-file-label" for="warranty_main_image">Choose file</label>
                                    </div>
                                  </div>
                                  <div class="dz-preview dz-preview-single" style="height: 300px;">
                                    <div class="dz-preview-cover">
                                      <img class="dz-preview-img" src="" data-dz-thumbnail>
                                    </div>
                                  </div>
                                </div>
                                @if($errors->has('warranty_main_image'))
                                    <span class="form-text text-danger">{{ $errors->first('warranty_main_image') }}</span>
                                @endif
                            </div>
                        </div>

                        @if($warrantyExtension->status != '0' && $warrantyExtension->status != '1')
                            @if($warrantyExtension->picture_by_user)
                            <div class="col-md-6 machine-img">
                                <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                    <label class="form-control-label" for="name">Picture By User</label>
                                    <div class="">
                                        <img class="picture-by-user" src="{{asset($warrantyExtension->image_by_user)}}">
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endif

                    </div>

                    @if($warrantyExtension->status != '0' && $warrantyExtension->status != '1')
                        <div class="row">
                            @if($warrantyExtension->voltage)
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('voltage')) has-danger @endif">
                                    <label class="form-control-label" for="name">Measure the current over the terminals and enter the Voltage&nbsp;<strong class="text-danger">*</strong></label>
                                    <input class="form-control" type="text" name="voltage" value="{{old('voltage',$warrantyExtension->voltage)}}">
                                    @if($errors->has('voltage'))
                                        <span class="form-text text-danger">{{ $errors->first('voltage') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            @if($warrantyExtension->temperat)
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('temperat')) has-danger @endif ">
                                    <label class="form-control-label" for="temperat">Measure the temperature here&nbsp;<strong class="text-danger">*</strong></label>
                                    <input class="form-control" type="text" name="temperat" value="{{old('temperat',$warrantyExtension->temperat)}}">
                                    @if($errors->has('temperat'))
                                        <span class="form-text text-danger">{{ $errors->first('temperat') }}</span>
                                    @endif
                                </div>
                            </div>
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('voltage')) has-danger @endif">
                                    <label class="form-control-label" for="name">Is the thing on &nbsp; &nbsp;</label><br>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="thing_on1" name="thing_on" class="custom-control-input" @if($warrantyExtension->thing_on == 'yes') checked @endif>
                                        <label class="custom-control-label" for="thing_on1">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="thing_on2" name="thing_on" class="custom-control-input" @if($warrantyExtension->thing_on == 'no') checked @endif>
                                        <label class="custom-control-label" for="thing_on2">No</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group @if($errors->has('temperat')) has-danger @endif ">
                                    <label class="form-control-label" for="temperat">Did you do something</label>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="customCheck1" name="do_something" @if($warrantyExtension->do_something == 'true') checked @endif>
                                        <label class="custom-control-label" for="customCheck1"></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

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
                    <button class="btn btn-primary" type="submit">Save</button>
                    @if($warrantyExtension->status == '3')
                        <button class="btn btn-success" type="submit" name="approve" value="approve">Approve</button>
                        <button class="btn btn-danger" type="submit" name="decline" value="decline">Decline</button>
                    @endif
                </form>
            </div>
        </div>
    </div>
<style type="text/css">
  .machine-img .dz-default.dz-message{
    height: 300px;
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