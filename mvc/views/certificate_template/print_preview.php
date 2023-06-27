
<!DOCTYPE html>
<html>
<head>
    <title></title>
 
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

        .topHeadingMiddleImg {
            width: 50px;
            height: 50px;
        }

        .topHeadingLeft {
            font-family: 'Prosto One', cursive;
            left: 11%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .topHeadingMiddle {
            font-family: 'Prosto One', cursive;
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .topHeadingRight {
            font-family: 'Prosto One', cursive;
            left: 84%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .mainMiddleTextCenter {
            text-align: center;
        }

        .mainMiddleText {
            font-family: 'Limelight', cursive;
            font-size: 20px;
            padding: 3px 5px;
            width: 100%;
            text-align: center;
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
            border: 1px solid #CCC;
            padding: 50px;
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
            line-height: 25px;
        }

       .testimonialContent .dots {
            display: inline-block;
            height: 20px;
            line-height: 10px;
            position: relative;
            font-weight: 600;
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
            top: 10px;
            width: 100%;
        }

        .testimonialContent .dots.widthcss{
            width: 20%;
        }

        .headSection {
            margin-top: 80px;
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



        .row {
  margin-right: -15px;
  margin-left: -15px;
}
.col-xs-1, .col-sm-1, .col-md-1, .col-lg-1, .col-xs-2, .col-sm-2, .col-md-2, .col-lg-2, .col-xs-3, .col-sm-3, .col-md-3, .col-lg-3, .col-xs-4, .col-sm-4, .col-md-4, .col-lg-4, .col-xs-5, .col-sm-5, .col-md-5, .col-lg-5, .col-xs-6, .col-sm-6, .col-md-6, .col-lg-6, .col-xs-7, .col-sm-7, .col-md-7, .col-lg-7, .col-xs-8, .col-sm-8, .col-md-8, .col-lg-8, .col-xs-9, .col-sm-9, .col-md-9, .col-lg-9, .col-xs-10, .col-sm-10, .col-md-10, .col-lg-10, .col-xs-11, .col-sm-11, .col-md-11, .col-lg-11, .col-xs-12, .col-sm-12, .col-md-12, .col-lg-12 {
  position: relative;
  min-height: 1px;
  padding-right: 15px;
  padding-left: 15px;
}
.col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
  float: left;
}
.col-xs-12 {
  width: 100%;
}
.col-xs-11 {
  width: 91.66666667%;
}
.col-xs-10 {
  width: 83.33333333%;
}
.col-xs-9 {
  width: 75%;
}
.col-xs-8 {
  width: 66.66666667%;
}
.col-xs-7 {
  width: 58.33333333%;
}
.col-xs-6 {
  width: 50%;
}
.col-xs-5 {
  width: 41.66666667%;
}
.col-xs-4 {
  width: 33.33333333%;
}
.col-xs-3 {
  width: 25%;
}
.col-xs-2 {
  width: 16.66666667%;
}
.col-xs-1 {
  width: 8.33333333%;
}
.col-xs-pull-12 {
  right: 100%;
}
.col-xs-pull-11 {
  right: 91.66666667%;
}
.col-xs-pull-10 {
  right: 83.33333333%;
}
.col-xs-pull-9 {
  right: 75%;
}
.col-xs-pull-8 {
  right: 66.66666667%;
}
.col-xs-pull-7 {
  right: 58.33333333%;
}
.col-xs-pull-6 {
  right: 50%;
}
.col-xs-pull-5 {
  right: 41.66666667%;
}
.col-xs-pull-4 {
  right: 33.33333333%;
}
.col-xs-pull-3 {
  right: 25%;
}
.col-xs-pull-2 {
  right: 16.66666667%;
}
.col-xs-pull-1 {
  right: 8.33333333%;
}
.col-xs-pull-0 {
  right: auto;
}
.col-xs-push-12 {
  left: 100%;
}
.col-xs-push-11 {
  left: 91.66666667%;
}
.col-xs-push-10 {
  left: 83.33333333%;
}
.col-xs-push-9 {
  left: 75%;
}
.col-xs-push-8 {
  left: 66.66666667%;
}
.col-xs-push-7 {
  left: 58.33333333%;
}
.col-xs-push-6 {
  left: 50%;
}
.col-xs-push-5 {
  left: 41.66666667%;
}
.col-xs-push-4 {
  left: 33.33333333%;
}
.col-xs-push-3 {
  left: 25%;
}
.col-xs-push-2 {
  left: 16.66666667%;
}
.col-xs-push-1 {
  left: 8.33333333%;
}
.col-xs-push-0 {
  left: auto;
}
.col-xs-offset-12 {
  margin-left: 100%;
}
.col-xs-offset-11 {
  margin-left: 91.66666667%;
}
.col-xs-offset-10 {
  margin-left: 83.33333333%;
}
.col-xs-offset-9 {
  margin-left: 75%;
}
.col-xs-offset-8 {
  margin-left: 66.66666667%;
}
.col-xs-offset-7 {
  margin-left: 58.33333333%;
}
.col-xs-offset-6 {
  margin-left: 50%;
}
.col-xs-offset-5 {
  margin-left: 41.66666667%;
}
.col-xs-offset-4 {
  margin-left: 33.33333333%;
}
.col-xs-offset-3 {
  margin-left: 25%;
}
.col-xs-offset-2 {
  margin-left: 16.66666667%;
}
.col-xs-offset-1 {
  margin-left: 8.33333333%;
}
.col-xs-offset-0 {
  margin-left: 0;
}


.panel {
  margin-bottom: 20px;
  background-color: #fff;
  border: 1px solid transparent;
  border-radius: 4px;
  -webkit-box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
          box-shadow: 0 1px 1px rgba(0, 0, 0, .05);
}
.panel-body {
  padding: 15px;
}



@font-face {
  font-family: 'Great Vibes';
  font-style: normal;
  font-weight: 400;
  src: local('Great Vibes'), local('GreatVibes-Regular'), url(https://fonts.gstatic.com/s/greatvibes/v4/6q1c0ofG6NKsEhAc2eh-3SYE0-AqJ3nfInTTiDXDjU4.woff2) format('woff2');

}
/* latin */
@font-face {
  font-family: 'Great Vibes';
  font-style: normal;
  font-weight: 400;
  src: local('Great Vibes'), local('GreatVibes-Regular'), url(https://fonts.gstatic.com/s/greatvibes/v4/6q1c0ofG6NKsEhAc2eh-3Y4P5ICox8Kq3LLUNMylGO4.woff2) format('woff2');

}



@font-face {
  font-family: 'Allerta Stencil';
  font-style: normal;
  font-weight: 400;
  src: local('Allerta Stencil Regular'), local('AllertaStencil-Regular'), url(https://fonts.gstatic.com/s/allertastencil/v7/CdSZfRtHbQrBohqmzSdDYHyjZGU_SYMIAZWjSGDHnGA.woff2) format('woff2');

}


@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGed8PZoqYNvuaEtxMzhnQk8SbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');

}
/* cyrillic */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGb36aABA35U5KbBGmAqRMh0SbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');
}
/* greek-ext */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGTdJ-uZdA0sUHfhF3lCH6_oSbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');

}
/* greek */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGaysm4-mRWEBmjlBOiZJoSUSbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');

}
/* vietnamese */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGaCRTYQsFMQlqItlwK_ntW0SbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');
}
/* latin-ext */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGQCHOeWCQWG0jW-ep4kIJ70SbZyiE6aTiPyL3F1wza7H.woff2) format('woff2');
}
/* latin */
@font-face {
  font-family: 'Fira Sans Extra Condensed';
  font-style: normal;
  font-weight: 400;
  src: local('Fira Sans Extra Condensed Regular'), local('FiraSansExtraCondensed-Regular'), url(https://fonts.gstatic.com/s/firasansextracondensed/v1/wg_5XrW_o1_ZfuCbAkBfGVRjX9Jlut_-eN40c1mQErxbV0WvE1cEyAoIq5yYZlSc.woff2) format('woff2');

}


@font-face {
  font-family: 'Limelight';
  font-style: normal;
  font-weight: 400;
  src: local('Limelight'), url(https://fonts.gstatic.com/s/limelight/v7/jVTBRAYIWabRXjx2ji3VChJtnKITppOI_IvcXXDNrsc.woff2) format('woff2');
}
/* latin */
@font-face {
  font-family: 'Limelight';
  font-style: normal;
  font-weight: 400;
  src: local('Limelight'), url(https://fonts.gstatic.com/s/limelight/v7/kD_2YDkzv1rorNqQ2oFK5ltXRa8TVwTICgirnJhmVJw.woff2) format('woff2');

}


@font-face {
  font-family: 'Michroma';
  font-style: normal;
  font-weight: 400;
  src: local('Michroma'), url(https://fonts.gstatic.com/s/michroma/v7/-4P7knfa8IRSEOi-sKrsivesZW2xOQ-xsNqO47m55DA.woff2) format('woff2');

}


@font-face {
  font-family: 'Prosto One';
  font-style: normal;
  font-weight: 400;
  src: local('Prosto One'), local('ProstoOne-Regular'), url(https://fonts.gstatic.com/s/prostoone/v5/g_U08WmVcCfOwPUBpEKV5CEAvth_LlrfE80CYdSH47w.woff2) format('woff2');
}
/* latin-ext */
@font-face {
  font-family: 'Prosto One';
  font-style: normal;
  font-weight: 400;
  src: local('Prosto One'), local('ProstoOne-Regular'), url(https://fonts.gstatic.com/s/prostoone/v5/mTFYjVXEgUAP8V1WIJc9cCEAvth_LlrfE80CYdSH47w.woff2) format('woff2');
}
/* latin */
@font-face {
  font-family: 'Prosto One';
  font-style: normal;
  font-weight: 400;
  src: local('Prosto One'), local('ProstoOne-Regular'), url(https://fonts.gstatic.com/s/prostoone/v5/nr9AbvIL_iERRXbqcIK3-_k_vArhqVIZ0nv9q090hN8.woff2) format('woff2');

}



    </style>

</head>
<body>

    <section class="panel">
        <div class="panel-body bio-graph-info">
            <div id="printablediv" class="box-body">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="testimonial">
                            <div class="mainTestimonial">
                                <h2 style="text-align: center;font-family: 'Allerta Stencil', sans-serif;text-transform: uppercase;"><?=$certificate_template->top_heading_title?></h2>

                               <div class="row">
                                    <span class="topHeadingLeft"><?=$certificate_template->top_heading_middle?></span> 

                                    <span class="topHeadingMiddle"><img class="topHeadingMiddleImg" src="<?=base_url('uploads/images/'.$siteinfos->photo)?>"></span> 

                                    <span class="topHeadingRight"><?=$certificate_template->top_heading_right?></span> 
                               </div>
                            </div>


                            <div class="headSection">
                                <div class="row" >
                                    <div class="col-sm-12 mainMiddleTextCenter"><span class="mainMiddleText"><?=$certificate_template->main_middle_text?></span></div>
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


</body>
</html>



