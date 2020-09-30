@extends('layouts.adminapp')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Users</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('admin.user.list')}}">Users List</a></li>
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
            <div class="card-header">
              <h3 class="mb-0">{{$title}}</h3>
            </div>
            <!-- Card body -->
            <div class="card-body">
                <!-- Form groups used in grid -->
                <form class="form" action="{{ route('admin.user.store') }}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                    <div class="row">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label class="form-control-label" for="input-email">{{ __('Profile Picture') }}</label>
                                <div class="custom-file">
                                    <input type="file" name="profile_img" class="custom-file-input" id="customFileLang" lang="en" onchange="loadFile(event)">
                                    <label class="custom-file-label" for="customFileLang">Select file</label>
                                </div>   
                            </div>
                        </div>
                        <div class="col-md-3">
                            <a href="javascript:void(0);">
                                <img id="output" src="{{asset('assets/img/theme/defualt-user.png')}}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 140px;">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('name')) has-danger @endif ">
                                <label class="form-control-label" for="name">Name&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control  @if($errors->has('name')) is-invalid @endif maxlength" name="name" id="name" placeholder="Name" value="{{old('name')}}">
                                @if($errors->has('name'))
                                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('email')) has-danger @endif ">
                                <label class="form-control-label" for="email">Email&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control  @if($errors->has('email')) is-invalid @endif maxlength" name="email" id="email" placeholder="Email" value="{{old('email')}}">
                                @if($errors->has('email'))
                                    <span class="form-text text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('password')) has-danger @endif ">
                                <label class="form-control-label" for="password">Password&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="password" class="form-control  @if($errors->has('password')) is-invalid @endif maxlength" name="password" id="password" placeholder="Password">
                                @if($errors->has('password'))
                                    <span class="form-text text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group @if($errors->has('confirmpass')) has-danger @endif ">
                                <label class="form-control-label" for="confirmpass">Confirm Password&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="password" class="form-control  @if($errors->has('confirmpass')) is-invalid @endif maxlength" name="confirmpass" id="confirmpass" placeholder="Confirm Password">
                                @if($errors->has('confirmpass'))
                                    <span class="form-text text-danger">{{ $errors->first('confirmpass') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-control-label" for="name">Status</label>
                            <div class="form-group">
                                <label class="custom-toggle custom-toggle-success">
                                    <input type="checkbox" checked="checked" value="1" name="status">
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

var loadFile = function(event) {
    var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
};
</script>
@endsection