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
        {{ Session::get('alert-success') }}
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <div class="alert alert-custom alert-{{ $msg }} alert-dismissible fade show mb-2" role="alert">                           
                    <div class="alert-text">{{ Session::get('alert-' . $msg) }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                </div>
            @endif 
        @endforeach

        <div class="row card-wrapper">
            <div class="col">

                <div class="card">
                    <div class="card-header">
                      <div class="row align-items-center">
                        <div class="col-8">
                          <h5 class="h3 mb-0">{{ucfirst($flowchart->title)}}</h5>
                        </div>
                      </div>
                    </div>

                    <div class="table-responsive py-4">

                        <form class="form" action="{{ route('admin.flowchart.update',$flowchart->id) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }}    
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3 class="mb-0">Add New Node</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group @if($errors->has('lable')) has-danger @endif ">
                                                    <label class="form-control-label" for="lable">Label <a href="javascript:void(0);" title="Label Instructions" data-toggle="popover" data-placement="top" data-content="Add unique label per flowchart"><i class="fas fa-question-circle" style="font-size: 16px;"></i></a></label>
                                                    <input type="text" class="form-control @if($errors->has('lable')) is-invalid @endif" id="lable" name="lable" placeholder="Lable" value="{{old('lable')}}">
                                                    @if($errors->has('lable'))
                                                        <span class="form-text text-danger">{{ $errors->first('lable') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="type">Type</label>
                                                    <select class="form-control" id="type" name="type" onchange="changeNodeType(this);">
                                                        <option value="decision" @if(old('type') == 'decision') selected="" @endif>Decision</option>
                                                        <option value="finish" @if(old('type') == 'finish') selected="" @endif>Finish</option>
                                                        <option value="process" @if(old('type') == 'process') selected="" @endif>Process</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-sm-4 col-md-4">
                                                <div class="form-group @if($errors->has('text')) has-danger @endif">
                                                    <label class="form-control-label" for="text">Text</label>
                                                    <input type="text" class="form-control @if($errors->has('text')) is-invalid @endif" id="text" name="text" placeholder="Descriptive text for node" value="{{old('text')}}">
                                                    @if($errors->has('text'))
                                                        <span class="form-text text-danger">{{ $errors->first('text') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center">
                                            
                                            <div class="col-sm-3 col-md-3 dicision_section" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
                                                <div class="form-group @if($errors->has('dicision_yes')) has-danger @endif">
                                                    <label class="form-control-label" for="dicision_yes">Yes (Choose Child)</label>
                                                    <select class="form-control @if($errors->has('dicision_yes')) is-invalid @endif" id="dicision_yes" name="dicision_yes">
                                                        <option value="">Select Child Node</option>
                                                        @foreach($childNode as $node)
                                                            <option value="{{$node->id}}" @if(old('dicision_yes') == $node->id) selected @endif>{{$node->label}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('dicision_yes'))
                                                        <span class="form-text text-danger">{{ $errors->first('dicision_yes') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-3 col-md-3 dicision_section" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
                                                <div class="form-group @if($errors->has('dicision_no')) has-danger @endif">
                                                    <label class="form-control-label" for="dicision_no">No (Choose Child)</label>
                                                    <select class="form-control @if($errors->has('dicision_no')) is-invalid @endif" id="dicision_no" name="dicision_no">
                                                        <option value="">Select Child Node</option>
                                                        @foreach($childNode as $node)
                                                            <option value="{{$node->id}}" @if(old('dicision_no') == $node->id) selected @endif>{{$node->label}}</option>
                                                        @endforeach
                                                    </select>
                                                     @if($errors->has('dicision_no'))
                                                        <span class="form-text text-danger">{{ $errors->first('dicision_no') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="col-sm-3 col-md-3 process_section" @if(old('type') == 'process') @else style="display: none;" @endif >
                                                <div class="form-group @if($errors->has('process_next')) has-danger @endif">
                                                    <label class="form-control-label" for="process_next">Next (Choose Child)</label>
                                                    <select class="form-control" id="process_next" name="process_next">
                                                        <option value="">Select Child Node</option>
                                                        @foreach($childNode as $node)
                                                            <option value="{{$node->id}}" @if(old('process_next') == $node->id) selected @endif>{{$node->label}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('process_next'))
                                                        <span class="form-text text-danger">{{ $errors->first('process_next') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 add_link_block" @if(old('type') && old('type') != 'decision') @else style="display: none;" @endif >
                                                <div class="form-group">
                                                    <label class="form-control-label" for="add_link">Add Link?</label><br>
                                                    <label class="custom-toggle  custom-toggle-success">
                                                        <input type="checkbox" name="add_link" id="add_link" onchange="showSection(this)" @if(old('add_link')) checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 add_tip_block" @if(old('type') && old('type') != 'decision') @else style="display: none;" @endif >
                                                <div class="form-group">
                                                    <label class="form-control-label" for="example4cols2Input">Add Tip?</label><br>
                                                    <label class="custom-toggle  custom-toggle-primary">
                                                        <input type="checkbox" name="add_tip" id="add_tip" onchange="showSection(this)" @if(old('add_tip')) checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 change_orient_block" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="example4cols2Input">Change Orient?</label><br>
                                                    <label class="custom-toggle custom-toggle-warning">
                                                        <input type="checkbox" name="change_orient" id="change_orient" onchange="showSection(this)" @if(old('change_orient')) checked="" @endif>
                                                        <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="add_link_section" @if(old('add_link')) @else style="display: none;" @endif>
                                            <div class="custom-hr"></div>
                                            <p><h3 class="mb-0">Link Section</h3></p>
                                            <div class="row align-items-center">
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group @if($errors->has('link_text')) has-danger @endif">
                                                        <label class="form-control-label" for="link_text">Text</label>
                                                        <input type="text" class="form-control @if($errors->has('link_text')) is-invalid @endif" name="link_text" id="link_text" placeholder="Link Text" value="{{old('link_text')}}">
                                                        @if($errors->has('link_text'))
                                                            <span class="form-text text-danger">{{ $errors->first('link_text') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group @if($errors->has('link_url')) has-danger @endif">
                                                        <label class="form-control-label" for="link_url">URL</label>
                                                        <input type="text" class="form-control @if($errors->has('link_url')) is-invalid @endif" id="link_url" name="link_url" placeholder="Link URL" value="{{old('link_url')}}">
                                                        @if($errors->has('link_url'))
                                                            <span class="form-text text-danger">{{ $errors->first('link_url') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="link_target">Target</label><br>
                                                        <select class="form-control" name="link_target">
                                                            <option value="_blank" @if(old('link_target') == '_blank') selected @endif>Blank</option>
                                                            <option value="_self" @if(old('link_target') == '_self') selected @endif>Self</option>
                                                            <option value="_parent" @if(old('link_target') == '_parent') selected @endif>Parent</option>
                                                            <option value="_top" @if(old('link_target') == '_top') selected @endif>Top</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="add_tip_section" @if(old('add_tip')) @else style="display: none;" @endif >
                                            <div class="custom-hr"></div>
                                            <p><h3 class="mb-0">Tip Section</h3></p>
                                            <div class="row align-items-center">
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group @if($errors->has('tip_title')) has-danger @endif">
                                                        <label class="form-control-label" for="example4cols2Input">Title</label>
                                                        <input type="text" class="form-control @if($errors->has('tip_title')) is-invalid @endif" id="tip_title" name="tip_title" placeholder="Tip Title" value="{{old('tip_title')}}">
                                                        @if($errors->has('tip_title'))
                                                            <span class="form-text text-danger">{{$errors->first('tip_title')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group @if($errors->has('tip_text')) has-danger @endif">
                                                        <label class="form-control-label" for="tip_text">Text</label>
                                                        <input type="text" class="form-control @if($errors->has('tip_text')) is-invalid @endif" id="tip_text" name="tip_text" placeholder="Tip Text" value="{{old('tip_text')}}">
                                                        @if($errors->has('tip_text'))
                                                            <span class="form-text text-danger">{{$errors->first('tip_text')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="change_orient_section" @if(old('change_orient')) @else style="display: none;" @endif >
                                            <div class="custom-hr"></div>
                                            <p><h3 class="mb-0">Orient Section</h3></p>
                                            <div class="row align-items-center">
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="orient_yes">Yes</label>
                                                        <select class="form-control" name="orient_yes">
                                                            <option value="l" @if(old('orient_yes') == 'l') selected @endif>Left</option>
                                                            <option value="r" @if(old('orient_yes') == 'r') selected @endif >Right</option>
                                                            <option value="b" @if(old('orient_yes') == 'b') selected @endif>Bottom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3 col-md-3">
                                                    <div class="form-group">
                                                        <label class="form-control-label" for="orient_no">No</label>
                                                        <select class="form-control" name="orient_no">
                                                            
                                                            <option value="r" @if(old('orient_no') == 'r') selected @endif >Right</option>
                                                            <option value="l" @if(old('orient_no') == 'l') selected @endif>Left</option>
                                                            <option value="b" @if(old('orient_no') == 'b') selected @endif>Bottom</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="hr-dotted">
                                        <input type="submit" class="btn btn-info" name="submit" value="Add Note">
                                        <input type="reset" class="btn btn-success" name="submit" value="Clear">
                                        <input type="submit" class="btn btn-success" name="submit" value="Preview">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>

                        <div class="col-lg-12">
                            <div class="card">
                              <!-- Card header -->
                              <div class="card-header border-0">
                                <div class="row">
                                  <div class="col-6">
                                    <h3 class="mb-0">Node Listing</h3>
                                  </div>
                                  
                                </div>
                              </div>
                              <!-- Light table -->
                              <div class="table-responsive">
                                <table class="table align-items-center table-flush">
                                  <thead class="thead-light">
                                    <tr>
                                      <th>Lable</th>
                                      <th>Type</th>
                                      <th>Text</th>
                                      <th>Created at</th>
                                      <th></th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    <tr>
                                      <td class="table-user">
                                        
                                        <b>John Michael</b>
                                      </td>
                                      <td>
                                        <span class="text-muted">10/09/2018</span>
                                      </td>
                                      <td>
                                        <a href="#!" class="font-weight-bold">Argon Dashboard PRO</a>
                                      </td>
                                      <td class="table-actions">
                                        <a href="#!" class="table-action" data-toggle="tooltip" data-original-title="Edit product">
                                          <i class="fas fa-user-edit"></i>
                                        </a>
                                        <a href="#!" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete product">
                                          <i class="fas fa-trash"></i>
                                        </a>
                                      </td>
                                    </tr>
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                        </div>

                        <hr>
                        <div id="drawing" style="margin:30px auto; width:900px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<style type="text/css">
  .custom-toggle{
    width: 53px;
  }
  .custom-toggle-success input:checked + .custom-toggle-slider:before{
    left:0px !important;
  }
  .custom-toggle input:checked + .custom-toggle-slider:after{
     left:0px !important;
  }
</style>
@endsection
@section('pagewise_js')
<script src="{{asset('assets/vendor/flowsvg/jquery.scrollTo.min.js')}}"></script>
<script src="{{asset('assets/vendor/flowsvg/svg.min.js')}}"></script>
<script src="{{asset('assets/vendor/flowsvg/flowsvg.min.js')}}"></script>
<script>
///////////////////// start flow chart ////////////////////////////////////////////////////////////
    flowSVG.draw(SVG('drawing').size(900, 1100));
    flowSVG.config({
        interactive: false,
        showButtons: false,
        connectorLength: 60,
        scrollto: true
    });
    flowSVG.shapes(
        [
            {
          label: 'knowPolicy',
          type: 'decision',
          text: [
            'Do you know the ',
                    'Open Access policy',
                    'of the journal?'
          ],
          yes: 'hasOAPolicy',
          no: 'checkPolicy'
        }, 
            {
          label: 'hasOAPolicy',
          type: 'decision',
          text: [
            'Does it have Open',
                    'Access paid option or is it an',
                    ' Open Access journal?'
          ],
          yes: 'CCOffered',
          no: 'canWrap'
        }, 
        {
          label: 'CCOffered',
          type: 'decision',
          text: [
                    'Creative Commons licence',
                    'CC-BY offered?'
                ],
                yes: 'canComply',
                no:'checkGreen'
        },
            {
                label: 'canComply',
                type: 'finish',
                text: [
                    'Great - can comply. ',
                    'Please complete'
                ],
                links: [
                    {
                        text: 'application form', 
                        url: 'http://www.jqueryscript.net/chart-graph/Simple-SVG-Flow-Chart-Plugin-with-jQuery-flowSVG.html', 
                      textarget: '_blank'
                    },
                ],
                tip: {
                    title: 'HEFCE Note',
                    text:[
                        'You must put your',
                        'accepted version into',
                        'WRAP and/or subject',
                        'repository within 3 months',
                        'of acceptance.'
                    ]
                }
            },
        {
          label: 'canWrap',
          type: 'decision',
          text: [
                    'Can you archive in ',
                    'WRAP and/or Subject',
                    'repository?'
                ],
                yes: 'checkTimeLimits',
          no: 'doNotComply'
        }, 
            {
                label: 'doNotComply',
                type: 'finish',
                text: [
                    'You do not comply at all. ',
                    'Is this really the only journal',
                    ' you want to use? ',
                    'Choose another or make ',
                    'representations to journal'
                ],
                tip: {
                    title: 'HEFCE Note',
                    text:
                    [
                        'If you really have to go',
                        'this route you must log',
                        'the exception in WRAP on',
                        'acceptance in order',
                        'to comply.'
                    ]
                }
            },       
            {
          label: 'checkGreen',
          type: 'process',
          text: [
                    'Check the journal\'s policy',
                    'on the green route'
                ],
          next: 'journalAllows',
        }, 
            {
                label: 'journalAllows',
                type: 'decision',
                text: ['Does the journal allow this?'],
                yes: 'checkTimeLimits',
                no: 'cannotComply',
                orient: {
                    yes:'r',
                    no: 'b'
                }
            },
            {
          label: 'checkTimeLimits',
          type: 'process',
          text: [
                    'Make sure the time limits',
                    'acceptable',
                    '6 month Stem',
                    '12 month AHSS'
                ],
                next: 'depositInWrap'
            },
            {
                label: 'cannotComply',
                type: 'finish',
                text: [
                    'You cannot comply with',
                    'RCUK policy. Contact ',
                    'journal to discuss or',
                    'choose another'
                ],
                tip: {
                    title: 'HEFCE Note',
                    text:
                    [
                        'Deposit in WRAP if',
                        'time limits acceptable. If',
                        'journal does not allow at all',
                        'an exception record will',
                        'have to be entered',
                        'in WRAP, if you feel this is',
                        'most appropriate journal.'
                    ]
                }
            },
            {
                label: 'depositInWrap',
                type: 'finish',
                text: [
                    'Deposit in WRAP here or ',
                    'contact team'
                ],
                tip: {
                    title: 'HEFCE Note',
                    text:
                    [
                        'You must put your',
                        'accepted version into',
                        'WRAP and/or subject',
                        'repository within 3 months',
                        'of acceptance.',
                        'Note also time limits:',
                        'HEFCE 12 months',
                        'STEM ? months',
                        'AHSS ? months',
                        'So you comply here too.'
                    ]
                }
            },
        {
          label: 'checkPolicy',
          type: 'process',
          text: [
            'Check journal website',
                    'or go to '
          ],
                links: [
                    {
                        text: 'SHERPA FACT/ROMEO ', 
                        url: 'http://www.jqueryscript.net/chart-graph/Simple-SVG-Flow-Chart-Plugin-with-jQuery-flowSVG.html', 
                        target: '_blank'
                    }
                ],
          next: 'hasOAPolicy'
            }
    ]);

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

</script>
@endsection