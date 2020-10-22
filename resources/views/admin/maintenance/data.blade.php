@if($maintenance && count($maintenance)>0)
    <div class="row">
        @foreach($maintenance as $mainten)
            <div class="col-lg-4 pb-5">
                <!-- Image-Text card -->
                <div class="card custom_card">
                    <!-- Card image -->
                    <img class="card-img-top" src="{{asset($mainten->main_image_url)}}" alt="Image placeholder">
                    <!-- Card body -->
                    <div class="card-body">
                        <h5 class="h2 card-title mb-0">{{ucfirst($mainten->main_title)}}</h5>
                        @php
                        $category_id = $mainten->guide_category->pluck('category_id')->toArray();
                        $category_name = App\Category::whereIn('id',$category_id)->pluck('name')->toArray();
                        @endphp
                        <p class="card-text mt-4 text-uppercase text-muted h5">
                            {{implode(', ',$category_name)}}
                        </p>
                        <p><a href="javascript:void(0);"><strong>{{$mainten->completion_guide_count}} people completed this guide</strong></a></p>
                        <div class="footer-button">
                            <a href="{{route('admin.maintenance.show',$mainten->id)}}" class="btn btn-success btn-sm">View</a>
                            <a href="{{route('admin.maintenance.edit',$mainten->id)}}" class="btn btn-info btn-sm">Edit</a>
                            <form action="{{route('admin.maintenance.destroy',$mainten->id)}}" method='POST' style='display: contents;' id="frm_{{$mainten->id}}">
                                @csrf
                                <input type='hidden' name='_method' value='DELETE'>
                                <a type="submit" class="btn btn-danger btn-sm" style="color: white;" onclick="return deleteConfirm(this);" id="{{$mainten->id}}">Delete</a>
                            </form>
                        </div>
                    </div>
                </div>
                @if($mainten->status == '3')
                <div class="ribbon-wrapper">
                    <div class="ribbon red">Draft</div>
                </div>
                @endif
            </div>
        @endforeach
    </div>
@else
        
            <!-- Image-Text card -->
<div class="card">
    <!-- Card body -->
    <div class="card-body">
        <h5 class="h3 card-title mb-0 text-center">No records available.</h5>
    </div>
</div>
        
@endif
