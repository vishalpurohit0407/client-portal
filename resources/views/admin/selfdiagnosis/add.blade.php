<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')
@section('content')

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
          <!-- Form groups used in grid -->
          <div class="row">
            <div class="col-md-5">
              <div class="form-group">
                <label class="form-control-label" for="example3cols1Input">Main Image</label>
                <div class="dropzone dropzone-single mb-3" data-toggle="dropzone" data-dropzone-url="http://">
                  <div class="fallback">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" id="projectCoverUploads">
                      <label class="custom-file-label" for="projectCoverUploads">Choose file</label>
                    </div>
                  </div>
                  <div class="dz-preview dz-preview-single">
                    <div class="dz-preview-cover">
                      <img class="dz-preview-img" src="..." alt="..." data-dz-thumbnail>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group">
                <label class="form-control-label" for="example3cols2Input">Description</label>
                <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
              </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label class="form-control-label" for="example3cols3Input">One of three cols</label>
                <input type="text" class="form-control" id="example3cols3Input" placeholder="One of three cols">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6 col-md-3">
              <div class="form-group">
                <label class="form-control-label" for="example4cols1Input">One of four cols</label>
                <input type="text" class="form-control" id="example4cols1Input" placeholder="One of four cols">
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="form-group">
                <label class="form-control-label" for="example4cols2Input">One of four cols</label>
                <input type="text" class="form-control" id="example4cols2Input" placeholder="One of four cols">
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="form-group">
                <label class="form-control-label" for="example4cols3Input">One of four cols</label>
                <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
              </div>
            </div>
            <div class="col-sm-6 col-md-3">
              <div class="form-group">
                <label class="form-control-label" for="example4cols3Input">One of four cols</label>
                <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="example2cols1Input">One of two cols</label>
                <input type="text" class="form-control" id="example2cols1Input" placeholder="One of two cols">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label" for="example2cols2Input">One of two cols</label>
                <input type="text" class="form-control" id="example2cols2Input" placeholder="One of two cols">
              </div>
            </div>
          </div>
        </div>
      </div>
      
      
    </div>

@endsection
@section('pagewise_js')
<script type="text/javascript">
$(document).ready(function() {
    $('[data-switch=true]').bootstrapSwitch();
    $('.groupofdocument').select2({
        placeholder:'Select a document group.',
    });
    $(".maxlength").maxlength({
        threshold: 3,
        warningClass: "label label-success label-rounded label-inline",
        limitReachedClass: "label label-danger label-rounded label-inline",
        appendToParent: true,
        separator: ' of ',
        preText: 'You have ',
        postText: ' chars remaining.',
        validate: true
    })
});
</script>
@endsection