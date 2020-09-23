@if($selfdiagnosis)
    @foreach($selfdiagnosis as $selfdiagnos)
        <div class="col-lg-4">
            <!-- Image-Text card -->
            <div class="card">
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
                    <a href="{{route('admin.selfdiagnosis.edit',$selfdiagnos->id)}}" class="btn btn-info btn-sm">Edit</a>
                    <form action="{{route('admin.selfdiagnosis.destroy',$selfdiagnos->id)}}" method='POST' style='display: contents;' id="frm_{{$selfdiagnos->id}}">
                        @csrf
                        <input type='hidden' name='_method' value='DELETE'>
                        <a type="submit" class="btn btn-danger btn-sm" style="color: white;" onclick="return deleteConfirm(this);" id="{{$selfdiagnos->id}}">Delete</a>
                    </form>
                </div>
            </div>
            <div class="ribbon-wrapper">
                <div class="ribbon red">Draft</div>
            </div>
        </div>
    @endforeach
@endif
