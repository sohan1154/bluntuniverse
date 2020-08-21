<header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-2 col-lg-2">
                                <div class="logo">
                                    <a href="{{ url('/') }}">
                                        <img src="{{ url('/') }}/img/logos/logo-white.png" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 header-menus-links">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <?php 
                                            $count = 0;
                                            foreach ($categories as $key=>$value) {
                                                $count++;
                                                if($count<=6) {
                                                    ?><li class="<?=($key==$urlSlug) ? 'active' : ''?>"><a href="{{ url('/') }}?slug={{ $key }}">{{ $value }}</a></li><?php 
                                                } else {
                                                    if($count==7) {
                                                        ?>
                                                        <li><a href="javascript:;">अधिक <i class="ti-angle-down"></i></a>
                                                        <ul class="submenu">
                                                        <?php 
                                                    }
                                                    ?><li class="<?=($key==$urlSlug) ? 'active' : ''?>"><a href="{{ url('/') }}?slug={{ $key }}">{{ $value }}</a></li><?php
                                                
                                                    if($count==count($categories)) {
                                                        ?></ul></li><?php
                                                    }
                                                }
                                            } ?>

                                            <!-- for mobile view only -->
                                            <li><a class="d-block d-lg-none" href="{{ url('/') }}/about-us">About</a></li>
                                            <li><a class="d-block d-lg-none" href="{{ url('/') }}/contact-us">Contact</a></li>
                                            <li><a class="d-block d-lg-none" href="{{ url('/') }}/terms-of-use">Terms of use</a></li>
                                            <li><a class="d-block d-lg-none" href="javascript:;">Follow on <i class="ti-angle-down"></i></a>
                                              <ul class="submenu">
                                                <li><a target="_blank" href="https://www.instagram.com/bluntuinverse/">Instagram</a></li>
                                                <li><a target="_blank" href="https://twitter.com/BluntUniverse?s=08">Twitter</a></li>
                                                <li><a target="_blank" href="https://www.facebook.com/bluntuniverse/">Facebook</a></li>
                                                <li><a target="_blank" href="https://www.youtube.com/channel/UCsg4ZCskJny2gyepquFfSgA?view_as=subscriber">YouTube</a></li>
                                              </ul>
                                            </li>

                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 d-none d-lg-block">
                                <div class="social_wrap d-flex align-items-center justify-content-end">
                                    <!-- <div class="number">
                                        <p class="header-mobile"> <i class="fa fa-phone"></i> 10(256)-928 256</p>
                                    </div> -->
                                    <div class="social_links d-none d-xl-block">
                                        <ul>
                                            <li><a href="{{ url('/') }}/about-us"> About </a></li>
                                            <li><a href="{{ url('/') }}/contact-us"> Contact </a></li>
                                            <li><a href="{{ url('/') }}/terms-of-use"> Terms of use </a></li>
                                        </ul>
                                    </div>
                                    <div class="social_links d-none d-xl-block">
                                        <ul>
                                            <li><a target="_blank" href="https://www.instagram.com/bluntuinverse/"> <i class="fa fa-instagram"></i> </a></li>
                                            <li><a target="_blank" href="https://twitter.com/BluntUniverse?s=08"> <i class="fa fa-twitter"></i> </a></li>
                                            <li><a target="_blank" href="https://www.facebook.com/bluntuniverse/"> <i class="fa fa-facebook"></i> </a></li>
                                            <li><a target="_blank" href="https://www.youtube.com/channel/UCsg4ZCskJny2gyepquFfSgA?view_as=subscriber"> <i class="fa fa-youtube"></i> </a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="seach_icon">
                                <a data-toggle="modal" data-target="#exampleModalCenter" href="#">
                                    <i class="fa fa-search"></i>
                                </a>
                            </div> -->
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>