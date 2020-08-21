<!doctype html>
<html class="no-js" lang="zxx">

<head>

    <?php 
      $url = url()->current();
      $current_url = explode("/",$url);
      $active_url = last($current_url);
      $segment1 = Request::segment(1);
      $segment2 = Request::segment(2);
      //   $role = Auth::user()->role;
      
      $urlSlug = app('request')->input('slug');
    ?>

    @include('website.elements.jsconstants')

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{ config('app.name') }}{{(@$title) ? ' :: '. $title : '' }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- <link rel="manifest" href="site.webmanifest"> -->
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <!-- Place favicon.ico in the root directory -->

    <!-- CSS here -->
    <link rel="stylesheet" href="{{ url('/') }}/website/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/owl.carousel.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/magnific-popup.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/themify-icons.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/nice-select.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/flaticon.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/gijgo.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/animate.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/slicknav.css">
    <link rel="stylesheet" href="{{ url('/') }}/website/css/style.css">
    <!-- <link rel="stylesheet" href="{{ url('/') }}/website/css/responsive.css"> -->
    <link rel="stylesheet" href="{{ url('/') }}/website/css/custom.css">

    <!-- social share buttons -->
    <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=5f2a59895b9ff10012072a89&product=inline-share-buttons" async="async"></script>
</head>

<body>
    <!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

    <!-- header-start -->
    @include('website.elements.top-menu')
    <!-- header-end -->

    <!-- breaking news  -->
    @include('website.elements.breaking-news')
    <!--/ breaking news end  -->
    
    <!--================Blog Area =================-->
    <section class="blog_area section-padding">
        <div class="container">
            <div class="row">

                <!-- side menu start -->
                @include('website.elements.side-menu')
                <!-- side menu end -->

                <!-- blog start -->
                @yield('content')
                <!-- blog end -->

            </div>

            <!-- loader -->
            <div class="row hide" id="load-more">
                <div class="col-lg-3 mb-5 mb-lg-0">
                &nbsp;
                </div>
                <div class="col-lg-9 mb-5 mb-lg-0">
                    <div>
                        <img src="loading.gif"/>
                    </div>
                </div>
            </div>
            <!-- loader end -->
            
        </div>
    </section>
    <!--================Blog Area =================-->

    <!-- footer start -->
    @include('website.elements.footer')
    <!--/ footer end  -->

    <!-- Modal -->
    <div class="modal fade custom_search_pop" id="exampleModalCenter" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="serch_form">
                    <input type="text" placeholder="Search">
                    <button type="submit">search</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS here -->
    <script src="{{ url('/') }}/website/js/vendor/modernizr-3.5.0.min.js"></script>
    <script src="{{ url('/') }}/website/js/vendor/jquery-1.12.4.min.js"></script>
    <script src="{{ url('/') }}/website/js/popper.min.js"></script>
    <script src="{{ url('/') }}/website/js/bootstrap.min.js"></script>
    <script src="{{ url('/') }}/website/js/owl.carousel.min.js"></script>
    <script src="{{ url('/') }}/website/js/isotope.pkgd.min.js"></script>
    <script src="{{ url('/') }}/website/js/ajax-form.js"></script>
    <script src="{{ url('/') }}/website/js/waypoints.min.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.counterup.min.js"></script>
    <script src="{{ url('/') }}/website/js/imagesloaded.pkgd.min.js"></script>
    <script src="{{ url('/') }}/website/js/scrollIt.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.scrollUp.min.js"></script>
    <script src="{{ url('/') }}/website/js/wow.min.js"></script>
    <script src="{{ url('/') }}/website/js/nice-select.min.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.slicknav.min.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.magnific-popup.min.js"></script>
    <script src="{{ url('/') }}/website/js/plugins.js"></script>
    <script src="{{ url('/') }}/website/js/gijgo.min.js"></script>

    <!--contact js-->
    <script src="{{ url('/') }}/website/js/contact.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.ajaxchimp.min.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.form.js"></script>
    <script src="{{ url('/') }}/website/js/jquery.validate.min.js"></script>
    <script src="{{ url('/') }}/website/js/mail-script.js"></script>

    <script src="{{ url('/') }}/website/js/main.js"></script>
    <script src="{{ url('/') }}/website/js/ajax-load-more.js"></script>
    <script>      
        
        $('#datepicker').datepicker({
            iconsLibrary: 'fontawesome',
            icons: {
                rightIcon: '<span class="fa fa-caret-down"></span>'
            }
        });
        $('#datepicker2').datepicker({
            iconsLibrary: 'fontawesome',
            icons: {
                rightIcon: '<span class="fa fa-caret-down"></span>'
            }

        });

        $(document).ready(function () {
          updateClock();
        });
        
        function updateClock() {

            let today = new Date();

            let hour = (today.getHours() < 10) ? '0' + today.getHours() : today.getHours();
            let minute = (today.getMinutes() < 10) ? '0' + today.getMinutes() : today.getMinutes();

            document.getElementById('liveClock').innerHTML = hour + ':' + minute;
        }
        setInterval(updateClock, 10000);
        
        
        $(document).ready(function(){

            // on sticky-header add side menu class
            $(window).on('scroll', function () {
                var scroll = $(window).scrollTop();
                if (scroll < 400) {
                    $("#side-bar-menu").removeClass("side-menu-padding");
                } else {
                    $("#side-bar-menu").addClass("side-menu-padding");
                }
            });
            
            window.currentPage = 1;
            window.totalPage = null;
            if(urlName == 'news' && window.currentRequest == null) {
                // var page = 1;
                console.log('first time called')
                // get first time date on page load
                loadMore(window.currentPage);

                // get data on scroll
                $(window).scroll(function(){
                    console.log('on scroll')
                    console.log('top-scroll:', $(window).scrollTop(), 'calculated:', ($(document).height() - $(window).height())-100)
                    if($(window).scrollTop() >= (($(document).height() - $(window).height()) - 100) ){
                        console.log('pages:', window.currentPage, '==', window.totalPage)
                        if(window.totalPage == null || window.currentPage < window.totalPage) {
                            console.log('function fired')
                            // page++;
                            window.currentPage++;
                            loadMore(window.currentPage);
                        }
                    }
                });
            }
        });

    </script>
</body>

</html>