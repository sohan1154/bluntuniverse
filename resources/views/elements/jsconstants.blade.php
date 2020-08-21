<script>
    
    var BASEURL = "{{ url('/') }}";
    
    var IMGBASEURL = "{{ asset('/images/') }}";
    
    var err_message = "{{config('siteconstants.ERR_SOMETHING')}}";

    var input_loader = '<img class="inside-loading" src="' + BASEURL + '/public/loading.gif" alt="loader-img">';

    var input_loading_max = '<img src="' + BASEURL + '/public/loading.gif" style="max-height: 100%;" alt="loader-img">';

    var input_loader_max = '<img src="' + BASEURL + '/public/images/loader.gif" style="max-height: 100%;" alt="loader-img">';

    var input_loader_full = '<div class="overlay-page"><div class="overlay-ldr"><img src="' + BASEURL + '/public/images/loader.gif" alt=""></div></div>';
    
</script>
