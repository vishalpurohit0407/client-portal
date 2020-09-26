@extends('layouts.adminapp')
@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">CMS Pages</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.cms.page.list')}}">CMS Pages List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
              <a href="{{route('admin.cms.page.list')}}" class="btn btn-sm btn-neutral">Back</a>
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
                <form class="form" action="{{ route('admin.cms.page.update',$page->id) }}" method="post" enctype="multipart/form-data">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('title')) has-danger @endif ">
                                <label class="form-control-label" for="title">Title&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control  @if($errors->has('title')) is-invalid @endif maxlength" name="title" id="title" placeholder="Title" value="{{old('title',$page->title)}}">
                                @if($errors->has('title'))
                                    <span class="form-text text-danger">{{ $errors->first('title') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('content')) has-danger @endif ">
                                <label class="form-control-label" for="content">Content&nbsp;<strong class="text-danger">*</strong></label>
                                <textarea name="content" id="content" class="form-control" rows="10">{{old('content',$page->content)}}</textarea>
                                @if($errors->has('content'))
                                    <span class="form-text text-danger">{{ $errors->first('content') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label" for="name">Status</label>
                            <div class="form-group">
                                <label class="custom-toggle custom-toggle-success">
                                    <input type="checkbox" {{$page->status == '1' ? "checked" : ''}} value="1" name="status">
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
@endsection
@section('pagewise_js')
<script src="{{asset('assets/vendor/ckeditor/ckeditor.js')}}"></script>
<script type="text/javascript">
$(document).ready(function() {
    CKEDITOR.replace('content', {
        extraPlugins:'justify,videodetector',
        height : '300px',
        allowedContent : true,
    });
});
</script>
@endsection