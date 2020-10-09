@extends('layouts.app')
@section('pagewise_css')
<style type="text/css">
    .main-img .dz-default.dz-message{
        height: 300px;
        padding: 8rem 1rem;
    }
    .dropzone-multiple .dz-default.dz-message{
        height: 150px;
    }
    .dropzone-multiple .dz-message{
        padding-top: 4rem;
    }
    .dz-size{
        display: none;
    }
</style>
@endsection
@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Maintenance</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="{{route('admin.user.list')}}" class="btn btn-sm btn-neutral">Back</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="card mb-4">
            <!-- Card header -->
            <div class="card-header text-center">
              <h3 class="mb-0">{{$title}}</h3>
            </div>

            <!-- Form groups used in grid -->
            <form class="form" action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
            {{ csrf_field() }}
                <!-- Card body -->
                <div class="card-body" id="stap1">
                    <h3 class="card-title ">Stap 1</h3>
                    <small class="text-muted mb-3">Take a picture of exactly the same view as the picture on the left.</small>
                    <div class="row pt-3">
                        <div class="col-md-6 main-img">
                            <div class="form-group">
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('user.warranty_extension.imgupload',['id' => $warranty->id])}}">
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
                            </div>
                        </div>
                        <div class="col-md-6 stap1 main-img">
                            <div class="form-group">
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('user.warranty_extension.imgupload',['id' => $warranty->id])}}">
                                    <div class="fallback">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="picture_by_user">
                                            <label class="custom-file-label" for="picture_by_user">Choose file</label>
                                        </div>
                                    </div>
                                    <div class="dz-preview dz-preview-single" style="height: 300px;">
                                        <div class="dz-preview-cover">
                                            <img class="dz-preview-img" src="" data-dz-thumbnail>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stap2 border border-1 rounded main-img p-4">
                            <div class="form-group row mt-2">
                                <label class="form-control-label col-md-9" for="type">Measure the current over the terminals and enter  the Voltage: <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control @if($errors->has('type')) is-invalid @endif" id="type" name="type" placeholder="Type" value="{{old('type')}}">
                                @if($errors->has('type'))
                                    <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                                @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-9" for="type">Measure the temperature here: <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control @if($errors->has('type')) is-invalid @endif" id="type" name="type" placeholder="Type" value="{{old('type')}}">
                                @if($errors->has('type'))
                                    <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                                @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-9" for="type">Is the thing on <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="custom-radio-1" class="custom-control-input" id="customRadio5" type="radio">
                                        <label class="custom-control-label" for="customRadio5">Unchecked</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="custom-radio-1" class="custom-control-input" id="customRadio5" type="radio">
                                        <label class="custom-control-label" for="customRadio5">Unchecked</label>
                                    </div>
                                    @if($errors->has('type'))
                                        <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="form-control-label col-md-9" for="type">Did you do somthing <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" id="customCheck1" type="checkbox">
                                        <label class="custom-control-label" for="customCheck1">Unchecked</label>
                                    </div>
                                    @if($errors->has('type'))
                                        <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary back-btn" type="submit">Back</button>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-success next-btn" type="submit">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function(){
    $(".stap2").hide();
    // Display alert message after hiding paragraphs
    $(".back-btn").click(function(){
        $(".stap1").hide();
        $(".stap2").show("slow");
    });
    
    // Display alert message after showing paragraphs
    $(".next-btn").click(function(){
        $(".stap1").hide("slow");
        $(".stap2").show("slow");
    });
});
</script>
@endsection