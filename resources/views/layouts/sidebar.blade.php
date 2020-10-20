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
          </div>-->

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

            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('user.maintenance.*') ? 'active' : '' }}" href="{{route('user.maintenance.list')}}">
                <i class="fas fa-toolbox text-red"></i>
                <span class="nav-link-text">Maintenance Guides</span>
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link {{Request::routeIs('user.warranty_extension.*') ? 'active' : '' }}" href="#warranty_extension" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                <i class="ni ni-collection text-default"></i>
                <span class="nav-link-text">Warranty Extensions</span>
              </a>
              <div class="collapse {{Request::routeIs('user.warranty_extension.*') ? 'show' : '' }}" id="warranty_extension">
                <ul class="nav nav-sm flex-column">
                  <li class="nav-item">
                    <a href="{{route('user.warranty_extension.list')}}" class="nav-link {{ Request::routeIs('user.warranty_extension.list') ? 'active' : '' }}">
                      <span class="sidenav-normal">All Warranty Extensions</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('user.warranty_extension.create')}}" class="nav-link {{ Request::routeIs('user.warranty_extension.create') ? 'active' : '' }}">
                      <span class="sidenav-normal">Add New Warranty Extension</span>
                    </a>
                  </li>
                  <li class="nav-item">
                    <a href="{{route('user.warranty_extension.listreqest')}}" class="nav-link {{ Request::routeIs('user.warranty_extension.listreqest') ? 'active' : '' }}">
                      <span class="sidenav-normal">Warranty Extension Request</span>
                    </a>
                  </li>
                </ul>
              </div>
            </li>
            
            <li class="nav-item">
              <a class="nav-link {{ Request::routeIs('user.support.ticket.*') ? 'active' : '' }}" href="{{route('user.support.ticket.list')}}">
                <i class="fa fa-question-circle text-info" aria-hidden="true"></i>
                <span class="nav-link-text">Support Tickets</span>
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>
  </nav>