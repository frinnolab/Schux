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

    <section id="about" class="about-area area-padding">
    	<div class="container register">
            <div class="row">
                <div class="col-md-3 register-left">
                    <img src="{{ base_url('uploads/images/'.frontendData::get_backend('photo')) }}" alt=""/>
                    <h3>Welcome</h3>
                    <p>We trust the following information will assist every prospective family and student in understanding our admissions procedures including all the necessary documentation required to process an application for admission</p>
                </div>
                <div class="col-md-9 register-right">
                    <div class="register-form">
                        <div class="admissionsearchBox">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="admissionID" name="admissionID" placeholder="Admission ID *" />
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone *" />
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-danger form-control" id="getadmissionresult">Get Result</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="mainregisterForm" id="mainregisterForm">                        
                            <h3 class="register-heading">Apply as a Student</h3>
                            <form id="admissionForm" method="post" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="name" placeholder="Name *" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="dob" id="dob" placeholder="Date of Birth *" />
                                        </div>

                                        <div class="form-group">
                                            <select class="form-control" name="sex">
                                                <option value="Male">Male</option>
                                                <option value="Female">Female</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" name="phone" placeholder="Phone *" />
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" name="email" placeholder="Email" />
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <?php
                                                $classesArray['0'] = 'Apply Class';
                                                foreach ($classes as $classaKey => $classa) {
                                                    if(frontendData::get_backend('ex_class') != $classa->classesID) {
                                                        $classesArray[$classa->classesID] = $classa->classes;
                                                    }
                                                }
                                                echo form_dropdown("classesID", $classesArray, set_value("classesID"), "class='form-control select2'");
                                            ?>
                                        </div>

                                        <div class="form-group">
                                            <input type="text" class="form-control" name="religion" placeholder="Religion *" />
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="address" placeholder="Address *" />
                                        </div>

                                        <div class="form-group">
                                            <?php
                                                $countryArray['0'] = 'Select Country';
                                                foreach ($countrys as $countryKey => $country) {
                                                    $countryArray[$countryKey] = $country;
                                                }
                                                echo form_dropdown("country", $countryArray, set_value("country"), "class='form-control select2'");
                                            ?>
                                        </div>

                                        <div class="input-group image-preview">
                                            <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                    <span class="fa fa-remove"></span>
                                                    Clear
                                                </button>
                                                <div class="btn btn-success image-preview-input">
                                                    <span class="fa fa-repeat"></span>
                                                    <span class="image-preview-input-title">
                                                        Browse
                                                    </span>
                                                    <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                                </div>
                                            </span>
                                        </div>

                                        <input type="button" class="btnRegister"  value="Apply"/>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('headerAssetPush')
<link type="text/css" rel="stylesheet" href="<?=base_url($frontendThemePath.'assets/select2/css/select2.css')?>">
<link type="text/css" rel="stylesheet" href="<?=base_url($frontendThemePath.'assets/select2/css/select2-bootstrap.css')?>">
<link type="text/css" rel="stylesheet" href="<?=base_url($frontendThemePath.'assets/datepicker/datepicker.css')?>">

<style type="text/css">
    .register {
        background: -webkit-linear-gradient(left, #3931af, #00c6ff);
        margin-top: 3%;
        padding: 3%;
    }

    .register-left {
        text-align: center;
        color: #fff;
        margin-top: 4%;
    }

    .register-left img {
        margin-top: 15%;
        margin-bottom: 5%;
        width: 25%;
        -webkit-animation: mover 2s infinite  alternate;
        animation: mover 1s infinite  alternate;
    }
    
    .register-left p {
        font-weight: lighter;
        padding: 12%;
        margin-top: -9%;
    }

    @-webkit-keyframes mover {
        0% { transform: translateY(0); }
        100% { transform: translateY(-20px); }
    }
    @keyframes mover {
        0% { transform: translateY(0); }
        100% { transform: translateY(-20px); }
    }
    .register-right{
        background: #f8f9fa;
        border-top-left-radius: 10% 50%;
        border-bottom-left-radius: 10% 50%;
    }
    
    .register-form {
        padding: 10%;
        overflow: hidden;
    }

    .admissionsearchBox {
        overflow: hidden;
        width: 100%;
    }
    
    .register-heading{
        text-align: center;
        color: #495057;
        overflow: hidden;
    }

    .mainregisterForm {
        padding-top: 25px;
    }

    .btnRegister{
        float: right;
        margin-top: 10%;
        border: none;
        border-radius: 1.5rem;
        padding: 2%;
        background: #0062cc;
        color: #fff;
        font-weight: 600;
        width: 50%;
        cursor: pointer;
    }
</style>
@endsection
@section('footerAssetPush')
    <script type="text/javascript" src="<?=base_url($frontendThemePath.'assets/select2/select2.js')?>"></script>
    <script type="text/javascript" src="<?=base_url($frontendThemePath.'assets/datepicker/datepicker.js')?>"></script>
    <script type="text/javascript">
        $('.select2').select2();
        $('#dob').datepicker({ startView: 2 });
        $(document).on('click', '#close-preview', function(){
            $('.image-preview').popover('hide');
            $('.image-preview').hover(
                function () {
                   $('.image-preview').popover('show');
                   $('.content').css('padding-bottom', '100px');
                },
                 function () {
                   $('.image-preview').popover('hide');
                   $('.content').css('padding-bottom', '20px');
                }
            );
        });
        
        $(function() {
            var closebtn = $('<button/>', {
                type:"button",
                text: 'x',
                id: 'close-preview',
                style: 'font-size: initial;',
            });
            closebtn.attr("class","close pull-right");
            $('.image-preview').popover({
                trigger:'manual',
                html:true,
                title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                content: "There's no image",
                placement:'bottom'
            });
            $('.image-preview-clear').click(function(){
                $('.image-preview').attr("data-content","").popover('hide');
                $('.image-preview-filename').val("");
                $('.image-preview-clear').hide();
                $('.image-preview-input input:file').val("");
                $(".image-preview-input-title").text("Browse");
            });
            $(".image-preview-input input:file").change(function (){
                var img = $('<img/>', {
                    id: 'dynamic',
                    width:250,
                    height:200,
                    overflow:'hidden'
                });
                var file = this.files[0];
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(".image-preview-input-title").text("Browse");
                    $(".image-preview-clear").show();
                    $(".image-preview-filename").val(file.name);
                    img.attr('src', e.target.result);
                    $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                    $('.content').css('padding-bottom', '100px');
                }
                reader.readAsDataURL(file);
            });
        });

        $('.btnRegister').click(function() {
            $('.btnRegister').prop('disabled', true);
            var formData = new FormData($('#admissionForm')[0]);
            $.ajax({
                type: 'POST',
                url: "<?=base_url('fonlineadmission/saveAdmission')?>",
                data: formData,
                async: true,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == false) {
                        $('.btnRegister').prop('disabled', false);
                        $.each(response, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value)
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
                        });
                    } else {
                        if(response.render != '') {
                            $('#mainregisterForm').html(response.render);
                        } else {
                            window.location.reload();
                        }
                    }
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });


        $('#getadmissionresult').click(function(e) {
            e.preventDefault();

            var error = 0
            var admissionID = $('#admissionID').val();
            var phone       = $('#phone').val();

            if(admissionID == '') {
                error++;   
                errorMessage("The Admission ID field are required.");
            } else {
                if(!((Math.floor(admissionID) == admissionID) && $.isNumeric(admissionID))) {
                    error++;
                    errorMessage("The Admission ID field value are invalid.");
                }
            }

            if(phone == '') {
                error++;
                errorMessage("The Phone field are required.");   
            }

            if(error == 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('fonlineadmission/getAdmission')?>",
                    data: {'admissionID':admissionID, 'phone':phone},
                    dataType: "html",
                    success: function(data) {
                        var response = JSON.parse(data);
                        if(response.status == false) {
                            $.each(response, function(index, value) {
                                if(index != 'status') {
                                    toastr["error"](value)
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
                            });
                        } else {
                            if(response.render != '') {
                                $('#mainregisterForm').html(response.render);
                            } else {
                                window.location.reload();
                            }
                        }
                    },
                });
            }

        });


        function errorMessage(message) {
            toastr["error"](message);
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

    </script>

    <?php if ($this->session->flashdata('success')): ?>
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
    <?php endif ?>
    <?php if ($this->session->flashdata('error')): ?>
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
    <?php endif ?>
@endsection



