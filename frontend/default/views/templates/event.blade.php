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


    <section id="events" class="events-area area-padding">
        <div class="container">
                @if(count($events))
                    <?php $i = 1; ?>
                    @foreach($events as $event)
                        @if($i <= 9)
                            @if($i%3 == 0)
                                <div class="row">
                            @endif
                                <div class="col-md-4 col-sm-6">

                                    <div class="single-event-list">
                                        <div class="event-img">
                                            <a href="{{ base_url('frontend/event/'.$event->eventID) }}"><img src="{{ imageLinkWithDefatulImage($event->photo, 'holiday.png') }}" alt=""></a>
                                        </div>
                                        <div class="event-content">
                                            <div class="event-meta">
                                                <div class="event-date first-date">
                                                    {{ date('d', strtotime($event->fdate))  }}
                                                    <span>{{ date('M', strtotime($event->fdate)) }}</span>
                                                </div>
                                                @if($event->fdate != $event->tdate)
                                                    <div class="event-date second-date">
                                                        {{ date('d', strtotime($event->tdate))  }}
                                                        <span>{{ date('M', strtotime($event->tdate)) }}</span>
                                                    </div>
                                                @endif

                                                <div class="event-info">
                                                    <h4><a href="{{ base_url('frontend/event/'.$event->eventID) }}">{{ $event->title }}</a></h4>
                                                    <div class="event-time">
                                                        <span class="event-title">Time: </span>
                                                        <span>{{ date('h:i A', strtotime($event->ftime)) }} - {{ date('h:i A', strtotime($event->ttime)) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a id="{{ $event->eventID }}" href="#" class="primary-btn style--two going-event">Going now</a>
                                        </div>
                                    </div>
                                </div>
                            @if($i%3 == 0)
                                </div>
                            @endif
                        @endif
                        <?php $i++; ?>
                    @endforeach
                @endif
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

@section('footerAssetPush')

    <script type="text/javascript">
        $(document).on('click', '.going-event', function(e) {
            e.preventDefault();
            var id = $(this).attr('id');
            if(id) {
                $.ajax({
                    dataType: 'json',
                    type: 'POST',
                    url: "<?=base_url('frontend/eventGoing')?>",
                    data: { 'id':id },
                    dataType: "html",
                    success: function(data) {
                        var response = jQuery.parseJSON(data);
                        if(response.status == true) {
                            toastr["success"](response.message)
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }

                        } else {
                            toastr["error"](response.message)
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": false,
                                "positionClass": "toast-top-right",
                                "preventDuplicates": false,
                                "onclick": null,
                                "showDuration": "500",
                                "hideDuration": "500",
                                "timeOut": "5000",
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            }
                        }
                    }
                });
            }
        });

    </script>

@endsection
