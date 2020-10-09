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
            <form class="form" action="{{ route('user.warranty_extension.update',$warranty->id) }}" method="post" enctype="multipart/form-data" id="regForm">
            {{ csrf_field() }}
            {{ method_field('PATCH') }}
                <!-- Card body -->
                <div class="card-body" id="stap1">
                    <h3 class="card-title">Stap 1</h3>
                    <small class="text-muted mb-3">Take a picture of exactly the same view as the picture on the left.</small>
                    <div class="row pt-3">
                        <div class="col-md-6 main-img">
                            <div class="form-group">
                                <img class="dz-preview-img"  style="height: 344px;" src="{{$warranty->image_by_admin}}">
                            </div>
                        </div>
                        <div class="col-md-6 tab main-img">
                            <div class="form-group">
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('user.warranty_extension.imgupload',['id' => $warranty->id])}}">
                                  <div class="fallback">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="picture_by_user">
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
                        </div>
                        <div class="col-md-6 tab border border-1 rounded main-img p-4" style="display: none;">
                            <div class="form-group row mt-2 @if($errors->has('voltage')) is-invalid @endif">
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
                            <div class="form-group row @if($errors->has('temperat')) is-invalid @endif">
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
                            <div class="form-group row  @if($errors->has('thing_on')) is-invalid @endif">
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
                            <div class="form-group row">
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
    @if($warranty->picture_by_user) 
        $(".dz-preview.dz-preview-single").html('<div class="dz-preview-cover dz-processing dz-image-preview dz-success dz-complete"><img class="dz-preview-img" src="{{asset($warranty->image_by_user)}}"></div>');
        $(".dropzone.dropzone-single").addClass('dz-clickable dz-max-files-reached');
    @endif
    var currentTab = 0;
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
      for (i = 0; i < y.length; i++) {
        if (y[i].innerHTML == "") {
          alert('please Image Uplode first');
          valid = false;
        }
      }
      return valid;
    }
</script>
@endsection