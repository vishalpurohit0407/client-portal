<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')
@section('content')
@php
    $step_count=old('step_count',1);
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
          <form action="{{ route('admin.selfdiagnosis.store') }}" method="post" enctype="multipart/form-data">
          @csrf
              <div class="row">
                <div class="col-md-5 main-img">
                  <div class="form-group">
                    <label class="form-control-label" for="guide_main_image">Main Image</label>
                    <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="http://">
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
                <div class="col-md-7">
                    <div class="form-group">
                        <label class="form-control-label" for="example3cols2Input">Main Title</label>
                        <input type="text" name="main_title" class="form-control" id="main_title">
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label" for="category">Category</label><br>
                        <select class="js-example-basic-single" id="category" name="category[]" multiple="multiple">
                            @if($category)
                              @foreach($category as $cate)
                                <option id="{{$cate->id}}">{{$cate->name}}</option>
                              @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="type">Type</label>
                                <input type="text" class="form-control" id="type" name="type" placeholder="Type">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="example4cols2Input">Duration</label>
                                <!--  -->
                                <div class="input-group mb-3">
                                  <div class="input-group-prepend">
                                    <input type="text" class="form-control" id="duration" name="duration" placeholder="Duration">
                                  </div>
                                  <select class="custom-select" id="duration_type" name="duration_type">
                                    <option value="minute">minute(s)</option>
                                    <option value="hour">hour(s)</option>
                                    <option value="day">day(s)</option>
                                    <option value="month">month(s)</option>
                                  </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="difficulty">Difficulty</label>
                                <select class="form-control" name="difficulty" id="difficulty">
                                    <option value="very_easy">Very easy</option>
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                    <option value="very_hard">Very Hard</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="form-control-label" for="cost">Cost</label>
                                <!--  -->
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" name="cost" id="cost" placeholder="Cost">
                                    <div class="input-group-append">
                                        <span class="input-group-text">$</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label" for="tags">Tags</label><br>
                        <input type="text" class="form-control" id="tags" name="tags" value="" data-toggle="tags" />
                    </div>

                </div>
              </div>

                <hr class="hr-dotted">
          
              <div class="row">
                <div class="col-sm-12">
                  <div class="form-group">
                    <label class="form-control-label" for="example4cols3Input">Introduction (optional)</label>
                    <textarea name="introduction" id="introduction" class="form-control" rows="10"></textarea>
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
                              <option value="youtube">Youtube</option>
                              <option value="vimeo">Vimeo</option>
                            </select>
                          </div>
                          <input type="text" class="form-control" id="vid_link_url" name="vid_link_url" placeholder="Enter here the URL of a Youtube video">
                        </div>
                    </div>
                </div>
              </div>

              <hr class="hr-dotted">
              <div class="guide_repeater">
                <div data-repeater-list="guide_step">
                  <div class="guide_step_list" data-repeater-item>
                    <div class="row mb-6">
                      <div class="col-sm-12">
                        <h1 class="step">Step <span class="step_number">1</span></h1>
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
                          <div class="form-group @if($errors->has('guide_step.0.step_title')) has-danger @endif">
                              <label class="form-control-label" for="step_title">Title</label>
                              <input type="text" class="form-control @if($errors->has('guide_step.0.step_title')) is-invalid @endif" name="step_title" placeholder="Type">
                                @if($errors->has('guide_step.0.step_title'))
                                    <span class="form-text text-danger">{{ $errors->first('guide_step.0.step_title') }}</span>
                                @endif
                          </div>
                      </div>
                      <div class="col-sm-6">
                          <div class="form-group @if($errors->has('guide_step.0.step_description')) has-danger @endif">
                              <label class="form-control-label" for="step_description">Points/Description</label>
                              <textarea id="step_description" name="step_description" class="form-control @if($errors->has('guide_step.0.step_description')) is-invalid @endif" rows="10"></textarea>
                                @if($errors->has('guide_step.0.step_description'))
                                    <span class="form-text text-danger">{{ $errors->first('guide_step.0.step_description') }}</span>
                                @endif
                          </div>
                      </div>
                    </div>
                  </div>
                </div>
                <input class="btn btn-primary btn-sm" data-repeater-create type="button" value="Add Steps"/>
              </div>

              <hr class="hr-dotted">

              <div class="row">
                <div class="col-6">
                  <a href="#!" class="btn btn-info">Save As Draft</a>
                  <input type="submit" class="btn btn-success" name="submit" value="Submit">
                  <a href="#!" class="btn btn-primary">Cancel</a>
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
</style>

@endsection
@section('pagewise_js')
<script src="{{asset('assets/vendor/ckeditor/ckeditor.js')}}"></script>
<script src="{{asset('assets/vendor/jquery-repeater/jquery.repeater.min.js')}}"></script>
<script type="text/javascript">
var stepCount = 1;
$(document).ready(function() {
    $('.js-example-basic-single').select2();

    CKEDITOR.replace('introduction', {
      extraPlugins:'justify,videodetector',
      height : '300px',
      allowedContent : true,
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
      filebrowserUploadMethod: 'form'
    });

    /*CKEDITOR.replace('step_description1', {
      extraPlugins:'justify,videodetector',
      height : '300px',
      allowedContent : true,
      filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
      filebrowserUploadMethod: 'form'
    });
*/
    var step_count=<?php echo $step_count;?>;
    $(".guide_repeater").repeater({
        initEmpty:false,
        isFirstItemUndeletable: true,
        defaultValues:{},
        show:function(){
            step_count++;
            $("#step_count").val(step_count);
            $(this).find('.is-invalid').removeClass("is-invalid");
            $(this).find('.step_number').text(step_count);
            $(this).show('fast',function(){
                                       
            })
        },
        hide:function(e){
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
                    step_count--;
                    $("#step_count").val(step_count);
                    $(this).remove();
                    stepgenerateID();
                }
            });
        },
        ready: function (setIndexes) {
            
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

function addMoreStep(){
  console.log(stepCount);
  stepCount++;

  var ids = 'step_description'+stepCount;
  console.log(ids);
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