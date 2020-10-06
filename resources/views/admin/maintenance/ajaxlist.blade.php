@if($maintenance && count($maintenance)>0)

	@include('admin.maintenance.data')

	<nav aria-label="Page navigation example">
	    <ul class="pagination justify-content-end">
	        {!! $maintenance->links() !!}
	    </ul>
	</nav>
@else

<!-- Image-Text card -->
<div class="card">
    <!-- Card body -->
    <div class="card-body">
        <h5 class="h3 card-title mb-0 text-center">No matching records found</h5>
    </div>
</div>

@endif