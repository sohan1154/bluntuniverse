
      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; {{ config('app.name') }} {{ date('Y')}}</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <!-- <script src="{{ url('/') }}/vendor/jquery/jquery.min.js"></script> -->
  <script src="{{ url('/') }}/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  
  <!-- Alertify script -->
  <script src="{{ url('/') }}/alertify/js/alertify.min.js"></script>
  
  <!-- loaders js -->
  <script src="{{ url('/') }}/loader/js/jquery.loading.js"></script>

  <!-- Core plugin JavaScript-->
  <script src="{{ url('/') }}/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="{{ url('/') }}/js/sb-admin-2.min.js"></script>

  <!-- Page level plugins -->
  <script src="{{ url('/') }}/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="{{ url('/') }}/vendor/datatables/dataTables.bootstrap4.min.js"></script>

  <!-- Page level custom scripts -->
  <script src="{{ url('/') }}/js/demo/datatables-demo.js"></script>

  @if(in_array($segment1,['dashboard']))
    <!-- Page level plugins -->
    <script src="{{ url('/') }}/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="{{ url('/') }}/js/demo/chart-area-demo.js"></script>
    <script src="{{ url('/') }}/js/demo/chart-pie-demo.js"></script>
  @endif

  <!-- ajax search functions -->
  <script src="{{ url('/') }}/js/ajax-search.js"></script>

  <!-- chosen -->
  <script src="{{ asset('js/chosen/chosen.jquery.js') }}" type="text/javascript"></script>
  <script>
    $(".chosen-select").chosen();
  </script>

  @if(in_array(trim(Request::segment(1)),['pages', 'news']))
    <!-- Ckeditor scripts-->
    <script src="{{ asset('js/ckeditor/ckeditor.js') }}"></script>
    <script type="text/javascript">
      CKEDITOR.replace('ckeditor', {});
    </script>	
  @endif

  <!-- js for common functions -->
  <script src="{{ url('/') }}/js/common.js"></script>
