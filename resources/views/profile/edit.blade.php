@extends('layouts.app', ['title' => __('User Profile')])

@section('content')
    @include('users.partials.header', [
        'title' => __('Hello') . ' '. auth()->user()->name,
        'description' => __('This is your profile page. You can edit your name, email, profile picture, and change the password from here.'),
        'class' => 'col-lg-7'
    ])   

    <div class="container-fluid mt--7 pb-5">
        <div class="row">
            <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
                
                <div class="card card-profile shadow">
                    <div class="row justify-content-center">
                        <div class="col-lg-3 order-lg-2">
                            <div class="card-profile-image">
                                <a href="javascript:void(0);">
                                    <img src="{{auth()->user()->user_image_url}}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 140px;">
                                </a>
                            </div>
                        </div>
                    </div>
                   
                    <div class="card-body pt-0 pt-md-5">
                        
                        <div class="text-center mt-md-6 mt-6">
                            <h3>
                                {{ auth()->user()->name }}
                            </h3>
                            <div class="h5 font-weight-300">
                                <i class="ni location_pin mr-2"></i>{{ auth()->user()->email }}
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-8 order-xl-1">
                <div class="card bg-secondary shadow">
                    <div class="card-header bg-white border-0">
                        <div class="row align-items-center">
                            <h3 class="col-12 mb-0">{{ __('Edit Profile') }}</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('profile.update') }}" autocomplete="off" enctype= "multipart/form-data">
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('User information') }}</h6>
                            
                            @if (session('status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-name">{{ __('Name') }}</label>
                                    <div class="{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input type="text" name="name" id="input-name" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="{{ __('Name') }}" value="{{ old('name', auth()->user()->name) }}"  autofocus>

                                        @if ($errors->has('name'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">{{ __('Email') }}</label>
                                    <div class="{{ $errors->has('email') ? ' has-danger' : '' }}">
                                        <input type="email" name="email" id="input-email" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" placeholder="{{ __('Email') }}" value="{{ old('email', auth()->user()->email) }}">

                                        @if ($errors->has('email'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                
                                <div class="row row-example">
                                    <div class="col-12 col-md-8">
                                        <div class="form-group">
                                            <label class="form-control-label" for="input-email">{{ __('Profile Picture') }}</label>
                                            <div class="custom-file">
                                                <input type="file" name="profile_img" class="custom-file-input" id="customFileLang" lang="en" onchange="loadFile(event)">
                                                <label class="custom-file-label" for="customFileLang">Select file</label>
                                            </div>   
                                        </div>
                                    </div>

                                    <div class="col-6 col-md-4">
                                        <a href="javascript:void(0);">
                                            <img id="output" src="{{auth()->user()->user_image_url}}" class="rounded-circle img-center img-fluid shadow shadow-lg--hover" style="width: 140px;">
                                        </a>
                                    </div>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Save') }}</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4" />
                        <form method="post" action="{{ route('profile.password') }}" autocomplete="off" >
                            @csrf
                            @method('put')

                            <h6 class="heading-small text-muted mb-4">{{ __('Password') }}</h6>

                            @if (session('password_status'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('password_status') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif

                            <div class="pl-lg-4">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-current-password">{{ __('Current Password') }}</label>
                                    <div class="{{ $errors->has('old_password') ? ' has-danger' : '' }}">
                                        <input type="password" name="old_password" id="input-current-password" class="form-control {{ $errors->has('old_password') ? ' is-invalid' : '' }}" placeholder="{{ __('Current Password') }}" value="" >
                                        
                                        @if ($errors->has('old_password'))
                                            <span class="invalid-feedback"  role="alert">
                                                <strong>{{ $errors->first('old_password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password">{{ __('New Password') }}</label>
                                    <div class="{{ $errors->has('password') ? ' has-danger' : '' }}">
                                        <input type="password" name="password" id="input-password" class="form-control form-control-alternative{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="{{ __('New Password') }}" value="" >
                                        
                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-control-label" for="input-password-confirmation">{{ __('Confirm New Password') }}</label>
                                    <input type="password" name="password_confirmation" id="input-password-confirmation" class="form-control form-control-alternative" placeholder="{{ __('Confirm New Password') }}" value="" >
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-success mt-4">{{ __('Change password') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
       
    </div>
@endsection

@section('pagewise_js')
<script type="text/javascript">
var loadFile = function(event) {
var output = document.getElementById('output');
    output.src = URL.createObjectURL(event.target.files[0]);
};
</script>
@endsection