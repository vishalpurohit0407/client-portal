@extends('layouts.adminapp')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Categories</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.category.list')}}">Category List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-6 col-5 text-right">
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
                <form method="post" action="{{route('admin.category.store')}}"> 
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Name&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control  @if($errors->has('name')) is-invalid @endif maxlength" name="name" id="name" placeholder="Name">
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
                                    <input type="checkbox" checked value="1" name="status">
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
<script type="text/javascript">
$(document).ready(function() {
    
});
</script>
@endsection