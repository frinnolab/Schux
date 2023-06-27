@layout('views/layouts/master')


@section('content')

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

    @if(strlen($page->content) > 0)
    <section id="about" class="about-area area-padding">
    	<div class="container">
            <div class="row">
                <div class="col-xs-12">
                	<p> {{ htmlspecialchars_decode($page->content) }} </p>
                </div>
            </div>
        </div>
    </section>
    @endif

    <section id="about" class="about-area area-padding">
    	<div class="container">
            <div class="row">
                <div class="col-sm-8 col-xs-12">
                	<div class="blog-section">
                		@if(count($posts)) 
                			@foreach($posts as $post)
		                    	<article class="blog">
		                    		<header>
		                    			<a href="{{ base_url('frontend/post/'.$post->url) }}">
		                    				{{ $post->title }}
		                    			</a> 
		                    		</header>
		                    		<div>Posted on {{ date('dS F, Y', strtotime($post->publish_date)) }} by {{ frontendData::get_user($post->create_usertypeID, $post->create_userID) }}</div>
		                    		<div class="blog-body">
		                    			@if(isset($featured_image[$post->featured_image]))
			                    			<a href="{{ base_url('frontend/post/'.$post->url) }}">
			                    				<img src="{{ imageLinkWithDefatulImage($featured_image[$post->featured_image]->file_name, 'holiday.png', 'uploads/gallery/') }}">
			                    			</a>
		                    			@endif
		                    			<p>
		                    				@if(strlen(strip_tags($post->content)) > 250)
		                    					{{ namesorting(strip_tags($post->content), 250) }} <a href="{{ base_url('frontend/post/'.$post->url) }}"> Read More » </a>
		                    				@else
		                    					{{ strip_tags($post->content) }}
		                    				@endif
		                    			</p>
		                    		</div>
		                    	</article>
		                    	<hr>
                			@endforeach
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
