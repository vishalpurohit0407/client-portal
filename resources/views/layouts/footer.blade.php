<footer class="footer">
  <div class="container-fluid">
    <div class="row align-items-center justify-content-lg-between">
      <div class="col-lg-6">
        <div class="copyright text-center text-lg-left text-muted">
           &copy; {{ now()->year }} <a class="font-weight-bold ml-1" target="_blank">{{env('APP_NAME')}}</a>
        </div>
      </div>
      <div class="col-lg-6">
        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
          @php 
          $cms_page = \App\CmsPage::where('status','1')->get();
          @endphp
          @if($cms_page)
            @foreach($cms_page as $page)
              <li class="nav-item">
                <a href="{{asset($page->url_slug)}}" target="_blank" class="nav-link">{{$page->title}}</a>
              </li>
            @endforeach
          @endif
        </ul>
      </div>
    </div>
  </div>
</footer>