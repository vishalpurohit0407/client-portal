<!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand main-logo" href="{{route('home')}}">
          <img src="{{asset('assets/img/brand/blue.png')}}" class="navbar-brand-img" alt="navbar-brand-img">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <!-- <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div> -->
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}" href="{{route('home')}}">
                <i class="ni ni-shop text-primary"></i>
                <span class="nav-link-text">Dashboards</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('user.selfdiagnosis.*') ? 'active' : '' }}" href="{{route('user.selfdiagnosis.list')}}">
                <i class="ni ni-settings text-orange"></i>
                <span class="nav-link-text">Self Diagnosis</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </nav>