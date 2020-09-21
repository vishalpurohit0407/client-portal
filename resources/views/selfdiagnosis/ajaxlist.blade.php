@if($selfdiagnosis && count($selfdiagnosis)>0)
<div class="row">
    @include('selfdiagnosis.selfdiagnosis_data')
</div>
<nav aria-label="Page navigation example">
    <ul class="pagination justify-content-end">
        {!! $selfdiagnosis->links() !!}
    </ul>
</nav>
@else
<div class="card col-12">
    <div class="card-body">
        <div class="pt-4 text-center">
            <h5 class="h3 title">
                <span class="d-block mb-1">No matching records found</span>
            </h5>
        </div>
    </div>
</div>
@endif