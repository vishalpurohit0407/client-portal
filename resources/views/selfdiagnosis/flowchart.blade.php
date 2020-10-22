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
                <div class="col-lg-4 col-2 text-right">
                    <a href="{{ URL::previous() }}" class="btn btn-sm btn-neutral">Back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt--6">
    <!-- Card stats -->
    <div class="card">
        <div class="card-header">
            <h5 class="h3 mb-0">{{$flowchart->title}}</h5>
        </div>
        <div class="card-body">
          <p class="card-text mb-4">{{$flowchart->description}}</p>
          <div class="drawing_wrapper">
            <div id="drawing" style="margin:30px auto; width:900px;"></div>
          </div>
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

                        $wordwrapDecision = explode("<br>",wordwrap($node->text,20,"<br>"));
    @endphp
                        var yes_lable = '{{$yes_decision ? $yes_decision->label : ''}}';
                        var no_lable = '{{$no_decision ? $no_decision->label : ''}}';
                        var decisionTextArr = <?php echo json_encode($wordwrapDecision); ?>;
                        var orientArr = {
                                yes:'{{$node->orient_yes}}',
                                no:'{{$node->orient_no}}',
                            }
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : decisionTextArr,
                            yes : yes_lable,
                            no : no_lable,
                            orient : orientArr,
                        });
    @php            
                    }else if($node->type == 'process') {
                    $next_process = \App\Flowchartnode::where('id',$node->next)->first();
                    $wordwrapProcess = explode("<br>",wordwrap($node->text,25,"<br>"));
                    $wordwrapProcessTip = explode("<br>",wordwrap($node->tips_text,25,"<br>"));
    @endphp
                        var processTextArr = <?php echo json_encode($wordwrapProcess); ?>;
                        var processTextTipsArr = <?php echo json_encode($wordwrapProcessTip); ?>;
                        var next_lable = '{{$next_process ? $next_process->label : ''}}';
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : processTextArr,
                            links: [
                            {
                                text : '{{$node->link_text}}',
                                url : '{{$node->link_url}}',
                                target : '{{$node->link_target}}',
                            }],
                            tip: {
                                title: '{{$node->tips_title}}',
                                text: processTextTipsArr
                            },
                            next :  next_lable,
                        });
    @php

                    }else if($node->type == 'finish') {

                        $wordwrapData = explode("<br>",wordwrap($node->text,25,"<br>"));
                        $wordwrapTipData = explode("<br>",wordwrap($node->tips_text,25,"<br>"));

    @endphp
                        var textArr = <?php echo json_encode($wordwrapData); ?>;
                        var tipsTextArr = <?php echo json_encode($wordwrapTipData); ?>;
                        shapesArr.push({
                            label: '{{$node->label}}', 
                            type: '{{$node->type}}', 
                            text : textArr,
                            links: [
                            {
                                text : '{{$node->link_text}}',
                                url : '{{$node->link_url}}',
                                target : '{{$node->link_target}}',
                            }],
                            tip: {
                                title: '{{$node->tips_title}}',
                                text: tipsTextArr,
                            },
                        });

    @php
                    }
                } 
            }
        }
    @endphp
///////////////////// start flow chart ////////////////////////////////////////////////////////////
    flowSVG.draw(SVG('drawing').size(900, 200));
    flowSVG.config({
        interactive: true,
        showButtons: true,
        connectorLength: 60,
        scrollto: true,
        defaultFontSize:'12',
    });
    flowSVG.shapes(shapesArr);

    $(document).ready(function () {

        var svg_height=20;
        $("#drawing svg > g").each(function() {console.log($(this));
            svg_height+=$(this).get(0).getBBox().height;
        })
        $("#drawing svg").attr('height',svg_height);
    });
</script>
@endsection
