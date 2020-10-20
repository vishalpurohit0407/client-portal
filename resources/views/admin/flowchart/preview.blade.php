<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')

@section('pagewise_css')
<link href="{{asset('assets/vendor/flowsvg/jquerysctipttop.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('content')
    <div class="header bg-primary pb-6">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">{{$title}}</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{route('admin.flowchart.list')}}">Flowchart List</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
        <div class="row card-wrapper">
            <div class="col">
                <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col-8">
                          <h5 class="h3 mb-0">{{$title}}</h5>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                        <div class="row row-example">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Title: </label>
                                    <span>{{$flowchart->title}}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row row-example">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="name">Description: </label>
                                    <span>{{$flowchart->description}}</span>
                                </div>
                            </div>
                        </div>
                        <div id="drawing" style="margin:30px auto; width:900px;"></div>
                    </div>
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

function changeNodeType(ele){
    if(ele.value == 'decision'){

        $('.dicision_section').show();
        $('.change_orient_block').show();

        $('.process_section').hide();
        $('.add_link_block').hide();
        $('.add_tip_block').hide();

    }else if(ele.value == 'process'){
        $('.dicision_section').hide();
        $('.change_orient_block').hide();

        $('.process_section').show();
        $('.add_link_block').show();
        $('.add_tip_block').show();

    }else{
        $('.dicision_section').hide();
        $('.process_section').hide();
        $('.change_orient_block').hide();
        $('.add_link_block').show();
        $('.add_tip_block').show();
    } 
}

function showSection(ele){

    var ids = $(ele).attr('id');

    if($("#"+ids).prop('checked')){
        $("."+ids+"_section").show();
    }else{
        $("."+ids+"_section").hide();
    }
}

function deleteConfirm(event){
  var id = $(event).attr('id');
  console.log(id);
  swal({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      type: "warning",
      showCancelButton: !0,
      buttonsStyling: !1,
      confirmButtonClass: "btn btn-danger",
      confirmButtonText: "Yes, delete it!",
      cancelButtonClass: "btn btn-secondary"
  }).then((result) => {
    if (result.value) {
      $("#frm_"+id).submit();
    }
  });
}
</script>
@endsection