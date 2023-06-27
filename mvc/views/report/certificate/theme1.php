


<!DOCTYPE html>
<html>
<head>
	<title><?=$this->lang->line('report_certificate')?></title>
	<script type="text/javascript" src="<?php echo base_url('assets/inilabs/jquery.min.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/inilabs/moment.min.js'); ?>"></script>
    <link rel="SHORTCUT ICON" href="<?=base_url("uploads/images/$siteinfos->photo")?>" />
	<link href="https://fonts.googleapis.com/css?family=Great+Vibes" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Allerta+Stencil" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Fira+Sans+Extra+Condensed" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Limelight" rel="stylesheet">  
    <link href="https://fonts.googleapis.com/css?family=Michroma" rel="stylesheet"> 
    <link href="https://fonts.googleapis.com/css?family=Prosto+One" rel="stylesheet"> 
    <link href="<?=base_url('assets/bootstrap/bootstrap.min.css')?>" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url('assets/inilabs/combined.css'); ?>" >

    <?php
        if(isset($headerassets)) {
            foreach ($headerassets as $assetstype => $headerasset) {
                if($assetstype == 'css') {
                  if(count($headerasset)) {
                    foreach ($headerasset as $keycss => $css) {
                      echo '<link rel="stylesheet" href="'.base_url($css).'">'."\n";
                    }
                  }
                } elseif($assetstype == 'js') {
                  if(count($headerasset)) {
                    foreach ($headerasset as $keyjs => $js) {
                      echo '<script type="text/javascript" src="'.base_url($js).'"></script>'."\n";
                    }
                  }
                }
            }
        }
    ?>

    <style type="text/css"> 

        @page {
            margin: 0.1cm;
            size: landscape;
            orientation: landscape;
            size: A4;
        }

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
            padding: 10%;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            background: url("<?='../../../../../uploads/images/'.$certificate_template->background_image?>") no-repeat !important;
            background-size: 100% 100% !important;
            -webkit-print-color-adjust: exact;
        }

        @media print {
            .testimonial {
                min-height: 550px;
                margin-left: auto;
                margin-right: auto;
                padding: 10%;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                background: url("<?='../../../../../uploads/images/'.$certificate_template->background_image?>") no-repeat !important;
                background-size: 100% 100% !important;
                -webkit-print-color-adjust: exact;
            }
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

        .btn_border {
            border: 1px solid #ddd;
            padding: 6px;
            display: inline-block;
            border-radius: 10px;
            background: #E9E9E9
        }
        .row{margin-left: 0px;margin-right: 0px}
        .col-sm-12{padding-left: 0px;padding-right: 0px}

    </style>
</head>
<body>
<img src="">
<div class="row">
    <div class="col-sm-12 text-center" style="margin:10px 0px;">
        <div class="btn_border">
            <?php
                echo btn_flat_printReport('student', $this->lang->line('report_print'), 'printablediv');
            ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div id="printablediv">
            <div class="testimonial">
                <div class="mainTestimonial">
                    <h2 id="demo1" class="top_heading_title"><?=$top_heading_title?></h2>
                    
                   <div class="row">
                        <span class="topHeadingMiddle"><?=$top_heading_middle?></span>       
                   </div>
                </div>

                <div class="headSection">
                    <div class="row" >
                        <div class="col-sm-4 col-sm-offset-4 mainMiddleTextCenter"><span class="mainMiddleText"><?=$main_middle_text?></span></div>
                    </div>
                </div>

                <div class="testimonialInfo">
                    <p class="testimonialContent">
                        <?=$template?>
                    </p>
                </div>

                <div class="footerSection">
                    <div class="row" >
                        <div class="col-sm-4 footer_left_text"><?=$footer_left_text?></div>
                        <div class="col-sm-4 footer_middle_text"></div>
                        <div class="col-sm-4 footer_right_text"><?=$footer_right_text?></div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript">
            var demo6 = new CircleType(document.getElementById('demo1')).radius(340);
        </script>

    </div>
</div>

<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = divElements;

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }

    function closeWindow() {
        location.reload();
    }
</script>
</body>
</html>