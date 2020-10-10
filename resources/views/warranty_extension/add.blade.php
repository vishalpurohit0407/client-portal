@extends('layouts.app')

@section('content')
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-9 col-9">
              <h6 class="h2 text-white d-inline-block mb-0">Warranty Extensions</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                  <li class="breadcrumb-item"><a href="{{route('user.warranty_extension.list')}}">Warranty Extension List</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                </ol>
              </nav>
            </div>
            <div class="col-lg-3 col-3 text-right">
              <a href="{{route('user.warranty_extension.list')}}" class="btn btn-sm btn-neutral">Back</a>
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
                <form method="post" action="{{route('user.warranty_extension.store')}}"> 
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group @if($errors->has('unique_key')) has-danger @endif ">
                                <label class="form-control-label" for="unique_key">Unique Key&nbsp;<strong class="text-danger">*</strong></label>
                                <input type="text" class="form-control  @if($errors->has('unique_key')) is-invalid @endif maxlength" name="unique_key" id="unique_key" placeholder="Unique Key">
                                @if($errors->has('unique_key'))
                                    <span class="form-text text-danger">{{ $errors->first('unique_key') }}</span>
                                @endif
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