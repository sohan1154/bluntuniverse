<!DOCTYPE html>
<html lang="en">

<head>

  <?php 
    $url = url()->current();
    $current_url = explode("/",$url);
    $active_url = last($current_url);
    $segment1 = Request::segment(1);
    $segment2 = Request::segment(2);
    $role = Auth::user()->role;
  ?>

  @include('elements.header')
</head>

<body id="page-top">

  <!-- Page Wrapper -->
  <div id="wrapper">

    <!-- Sidebar -->
    @include('elements.side-menu')
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        @include('elements.top-menu')
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div class="container-fluid">

          <!-- Page Heading -->
          @include('elements.breadcrumb')

          @include('elements.flash-message')

          @yield('content')

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

  @include('elements.footer')

</body>

</html>
