<!-- Admin Dashboard Page -->
@extends('layouts.adminapp')
@section('content')
<!--begin::Subheader-->
<div class="subheader py-2 py-lg-12 subheader-transparent" id="kt_subheader">
    <div class="container d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Heading-->
            <div class="d-flex flex-column">
                <!--begin::Title-->
                <h2 class="text-white font-weight-bold my-2 mr-5">Organization</h2>
                <!--end::Title-->
                <!--begin::Breadcrumb-->
                <div class="d-flex align-items-center font-weight-bold my-2">
                    <!--begin::Item-->
                    <a href="#" class="opacity-75 hover-opacity-100">
                        <i class="flaticon2-shelter text-white icon-1x"></i>
                    </a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <a href="{{ route('category.list') }}" class="text-white text-hover-white opacity-75 hover-opacity-100">Organizations Type</a>
                    <!--end::Item-->
                    <!--begin::Item-->
                    <span class="label label-dot label-sm bg-white opacity-75 mx-3"></span>
                    <a href="javascript:void(0);" class="text-white text-hover-white hover-opacity-100">{{$title}}</a>
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
        <!--begin::Card-->
        <div class="card card-custom gutter-b example example-compact">
            <div class="card-header">
                <div class="card-title">
                    <h3 class="card-label">{{$title}}</h3>
                </div>
                <div class="card-toolbar">
                    <a href="{!! URL::previous() !!}" class="btn btn-light-primary font-weight-bold text-uppercase px-9 py-4"><i class="icon-xl la la-arrow-left"></i> Back</a>
                </div>
            </div>
            <div class="card-body">
                <form class="form" action="{{ route('category.store') }}" method="post" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="card-body">
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label><span class="text-danger">*</span>&nbsp;Name :</label>
                                <input class="form-control  @if($errors->has('name')) is-invalid @endif maxlength" maxlength="255" type="text" name="name" id="name" placeholder="Enter name" value="{{ old('name') }}" />
                                @if($errors->has('name'))
                                    <span class="form-text text-danger">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="col-lg-6">
                                <label><span class="text-danger">*</span>&nbsp;Document Group :</label>
                                <select class="form-control groupofdocument @if($errors->has('groupofdocument')) is-invalid @endif" name="groupofdocument[]" multiple="multiple">
                                    @if($groupofdocuments)
                                        @foreach($groupofdocuments as $group)
                                            <option value="{{$group->id}}">{{$group->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('groupofdocument'))
                                    <span class="form-text text-danger">{{ $errors->first('groupofdocument') }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label>Status :</label>
                                <div class="inline">
                                    <input name="status" data-switch="true" type="checkbox" checked="checked" data-on-color="success" data-off-color="warning" data-on-text="Active" data-off-text="Inactive" value="1">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-lg-6">
                                <button type="submit" class="btn btn-primary mr-2">Save</button>
                                <button type="reset" class="btn btn-secondary">Reset</button>
                            </div>
                        </div>
                    </div>
                </form>
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
$(document).ready(function() {
    $('[data-switch=true]').bootstrapSwitch();
    $('.groupofdocument').select2({
        placeholder:'Select a document group.',
    });
    $(".maxlength").maxlength({
        threshold: 3,
        warningClass: "label label-success label-rounded label-inline",
        limitReachedClass: "label label-danger label-rounded label-inline",
        appendToParent: true,
        separator: ' of ',
        preText: 'You have ',
        postText: ' chars remaining.',
        validate: true
    })
});
</script>
@endsection