
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


    <!--Start Our Contact Us Area -->
    <div id="contact" class="our-contact-us-area area-padding">
        <div class="container white-bg">
            <div class="row">
                <!--COMPANY INFORMATION ITEM-->
                <div class="col-sm-4">
                    <div class="information-box">
                        <div class="company-info">
                            <div class="info-icon">
                                <i class="fa fa-map-marker"></i>
                            </div>
                            <div class="info-text">
                                <p>{{ frontendData::get_backend('address') }}</p>
                            </div>
                        </div>
                        <div class="company-info">
                            <div class="info-icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="info-text">
                                <p>{{ frontendData::get_backend('phone') }}</p>
                            </div>
                        </div>
                        <div class="company-info">
                            <div class="info-icon">
                                <i class="fa fa-envelope-o"></i>
                            </div>
                            <div class="info-text">
                                <p>{{ frontendData::get_backend('email') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!--SEND MESSSAGE  ITEM-->
                <div class="col-sm-8">
                    <div class="send-message">
                        <div class="message-field">
                            <form id="contact-form" action="mail.php" method="post">
                                <div class="input-box">
                                    <input id="send-email-name" type="text" name="name" placeholder="Your Name">
                                </div>
                                <div class="input-box">
                                    <input id="send-email-email" type="email" name="email" placeholder="Your Email">
                                </div>
                                <div class="input-box">
                                    <input id="send-email-subject" type="text" name="sub" placeholder="Subject">
                                </div>
                                <div class="input-box">
                                    <textarea name="message" id="send-email-message" maxlength="550" placeholder="Message..."></textarea>
                                    <h5>
                                        <span id="counter__length"></span>
                                        characters remain
                                    </h5>
                                </div>
                                <button type="button" name="ok" class="send-btn" id="send-email">Submit</button>
                            </form>
    						<p class="form-messege"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--End Our Contact Us Area -->

    
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
    <script>
        var counterLength = document.querySelector('#send-email-message');
        document.getElementById('counter__length').innerHTML = counterLength.getAttribute('maxlength');
        function val(e) {
            var attrLength = counterLength.getAttribute('maxlength');
            var currentLength = counterLength.value.length;
            var totalLength = attrLength - currentLength;
            document.querySelector('#counter__length').innerHTML = totalLength;
            if (e.keyCode === 8) {
                document.querySelector('#counter__length').innerHTML = totalLength++;
            }
        }
        counterLength.addEventListener("keyup", val, false);


        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }

        $(document).on('click', '#send-email', function() {
            var error       = 0;
            var name        = $('#send-email-name').val();
            var email       = $('#send-email-email').val();
            var subject     = $('#send-email-subject').val();
            var message     = $('#send-email-message').val();


            if(name == '') {
                error++;
                $('#send-email-name').css("border-color", 'red');
            } else {
                $('#send-email-name').css("border-color", '');
            }   

            if(email == '') {
                error++;
                $('#send-email-email').css("border-color", 'red');
            } else {
                $('#send-email-email').css("border-color", '');
            }

            if(subject == '') {
                error++;
                $('#send-email-subject').css("border-color", 'red');
            } else {
                $('#send-email-subject').css("border-color", '');
            }

            if(message == '') {
                error++;
                $('#send-email-message').css("border-color", 'red');
            } else {
                $('#send-email-message').css("border-color", '');
            }

            if(check_email(email) == false) {
                error++;
                $('#send-email-email').css("border-color", 'red');
            } else {
                $('#send-email-email').css("border-color", '');
            }

            if(error <= 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('frontend/contactMailSend')?>",
                    data: {'name' : name, 'email' : email, 'subject' : subject, 'message' : message},
                    dataType: "html",
                    success: function(data) {
                        if(data = 'success') {
                            location.reload();
                        }
                    }
                });

            }
        });

    </script>


    @if($this->session->flashdata('success'))
        <script type="text/javascript">
            toastr["success"]("<?=$this->session->flashdata('success');?>")
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
        </script>
    @endif

    @if($this->session->flashdata('error'))
       <script type="text/javascript">
            toastr["error"]("<?=$this->session->flashdata('error');?>")
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
        </script>
    @endif
@endsection