<aside class="main-sidebar sidebar-dark-primary elevation-4" style="position: fixed; width: 250px;">
  <a href="index3.html" class="brand-link">
      <img src="{{ asset('assets/images/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">Sumanas.</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="info">
              <a href="#" class="d-block text-decoration-none">{{Auth::user()->name}}</a>
          </div>
      </div>

      <!-- SidebarSearch Form -->
      <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
              <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
              <div class="input-group-append">
                  <button class="btn btn-sidebar">
                      <i class="fas fa-search fa-fw"></i>
                  </button>
              </div>
          </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                  <a href="{{route('home.index')}}" class="nav-link {{ Request::is('admin/home') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tachometer-alt"></i>
                      <p>
                          Dashboard
                      </p>
                  </a>
              </li>
              <li class="nav-item {{ Request::is('admin/category*') || Request::is('admin/post*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Request::is('admin/category*') || Request::is('admin/post*') ? 'active' : '' }}">
                      <i class="nav-icon fas fa-tools"></i>
                      <p>
                          Quiz
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview">
                      <li class="nav-item">
                          <a href="{{route('quiz.index')}}" class="nav-link {{ Request::is('admin/quiz') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>View Quiz</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{{route('quiz.create')}}" class="nav-link {{ Request::is('admin/quiz/create') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Add Quiz</p>
                          </a>
                      </li>
                  </ul>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fas fa-sign-out-alt"></i>
                    <p>
                        Log out
                    </p>
                </a>
               <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </li>
          </ul>
      </nav>
  </div>
</aside>