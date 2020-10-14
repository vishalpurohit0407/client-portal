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
            <h5 class="h3 mb-0">Card title</h5>
        </div>
        <div class="card-body">
          <p class="card-text mb-4">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Facilis non dolore est fuga nobis ipsum illum eligendi nemo iure repellat, soluta, optio minus ut reiciendis voluptates enim impedit veritatis officiis.</p>
          <div id="drawing" style="margin:30px auto; width:900px;">yeeeeee</div>
        </div>
    </div>
</div>    
@endsection

@section('pagewise_js')
<script type="text/javascript"> 
jQuery(document).ready(function($){
   
});

flowSVG.draw(SVG('drawing').size(900, 1100));
    flowSVG.config({
        interactive: true,
        showButtons: true,
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
                  target: '_blank'
              }
          ],
          tip: {title: 'HEFCE Note',
          text:
          [
              'You must put your',
              'accepted version into',
              'WRAP and/or subject',
              'repository within 3 months',
              'of acceptance.'
          ]}
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
            tip: {title: 'HEFCE Note',
            text:
            [
                'If you really have to go',
                'this route you must log',
                'the exception in WRAP on',
                'acceptance in order',
                'to comply.'
            ]}
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
            tip: {title: 'HEFCE Note',
            text:
            [
                'Deposit in WRAP if',
                'time limits acceptable. If',
                'journal does not allow at all',
                'an exception record will',
                'have to be entered',
                'in WRAP, if you feel this is',
                'most appropriate journal.'
            ]}
        },
        {
            label: 'depositInWrap',
            type: 'finish',
            text: [
                'Deposit in WRAP here or ',
                'contact team'
            ],
            tip: {title: 'HEFCE Note',
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
            ]}
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
