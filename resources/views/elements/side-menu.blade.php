<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
    <!-- <div class="sidebar-brand-icon rotate-n-0">
      <i class="fas fa-news"></i>
    </div>
    <div class="sidebar-brand-text mx-3">{{ Config::get('app.name') }}</div> -->
    <img src="img/logos/logo-3.png" style="height: 151px;margin-top: 80px;">
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <li style="margin-top: 90px;" class="nav-item <?=($segment1=='dashboard')?'active':''?>">
    <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>

  <!-- <li class="nav-item <?=($segment1=='users')?'active':''?>">
    <a class="nav-link" href="{{ route('users-index') }}">
      <i class="fas fa-fw fa-user-tie"></i>
      <span>Users</span></a>
  </li> -->

  <li class="nav-item <?=($segment1=='breaking_news')?'active':''?>">
    <a class="nav-link" href="{{ route('breaking_news-index') }}">
      <i class="fas fa-fw fa-newspaper"></i>
      <span>Breaking News</span></a>
  </li>

  <li class="nav-item <?=($segment1=='news')?'active':''?>">
    <a class="nav-link" href="{{ route('news-index') }}">
      <i class="fas fa-fw fa-newspaper"></i>
      <span>News</span></a>
  </li>

  <li class="nav-item <?=($segment1=='categories')?'active':''?>">
    <a class="nav-link" href="{{ route('categories-index') }}">
      <i class="fas fa-fw fa-th-large"></i>
      <span>Categories</span></a>
  </li>

  <li class="nav-item <?=($segment1=='galleries')?'active':''?>">
    <a class="nav-link" href="{{ route('galleries-index') }}">
      <i class="fas fa-fw fa-image"></i>
      <span>Galleries</span></a>
  </li>

  <!-- <li class="nav-item <?=($segment1=='contactus')?'active':''?>">
    <a class="nav-link" href="{{ route('contactus-index') }}">
      <i class="fas fa-fw fa-envelope-open"></i>
      <span>Contact us</span></a>
  </li> -->

  <li class="nav-item <?=($segment1=='pages')?'active':''?>">
    <a class="nav-link" href="{{ route('pages-index') }}">
      <i class="fas fa-fw fa-file"></i>
      <span>Pages</span></a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler (Sidebar) -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>