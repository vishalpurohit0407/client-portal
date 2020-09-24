@if($selfdiagnosis && count($selfdiagnosis)>0)
    @foreach($selfdiagnosis as $selfdiagnos)
        <div class="col-lg-4 pb-5">
            <!-- Image-Text card -->
            <a href="{{route('user.selfdiagnosis.show',$selfdiagnos->id)}}">
                <div class="card custom_card_front">
                    <!-- Card image -->
                    <img class="card-img-top" src="{{asset($selfdiagnos->main_image_url)}}" alt="Image placeholder">
                    <!-- Card body -->
                    <div class="card-body">
                        <h5 class="h2 card-title mb-0">{{ucfirst($selfdiagnos->main_title)}}</h5>
                        @php
                        $category_id = $selfdiagnos->guide_category->pluck('category_id')->toArray();
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
