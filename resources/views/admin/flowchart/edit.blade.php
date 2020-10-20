<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')

@section('pagewise_css')
<link href="{{asset('assets/vendor/flowsvg/jquerysctipttop.css')}}" rel="stylesheet" type="text/css">
<style type="text/css">
    .select2-container--default .select2-selection--multiple .select2-selection__choice{
        color: #fcfdfe !important;
    }
</style>
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

                        <form class="form" id="flowchart_details" action="{{ route('admin.flowchart.update',[$flowchart->id,'flag'=>'flowchart_details']) }}" method="post" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PUT') }} 
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header border-0">
                                        <div class="row">
                                            <div class="col-6">
                                                <h3 class="mb-0">Flowchart Details</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group @if($errors->has('title')) has-danger @endif">
                                                    <label class="form-control-label" for="title">Title</label>
                                                    <input type="text" class="form-control @if($errors->has('title')) is-invalid @endif" id="title" name="title" placeholder="Title" value="{{old('title',$flowchart->title)}}">
                                                    @if($errors->has('title'))
                                                        <span class="form-text text-danger">{{ $errors->first('title') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-12">
                                                <div class="form-group @if($errors->has('description')) has-danger @endif">
                                                    <label class="form-control-label" for="description">Description</label>
                                                    <textarea rows="5" class="form-control @if($errors->has('description')) is-invalid @endif" id="description" name="description" placeholder="Description">{{old('description',$flowchart->description)}}</textarea> 
                                                    @if($errors->has('description'))
                                                        <span class="form-text text-danger">{{ $errors->first('description') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-sm-6 col-md-6">
                                                <div class="form-group">
                                                    <label class="form-control-label" for="guide_id[]">Choose Self Diagnosis for this flowchart</label>
                                                    <select class="form-control" name="guide_id[]" data-toggle="select" multiple data-placeholder="Select Self Diagnosis" id="self_diagnosis">
                                                        @foreach($self_diagnosis as $diagnosis)
                                                            <option value="{{$diagnosis->id}}" {{ $diagnosis->id == in_array($diagnosis->id, $guide_id_array) ? 'selected' : '' }}>{{$diagnosis->main_title}}</option>
                                                        @endforeach
                                                    </select>
                                                    @if($errors->has('guide_id[]'))
                                                        <span class="form-text text-danger">{{ $errors->first('guide_id[]') }}</span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="col-sm-6 col-md-6" >
                                                <div class="form-group">
                                                    <label class="form-control-label" for="maintenance">Choose Maintenance Guide for this flowchart</label>
                                                    <select class="form-control" id="maintenance" name="guide_id[]" data-toggle="select" multiple placeholder="Select Maintenance">
                                                        @foreach($maintenance as $mainten)
                                                            <option class="text-white" value="{{$mainten->id}}" {{ $mainten->id == in_array($mainten->id, $guide_id_array) ? 'selected' : '' }}>{{$mainten->main_title}}</option>
                                                        @endforeach
                                                    </select>
                                                     @if($errors->has('guide_id[]'))
                                                        <span class="form-text text-danger">{{ $errors->first('guide_id[]') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <hr class="hr-dotted">
                                        <input type="submit" form="flowchart_details" class="btn btn-info" name="flowchart_details_submit" value="Publish">
                                        @if($flowchart->status == '0')
                                            <input type="submit" form="flowchart_details" class="btn btn-primary" name="flowchart_details_submit" value="save as a draft">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr>

                        <form class="form" action="{{ route('admin.flowchart.update',[$flowchart->id,'flag'=>'flowchart_addnode']) }}" method="post" enctype="multipart/form-data" id="add_node_frm">

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
                                            
                                            <div class="col-sm-4 col-md-4 dicision_section" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
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

                                            <div class="col-sm-4 col-md-4 dicision_section" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
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
                                            
                                            <div class="col-sm-4 col-md-4 process_section" @if(old('type') == 'process') @else style="display: none;" @endif >
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
                                                    <label class="form-control-label" for="add_link">Add Link?</label>
                                                    <div class="custom-switch-lable">
                                                        <label class="custom-toggle  custom-toggle-success">
                                                            <input type="checkbox" name="add_link" id="add_link" onchange="showSection(this)" @if(old('add_link')) checked="" @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 add_tip_block" @if(old('type') && old('type') != 'decision') @else style="display: none;" @endif >
                                                <div class="form-group">
                                                    <label class="form-control-label" for="example4cols2Input">Add Tip?</label><br>
                                                    <div class="custom-switch-lable">
                                                        <label class="custom-toggle  custom-toggle-primary">
                                                            <input type="checkbox" name="add_tip" id="add_tip" onchange="showSection(this)" @if(old('add_tip')) checked="" @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-2 col-md-2 change_orient_block" @if(old('type') && old('type') != 'decision') style="display: none;" @endif>
                                                <div class="form-group">
                                                    <label class="form-control-label" for="example4cols2Input">Change Orient?</label><br>
                                                    <div class="custom-switch-lable">
                                                        <label class="custom-toggle custom-toggle-warning">
                                                            <input type="checkbox" name="change_orient" id="change_orient" onchange="showSection(this)" @if(old('change_orient')) checked="" @endif>
                                                            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="add_link_section" @if(old('add_link')) @else style="display: none;" @endif>
                                            <div class="custom-hr"></div>
                                            <p><h3 class="mb-0">Link Section</h3></p>
                                            <div class="row align-items-center">
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group @if($errors->has('link_text')) has-danger @endif">
                                                        <label class="form-control-label" for="link_text">Text</label>
                                                        <input type="text" class="form-control @if($errors->has('link_text')) is-invalid @endif" name="link_text" id="link_text" placeholder="Link Text" value="{{old('link_text')}}">
                                                        @if($errors->has('link_text'))
                                                            <span class="form-text text-danger">{{ $errors->first('link_text') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group @if($errors->has('link_url')) has-danger @endif">
                                                        <label class="form-control-label" for="link_url">URL</label>
                                                        <input type="text" class="form-control @if($errors->has('link_url')) is-invalid @endif" id="link_url" name="link_url" placeholder="Link URL" value="{{old('link_url')}}">
                                                        @if($errors->has('link_url'))
                                                            <span class="form-text text-danger">{{ $errors->first('link_url') }}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
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
                                                <div class="col-sm-4 col-md-4">
                                                    <div class="form-group @if($errors->has('tip_title')) has-danger @endif">
                                                        <label class="form-control-label" for="example4cols2Input">Title</label>
                                                        <input type="text" class="form-control @if($errors->has('tip_title')) is-invalid @endif" id="tip_title" name="tip_title" placeholder="Tip Title" value="{{old('tip_title')}}">
                                                        @if($errors->has('tip_title'))
                                                            <span class="form-text text-danger">{{$errors->first('tip_title')}}</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-sm-4 col-md-4">
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
                                        <input type="submit" form="add_node_frm" class="btn btn-info" name="submit" value="Add Node">
                                        <a href="#flowchart_preview" class="btn btn-success">Preview</a>
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
                                    @if(count($childNode) > 0)

                                        @foreach($childNode as $nodeData)
                                            <tr>
                                              <td class="table-user"><b>{{$nodeData->label}}</b></td>
                                              <td><span class="text-muted">{{ucfirst($nodeData->type)}}</span></td>
                                              <td><a href="javascript:void(0);" class="font-weight-bold">{{mb_strimwidth($nodeData->text, 0, 90, "...")}}</a></td>
                                              <td><a href="javascript:void(0);" class="font-weight-bold">{{date('d M, Y',strtotime($nodeData->created_at))}}</a></td>
                                              <td class="table-actions">
                                                <a href="javascript:void(0);" class="table-action" data-toggle="modal" data-target="#node-form-{{$nodeData->id}}" ><i class="fas fa-user-edit"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.flowchart.remove.node',['id' => $nodeData->id]) }}" method="POST" style="display: contents;" id="frm_{{$nodeData->id}}"> 
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <a href="javascript:void(0);" class="table-action table-action-delete" data-toggle="tooltip" data-original-title="Delete Node" onclick="return deleteConfirm(this);" id='{{$nodeData->id}}'>
                                                  <i class="fas fa-trash"></i>
                                                </a>
                                                </form>
                                                <div class="modal fade" id="node-form-{{$nodeData->id}}" tabindex="-2" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
                                                <div class="modal-dialog modal- modal-dialog-centered modal-md" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-body p-0">
                                                            <div class="card bg-secondary border-0 mb-0">
                                                                <div class="card-header bg-transparent">
                                                                    <div class="text-muted text-center">Edit Flowchart</div>
                                                                </div>
                                                                <div class="card-body">
                                                                    <form class="form editNodeForm" action="{{ route('admin.flowchart.node.update',['flowchart_id' => $flowchart->id , 'id' => $nodeData->id]) }}" method="post" enctype="multipart/form-data" id="node_frm_{{$nodeData->id}}">
                                                                        {{ csrf_field() }}
                                                                         
                                                                        <div class="col-lg-12">
                                                                            
                                                                            <div class="row align-items-center">
                                                                                <div class="col-sm-4 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="lable">Label <a href="javascript:void(0);" title="Label Instructions" data-toggle="popover" data-placement="top" data-content="Add unique label per flowchart"><i class="fas fa-question-circle" style="font-size: 16px;"></i></a></label>
                                                                                        <input type="text" class="form-control " id="lable" name="lable" placeholder="Lable" value="{{old('lable',$nodeData->label)}}">
                                                                                        <span class="form-text text-danger" id="lable_error"></span>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="type">Type</label>
                                                                                        <select class="form-control" id="type" name="type" onchange="changeEditNodeType(this,'{{$nodeData->id}}');">
                                                                                            <option value="decision" @if(old('type',$nodeData->type) == 'decision') selected="" @endif>Decision</option>
                                                                                            <option value="finish" @if(old('type',$nodeData->type) == 'finish') selected="" @endif>Finish</option>
                                                                                            <option value="process" @if(old('type',$nodeData->type) == 'process') selected="" @endif>Process</option>
                                                                                        </select>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4 col-md-4">
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="text">Text</label>
                                                                                        <input type="text" class="form-control" id="text" name="text" placeholder="Descriptive text for node" value="{{old('text',$nodeData->text)}}">
                                                                                        <span class="form-text text-danger" id="text_error"></span>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="row align-items-center">
                                                                                
                                                                                <div class="col-sm-4 col-md-4 dicision_section" @if(old('type',$nodeData->type) && old('type',$nodeData->type) != 'decision') style="display: none;" @endif>
                                                                                    <div class="form-group @if($errors->has('dicision_yes')) has-danger @endif">
                                                                                        <label class="form-control-label" for="dicision_yes">Yes (Choose Child)</label>
                                                                                        <select class="form-control @if($errors->has('dicision_yes')) is-invalid @endif" id="dicision_yes" name="dicision_yes">
                                                                                            <option value="">Select Child Node </option>
                                                                                            @foreach($childNode as $node)
                                                                                                <option value="{{$node->id}}" @if(old('dicision_yes', $nodeData->yes) == $node->id) selected @endif>{{$node->label}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @if($errors->has('dicision_yes'))
                                                                                            <span class="form-text text-danger">{{ $errors->first('dicision_yes') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-4 col-md-4 dicision_section" @if(old('type',$nodeData->type) && old('type',$nodeData->type) != 'decision') style="display: none;" @endif>
                                                                                    <div class="form-group @if($errors->has('dicision_no')) has-danger @endif">
                                                                                        <label class="form-control-label" for="dicision_no">No (Choose Child)</label>
                                                                                        <select class="form-control @if($errors->has('dicision_no')) is-invalid @endif" id="dicision_no" name="dicision_no">
                                                                                            <option value="">Select Child Node</option>
                                                                                            @foreach($childNode as $node)
                                                                                                <option value="{{$node->id}}" @if(old('dicision_no',$nodeData->no) == $node->id) selected @endif>{{$node->label}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                         @if($errors->has('dicision_no'))
                                                                                            <span class="form-text text-danger">{{ $errors->first('dicision_no') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="col-sm-4 col-md-4 process_section" @if(old('type',$nodeData->type) == 'process') @else style="display: none;" @endif >
                                                                                    <div class="form-group @if($errors->has('process_next')) has-danger @endif">
                                                                                        <label class="form-control-label" for="process_next">Next (Choose Child)</label>
                                                                                        <select class="form-control" id="process_next" name="process_next">
                                                                                            <option value="">Select Child Node</option>
                                                                                            @foreach($childNode as $node)
                                                                                                <option value="{{$node->id}}" @if(old('process_next',$nodeData->next) == $node->id) selected @endif>{{$node->label}}</option>
                                                                                            @endforeach
                                                                                        </select>
                                                                                        @if($errors->has('process_next'))
                                                                                            <span class="form-text text-danger">{{ $errors->first('process_next') }}</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-2 col-md-2 add_link_block" @if(old('type',$nodeData->type) && old('type',$nodeData->type) != 'decision') @else style="display: none;" @endif >
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="add_link">Add Link?</label><br>
                                                                                        <div class="custom-switch-lable">
                                                                                            <label class="custom-toggle  custom-toggle-success">
                                                                                                <input type="checkbox" name="add_link" id="add_link" onchange="showEditSection(this,'{{$nodeData->id}}')" @if(old('add_link',$nodeData->link_text)) checked="" @endif>
                                                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-2 col-md-2 add_tip_block" @if(old('type',$nodeData->type) && old('type',$nodeData->type) != 'decision') @else style="display: none;" @endif >
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="example4cols2Input">Add Tip?</label><br>
                                                                                        <div class="custom-switch-lable">
                                                                                            <label class="custom-toggle  custom-toggle-primary">
                                                                                                <input type="checkbox" name="add_tip" id="add_tip" onchange="showEditSection(this,'{{$nodeData->id}}')" @if(old('add_tip',$nodeData->tips_title)) checked="" @endif>
                                                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                                <div class="col-sm-2 col-md-2 change_orient_block" @if(old('type',$nodeData->type) && old('type',$nodeData->type) != 'decision') style="display: none;" @endif>
                                                                                    <div class="form-group">
                                                                                        <label class="form-control-label" for="example4cols2Input">Change Orient?</label><br>
                                                                                        <div class="custom-switch-lable">
                                                                                            <label class="custom-toggle custom-toggle-warning">
                                                                                                <input type="checkbox" name="change_orient" id="change_orient" onchange="showEditSection(this,'{{$nodeData->id}}')" @if(old('change_orient',$nodeData->orient_yes)) checked="" @endif>
                                                                                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                                                                                            </label>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="add_link_section" @if(old('add_link',$nodeData->link_text)) @else style="display: none;" @endif>
                                                                                <div class="custom-hr"></div>
                                                                                <p><h3 class="mb-0">Link Section</h3></p>
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-sm-4 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="link_text">Text</label>
                                                                                            <input type="text" class="form-control" name="link_text" id="link_text" placeholder="Link Text" value="{{old('link_text',$nodeData->link_text)}}">
                                                                                            <span class="form-text text-danger" id="link_text_error"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="link_url">URL</label>
                                                                                            <input type="text" class="form-control" id="link_url" name="link_url" placeholder="Link URL" value="{{old('link_url',$nodeData->link_url)}}">
                                                                                            <span class="form-text text-danger" id="link_url_error"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="link_target">Target</label><br>
                                                                                            <select class="form-control" name="link_target">
                                                                                                <option value="_blank" @if(old('link_target',$nodeData->link_target) == '_blank') selected @endif>Blank</option>
                                                                                                <option value="_self" @if(old('link_target',$nodeData->link_target) == '_self') selected @endif>Self</option>
                                                                                                <option value="_parent" @if(old('link_target',$nodeData->link_target) == '_parent') selected @endif>Parent</option>
                                                                                                <option value="_top" @if(old('link_target',$nodeData->link_target) == '_top') selected @endif>Top</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="add_tip_section" @if(old('add_tip',$nodeData->tips_title)) @else style="display: none;" @endif >
                                                                                <div class="custom-hr"></div>
                                                                                <p><h3 class="mb-0">Tip Section</h3></p>
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-sm-4 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="example4cols2Input">Title</label>
                                                                                            <input type="text" class="form-control" id="tip_title" name="tip_title" placeholder="Tip Title" value="{{old('tip_title',$nodeData->tips_title)}}">
                                                                                            <span class="form-text text-danger" id="tip_title_error"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-4 col-md-4">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="tip_text">Text</label>
                                                                                            <input type="text" class="form-control" id="tip_text" name="tip_text" placeholder="Tip Text" value="{{old('tip_text',$nodeData->tips_title)}}">
                                                                                            <span class="form-text text-danger" id="tip_text_error"></span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <div class="change_orient_section" @if(old('change_orient',$nodeData->orient_yes)) @else style="display: none;" @endif >
                                                                                <div class="custom-hr"></div>
                                                                                <p><h3 class="mb-0">Orient Section</h3></p>
                                                                                <div class="row align-items-center">
                                                                                    <div class="col-sm-3 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="orient_yes">Yes</label>
                                                                                            <select class="form-control" name="orient_yes">
                                                                                                <option value="l" @if(old('orient_yes',$nodeData->orient_yes) == 'l') selected @endif>Left</option>
                                                                                                <option value="r" @if(old('orient_yes',$nodeData->orient_yes) == 'r') selected @endif >Right</option>
                                                                                                <option value="b" @if(old('orient_yes',$nodeData->orient_yes) == 'b') selected @endif>Bottom</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-sm-3 col-md-3">
                                                                                        <div class="form-group">
                                                                                            <label class="form-control-label" for="orient_no">No</label>
                                                                                            <select class="form-control" name="orient_no">
                                                                                                
                                                                                                <option value="r" @if(old('orient_no',$nodeData->orient_no) == 'r') selected @endif >Right</option>
                                                                                                <option value="l" @if(old('orient_no',$nodeData->orient_no) == 'l') selected @endif>Left</option>
                                                                                                <option value="b" @if(old('orient_no',$nodeData->orient_no) == 'b') selected @endif>Bottom</option>
                                                                                            </select>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>

                                                                            <hr class="hr-dotted">
                                                                            <input type="submit" class="btn btn-info" name="submit" value="Save">
                                                                            <input  type="button" data-dismiss="modal" class="btn btn-success" name="submit" value="Cancel">    
                                                                        </div>
                                                                    </form>  
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr align="center"><td colspan="4">Record Not Found.</td></tr>
                                    @endif
                                    
                                  </tbody>
                                </table>
                              </div>
                            </div>
                        </div>

                        <hr>
                        <div class="card-header border-0" id="flowchart_preview">
                            <div class="row">
                              <div class="col-6">
                                <h3 class="mb-0">Flowchart Preview</h3>
                              </div>
                            </div>
                        </div>
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
  .modal-dialog{
    max-width: 800px;
  }
</style>
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
    @php
                        if ($node->orient_yes && $node->orient_no) {
    @endphp
                        var orientArr = {
                                yes:'{{$node->orient_yes}}',
                                no:'{{$node->orient_no}}',
                            }
    @php
                        }
    @endphp            
                        var orientArr = {
                                yes:'b',
                                no:'r',
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

    $('.editNodeForm').submit(function (e) {
        
        e.preventDefault();
        var frm = $(this);
        var id = $(this).attr('id');
        var current = this;

        $.ajax({
            type: frm.attr('method'),
            url: frm.attr('action'),
            data: frm.serialize(),
            success: function (data) {
                
                if(data.status){
                    location.reload();
                }
            },
            error: function (data) {

                console.log(data);
                if( data.status === 422 ) {
                    var errors = $.parseJSON(data.responseText);
                    console.log(errors);
                    $.each(errors.errors, function (key, val) {
                        
                        frm.find("#" + key + "_error").text(val[0]);
                        frm.find("#" + key).addClass('is-invalid');
                    });
                }
            },
        });
    });

    var svg_height=20;
    $("#drawing svg > g").each(function() {console.log($(this));
        svg_height+=$(this).get(0).getBBox().height;
    })
    $("#drawing svg").attr('height',svg_height);
});

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

function changeEditNodeType(ele,id){

    var ids = "#node-form-"+id
   
    if(ele.value == 'decision'){

        $(ids+' .dicision_section').show();
        $(ids+' .change_orient_block').show();

        $(ids+' .process_section').hide();
        $(ids+' .add_link_block').hide();
        $(ids+' .add_tip_block').hide();

    }else if(ele.value == 'process'){

        $(ids+' .dicision_section').hide();
        $(ids+' .change_orient_block').hide();

        $(ids+' .process_section').show();
        $(ids+' .add_link_block').show();
        $(ids+' .add_tip_block').show();

    }else{
        $(ids+' .dicision_section').hide();
        $(ids+' .process_section').hide();
        $(ids+' .change_orient_block').hide();
        $(ids+' .add_link_block').show();
        $(ids+' .add_tip_block').show();
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

function showEditSection(ele, id){

    var fetchId = "#node-form-"+id
    var ids = $(ele).attr('id');

    if($(fetchId+" #"+ids).prop('checked')){
        $(fetchId+" ."+ids+"_section").show();
    }else{
        $(fetchId+" ."+ids+"_section").hide();
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

function showEditForm(id){
    
    $("#add_node_frm").hide();
    $(".node_edit_form").hide();
    $(".node_edit_form").show();
}
</script>
@endsection