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
                <form class="form" action="{{ route('admin.category.update', $warrantyExtension->id) }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                {{ method_field('PUT') }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Unique Key: </label>
                                <h4>{{$warrantyExtension->unique_key}}</h4>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Warranty Valid Until&nbsp;<strong class="text-danger">*</strong></label>
                                <input class="form-control" type="date" value="2018-11-23" id="example-date-input">
                                @if($errors->has('name'))
                                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 machine-img">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Picture&nbsp;<strong class="text-danger">*</strong></label>
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('admin.selfdiagnosis.mainupload',['id' => $warrantyExtension->id])}}">
                                  <div class="fallback">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="guide_main_image">
                                      <label class="custom-file-label" for="guide_main_image">Choose file</label>
                                    </div>
                                  </div>
                                  <div class="dz-preview dz-preview-single" style="height: 300px;">
                                    <div class="dz-preview-cover">
                                      <img class="dz-preview-img" src="" data-dz-thumbnail>
                                    </div>
                                  </div>
                                </div>
                                @if($errors->has('name'))
                                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label" for="name">Status</label>
                            <div class="form-group">
                                <label class="custom-toggle custom-toggle-success">
                                    <input type="checkbox"  value="1" name="status">
                                    <span class="custom-toggle-slider rounded-circle" data-label-off="Inactive" data-label-on="Active"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button class="btn btn-primary" type="submit">Save</button>
                </form>
            </div>
        </div>
    </div>
<style type="text/css">
  .machine-img .dz-default.dz-message{
    height: 300px;
    padding: 8rem 1rem;
  }
</style>
@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function() {
    
});
</script>
@endsection