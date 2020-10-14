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
                <h6 class="h2 text-white d-inline-block mb-0">Flowchart</h6>
                <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                    <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}"><i class="fas fa-home"></i></a></li>
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
      <!-- Table -->
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
            <!-- Card header -->
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <!-- Title -->
                  <h5 class="h3 mb-0">{{$title}}</h5>
                </div>
              </div>
            </div>
            <div class="table-responsive py-4">
                <div id="drawing" style="margin:30px auto; width:900px;"></div>
                <hr>

                <div class="col-lg-12">
                    <div class="card">
                      <!-- Card header -->
                      <div class="card-header border-0">
                        <div class="row">
                          <div class="col-6">
                            <h3 class="mb-0">Step Listing</h3>
                          </div>
                          
                        </div>
                      </div>
                      <!-- Light table -->
                      <div class="table-responsive">
                        <table class="table align-items-center table-flush">
                          <thead class="thead-light">
                            <tr>
                              <th>Author</th>
                              <th>Created at</th>
                              <th>Product</th>
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
                            <tr>
                              <td class="table-user">
                                
                                <b>Alex Smith</b>
                              </td>
                              <td>
                                <span class="text-muted">08/09/2018</span>
                              </td>
                              <td>
                                <a href="#!" class="font-weight-bold">Argon Design System</a>
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
                            <tr>
                              <td class="table-user">
                               
                                <b>Samantha Ivy</b>
                              </td>
                              <td>
                                <span class="text-muted">30/08/2018</span>
                              </td>
                              <td>
                                <a href="#!" class="font-weight-bold">Black Dashboard</a>
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

                <div class="col-lg-12">
                  
                    <div class="card">
                      <!-- Card header -->
                      <div class="card-header border-0">
                        <div class="row">
                          <div class="col-6">
                            <h3 class="mb-0">Add New Step</h3>
                          </div>
                          
                        </div>
                      </div>
                    <!-- Card body -->
                    <div class="card-body">
                      <div class="row align-items-center">
                          <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols1Input">Label <a href="javascript:void(0);" title="Header" data-toggle="popover" data-placement="top" data-content="Content"><i class="fas fa-question-circle" style="font-size: 16px;"></i></a></label>
                              <input type="text" class="form-control" id="example4cols1Input" placeholder="Lable">
                            </div>
                          </div>
                          <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Type</label>
                              <select class="form-control" id="exampleFormControlSelect1">
                                <option>Decision</option>
                                <option>Finish</option>
                                <option>Process</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols3Input">Text</label>
                              <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
                            </div>
                          </div>
                      </div>

                      <div class="row align-items-center">
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Yes (Choose Child)</label>
                              <select class="form-control" id="exampleFormControlSelect1">
                                <option>knowPolicy</option>
                                <option>hasOAPolicy</option>
                                <option>CCOffered</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">No (Choose Child)</label>
                              <select class="form-control" id="exampleFormControlSelect1">
                                <option>knowPolicy</option>
                                <option>hasOAPolicy</option>
                                <option>CCOffered</option>
                              </select>
                            </div>
                          </div>

                          <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Add Link?</label><br>
                              <label class="custom-toggle  custom-toggle-success">
                                <input type="checkbox" checked="">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                              </label>
                            </div>
                          </div>

                          <div class="col-sm-2 col-md-2">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Add Tip?</label><br>
                              <label class="custom-toggle  custom-toggle-primary">
                                <input type="checkbox" checked="">
                                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                              </label>
                            </div>
                          </div>
                      </div>

                      <div class="custom-hr"></div>

                      <p><h3 class="mb-0">Link Section</h3></p>

                      <div class="row align-items-center">
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Text</label>
                              <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
                            </div>
                          </div>
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">URL</label>
                              <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
                            </div>
                          </div>

                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Target</label><br>
                              <select class="form-control" id="exampleFormControlSelect1">
                                <option value="_blank">Blank</option>
                                <option value="_self">Self</option>
                                <option value="_parent">Parent</option>
                                <option value="_top">Top</option>
                              </select>
                            </div>
                          </div>
                      </div>

                      <div class="custom-hr"></div>

                      <p><h3 class="mb-0">Tip Section</h3></p>
                      <div class="row align-items-center">
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Title</label>
                              <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
                            </div>
                          </div>
                          <div class="col-sm-3 col-md-3">
                            <div class="form-group">
                              <label class="form-control-label" for="example4cols2Input">Text</label>
                              <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
                            </div>
                          </div>

                          
                      </div>

                    </div>
                  </div>
                </div>
                <div class="col-lg-12 text-right">
                  <input class="btn btn-primary btn-sm" data-repeater-create="" type="button" value="Add More">
                </div>
                <div class="col-lg-12">
                  <hr class="hr-dotted">
                  <input type="submit" class="btn btn-info" name="submit" value="Preview">
                  <input type="submit" class="btn btn-success" name="submit" value="Published">
                  <a href="http://localhost/client-portal/public/admin/guide" class="btn btn-primary">Cancel</a>
                  <input type="hidden" name="step_count" id="step_count" value="1">
                </div>
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
</script>
@endsection