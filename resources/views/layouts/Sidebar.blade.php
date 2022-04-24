  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
      <img src="{{ asset('assets') }}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ env('APP_NAME') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ asset('assets') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ Auth::user()->person->name ?? "" }}</a>
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
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-header">Master Data</li>
          @if(Auth::user()->hasAccess('lecturer-read'))
            <x-nav-item :icon="'fas fa-users'" :text="'Dosen'" :href="route('lecturer.index')" />
          @endif

          @if(Auth::user()->hasAccess('learner-read'))
            <x-nav-item :icon="'fas fa-users'" :text="'Mahasiswa'" :href="route('learner.index')" />
          @endif

          @if(Auth::user()->hasAccess('course-master-read'))
            <x-nav-item :icon="'fab fa-leanpub'" :text="'Master Mata Kuliah'" :href="route('course-master.index')" />
          @endif

          @if(Auth::user()->hasAccess('course-read'))
            <x-nav-item :icon="'fab fa-leanpub'" :text="'Mata Kuliah'" :href="route('course.index')" />
          @endif

          <x-nav-item :icon="'fas fa-list'" :text="'Daftar Kategori'" :href="route('category.list')" />

          @if(Auth::user()->hasAccess('access-right-read'))
            <x-nav-item :icon="'fas fa-list'" :text="'Hak Akses'" :href="route('access-right.index')" />
          @endif
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>