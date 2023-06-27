@layout('views/layouts/master')


@section('content')
    
    @if(count($sliders))
        <div id="main-slider" class="slider-area">
        @foreach($sliders as $slider)
            <div class="single-slide">
                <img src="{{ base_url('uploads/gallery/'.$slider->file_name) }}" alt="">
                <div class="banner-overlay">
                    <div class="container">
                        <div class="caption style-2">
                            <h2>{{ sentenceMap(htmlspecialchars_decode($slider->file_title), 17, '<span>', '</span>') }}</h2>
                            <p>{{ htmlspecialchars_decode($slider->file_description) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
    @endif
    
    @if(count($featured_image))
        <div class="featured-slider">
            <img src="{{ base_url('uploads/gallery/'.$featured_image->file_name) }}" alt="{{ $featured_image->file_alt_text }}">
        </div>
    @endif

    <!-- bradcame area  -->
    <div class="bradcam-area area-padding">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
    				<div class="section-title white-title bradcam-title text-uppercase text-center">
    					<h2> {{ $page->title }} </h2>
                        <span class="star"></span>
                        <span class="star"></span>
                        <span class="star"></span>
    				</div>
    			</div>
                <div class="bradcam-wrap text-center">
                    <nav class="bradcam-inner">
                      <a class="bradcam-item text-uppercase" href="{{ base_url('frontend/'.$homepageType.'/'.$homepage->url) }}">{{ $homepageTitle }}</a>
                      <span class="brd-separetor">/</span>
                      <span class="bradcam-item active text-uppercase">{{ $page->title }}</span>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- bradcame area  -->
    
    <section id="notice" class="notice-area area-padding gray-bg">
        <div class="container">
            <div class="row text-center">
                @if(count($notices))
                    <?php $i = 1; ?>
                    @foreach($notices as $notice)
                        @if($i <= 9)
                            <div class="col-md-4 col-sm-6">
                                <div class="single-notice">
                                    <div class="notice-content">
                                        <h3><a href="{{ base_url('frontend/notice/'.$notice->noticeID) }}">{{ namesorting($notice->title, 45) }}</a></h3>
                                        <div class="notice-meta">
                                            <span class="published-date">
                                                <i class="fa fa-calendar"></i>
                                                {{ date('d M Y', strtotime($notice->date)) }}
                                            </span>
                                        </div>
                                        <p>{{ namesorting($notice->notice, 140)  }}</p>
                                        <a href="{{ base_url('frontend/notice/'.$notice->noticeID) }}" class="read-more-btn">read more <i class="fa fa-long-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <?php $i++; ?>
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    
    <!-- Start About Content -->
    <section id="about" class="">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="main-about">
                        <p> {{ htmlspecialchars_decode($page->content) }} </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Close About Content -->
@endsection
