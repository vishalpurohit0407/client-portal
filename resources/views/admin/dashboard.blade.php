@extends('layouts.adminapp')

@section('content')
<div class="header bg-primary pb-7">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                  <h6 class="h2 text-white d-inline-block mb-0">Dashboards</h6>
                    <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        </ol>
                    </nav>
                </div>
            </div>
          
            <div class="row">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Users</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$totalUser}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a href="{{route('admin.user.list')}}" class="text-nowrap font-weight-600">See Details</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Warranty Extension Request</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$totalWarrantyRequest}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="ni ni-collection"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a href="{{route('admin.warrantyextension.listreqest')}}" class="text-nowrap font-weight-600">See Details</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Self Diagnosis</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$totalSelfDiagnosis}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="ni ni-settings"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a href="{{route('admin.selfdiagnosis.list')}}" class="text-nowrap font-weight-600">See Details</a>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card card-stats">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h5 class="card-title text-uppercase text-muted mb-0">Total Maintenance Guides</h5>
                                    <span class="h2 font-weight-bold mb-0">{{$totalMaintenance}}</span>
                                </div>
                                <div class="col-auto">
                                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                                        <i class="fas fa-toolbox"></i>
                                    </div>
                                </div>
                            </div>
                            <p class="mt-3 mb-0 text-sm">
                                <a href="{{route('admin.maintenance.list')}}" class="text-nowrap font-weight-600">See Details</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--6">
    <div class="row">
       <div class="col-xl-12">
          <div class="card">
             <div class="card-header border-0">
                <div class="row align-items-center">
                   <div class="col">
                      <h3 class="mb-0">Latest Warranty Extension Request</h3>
                   </div>
                   <div class="col text-right">
                      <a href="{{route('admin.warrantyextension.listreqest')}}" class="btn btn-sm btn-primary">See all</a>
                   </div>
                </div>
             </div>
             <div class="table-responsive">
                <!-- Projects table -->
                <table class="table align-items-center table-flush" id="datatable-warranty">
                    <thead class="thead-light">
                      <tr>
                        <th class="w-10">No.</th>
                        <th class="w-100">User Name</th>
                        <th>Unique Key</th>
                        <th>Status</th>
                        <th>Created At</th>
                      </tr>
                    </thead>
                    <tbody>
                        @if(count($extensions) > 0)
                            @foreach($extensions as $key => $extension)

                                <tr>
                                    <td>{{$key+1}}</td>
                                    <td><img src="{{asset($extension->user->user_image_url)}}" class="avatar rounded-circle mr-3"> <b>{{ucfirst($extension->user->name)}}</b></td>
                                    <td>{{$extension->unique_key}}</td>
                                    @php
                                        $extension->status  = App\WarrantyExtension::where('unique_key',$extension->unique_key)->latest()->first()->status;
                                    @endphp
                                    @if($extension->status == '0') 
                                      <td><span class="badge badge-pill badge-warning">Initial</span></td>
                                    @elseif($extension->status == '1') 
                                      <td><span class="badge badge-pill badge-primary">Admin Reply</span></td>
                                    @elseif($extension->status == '2')
                                      <td><span class="badge badge-pill badge-success">Request</span></td>
                                    @elseif($extension->status == '3')
                                      <td><span class="badge badge-pill badge-success">Approved</span></td>
                                    @elseif($extension->status == '4')
                                      <td><span class="badge badge-pill badge-danger">Declined</span></td>
                                    @endif
                                    <td>{{date('d-M-Y',strtotime($extension->created_at))}}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr><td colspan="2" align="center">Record not found.</td></tr>
                        @endif
                    </tbody>
                    <tfoot>
                      <tr>
                        <th class="w-10">No.</th>
                        <th class="w-100">User Name</th>
                        <th>Unique Key</th>
                        <th>Status</th>
                        <th>Created At</th>
                       
                      </tr>
                    </tfoot>
                </table>
                
             </div>
          </div>
       </div>
    </div>
</div>
<style type="text/css">
    .card{
        height: 100%;
        margin-bottom: 2.5rem;
    }
    .card-stats .card-body{
        display: block;
        flex: 0;
    }
    .card-stats .card-body p{
        position: absolute;
        bottom: 1rem;
    }
</style>
    
@endsection
