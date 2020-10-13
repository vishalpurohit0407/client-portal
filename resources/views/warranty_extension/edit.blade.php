@extends('layouts.app')
@section('pagewise_css')
<style type="text/css">
    .main-img .dz-default.dz-message{
        height: 344px;
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
              <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="{{url()->previous()}}" class="btn btn-sm btn-neutral">Back</a>
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
            <form class="form" action="{{ route('user.warranty_extension.update',$warranty->id) }}" method="post" enctype="multipart/form-data" id="regForm">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
                <!-- Card body -->
                <div class="card-body" id="stap1">
                    <h3 class="card-title" id="stap">{{$errors->any() ? 'Stap 2' : 'Stap 1' }}</h3>
                    <div class="row">
                      <small class="text-muted mb-3 col-6">Take a picture of exactly the same view as the picture on the left.</small>
                    </div>
                    <div class="row">
                        @if($warranty->admin_vid_link_url)
                          <div class="col-md-6 main-img ">
                              <div class="form-group">
                                  <label class="form-control-label">{{$warranty->admin_vid_link_type ? ucfirst($warranty->admin_vid_link_type) : ''}}  By Admin</label>
                                    <iframe class="dz-preview-img mt-4" style="height: 344px;" src="{{asset($warranty->admin_vid_link_url)}}"></iframe>
                              </div>
                          </div>
                        @else
                          <div class="col-md-6 main-img pt-3">
                              <div class="form-group">
                                  <label class="form-control-label">Picture By Admin</label>
                                  <img class="dz-preview-img"  style="height: 344px;" src="{{$warranty->image_by_admin}}">
                              </div>
                          </div>
                        @endif
                        <div class="col-md-6 tab main-img" style="{{$errors->any() ? 'display: none!important' : '' }}">
                            <div class=" row form-group" id="radiodiv">
                              <div class="custom-control custom-radio text-right ml-3 mr-2">
                                  <input name="imgorvideo" class="custom-control-input" checked id="image" value="img" type="radio">
                                  <label class="custom-control-label" for="image">Image</label>
                              </div>
                              <div class="custom-control custom-radio text-right ml-2">
                                  <input name="imgorvideo" class="custom-control-input" id="video" value="video" type="radio">
                                  <label class="custom-control-label" for="video">Video</label>
                              </div>
                            </div>
                            <div class="form-group video-or-img" id="Chooseimg">
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('user.warranty_extension.imgupload',['id' => $warranty->id])}}">
                                  <div class="fallback">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="picture_by_user" >
                                      <label class="custom-file-label" for="picture_by_user">Choose file</label>
                                    </div>
                                  </div>
                                  <div class="dz-preview dz-preview-single" style="height: 344px;">
                                    <div class="dz-preview-cover">
                                      <img class="dz-preview-img" src="" data-dz-thumbnail>
                                    </div>
                                  </div>
                                </div>
                            </div>
                            <div class="form-group mb-1 video-or-img" id="Choosevideo" style="display: none;">
                                <label class="form-control-label" for="vid_link_type">Video Link</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <select class="custom-select" id="vid_link_type" name="vid_link_type" style="border-radius: 0;font-size:1rem;">
                                      <option value="youtube">Youtube</option>
                                      <option value="vimeo">Vimeo</option>
                                    </select>
                                  </div>
                                  <input type="text" class="form-control" id="vid_link_url" name="vid_link_url" placeholder="Enter here the URL of a Youtube or Vimeo video" value="{{old('vid_link_url')}}">
                                </div>
                                <p class="text-info mb-0"><strong>Note: Please add embed URL for Youtube and Vimeo video.</strong></p>
                            </div>
                            <div class="col-12 p-0" id="imgerror" style="display: none;">
                                <span class="text-danger"><strong>The image or video field is required.</strong></span>
                            </div>
                            <div class="col-12 p-0" id="imgtypeerror" style="display: none;">
                                <span class="text-danger"><strong>The image must be a file type of: jpeg, png, jpg, gif, svg, and may not be greater than 1024 kilobytes.</strong></span>
                            </div>
                        </div>
                        <div class="col-md-6 tab " style="margin-top: 45px; {{$errors->any() ? 'display: block' : 'display: none' }}">
                          <div class="border border-1 rounded main-img p-3">
                            <div class="form-group row mt-0 @if($errors->has('voltage')) is-invalid @endif">
                                <label class="form-control-label col-md-9" for="voltage">Measure the current over the terminals and enter  the Voltage: <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control @if($errors->has('voltage')) is-invalid @endif" id="voltage" name="voltage" placeholder="Voltage" value="{{old('voltage')}}">
                                </div>
                                @if($errors->has('voltage'))
                                    <div class="text-right col-12">
                                        <span class="form-text text-danger">{{ $errors->first('voltage') }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mt-0 @if($errors->has('temperat')) is-invalid @endif">
                                <label class="form-control-label col-md-9" for="temperat">Measure the temperature here: <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <input type="text" class="form-control @if($errors->has('temperat')) is-invalid @endif" id="temperat" name="temperat" placeholder="Temperature" value="{{old('temperat')}}">
                                </div>
                                @if($errors->has('temperat'))
                                    <div class="text-right col-12">
                                        <span class="form-text text-danger">{{ $errors->first('temperat') }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mt-0 @if($errors->has('thing_on')) is-invalid @endif">
                                <label class="form-control-label col-md-9" for="thing_on">Is the thing on <strong class="text-danger">*</strong></label>
                                <div class="col-md-3">
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="thing_on" class="custom-control-input" value="yes" id="yes" type="radio">
                                        <label class="custom-control-label" for="yes">Yes</label>
                                    </div>
                                    <div class="custom-control custom-radio mb-3">
                                        <input name="thing_on" class="custom-control-input" id="no" value="no" type="radio">
                                        <label class="custom-control-label" for="no">No</label>
                                    </div>
                                </div>
                                @if($errors->has('temperat'))
                                    <div class="text-right col-12">
                                        <span class="form-text text-danger text-right">{{ $errors->first('temperat') }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group row mt-0">
                                <label class="form-control-label col-md-9" for="do_something">Did you do somthing</label>
                                <div class="col-md-3">
                                    <div class="custom-control custom-checkbox mb-3">
                                        <input class="custom-control-input" name="do_something" id="do_something" type="checkbox">
                                        <label class="custom-control-label" for="do_something"></label>
                                    </div>
                                </div>
                            </div>
                          </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-primary back-btn" type="button" id="prevBtn" onclick="nextPrev(-1)">Back</button>
                    </div>
                    <div class="col text-right">
                        <button class="btn btn-success next-btn"  type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('pagewise_js')
<script type="text/javascript">
  $( document ).ready(function() {
    @if($warranty->picture_by_user) 
        $(".dz-preview.dz-preview-single").html('<div class="dz-preview-cover dz-processing dz-image-preview dz-success dz-complete"><img class="dz-preview-img" src="{{asset($warranty->image_by_user)}}"></div>');
        $(".dropzone.dropzone-single").addClass('dz-clickable dz-max-files-reached');
        $("#imgerror").addClass('d-none');
    @endif

    $("input[name$='imgorvideo']").click(function() {
      var test = $(this).val();
      document.getElementById("imgerror").style.display = "none";
      document.getElementById("imgtypeerror").style.display = "none";
      $("div.video-or-img").hide();
      $("#Choose" + test).show();
    });
  });
    @if($errors->any())
      var currentTab = 1;
    @else
      var currentTab = 0;
    @endif
    showTab(currentTab); 
    function showTab(n) {
      var x = document.getElementsByClassName("tab");
      x[n].style.display = "block";

      if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
      } else {
        document.getElementById("prevBtn").style.display = "inline";
      }
      if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
      } else {
        document.getElementById("nextBtn").innerHTML = "Next";
      }
    }

    function nextPrev(n) {
      var x = document.getElementsByClassName("tab");
      if (n == 1 && !validateForm()) return false;
      x[currentTab].style.display = "none";
      if (n == -1) {
        $("#stap")[0].innerHTML = "Stap 1";
      }else{
        $("#stap")[0].innerHTML = "Stap 2";
      }
      currentTab = currentTab + n;
      if (currentTab >= x.length) {
        document.getElementById("regForm").submit();
        return false;
      }
      showTab(currentTab);
    }

    function validateForm() {
      var x, y, i, valid = true;
      x = document.getElementsByClassName("tab");
      y = x[currentTab].getElementsByClassName("dz-preview dz-preview-single");
      z = document.getElementById("vid_link_url");
      for (i = 0; i < y.length; i++) {
        if (y[i].innerHTML == "" && z.value == "" ) {
          document.getElementById("imgerror").style.display = "block";
          valid = false;
        }
        if ($(".dz-preview-cover.dz-processing.dz-image-preview.dz-error.dz-complete").length > 0) {
            document.getElementById("imgerror").style.display = "none";
            document.getElementById("imgtypeerror").style.display = "block";
            valid = false;
        }
      }
      return valid;
    }
</script>
@endsection