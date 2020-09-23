<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
<style type="text/css">
@media print {

    .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
      float: left;
    }
    .col-sm-12 {
      width: 100%;
    }
    .col-sm-11 {
      width: 91.66666666666666%;
    }
    .col-sm-10 {
      width: 83.33333333333334%;
    }
    .col-sm-9 {
      width: 75%;
    }
    .col-sm-8 {
      width: 66.66666666666666%;
    }
    .col-sm-7 {
      width: 58.333333333333336%;
    }
    .col-sm-6 {
      width: 50%;
    }
    .col-sm-5 {
      width: 41.66666666666667%;
    }
    .col-sm-4 {
      width: 33.33333333333333%;
     }
     .col-sm-3 {
       width: 25%;
     }
     .col-sm-2 {
       width: 16.666666666666664%;
     }
     .col-sm-1 {
      width: 8.333333333333332%;
     }
     img {
      border: 1px solid #ddd; /* Gray border */
      border-radius: 4px;  /* Rounded border */
      padding: 10px; /* Some padding */
      width: 200px; /* Set a small width */
      margin-top: 10px;
    }
    .responsive {
      width: 100%;
      height: auto;
    }
}
</style>
</head>

<body>
<div class="container-fluid mt--6">
    <!-- Card stats -->
    <div class="card">
        <div class="card-header col-sm-12 col-xs-12">
            <div class="row align-items-center">
                <div class="col-sm-12 col-xs-12"> <h5 class="h3 mb-0">{{$selfdiagnosis->main_title}}</h5>
                </div>
            </div>
        </div>
        <div class="p-4">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="tuto-main-image noprint">
                        @if($selfdiagnosis->main_image)
                            <img class="img-fluid" height="200px" width="500px" src="{{asset($selfdiagnosis->main_image)}}">
                        @else
                            <img class="img-fluid" height="200px" width="500px" src="{{asset('assets/img/theme/no-image-available.png')}}">
                        @endif
                    </div>
                </div>
                <div class=" col-sm-3  col-xs-12">
                    <div class="tuto-details-box">
                        <p class="mt-0">{{$selfdiagnosis->description}}</p>
                        <div class="tuto-items-container">
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="ni ni-app"></i></span>
                                <span class="alert-text">Type</span>
                                <span class="alert-text-right"><strong>{{$selfdiagnosis->type}}</strong></span>
                            </div>
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-tachometer-alt"></i></span>
                                <span class="alert-text">Difficulty</span>
                                <span class="alert-text-right"><strong>{{$selfdiagnosis->difficulty}}</strong></span>
                            </div>
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-clock"></i></span>
                                <span class="alert-text">Duration</span>
                                <span class="alert-text-right"><strong>{{$selfdiagnosis->duration}}&nbsp;{{$selfdiagnosis->duration_type}}</strong></span>
                            </div>
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-tag"></i></span>
                                <span class="alert-text">Categories</span>
                                @php
                                    $category_id = $selfdiagnosis->guide_category->pluck('category_id')->toArray();
                                    $category_name = App\Category::whereIn('id',$category_id)->pluck('name')->toArray();
                                @endphp
                                <span class="alert-text-right"><strong>{{implode(', ',$category_name)}}</strong></span>
                            </div>
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-money-bill-alt"></i></span>
                                <span class="alert-text">Cost</span>
                                <span class="alert-text-right"><strong><div class="tuto-items-details-container-right">{{$selfdiagnosis->cost}} USD ($)</div></strong></span>
                            </div>
                            <div class="alert alert-secondary fade show" role="alert">
                                <span class="alert-icon"><i class="fas fa-list-ol"></i></span>
                                <span class="alert-text">Contents</span>
                                <span class="alert-text-right">&nbsp;[
                                    <a href="#content-sr" role="button" tabindex="0" class="togglelink">hide</a>]&nbsp;
                                </span>
                                <ul id="content-sr">
                                    @if($selfdiagnosis->guide_step)
                                    @php $step = 1; @endphp
                                    @php $srno = 2; @endphp
                                        <li class="toclevel-1">
                                            <a >
                                                <span class="tocnumber">1</span>
                                                <span class="toctext">Introduction</span>
                                            </a>
                                        </li>
                                        @foreach($selfdiagnosis->guide_step as $stepdata)
                                            <li class="toclevel-1">
                                                <a>
                                                    <span class="tocnumber">{{$srno}}</span> 
                                                    <span class="toctext">Step {{$step}} - {{$stepdata->title}}</span>
                                                </a>
                                            </li>
                                            @php $step++; @endphp
                                            @php $srno++; @endphp
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="Introduction" class="mt-4 col-sm-12  col-xs-12">
                @if($selfdiagnosis->introduction)
                    <h2 class="display-3 mb-0 col-sm-6">Introduction</h2>
                    <p>{{$selfdiagnosis->introduction}}</p>
                @endif
            </div>

            @if($selfdiagnosis->guide_step)
            @php $step = 1; @endphp
                @foreach($selfdiagnosis->guide_step as $stepkey => $stepdata)
                        <div class="row">
                            <div class="col-sm-12  col-xs-12step-instructions">
                                <h3 class="display-4 col-sm-12 col-xs-12">Step {{$step}} - {{$stepdata->title}}</h3>
                                <div class="col-sm-12 mb-5 col-xs-12">
                                    {{$stepdata->description}}
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="tuto-main-image noprint">
                                    @if($stepdata->guide_step_media)
                                    @php $dataslide = 0; @endphp
                                        @foreach($stepdata->guide_step_media as $media)
                                            <img class="img-fluid" src="{{asset($media->media)}}"  height="100px" width="150px">
                                        @php $dataslide++; @endphp
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                @php $step++; @endphp
                @endforeach
            @endif
        </div>
    </div>
</div>
</body>

</html>
