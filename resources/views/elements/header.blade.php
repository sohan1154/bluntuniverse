  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>{{ config('app.name') }}{{(@$title) ? ' :: '. $title : '' }}</title>

  <!-- Custom fonts for this template-->
  <link href="{{ url('/') }}/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="{{ url('/') }}/css/sb-admin-2.min.css" rel="stylesheet">
  
  <!-- Custom styles for this page -->
  <link href="{{ url('/') }}/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  
  <!-- Alertify css -->
  <link href="{{ url('/') }}/alertify/css/alertify.min.css" rel="stylesheet">
  <link href="{{ url('/') }}/alertify/css/themes/default.min.css" rel="stylesheet">
  
  <!-- loaders css -->
  <link href="{{ url('/') }}/loader/css/jquery.loading.css" rel="stylesheet">

  <!-- Custom styles -->
  <link href="{{ url('/') }}/css/custom.css" rel="stylesheet">

  <!-- js constants -->
  @include('elements.jsconstants')

  <script src="{{ url('/') }}/vendor/jquery/jquery.min.js"></script>
  
  <!-- Date-Time Picker -->
  <!-- <script type="text/javascript" src="{{ url('/') }}/bower_components/jquery/dist/jquery.min.js"></script>
  <script type="text/javascript" src="{{ url('/') }}/bower_components/moment/min/moment.min.js"></script>
  <script type="text/javascript" src="{{ url('/') }}/bower_components/bootstrap/js/bootstrap.min.js"></script>
  <script type="text/javascript" src="{{ url('/') }}/bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/bootstrap/css/bootstrap.min.css" />
  <link rel="stylesheet" href="{{ url('/') }}/bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" /> -->
