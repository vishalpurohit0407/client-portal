<!-- Admin Dashboard Page -->
@extends('layouts.app')
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">Organization Type</h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <div class="d-flex align-items-center font-weight-bold my-2">
                    <!--begin::Item-->
                    <a href="{{route('admin.dashboard')}}" class="opacity-75 hover-opacity-100">
                        <i class="flaticon2-shelter text-white icon-1x"></i>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <a href="{{route('organization.list')}}" class="text-white text-hover-white hover-opacity-100">Organization</a>
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <a class="text-white text-hover-white hover-opacity-100">{{$title}}</a>
                    <!--end::Item-->
                </div>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Heading-->
        </div>
        <!--end::Info-->
    </div>
</div>
<!--end::Subheader-->
<!--begin::Entry-->
<div class="d-flex flex-column-fluid">
    <!--begin::Container-->
    <div class="container">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
            @if(Session::has('alert-' . $msg))
                <div class="alert alert-custom alert-{{ $msg }} fade show mb-5" role="alert">                           
                    <div class="alert-text">{{ Session::get('alert-' . $msg) }}</div>
                    <div class="alert-close">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">
                                <i class="ki ki-close"></i>
                            </span>
                        </button>
                    </div>
                </div>
            @endif 
        @endforeach
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-header flex-wrap border-0 pt-6 pb-0">
                <div class="card-title">
                    <h3 class="card-label">{{$title}}
                        <span class="d-block text-muted pt-2 font-size-sm">You can add, edit &amp; delete of particular Organization Type.</span>
                    </h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{route('category.create')}}" class="btn btn-primary font-weight-bolder">
                    <span class="svg-icon svg-icon-md">
                        <!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg-->
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <rect x="0" y="0" width="24" height="24" />
                                <circle fill="#000000" cx="9" cy="15" r="6" />
                                <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                            </g>
                        </svg>
                        <!--end::Svg Icon-->
                    </span>Add New</a>
                    <!--end::Button-->
                </div>
            </div>
            <div class="card-body">
                <!--begin: Search Form-->
                <!--begin::Search Form-->
                <div class="mb-7">
                    <div class="row align-items-center">
                        <div class="col-lg-9 col-xl-8">
                            <div class="row align-items-center">
                                <div class="col-md-4 my-2 my-md-0">
                                    <div class="input-icon">
                                        <input type="text" class="form-control" placeholder="Search..." id="generalSearch" />
                                        <span>
                                            <i class="flaticon2-search-1 text-muted"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Search Form-->
                <!--end: Search Form-->
                <!--begin: Datatable-->
                <div class="datatable datatable-bordered datatable-head-custom" id="category-datatable"></div>
                <!--end: Datatable-->
            </div>
        </div>
        <!--end::Card-->
    </div>
    <!--end::Container-->
</div>
<!--end::Entry-->
@endsection
@section('pagewise_js')
<script type="text/javascript">
    jQuery(document).ready(function() {
        
        var columns=[{
            field: "number",
            title: "#",
            sortable: !1,
            type: "number",
            selector: !1,
            textAlign: "center",
            width:40
        }, {
            field: "name",
            sortable: !0,
            title: "Name",
            template: function(category) {
                return '<div class="d-flex align-items-center">\ <div class="ml-4">\ <div class="text-dark-75 font-weight-bolder font-size-lg mb-0">\ ' + category.name.toUpperCase() + ' </div>\ </div>\ </div>';
            }
        },{
            field: "status",
            sortable: !0,
            title: "Status",
            template: function(t) {
                var e = {
                    0: {
                        title: "Deleted",
                        class: "label-light-danger"
                    },
                    1: {
                        title: "Active",
                        class: "label-light-success"
                    },
                    2: {
                        title: "Inactive",
                        class: "label-light-warning"
                    },
                };
                return '<span class="label font-weight-bold label-lg label-inline ' + e[t.status].class + '">' + e[t.status].title + "</span>"
            }
        }, {
            field: "Actions",
            title: "Actions",
            sortable: !1,
            width: 110,
            overflow: "visible",
            autoHide: !1,
            template: function(category) {
                
                var urledit = '{{ route("category.edit", ":id") }}';
                var urldelete = '{{ route("category.destroy", ":id") }}';
                urledit = urledit.replace(':id', category.id);
                urldelete = urldelete.replace(':id', category.id);

                if(category.status == '0'){
                    return '';
                }else{
                    return '<a href="'+urledit+'" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" title="Edit details">\ <span class="svg-icon svg-icon-md">\ <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\ <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\ <rect x="0" y="0" width="24" height="24"/>\ <path d="M12.2674799,18.2323597 L12.0084872,5.45852451 C12.0004303,5.06114792 12.1504154,4.6768183 12.4255037,4.38993949 L15.0030167,1.70195304 L17.5910752,4.40093695 C17.8599071,4.6812911 18.0095067,5.05499603 18.0083938,5.44341307 L17.9718262,18.2062508 C17.9694575,19.0329966 17.2985816,19.701953 16.4718324,19.701953 L13.7671717,19.701953 C12.9505952,19.701953 12.2840328,19.0487684 12.2674799,18.2323597 Z" fill="#000000" fill-rule="nonzero" transform="translate(14.701953, 10.701953) rotate(-135.000000) translate(-14.701953, -10.701953) "/>\ <path d="M12.9,2 C13.4522847,2 13.9,2.44771525 13.9,3 C13.9,3.55228475 13.4522847,4 12.9,4 L6,4 C4.8954305,4 4,4.8954305 4,6 L4,18 C4,19.1045695 4.8954305,20 6,20 L18,20 C19.1045695,20 20,19.1045695 20,18 L20,13 C20,12.4477153 20.4477153,12 21,12 C21.5522847,12 22,12.4477153 22,13 L22,18 C22,20.209139 20.209139,22 18,22 L6,22 C3.790861,22 2,20.209139 2,18 L2,6 C2,3.790861 3.790861,2 6,2 L12.9,2 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/>\ </g>\ </svg>\ </span>\ </a>\t\t\t\t\t <form name="form" method="post" action="'+urldelete+'" style="display: inline-block;"  data-toggle="tooltip" title="Delete" id="frm_'+category.id+'"><a type="submit" class="btn btn-sm btn-default btn-text-primary btn-hover-primary btn-icon mr-2" onclick="return deleteConfirm(this);" id="'+category.id+'">\ <span class="svg-icon svg-icon-md">\ <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\ <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\ <rect x="0" y="0" width="24" height="24"/>\ <path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#000000" fill-rule="nonzero"/>\ <path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#000000" opacity="0.3"/>\ </g>\ </svg>\ </span>\ </a>{{ csrf_field() }} {{ method_field('delete') }}</form>  ';
                }
            }
        }];

        var ajax_url="{{ route('category.listdata') }}";
        var t;

        t = $("#category-datatable").KTDatatable({
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: ajax_url,
                    },
                },
                pageSize: 10, // display 20 records per page
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
                saveState: {
                    cookie:false,
                    webstorage:false
                }
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                footer: false, // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#generalSearch'),
                delay: 400,
                key: 'generalSearch'
            },
            columns: columns
        })
    });

    function deleteConfirm(event){
        var id = $(event).attr('id');
        swal.fire({
          title: 'Are you sure?',
          text: "You won't be delete this organization type!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            $("#frm_"+id).submit();
          }
        })
    }; 
</script>
@endsection