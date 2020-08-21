<div class="col-lg-3">
    <div id="side-bar-menu" class="blog_right_sidebar">

        <aside class="single_sidebar_widget post_category_widget">
            <ul class="list cat-list">
                @foreach ($categories as $key=>$value)
                    <li class="<?=($key==$urlSlug) ? 'active' : ''?>"><a class="d-flex" href="{{ url('/') }}?slug={{ $key }}"><p>{{ $value }}</p></a></li>
                @endforeach
            </ul>
        </aside>

    </div>
</div>