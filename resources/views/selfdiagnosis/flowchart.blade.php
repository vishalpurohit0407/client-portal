@extends('layouts.app')
@section('pagewise_css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/svg.js/1.0.1/svg.min.js"></script>
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
                            @if($guide->guide_type == 'self-diagnosis')
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.selfdiagnosis.show',$guide->id)}}">Self Diagnosis Details</a></li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page"><a href="{{route('user.maintenance.show',$guide->id)}}">Maintenance Guide Details</a></li>
                            @endif
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
            <h5 class="h3 mb-0">Card title</h5>
        </div>
        <div class="card-body">
          <p class="card-text mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis non dolore est fuga nobis ipsum illum eligendi nemo iure repellat, soluta, optio minus ut reiciendis voluptates enim impedit veritatis officiis.</p>
          <div id="drawing" style="margin:30px auto; width:900px;"></div>
        </div>
    </div>
</div>    
@endsection

@section('pagewise_js')
<script src="{{asset('assets/vendor/flowsvg/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('assets/vendor/flowsvg/svg.min.js')}}"></script>
<script src="{{asset('assets/vendor/flowsvg/flowsvg.min.js')}}"></script>
<script>
    var shapesArr = new Array();
    @php
        if($flowchart){
            if($flowchart->flowchart_node){
                foreach ($flowchart->flowchart_node as $node){
                    if ($node->type == 'decision') {
                        $yes_decision = \App\Flowchartnode::where('id',$node->yes)->first();
                        $no_decision = \App\Flowchartnode::where('id',$node->no)->first();
    @endphp
                        var yes_lable = '{{$yes_decision ? $yes_decision->label : ''}}';
                        var no_lable = '{{$no_decision ? $no_decision->label : ''}}';
                        var orientArr = {
                                yes:'{{$node->orient_yes}}',
                                no:'{{$node->orient_no}}',
                            }
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : ['{{$node->text}}'],
                            yes : yes_lable,
                            no : no_lable,
                            orient : orientArr,
                        });
    @php            
                    }else if($node->type == 'process') {
                    $next_process = \App\Flowchartnode::where('id',$node->next)->first();
    @endphp
                        var next_lable = '{{$next_process ? $next_process->label : ''}}';
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : ['{{$node->text}}'],
                            links: [
                            {
                                text : '{{$node->link_text}}',
                                url : '{{$node->link_url}}',
                                target : '{{$node->link_target}}',
                            }],
                            tip: {
                                title: '{{$node->tips_title}}',
                                text:
                                [
                                    '{{$node->tips_text}}',
                                ]
                            },
                            next :  next_lable,
                        });
    @php

                    }else if($node->type == 'finish') {
    @endphp
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : ['{{$node->text}}'],
                            links: [
                            {
                                text : '{{$node->link_text}}',
                                url : '{{$node->link_url}}',
                                target : '{{$node->link_target}}',
                            }],
                            tip: {
                                title: '{{$node->tips_title}}',
                                text:
                                [
                                    '{{$node->tips_text}}',
                                ]
                            },
                        });

    @php
                    }
                } 
            }
        }
    @endphp
///////////////////// start flow chart ////////////////////////////////////////////////////////////
    flowSVG.draw(SVG('drawing').size(900, 1500));
    flowSVG.config({
        interactive: true,
        showButtons: true,
        connectorLength: 60,
        scrollto: true,
        defaultFontSize:'14',
    });
    flowSVG.shapes(shapesArr);
</script>
@endsection
