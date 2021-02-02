@extends('layouts.app')
@section('content')
    <div class="header bg-primary pb-7">
        <div class="container-fluid">
            <div class="header-body">
                <div class="row align-items-center py-4">
                    <div class="col-lg-6 col-7">
                        <h6 class="h2 text-white d-inline-block mb-0">Maintenance Guides</h6>
                        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                                <li class="breadcrumb-item"><a href="{{route('home')}}"><i class="fas fa-home"></i></a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-6 col-5 text-right">
                        
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <div class="input-group input-group-merge">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                </div>
                                <input class="form-control" placeholder="Search..." type="text" id="search">   
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group row">
                            <div class="col-md-12">
                                <select class="form-control" id="category">
                                    <option value="">All Category</option>
                                    @if($categorys)
                                        @foreach($categorys as $category)
                                            <option value="{{$category->id}}">{{$category->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        <a href="javascript:void(0);" class="btn btn-neutral" style="height: 45px;" onclick="return resetFilter();">Clear</a>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- Page content -->
    <div class="guide-listing-loader" style="display: none;">
        <i class="fa fa-spinner fa-pulse"></i>
    </div>
    <div class="container-fluid mt--6" id="selfdiagnosis_data">
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
        <div class="row">
            @include('maintenance.maintenance_data')
        </div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-end">
                {!! $maintenances->links() !!}
            </ul>
        </nav>
    </div>
@endsection
@section('pagewise_js')
<script type="text/javascript">
/*$(window).on('hashchange', function() {
    if (window.location.hash) {
        var page = window.location.hash.replace('#', '');
        if (page == Number.NaN || page <= 0) {
            return false;
        }else{
            getData(page);
        }
    }
});
*/
var pageno=1;
$(document).ready(function() {
    $(document).on('click', '.pagination a',function(event){
        event.preventDefault();
        $('li').removeClass('active');
        $(this).parent('li').addClass('active');
        var myurl = $(this).attr('href');
        pageno=$(this).attr('href').split('page=')[1];
        getData();
    });
    var delaySearchTimer;
    $('#search').on('keyup',function(){
        clearTimeout(delaySearchTimer);
        delaySearchTimer = setTimeout(function() {
            pageno=1;
            getData();
        }, 500);        
    });
    $('#category').on('change',function(){
        pageno=1;
        getData();
    });
});
function getData(){
    $(".guide-listing-loader").show();
    $.ajax(
    {
        url: '{{route("user.maintenance.search")}}',
        type: "get",
        datatype: "html",
        data:{page:pageno,search:$('#search').val(),category_id:$('#category').val()},
    }).done(function(data){
        $("#selfdiagnosis_data").html(data);
        $(".guide-listing-loader").hide();
        //location.hash = page;
    }).fail(function(jqXHR, ajaxOptions, thrownError){
          //alert('No response from server');
          $(".guide-listing-loader").hide();
    });
}
function resetFilter(){
    $("#search").val('');
    $("#category").val('');
    $('#category').change();
}
</script>
@endsection