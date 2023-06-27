<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?php if(permissionChecker('certificate_template_edit')) { echo btn_sm_edit('certificate_template/edit/'.$certificate_template->certificate_templateID, $this->lang->line('edit')); }
            ?>
        </div>

        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("certificate_template/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                <li class="active"><?=$this->lang->line('menu_view')?></li>
            </ol>
        </div>
    </div>
</div>

<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">
            <link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet"> 
            <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet"> 
            <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Extra+Condensed" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css?family=Limelight" rel="stylesheet">  
            <link href="https://fonts.googleapis.com/css?family=Michroma" rel="stylesheet"> 
            <link href="https://fonts.googleapis.com/css?family=Prosto+One" rel="stylesheet"> 

            <style type="text/css">
                .mainTestimonial {
                    position: relative;
                }

                .testimonialHead {
                    left: 50%;
                    position: absolute;
                    top: 50%;
                    transform: translate(-50%, 80%);
                    font-size: 18px;
                    padding: 3px 5px;
                }

                .topHeadingLeft {
                    left: 9%;
                    position: absolute;
                    top: 50%;
                    transform: translate(-50%, 50%);
                    font-size: 18px;
                    padding: 3px 5px;
                }

                .topHeadingMiddle {
                    font-family: 'Prosto One', cursive;
                    left: 50%;
                    position: absolute;
                    top: 50%;
                    transform: translate(-50%, 50%);
                    font-size: 22px;
                    padding: 3px 5px;
                }

                .topHeadingRight {
                    left: 84%;
                    position: absolute;
                    top: 50%;
                    transform: translate(-50%, 50%);
                    font-size: 18px;
                    padding: 3px 5px;
                }

                .mainMiddleTextCenter {
                    text-align: center;
                }

                .mainMiddleText {
                    font-family: 'Limelight', cursive;
                    font-size: 18px;
                    border:1px solid #34495e;
                    padding: 3px 5px;
                    box-shadow: 3px 3px 2px 1px #34495e;
                    color: #333;

                }

                .top_heading_title {
                    text-align: center;
                    font-family: 'Allerta Stencil', sans-serif;
                    text-transform: uppercase;
                }

                .testimonial {
                    min-height: 550px;
                    margin-left: auto;
                    margin-right: auto;
                    padding: 100px;
                    background: url("<?=base_url('uploads/images/'.$certificate_template->background_image)?>") no-repeat ;
                    background-size: 100% 100%;
                }

                .slno span {
                    font-size: 16px;
                    font-weight: 600;
                }

                .testimonialInfo {
                    margin-top: 30px; 
                }

                .testimonialContent {
                    font-family: 'Great Vibes', cursive;
                    font-size: 24px;
                    letter-spacing: 1px;
                    line-height: 24px;
                    letter-spacing: 4px;
                }

               .testimonialContent .dots {
                    display: inline-block;
                    height: 20px;
                    line-height: 10px;
                    position: relative;
                }

                .testimonialContent .dots::after {
                    border-bottom: 2px dotted #999;
                    bottom: 0;
                    content: "";
                    height: 0;
                    left: 0;
                    position: absolute;
                    width: 100%;
                }

                .testimonialContent .dots::before {
                    content: attr(data-hover);
                    font-style: italic;
                    height: 5px;
                    left: 0;
                    position: absolute;
                    text-align: center;
                    top: 9px;
                    width: 100%;
                }

                .testimonialContent .dots.widthcss{
                    width: 20%;
                }

                .headSection {
                    margin-top: 30px;
                }

                .footerSection {
                    margin-top: 30px;
                }

                .footer_left_text {
                    font-family: 'Prosto One', cursive;
                    font-size: 14px;

                }

                .footer_middle_text {
                    font-family: 'Prosto One', cursive;
                    font-size: 14px;
                    text-align: center;
                }

                .footer_right_text {
                    font-family: 'Prosto One', cursive;
                    font-size: 14px;
                    text-align: center;
                }
            </style>


        
            <div class="row">
                <div class="col-sm-12">
                    <div class="testimonial">
                        <div class="mainTestimonial">
                            <h2 id="demo1" class="top_heading_title"><?=$certificate_template->top_heading_title?></h2>
                            <div class="row">
                                <span class="topHeadingMiddle"><?=$certificate_template->top_heading_middle?></span>       
                            </div>
                        </div>

                        <div class="headSection">
                            <div class="row" >
                                <div class="col-sm-4 col-sm-offset-4 mainMiddleTextCenter"><span class="mainMiddleText"><?=$certificate_template->main_middle_text?></span></div>
                            </div>
                        </div>

                        <div class="testimonialInfo">
                            <p class="testimonialContent">
                                <?=$template_convert?>
                            </p>
                        </div>

                        <div class="footerSection">
                            <div class="row" >
                                <div class="col-sm-4 footer_left_text"><?=$certificate_template->footer_left_text?></div>
                                <div class="col-sm-4 footer_middle_text"></div>
                                <div class="col-sm-4 footer_right_text"><?=$certificate_template->footer_right_text?></div>
                            </div>
                        </div>
                    </div>
                        
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
    var demo6 = new CircleType(document.getElementById('demo1')).radius(340);

    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
        
        closeWindow();
    }

    function closeWindow() {
        location.reload();
    }
</script>