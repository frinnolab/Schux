@layout('views/layouts/master')


@section('content')

    <!-- bradcame area  -->
    <div class="bradcam-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="section-title white-title bradcam-title text-uppercase text-center">
                        <h2> {{ $post->title }} </h2>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
                    </div>
                </div>
                <div class="bradcam-wrap text-center">
                    <nav class="bradcam-inner">
                      <a class="bradcam-item text-uppercase" href="{{ base_url('frontend/'.$homepageType.'/'.$homepage->url) }}">{{ $homepageTitle }}</a>
                      <span class="brd-separetor">/</span>
                      <span class="bradcam-item active text-uppercase">{{ $post->title }}</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- bradcame area  -->


    <section id="about" class="about-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                    <div class="blog-section">
                        @if(count($post))
                            <article class="blog">
                                <header>
                                    <a href="{{ base_url('frontend/post/'.$post->url) }}">
                                        {{ $post->title }}
                                    </a> 
                                </header>
                                <div>Posted on {{ date('dS F, Y', strtotime($post->publish_date)) }} by {{ frontendData::get_user($post->create_usertypeID, $post->create_userID) }}</div>

                                <div class="blog-body">
                                    @if(count($featured_image))
                                        <a href="{{ base_url('frontend/post/'.$post->url) }}">
                                            <img class="fixedsize" src="{{ base_url('uploads/gallery/'.$featured_image->file_name) }}">
                                        </a>       
                                    @endif

                                    <p>
                                        {{ htmlspecialchars_decode($post->content) }}
                                    </p>

                                </div>
                            </article>
                        @endif
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4">
                    <div class="blog-recennt-post">
                        <h2><span>Recent</span> Posts</h2> 
                        @if(count($posts))
                            <?php $i=1; ?> 
                            @foreach($posts as $post)
                                <div class="post-title"><a href="{{ base_url('frontend/post/'.$post->url) }}"> <i class="fa fa-arrow-right"></i> {{ namesorting(strip_tags($post->title), 75) }}</a></div>
                                @if($i == 6)
                                    <?php break; ?>
                                @endif
                                <?php $i++; ?>
                            @endforeach
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
