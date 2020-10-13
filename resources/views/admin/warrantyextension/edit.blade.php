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
                  <li class="breadcrumb-item"><a href="{{route('admin.warrantyextension.list')}}">Warranty Extensions List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-5 text-right">
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
            <div class="card-header">
              <h3 class="mb-0">{{$title}}</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <!-- Form groups used in grid -->
                <form class="form" action="{{ route('admin.warrantyextension.update', $warrantyExtension->id) }}" method="post" enctype="multipart/form-data" id="form">
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
                        @if($warrantyExtension->status != '0' && $warrantyExtension->status != '1')
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('next_warranty_valid_date')) has-danger @endif ">
                                <label class="form-control-label" for="next_warranty_valid_date">Next Warranty Valid Until&nbsp;<strong class="text-danger">*</strong></label>
                                <input class="form-control" type="date" name="next_warranty_valid_date" value="{{old('next_warranty_valid_date',$warrantyExtension->next_warranty_valid_date)}}">
                                @if($errors->has('next_warranty_valid_date'))
                                    <span class="form-text text-danger">{{ $errors->first('next_warranty_valid_date') }}</span>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6 tab machine-img">
                            <div class="row mb-1" id="radiodiv">
                              <div class="custom-control custom-radio mb-1 text-right ml-3 mr-2">
                                  <input name="imgorvideo" class="custom-control-input" {{$warrantyExtension->image_by_admin != '' ? 'checked' : ''}} id="image" value="img" type="radio">
                                  <label class="custom-control-label" for="image">Image</label>
                              </div>
                              <div class="custom-control custom-radio mb-1 text-right ml-2">
                                  <input name="imgorvideo" class="custom-control-input" {{$warrantyExtension->admin_vid_link_url != '' ? 'checked' : ''}} id="video" value="video" type="radio">
                                  <label class="custom-control-label" for="video">Video</label>
                              </div>
                            </div>
                            <div class="form-group video-or-img @if($errors->has('name')) has-danger @endif " id="Chooseimg" >
                                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('admin.warrantyextension.imgupload',['_token' => csrf_token(), 'id' => $warrantyExtension->id])}}">
                                  <div class="fallback">
                                    <div class="custom-file">
                                      <input type="file" class="custom-file-input" id="warranty_main_image">
                                      <label class="custom-file-label" for="warranty_main_image">Choose file</label>
                                    </div>
                                  </div>
                                  <div class="dz-preview dz-preview-single" id="dz-preview" style="height: 344px;">
                                    <div class="dz-preview-cover">
                                      <img class="dz-preview-img" src="" data-dz-thumbnail>
                                    </div>
                                  </div>
                                </div>
                                @if($errors->has('warranty_main_image'))
                                    <span class="form-text text-danger">{{ $errors->first('warranty_main_image') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-1 video-or-img" id="Choosevideo">
                                <label class="form-control-label" for="admin_vid_link_type">Video Link</label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <select class="custom-select" id="admin_vid_link_type" name="admin_vid_link_type" style="border-radius: 0;font-size:1rem;">
                                      <option value="youtube">Youtube</option>
                                      <option value="vimeo">Vimeo</option>
                                    </select>
                                  </div>
                                  <input type="text" class="form-control" id="admin_vid_link_url" name="admin_vid_link_url" placeholder="Enter here the URL of a Youtube or Vimeo video" value="{{old('admin_vid_link_url',$warrantyExtension->admin_vid_link_url)}}">
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

                        @if($warrantyExtension->status != '0' && $warrantyExtension->status != '1')
                            @if($warrantyExtension->vid_link_url)
                                <div class="col-md-6 machine-img">
                                    <div class="form-group">
                                        <label class="form-control-label">{{$warrantyExtension->vid_link_type ? ucfirst($warrantyExtension->vid_link_type) : ''}}   By Admin</label>
                                            <iframe class="dz-preview-img mt-1" style="height: 344px;" src="{{asset($warrantyExtension->vid_link_url)}}"></iframe>
                                    </div>
                                </div>
                            @else
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
                    <a href="javascript:void(0);"  onclick="validateForm();" class="btn btn-primary" type="submit">Save</a>
                    @if($warrantyExtension->status == '2')
                        <button class="btn btn-success" type="submit" name="approve" value="approve">Approve</button>
                        <button class="btn btn-danger" type="submit" name="decline" value="decline">Decline</button>
                    @endif
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
    height: 344px;
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
    var checkBox = document.getElementById("image");
    if (checkBox.checked == true){
        $("#Chooseimg").show();
        $("#Choosevideo").hide();
    } else {
        $("#Chooseimg").hide();
        $("#Choosevideo").show();
    }
    $("input[name$='imgorvideo']").click(function() {
        var test = $(this).val();
        $("div.video-or-img").hide();
        $("#Choose" + test).show();
    });
});
function validateForm() {
    var z = document.getElementById("admin_vid_link_url");
    var form = document.getElementById("form");
    if (z.value == "" && $('.dz-preview.dz-preview-single')[0].innerHTML == "") {
        document.getElementById("imgerror").style.display = "block";
        return false;
    }else{
        if ($(".dz-preview-cover.dz-processing.dz-image-preview.dz-error.dz-complete").length > 0) {
            document.getElementById("imgerror").style.display = "none";
            document.getElementById("imgtypeerror").style.display = "block";
            return false;
        }else{
            form.submit();
            return true;
        }
    }

    }
</script>
@endsection