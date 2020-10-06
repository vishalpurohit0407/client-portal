@if($maintenances && count($maintenances)>0)
    @foreach($maintenances as $maintenance)
        <div class="col-lg-4 pb-5">
            <!-- Image-Text card -->
            <a href="{{route('user.maintenance.show',$maintenance->id)}}">
                <div class="card custom_card_front">
                    <!-- Card image -->
                    <img class="card-img-top" src="{{asset($maintenance->main_image_url)}}" alt="Image placeholder">
                    <!-- Card body -->
                    <div class="card-body">
                        <h5 class="h2 card-title mb-0">{{ucfirst($maintenance->main_title)}}</h5>
                        @php
                        $category_id = $maintenance->guide_category->pluck('category_id')->toArray();
                        $category_name = App\Category::whereIn('id',$category_id)->pluck('name')->toArray();
                        @endphp
                        <p class="card-text mt-4 text-uppercase text-muted h5">
                            {{implode(', ',$category_name)}}
                        </p>
                    </div>
                </div>
            </a>
        </div>
    @endforeach
@else
<div class="col-lg-12">
    <!-- Image-Text card -->
    <div class="card">
        <!-- Card body -->
        <div class="card-body">
            <h5 class="h3 card-title mb-0 text-center">No records available.</h5>
        </div>
    </div>
</div>
@endif
