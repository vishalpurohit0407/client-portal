<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')
@section('content')
@php
    $stepcount=count($guide_step);
    if($stepcount==0){
        $stepcount=1;
    }
    $step_count=old('step_count',$stepcount);
@endphp
<div class="header bg-primary pb-6">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-lg-6 col-7">
          <h6 class="h2 text-white d-inline-block mb-0">Add Self Diagnosis</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                <li class="breadcrumb-item"><a href="#">Self Diagnosis</a></li>
                <li class="breadcrumb-item active" aria-current="page">Add Self Diagnosis</li>
            </ol>
          </nav>
        </div>
       
      </div>
      <!-- Card stats -->
      
    </div>
  </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
      <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
          <h3 class="mb-0">Add Self Diagnosis</h3>
        </div>

        <!-- Card body -->
        <div class="card-body">
          <form action="{{ route('admin.selfdiagnosis.update',$guide->id) }}" method="post" enctype="multipart/form-data">
          @csrf
          {{ method_field('PUT') }}
              <div class="row">
                <div class="col-md-5 main-img">
                  <div class="form-group">
                    <label class="form-control-label" for="guide_main_image">Main Image</label>
                    
                    <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="{{route('admin.selfdiagnosis.mainupload',['id' => $guide->id])}}">
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
                    <p class="text-info mb-0"><strong>Recommended Size: 800 X 600 px</strong></p>
                   
                  </div>
                </div>
                <div class="col-md-7">
                    <div class="form-group ">
                        <label class="form-control-label" for="example3cols2Input">Main Title <strong class="text-danger">*</strong></label>
                        <input type="text" name="main_title" class="form-control @if($errors->has('main_title')) is-invalid @endif" id="main_title" value="{{old('main_title', $guide->main_title)}}">
                        @if($errors->has('main_title'))
                            <span class="form-text text-danger">{{ $errors->first('main_title') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-control-label @if($errors->has('description')) has-danger @endif" for="description">Description <strong class="text-danger">*</strong></label>
                        <textarea class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" rows="3">{{old('description', $guide->description)}}</textarea>
                        @if($errors->has('description'))
                            <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="category">Category <strong class="text-danger">*</strong></label><br>
                        <select class="js-example-basic-single form-control @if($errors->has('category')) is-invalid @endif" id="category" name="category[]" multiple="multiple">
                            @if($category)
                              @foreach($category as $cate)
                                <option value="{{$cate->id}}" @if(in_array($cate->id,$selectedCategory)) selected @endif>{{$cate->name}}</option>
                              @endforeach
                            @endif
                        </select>
                        @if($errors->has('category'))
                            <span class="form-text text-danger">{{ $errors->first('category') }}</span>
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="type">Type <strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control @if($errors->has('type')) is-invalid @endif" id="type" name="type" placeholder="Type" value="{{old('type', $guide->type)}}">
                                @if($errors->has('type'))
                                    <span class="form-text text-danger">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example4cols2Input">Duration <strong class="text-danger">*</strong></label>
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <input type="number" class="form-control @if($errors->has('duration')) is-invalid @endif" id="duration" name="duration" placeholder="Duration" value="{{old('duration', $guide->duration)}}">
                                  </div>
                                  <select class="custom-select" id="duration_type" name="duration_type">
                                    <option value="minute(s)" @if(old('duration_type', $guide->duration_type) == 'minute(s)') selected @endif>minute(s)</option>
                                    <option value="hour(s)" @if(old('duration_type', $guide->duration_type) == 'hour(s)') selected @endif>hour(s)</option>
                                    <option value="day(s)" @if(old('duration_type', $guide->duration_type) == 'day(s)') selected @endif>day(s)</option>
                                    <option value="month(s)" @if(old('duration_type', $guide->duration_type) == 'month(s)') selected @endif>month(s)</option>
                                  </select>
                                    @if($errors->has('duration'))
                                        <span class="form-text text-danger">{{ $errors->first('duration') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="difficulty">Difficulty <strong class="text-danger">*</strong></label>
                                <select class="form-control" name="difficulty" id="difficulty">
                                    <option value="Very easy" @if(old('difficulty', $guide->difficulty) == 'Very easy') selected @endif>Very easy</option>
                                    <option value="Easy" @if(old('difficulty', $guide->difficulty) == 'Easy') selected @endif>Easy</option>
                                    <option value="Medium" @if(old('difficulty', $guide->difficulty) == 'Medium') selected @endif>Medium</option>
                                    <option value="Hard" @if(old('difficulty', $guide->difficulty) == 'Hard') selected @endif>Hard</option>
                                    <option value="Very Hard" @if(old('difficulty', $guide->difficulty) == 'Very Hard') selected @endif>Very Hard</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="cost">Cost <strong class="text-danger">*</strong></label>
                                <!--  -->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @if($errors->has('cost')) is-invalid @endif" name="cost" id="cost" placeholder="Cost" value="{{old('cost', $guide->cost)}}">
                                    <div class="input-group-append">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                                @if($errors->has('cost'))
                                    <span class="form-text text-danger">{{ $errors->first('cost') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="tags">Tags</label><br>
                        <input type="text" class="form-control" id="tags" name="tags" value="{{old('tags', $guide->tags)}}" data-toggle="tags" />
                    </div>

                </div>
              </div>

                <hr class="hr-dotted">
          
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="form-control-label" for="example4cols3Input">Introduction (optional)</label>
                    <textarea name="introduction" id="introduction" class="form-control" rows="10">{{old('introduction', $guide->introduction)}}</textarea>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="form-control-label" for="vid_link_type">Video Link</label>
                        <div class="input-group mb-3">
                          <div class="input-group-prepend">
                            <select class="custom-select" id="vid_link_type" name="vid_link_type" style="border-radius: 0;font-size:1rem;">
                              <option value="youtube" @if(old('vid_link_type', $guide->introduction_video_type) == 'youtube') selected @endif>Youtube</option>
                              <option value="vimeo" @if(old('vid_link_type', $guide->introduction_video_type) == 'vimeo') selected @endif>Vimeo</option>
                            </select>
                          </div>
                          <input type="text" class="form-control" id="vid_link_url" name="vid_link_url" placeholder="Enter here the URL of a Youtube or Vimeo video" value="{{old('vid_link_url', $guide->introduction_video_link)}}">
                        </div>
                        <p class="text-info mb-0"><strong>Note: Please add embed URL for Youtube and Vimeo video.</strong></p>
                    </div>
                </div>
              </div>

              <hr class="hr-dotted">
              <div class="guide_repeater">
                <div data-repeater-list="guide_step">
                    <script type="text/javascript">let stepMediaArr = new Array();</script>

                    @if(old('step_count') == '' && $guide_step->count() > 0)
                        @foreach ($guide_step as $key => $guidestep)
                           
                            <div class="guide_step_list" data-repeater-item>
                                <div class="row mb-6">
                                  <div class="col-sm-12">
                                    <h1 class="step">Step <span class="step_number">{{$key + 1}}</span></h1>
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>
                                    <input type="hidden" class="step_key" name="step_key" value="{{ $guidestep->step_key }}">

                                    <div class="dropzone dropzone-init"></div>
                                
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-sm-6">
                                      <div class="form-group">
                                          <label class="form-control-label" for="step_title">Title</label>
                                          <input type="text" class="form-control" name="step_title" placeholder="Title" value="{{ $guidestep->title }}">
                                      </div>
                                      <div class="form-group">
                                          <label class="form-control-label" for="step_video">Video</label>
                                          <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                              <select class="custom-select" id="step_video_type" name="step_video_type" style="border-radius: 0;font-size:1rem;">
                                                <option value="youtube" @if(old('step_video_type', $guidestep->video_type) == 'youtube') selected @endif>Youtube</option>
                                                <option value="vimeo" @if(old('step_video_type', $guidestep->video_type) == 'vimeo') selected @endif>Vimeo</option>
                                              </select>
                                            </div>
                                            <input type="text" class="form-control" id="step_video_media" name="step_video_media" placeholder="Enter here the URL of a Youtube or vimeo video" value="{{old('step_video_media', $guidestep->video_media)}}">
                                          </div>
                                          <p class="text-info mb-0"><strong>Note: Please add embed URL for Youtube and Vimeo video.</strong></p>
                                      </div>
                                  </div>
                                  <div class="col-sm-6">
                                      <div class="form-group">
                                          <label class="form-control-label">Points/Description</label>
                                          <textarea name="step_description" id="step_description_{{$key}}" class="form-control step_description" rows="10">{{ $guidestep->description }}</textarea>
                                      </div>
                                  </div>
                                </div>
                                <hr>
                            </div>
                            @if($guidestep->media)
                                <script type="text/javascript">var subMediaArr = new Array();</script>
                                @foreach ($guidestep->media as $key => $substep)
                                    <script type="text/javascript">
                                        subMediaArr.push({ name: '{{$substep->media}}', size: '', url: '{{$substep->media_url}}', id: '{{$substep->id}}'});
                                    </script>
                                @endforeach
                                <script type="text/javascript">
                                    stepMediaArr['{{$guidestep->step_key}}'] = subMediaArr;
                                </script>
                            @endif
                             
                        @endforeach
                    @else

                      <div class="guide_step_list" data-repeater-item>

                        <div class="row mb-6">
                          <div class="col-sm-12">
                            <h1 class="step">Step <span class="step_number">1</span></h1>
                            <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>
                            <input type="hidden" class="step_key" name="step_key" value="{{ old('guide_step.0.step_key') }}">

                            <div class="dropzone dropzone-init"></div>
                            
                            @if($errors->has('guide_step.0.stepfilupload'))
                                <div class="invalid-feedback" >{{ $errors->first('guide_step.0.stepfilupload') }}</div>
                            @elseif($errors->has('guide_step.0.stepfilupload.*'))
                                <div class="invalid-feedback" >{{ $errors->first('guide_step.0.stepfilupload.*') }}</div>
                            @endif
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-sm-6">
                              <div class="form-group @if($errors->has('guide_step.0.step_title')) has-danger @endif">
                                  <label class="form-control-label" for="step_title">Title</label>
                                  <input type="text" class="form-control @if($errors->has('guide_step.0.step_title')) is-invalid @endif" name="step_title" placeholder="Title" value="{{ old('guide_step.0.step_title') }}">
                                    @if($errors->has('guide_step.0.step_title'))
                                        <span class="form-text text-danger">{{ $errors->first('guide_step.0.step_title') }}</span>
                                    @endif
                              </div>
                                <div class="form-group">
                                  <label class="form-control-label" for="step_video">Video</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                          <select class="custom-select" id="step_video_type" name="step_video_type" style="border-radius: 0;font-size:1rem;">
                                            <option value="youtube" @if(old('guide_step.0.step_video_type') == 'youtube') selected @endif>Youtube</option>
                                            <option value="vimeo" @if(old('guide_step.0.step_video_type') == 'vimeo') selected @endif>Vimeo</option>
                                          </select>
                                        </div>
                                        <input type="text" class="form-control" id="step_video_media" name="step_video_media" placeholder="Enter here the URL of a Youtube or vimeo video" value="{{old('guide_step.0.step_video_media')}}">
                                    </div>
                                    <p class="text-info mb-0"><strong>Note: Please add embed URL for Youtube and Vimeo video.</strong></p>
                                </div>
                          </div>
                          <div class="col-sm-6">
                              <div class="form-group @if($errors->has('guide_step.0.step_description')) has-danger @endif">
                                  <label class="form-control-label">Points/Description</label>
                                  <textarea name="step_description" id="step_description_1" class="form-control step_description @if($errors->has('guide_step.0.step_description')) is-invalid @endif" rows="10">{{ old('guide_step.0.step_description') }}</textarea>
                                    @if($errors->has('guide_step.0.step_description'))
                                        <span class="form-text text-danger">{{ $errors->first('guide_step.0.step_description') }}</span>
                                    @endif
                              </div>
                          </div>
                        </div>
                        <hr>
                      </div>
                        @if($step_count>=2)
                            @for($e=1;$e<$step_count;$e++)
                            <div class="guide_step_list" data-repeater-item>
                                <div class="row mb-6">
                                  <div class="col-sm-12">
                                    <h1 class="step">Step <span class="step_number">{{ $e+1 }}</span></h1>
                                    <a href="javascript:;" data-repeater-delete="" class="btn btn-icon btn-danger"><i class="fas fa-trash"></i></a>
                                    <input type="hidden" class="step_key" name="step_key" value="{{ old('guide_step.'.$e.'.step_key') }}">
                                    <div class="dropzone dropzone-multiple" data-toggle="dropzone" data-dropzone-multiple data-dropzone-url="http://">
                                      <div class="fallback">
                                        <div class="custom-file">
                                          <input type="file" class="custom-file-input" name="stepfilupload" multiple>
                                          <label class="custom-file-label" for="customFileUploadMultiple">Choose file</label>
                                        </div>
                                      </div>
                                      <ul class="dz-preview dz-preview-multiple list-group list-group-lg list-group-flush">
                                        <li class="list-group-item px-0">
                                          <img class="avatar-img rounded" src="" data-dz-thumbnail>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group @if($errors->has('guide_step.'.$e.'.step_title')) has-danger @endif">
                                              <label class="form-control-label" for="step_title">Title</label>
                                              <input type="text" class="form-control @if($errors->has('guide_step.'.$e.'.step_title')) is-invalid @endif" name="step_title" placeholder="Title" value="{{ old('guide_step.'.$e.'.step_title') }}">
                                                @if($errors->has('guide_step.'.$e.'.step_title'))
                                                    <span class="form-text text-danger">{{ $errors->first('guide_step.'.$e.'.step_title') }}</span>
                                                @endif
                                        </div>

                                        <div class="form-group">
                                            <label class="form-control-label" for="step_video">Video</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                  <select class="custom-select" id="step_video_type" name="step_video_type" style="border-radius: 0;font-size:1rem;">
                                                    <option value="youtube" @if(old('guide_step.'.$e.'.step_video_type') == 'youtube') selected @endif>Youtube</option>
                                                    <option value="vimeo" @if(old('guide_step.'.$e.'.step_video_type') == 'vimeo') selected @endif>Vimeo</option>
                                                  </select>
                                                </div>
                                                <input type="text" class="form-control" id="step_video_media" name="step_video_media" placeholder="Enter here the URL of a Youtube or vimeo video" value="{{ old('guide_step.'.$e.'.step_video_media') }}">
                                            </div>
                                            <p class="text-info mb-0"><strong>Note: Please add embed URL for Youtube and Vimeo video.</strong></p>
                                        </div>

                                    </div>
                                  <div class="col-sm-6">
                                      <div class="form-group @if($errors->has('guide_step.'.$e.'.step_description')) has-danger @endif">
                                          <label class="form-control-label">Points/Description</label>
                                          <textarea name="step_description" id="step_description_{{$e+1}}" class="form-control step_description @if($errors->has('guide_step.'.$e.'.step_description')) is-invalid @endif" rows="10">{{ old('guide_step.'.$e.'.step_description') }}</textarea>
                                            @if($errors->has('guide_step.'.$e.'.step_description'))
                                                <span class="form-text text-danger">{{ $errors->first('guide_step.'.$e.'.step_description') }}</span>
                                            @endif
                                      </div>
                                  </div>
                                </div>
                                <hr>
                              </div>
                            @endfor
                        @endif
                    @endif
                </div>
                <input class="btn btn-primary btn-sm" data-repeater-create type="button" value="Add Steps"/>
              </div>

              <hr class="hr-dotted">

              <div class="row">
                <div class="col-6">
                    @if($guide->status == '3')
                        <input type="submit" class="btn btn-info" name="submit" value="Save As Draft">
                    @endif
                    <input type="submit" class="btn btn-success" name="submit" value="Published">
                    <a href="{{route('admin.selfdiagnosis.list')}}" class="btn btn-primary">Cancel</a>
                    <input type="hidden" name="step_count" id="step_count" value="{{$step_count}}">
                </div>
              </div>
            </form>

        </div>
      </div>
      
      
    </div>
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
@section('pagewise_js')
<script src="{{asset('assets/vendor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-repeater/jquery.repeater.min.js')}}"></script>
<script type="text/javascript">
var stepCount = 1;
let guide_id= '{{$guide->id}}';
$(document).ready(function() {
    // console.log(stepMediaArr);
    @if($guide->main_image) 
        $(".dz-preview.dz-preview-single").html('<div class="dz-preview-cover dz-processing dz-image-preview dz-success dz-complete"><img class="dz-preview-img" src="{{asset($guide->main_image_url)}}"></div>');
        $(".dropzone.dropzone-single").addClass('dz-clickable dz-max-files-reached');
    @endif
    /*var mainImage = $(".dropzone-single-new").dropzone({
        url: "{{route('admin.selfdiagnosis.mainupload',['id' => $guide->id])}}",
        maxFiles: 5,
        paramName: 'file',
        maxFilesSize: 1024,
        init: function() {

            var thisDropzone = this;
            var mockFile = { name: 'Name Image', size: 12345, type: 'image/jpeg' };
            thisDropzone.emit("addedfile", mockFile);
            thisDropzone.emit("success", mockFile);
            thisDropzone.emit("thumbnail", mockFile, "https://admin.scanit.in/storage/adminprofile/1/b06edd21b7724c7d3c7333fb142fa65a.jpg")
        },
    });*/


    /*var mockFile = { name: '{{$guide->main_image}}'};
    mainImage.emit("addedfile", mockFile);
    mainImage.emit("thumbnail", mockFile, '{{$guide->main_image_url}}');
    mainImage.emit("complete", mockFile);
    mainImage.files.push(mockFile);*/

    $('.js-example-basic-single').select2();

    CKEDITOR.replace('introduction', {
      extraPlugins:'justify,videodetector',
      height : '300px',
      allowedContent : true,
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
      filebrowserUploadMethod: 'form'
    });

   
    function addon_step_unique_id(){
        return "key_"+Math.random().toString(16).slice(2);
    }
    var step_count=<?php echo $step_count;?>;
    $(".guide_repeater").repeater({
        initEmpty:false,
        isFirstItemUndeletable: true,
        defaultValues:{},
        show:function(){
            step_count++;
            $("#step_count").val(step_count);
            $(this).find('.is-invalid').removeClass("is-invalid");
            $(this).find('.has-danger').removeClass("has-danger");
            $(this).find('.text-danger').remove();
            $(this).find('.step_number').text(step_count);
            $(this).show('fast',function(){

                var unique_id=addon_step_unique_id();
                
                $(this).find(".step_key").val(unique_id);

                $(this).find(".step_description").attr('id','step_description_'+step_count);
                CKEDITOR.replace('step_description_'+step_count, {
                  extraPlugins:'justify,videodetector',
                  height : '300px',
                  allowedContent : true,
                  filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                  filebrowserUploadMethod: 'form'
                });

                Dropzone.options.myAwesomeDropzone = false;
                Dropzone.autoDiscover = false;

                $(this).find('.dropzone-init').each(function(){
                    
                    var dropUrl = "{{ route('admin.selfdiagnosis.upload', ['_token' => csrf_token()]) }}";
                    dropUrl+="&unique_id="+unique_id+"&guide_id="+guide_id;
                    var dropMaxFiles = 6;
                    var dropParamName = 'file_image';
                    var dropMaxFileSize = 2048;

                    $(this).dropzone({
                        url: dropUrl,
                        maxFiles: dropMaxFiles,
                        paramName: dropParamName,
                        maxFilesSize: dropMaxFileSize,
                        addRemoveLinks: true,
                        init: function() {
                            this.on("complete", function(file) {
                                //linkObj.find(".dz-remove").html("<span class='fa fa-trash text-danger' style='font-size: 1.5em'></span>");
                                $(file._removeLink).html("<span class='fa fa-trash text-danger' style='font-size: 1.5em'></span>");
                            });
                        },
                        success: function(file, response){
                            
                            if(response.status){   
                                //linkObj.find(".dz-remove").attr("data-dz-media_id", response.id);
                                $(file._removeLink).attr("data-dz-media_id", response.id);
                                callRemoveImg();
                            }
                        }
                        // Rest of the configuration equal to all dropzones
                    });
                });
            })
        },
        hide:function(e){
            var keyId = $(this).find('.step_key').val();
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if(result.value){

                    $.ajax({
                        url: "{{ route('admin.selfdiagnosis.remove.step',['_token' => csrf_token() ]) }}",
                        data: { step_key: keyId},
                        type: 'POST',
                        success: function (data) {
                            
                            console.log();
                        },
                        error: function (data) {
                            
                        }
                    });
                    step_count--;
                    $("#step_count").val(step_count);
                    $(this).remove();
                    stepgenerateID();
                }
            });
        },
        ready: function (setIndexes) {

            Dropzone.options.myAwesomeDropzone = false;
            Dropzone.autoDiscover = false;

            $('.guide_step_list').each(function(){

                var unique_id = $(this).find(".step_key").val()
                if($(this).find(".step_key").val()==''){
                    var unique_id=addon_step_unique_id();
                    $(this).find(".step_key").val(unique_id);
                }

                CKEDITOR.replace($(this).find('.step_description').attr('id'), {
                  extraPlugins:'justify,videodetector',
                  height : '300px',
                  allowedContent : true,
                  filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
                  filebrowserUploadMethod: 'form'
                });

                $(this).find('.dropzone-init').each(function(){
                    
                    var dropUrl = "{{ route('admin.selfdiagnosis.upload', ['_token' => csrf_token()]) }}";
                    dropUrl+="&unique_id="+unique_id+"&guide_id="+guide_id;
                    var dropMaxFiles = 6;
                    var dropParamName = 'file_image';
                    var dropMaxFileSize = 2048;

                    $(this).dropzone({
                        url: dropUrl,
                        maxFiles: dropMaxFiles,
                        paramName: dropParamName,
                        maxFilesSize: dropMaxFileSize,
                        addRemoveLinks: true,
                        init: function() {
                            this.on("complete", function(file) {
                                //linkObj.find(".dz-remove").html("<span class='fa fa-trash text-danger' style='font-size: 1.5em'></span>");
                                $(file._removeLink).html("<span class='fa fa-trash text-danger' style='font-size: 1.5em'></span>");
                            });

                            let myDropzone = this;

                            // If you only have access to the original image sizes on your server,
                            // and want to resize them in the browser:
                            

                            if(stepMediaArr[unique_id]){
                                for (i = 0; i < stepMediaArr[unique_id].length; i++) {
                                    
                                    let mockFile = { name: stepMediaArr[unique_id][i].name};
                                    myDropzone.emit("addedfile", mockFile);
                                    myDropzone.emit("thumbnail", mockFile, stepMediaArr[unique_id][i].url);
                                    myDropzone.emit("complete", mockFile);
                                    
                                    $(mockFile._removeLink).attr("data-dz-media_id", stepMediaArr[unique_id][i].id);
                                    $(mockFile.previewTemplate).find(".dz-image img").addClass("dropzone-saved-img");
                                    //$(myDropzone).find(".dz-remove").attr("data-dz-media_id", stepMediaArr[unique_id][i].id);
                                }
                            }

                            callRemoveImg();

                        },
                        success: function(file, response){
                            
                            if(response.status){   
                                //linkObj.find(".dz-remove").attr("data-dz-media_id", response.id);
                                $(file._removeLink).attr("data-dz-media_id", response.id);
                                callRemoveImg();
                            }
                        }
                    });
                });

            });
        },
    });
    function stepgenerateID(){
        var step_number=0;
        $( "#guide_repeater > .guide_step_list").each(function( index ) {
            step_number++;             
            $(this).find('.step_number').text(step_number);
        });
    }

});


function callRemoveImg(){

    $(".dz-remove").on("click", function (e) {
        
        e.preventDefault();
        e.stopPropagation();

        var imageId = $(this).data('dz-media_id');
       
        if(imageId){

            $.ajax({
                url: "{{ route('admin.selfdiagnosis.remove.image',['_token' => csrf_token() ]) }}",
                data: { imageId: imageId},
                type: 'POST',
                success: function (data) {
                    
                    console.log();
                },
                error: function (data) {
                    
                }
            });     
        }
    });
}

function addMoreStep(){
  
  stepCount++;

  var ids = 'step_description'+stepCount;
  var html = $("#step_hidden_html");

  var ele = html.find('.step_description_cl').attr('id',ids);
  
  $("#step_html").append(html.html());

 /* $('.remove_level').on('click', function() {
    
    $(this).closest('.row').remove();
  });*/

  /*CKEDITOR.replace('step_description'+stepCount, {
    extraPlugins:'justify,videodetector',
    height : '300px',
    allowedContent : true,
    filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
    filebrowserUploadMethod: 'form'
  });*/

  /*CKEDITOR.editorConfig = function (config) {
      
    config.height = 300;
    config.extraPlugins ='justify,videodetector';
    config.height = '400px';
    config.allowedContent = true;
    config.filebrowserUploadUrl = "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}";
    config.filebrowserUploadMethod = 'form';
  };

  CKEDITOR.replace(ids);*/
}

</script>
@endsection