<!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="{{route('admin.dashboard')}}">
          <img src="{{asset('assets/img/brand/blue.png')}}" class="navbar-brand-img" alt="...">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}" href="{{route('admin.dashboard')}}">
                <i class="ni ni-shop text-primary"></i>
                <span class="nav-link-text">Dashboards</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('admin.selfdiagnosis.*') ? 'active' : '' }}" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                <i class="ni ni-settings text-orange"></i>
                <span class="nav-link-text">Self Diagnosis</span>
              </a>
              <div class="collapse {{ Request::routeIs('admin.selfdiagnosis.*') ? 'show' : '' }}" id="navbar-forms" style="">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="{{route('admin.selfdiagnosis.list')}}" class="nav-link {{ Request::routeIs('admin.selfdiagnosis.list') ? 'active' : '' }}">Listing</a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('admin.selfdiagnosis.create')}}" class="nav-link {{ Request::routeIs('admin.selfdiagnosis.create') ? 'active' : '' }}">Add New</a>
                  </li>
                </ul>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('admin.category.*') ? 'active' : '' }}" href="{{route('admin.category.list')}}">
                <i class="ni ni-ungroup text-info"></i>
                <span class="nav-link-text">Category</span>
              </a>
            </li>
          </ul>
          
        </div>
      </div>
    </div>
  </nav>