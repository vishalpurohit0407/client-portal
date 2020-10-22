@extends('layouts.app')
@section('pagewise_css')
<style type="text/css">

    #printable { display: none; }

    @media print
    {
        #non-printable { display: none; }
        #printable { display: block; }
    }
    .ckeditimg img{
        float: none !important;
    }
</style>
@endsection
@section('content')
<div class="header bg-primary pb-6">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-8 col-10">
                    <h6 class="h2 text-white d-inline-block mb-0">{{$title}}</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.selfdiagnosis.list')}}">Self Diagnosis</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                        </ol>
                    </nav>
                </div>
                <div class="col-lg-3 col-2 text-right">
                    
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    @foreach (['danger', 'warning', 'success', 'info'] as $msg)
        @if(Session::has('alert-' . $msg))
             <div class="alert alert-custom alert-{{ $msg }} alert-dismissible alert-dismissible fade show mb-2" role="alert">                           
                <div class="alert-text">{{ Session::get('alert-' . $msg) }}</div>
                <div class="alert-close">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        @endif 
    @endforeach
    <!-- Card stats -->
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col-8">
                    <h5 class="h3 mb-0">{{$selfdiagnosis->main_title}}</h5>
                </div>

                <div class="col-4 text-right">
                	<a href="{{route('selfdiagnosis.pdf.export',$selfdiagnosis->id)}}" class="btn btn-default btn-sm">Export PDF</a>
                	@php
                        $guide_flowchart = \App\GuideFlowchart::where('guide_id',$selfdiagnosis->id)->first();
                    @endphp
                    @if($guide_flowchart)
                        <a class="btn btn-primary btn-sm" href="{{route('user.flowchart',[$guide_flowchart->flowchart_id,$selfdiagnosis->id])}}">View Flowchart</a>
                    @endif
                    <!-- <button class="btn btn-sm btn-neutral" onclick="printDiv('printableArea')">Print</button> -->
                </div>
            </div>
        </div>
        <div class="p-4 " id="printableArea">
            <div class="row">
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <div class="tuto-main-image noprint">
                        <a class="image" href="" id="lightgallery" data-image="{{asset($selfdiagnosis->main_image_url)}}" data-maintitle="{{$selfdiagnosis->main_title}}" >
                            <img class="img-fluid" style="filter: blur(0px);" src="{{asset($selfdiagnosis->main_image_url)}}">
                        </a>
                    </div>
                </div>
                <div class="col-md-7 col-sm-7 col-xs-12">
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
                                            <a href="#Introduction">
                                                <span class="tocnumber">1</span>
                                                <span class="toctext">Introduction</span>
                                            </a>
                                        </li>
                                        @foreach($selfdiagnosis->guide_step as $stepdata)
                                            <li class="toclevel-1">
                                                <a href="#Step_{{$step}}_-_{{\Str::slug($stepdata->title, '_')}}">
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
            <hr>
            @if($selfdiagnosis->introduction)
                <div id="Introduction" class="mt-4 ckeditimg" s>
                    <h2 class="display-3 mb-0">Introduction</h2>
                    <p>{!!$selfdiagnosis->introduction!!}</p>
                    <hr> 
                </div>   
            @endif
            @if($selfdiagnosis->introduction_video_link)
                <div id="Introduction" class="mt-4">
                    <h2 class="display-3 mb-0">Video overview</h2>
                    <div class="embed-responsive embed-responsive-16by9" id="non-printable">
                        <iframe class="embed-responsive-item" class="text-center" src="{{$selfdiagnosis->introduction_video_link}}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                    <hr>
                </div>
            @endif
            <br>
            @if($selfdiagnosis->guide_step)
            @php $step = 1; @endphp
                @foreach($selfdiagnosis->guide_step as $stepkey => $stepdata)
                    <div id="Step_{{$step}}_-_{{\Str::slug($stepdata->title, '_')}}" class="mt-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-6">
                                <div id="carousel-step{{$step}}" class="carousel slide carousel-fade carousel-thumbnails carousel-thumbnails-bottom" data-ride="carousel" data-interval="false">
                                      <!--Slides-->
                                      <div class="carousel-inner lightgallery" style="cursor: pointer;" data-id="{{$stepdata->id}}" title="Show Image">
                                        @if($stepdata->media)
                                            @foreach($stepdata->media as $media)
                                                <div class="carousel-item @if($loop->first) active @endif">
                                                  <img class="d-block" src="{{asset($media->media_url)}}" alt="First slide">
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($stepdata->video_media)
                                            <div class="carousel-item @if(count($stepdata->media) == 0) active @endif step-item-video">
                                                <iframe class="embed-responsive-item" class="text-center" src="{{$stepdata->video_media}}" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                            </div>
                                        @endif
                                      </div>
                                      <!--/.Slides-->
                                      <!--/.Controls-->
                                      <ol class="carousel-indicators">
                                        @if($stepdata->media)
                                        @php $dataslide = 0; @endphp
                                            @foreach($stepdata->media as $media)
                                                <li data-target="#carousel-step{{$step}}" onmouseover="bigImg(this,'image')" data-slide-to="{{$dataslide}}" @if($loop->first) class="active" @endif > 
                                                    <img class="d-block" src="{{asset($media->media_url)}}" class="img-fluid">
                                                </li>
                                            @php $dataslide++; @endphp
                                            @endforeach
                                        @endif

                                        @if($stepdata->video_media)
                                            <li data-target="#carousel-step{{$step}}" onmouseover="bigImg(this,'video')" data-slide-to="{{$dataslide}}" class="step-video-li"> 
                                                <img class="d-block img-fluid step-video-icon" src="{{asset('assets/img/icons/'.$stepdata->video_type.'.png')}}" >
                                            </li>
                                        @endif
                                      </ol>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-{{(count($stepdata->media) > 0 || $stepdata->video_media != '') ? '6' : '12'}} step-instructions">
                                <h3 class="display-4">Step {{$step}} - {{$stepdata->title}}</h3>
                                <div>
                                    {!!$stepdata->description!!}
                                </div>
                            </div>
                        </div>
                    </div>
                   @if($stepdata != $loop->last) <hr> @endif
                @php $step++; @endphp
                @endforeach
            @endif
        </div>
    </div>
    <div class="card" style="background-color: #edf6ff;
    border: 1px solid #bddcff;">
        <!-- Card body -->
        <div class="card-body">
            <div class="text-center">
                <h5 class="h1 title">
                  <span class="d-block mb-1 text-muted"> <i class="fa fa-flag-checkered text-info"></i>&nbsp;FINISH LINE</span>
                </h5>
                <div class="mt-5 mb-4">
                    @php 
                        $completed_guide = \App\GuideCompletion::where('guide_id',$selfdiagnosis->id)->where('user_id',\Auth::user()->id)->first();
                        $completed_guide_count = \App\GuideCompletion::where('guide_id',$selfdiagnosis->id)->count();

                    @endphp
                    @if($completed_guide)
                        <a class="btn btn-icon btn-primary text-white">
                            <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                            <span class="btn-inner--text">Already Completed</span>
                        </a>
                    @else
                        <a href="{{route('user.complete.guide',$selfdiagnosis->id)}}" class="btn btn-icon btn-primary">
                            <span class="btn-inner--icon"><i class="fa fa-check"></i></span>
                            <span class="btn-inner--text">Complete Guide</span>
                        </a>
                    @endif
                </div>
                <small class="h4 font-weight-light text-primary">{{$completed_guide_count}} other people completed this guide.</small>
            </div>
        </div>
    </div>
</div>    
@endsection

@section('pagewise_js')
<script type="text/javascript"> 
var elementArr = new Array();
    @php
        if($selfdiagnosis->guide_step){
            foreach($selfdiagnosis->guide_step as $stepdata){
                if($stepdata->media){
    @endphp
                    var mediaArr = new Array();
    @php
                    foreach ($stepdata->media as $media){
    @endphp            
                        mediaArr.push({src: '{{asset($media->media_url)}}', thumb: '{{asset($media->media_url)}}', subHtml : '{{$stepdata->title}}'});
    @php   
                    } 
                }
    @endphp
                elementArr['{{$stepdata->id}}'] = mediaArr;
    @php        
            }
        }
    @endphp
jQuery(document).ready(function($){
    $('.lightgallery').on('click', function(e) {
        e.preventDefault();
        var ids = $(this).data('id');

        $(this).lightGallery({
            dynamic: true,
            dynamicEl: elementArr[ids],
            download:false,
            fullScreen:false,
            zoom:false,
            share:false,
            autoplay:false,
            autoplayControls:false,
        })
    }); 

    $('#lightgallery').on('click', function(e) {
        e.preventDefault();
        var url = $(this).data('image');
        var title = $(this).data('maintitle');

        $(this).lightGallery({
            dynamic: true,
            dynamicEl: [{src: url, thumb: url, subHtml : title}],
            download:false,
            fullScreen:false,
            zoom:false,
            share:false,
            autoplay:false,
            autoplayControls:false,
        })
    }); 

    $(".togglelink").click(function(e){
        e.preventDefault();
        $($(this).attr('href')).slideToggle();
        if ($(this).text() == "hide")
           $(this).text("show")
        else
           $(this).text("hide");
    });

    $("#content-sr li a[href^='#']").on('click', function(e) {
       e.preventDefault();
       var hash = this.hash;
       // animate
       return $('html, body').animate({
           scrollTop: $(hash).offset().top-20
         }, 1000, function(){
           // /window.location.hash = hash;
           return window.history.pushState(null, null, hash)
         });

    });
});

function bigImg(x) {
    x.click();

}

function printDiv(divName) {
    var printContents = document.getElementById(divName).innerHTML;
    var originalContents = document.body.innerHTML;

    document.body.innerHTML = printContents;

    window.print();

    document.body.innerHTML = originalContents;
}
</script>
@endsection
