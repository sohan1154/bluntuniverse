<div class="bradcam_area bradcam_bg_4">
        <div class="container breaking-container">
            <div class="row">
                <div class="col-xl-1 breaking-text">
                    <div class="bradcam_text text-center">
                        <h3 id="liveClock"><?php echo date('H:i'); ?></h3>
                    </div>
                </div>
                <div class="col-xl-11 breaking-news-box">
                    <div class="bradcam_text text-center">
                        <marquee behavior="scroll" direction="left">
                            <h3>{{ $breakingNews }}</h3>
                        </marquee>
                    </div>
                </div>
            </div>
        </div>
    </div>