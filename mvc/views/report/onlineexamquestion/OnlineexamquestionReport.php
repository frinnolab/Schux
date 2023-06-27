<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            echo btn_printReport('onlineexamquestionreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('onlineexamquestionreport',  base_url('onlineexamquestionreport/pdf/'.$onlineExamID.'/'.$typeID), $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('onlineexamquestionreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
   
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('onlineexamquestionreport_report_for')?> - <?=$this->lang->line('onlineexamquestionreport_examquestion')?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div id="printablediv">
    <?php if($typeID == 1) { ?>
        <style type="text/css">

            .clearfix {
                margin-bottom: 5px;
            }
            
            .question-body{
                font-size: 16px;
                font-weight: bold;
            }

            .question-body label { 
                font-size: 16px;
            }

            .question-body label h2 { 
                font-size: 16px;
                display: inline-block;
            }

            .question-answer {
                margin-top: 0px;
            }

            .table tr td {
                width: 50%;
            }

            .question-body .lb-mark {
                float: right;
                font-size: 16px;
                text-align: right;
            }

            .questionimg {
                width: 40% !important;
                padding-left: 10px;
                padding-top: 5px;
                height: 120px;
            }

            .headerInfo {
                margin-bottom: 5px;
            }

            .single_label {
                display: inline-block;
            }

            .singleFilup {
                display: inline-block;
                border-bottom: 1px solid #ddd;
                width: 50%;
            }

            @media print {
                .headerInfo {
                    margin-bottom: 30px;
                }
            }
        </style>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12" style="margin-bottom: 25px;">
                    <?=reportheader($siteinfos,$schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12 headerInfo">
                    <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
                    <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_marks')?> : <?=$getMark?></h4>
                </div>
                <div class="col-sm-12">
                    <?php 
                        if(count($questions)) {
                        $i = 0;
                        foreach($questions as $question) {
                            $optionCount = $question->totalOption;
                            $i++; ?>
                            <div class="clearfix">
                                <div class="question-body">
                                    <label><b><?=$i?>.</b> <?=$question->question?></label>
                                    <label class="lb-mark"> 
                                        <?=$question->mark != "" ? $question->mark.' '.$this->lang->line('onlineexamquestionreport_mark') : ''?>
                                    </label>
                                </div>

                                <?php if($question->upload != '') { ?>
                                    <div>
                                        <img style="width:250px;height:150px;padding-left: 20px" src="<?=base_url('uploads/images/'.$question->upload)?>" alt="">
                                    </div>
                                <?php } ?>

                                <div class="question-answer">
                                    <table class="table">
                                        <tr>
                                        <?php
                                            $oc = 1;
                                            $tdCount = 0;
                                            $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                                            if(count($questionoptions)) {
                                                $optionLabel = 'A';
                                                foreach ($questionoptions as $option) {
                                                    if($optionCount >= $oc) { $oc++; ?>
                                                    <td>
                                                        <span><?=$optionLabel?>.</span>
                                                        <span><?=$option->name?></span>
                                                        <label for="option<?=$option->optionID?>">
                                                            <?php
                                                                if(!is_null($option->img) && $option->img != "") { ?>
                                                                    <img class="questionimg" src="<?=base_url('uploads/images/'.$option->img)?>"/>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </label>
                                                    </td>
                                                    <?php
                                                        $optionLabel++;
                                                    }
                                                    $tdCount++;
                                                    if($tdCount == 2) {
                                                        $tdCount = 0;
                                                        echo "</tr><tr>";
                                                    }
                                                }
                                            }
                                        ?>
                                        </tr>
                                    </table>
                                </div>

                            </div>
                    <?php } } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12 text-center footerAll" style="margin-bottom: 25px;">
                    <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    <?php } elseif($typeID == 2) { ?>
        <style type="text/css">

            .clearfix {
                margin-bottom: 2px;
            }
            
            .question-body{
                font-size: 14px;
                display: inline-block;
            }

            .question-answer {
                margin-top: 0px;
                display: inline-block;
                font-weight: normal;
                font-size: 14px;
                width: auto;
                padding-left: 10px
            }

            .question-answer span {
                border: 1px solid #ddd;
                height: 20px !important;
                width: 20px !important;
                overflow: hidden;
                display: inline-block;
                vertical-align: middle;
                text-align: center;
                padding: 0px;
                border-radius: 10px;
                font-size: 12px;
            }

            .table tr td {
                width: 50%;
            }

            .question-body .lb-mark {
                float: right;
                font-size: 14px;
                text-align: right;
            }

            .questionimg {
                width: 40% !important;
                padding-left: 10px;
                padding-top: 5px;
            }

            .headerInfo {
                margin-bottom: 5px;
            }

            .single_label {
                display: inline-block;
            }

            .singleFilup {
                display: inline-block;
                border-bottom: 1px solid #ddd;
                width: 75%;
            }

            .singleFil {
                display: inline-block;
                width: 84px;
            }

            .singleFil .single_label {
                display: inline-block;
            }

            .singleFil .singleFilup {
                display: inline-block;
            }

            .leftQuestion {
                float: left;
                width: 50%;
                display: block;
            }

            .rightQuestion {
                float: right;
                width: 50%;
            }

            .userInfo {
                margin-bottom: 10px;
            }

            .singleUser {
                float: left;
                width: 50%;
                font-size: 14px;
            }

            .userLabel {
                float: left;
                width: 70px;
            }

            .userValue {
                float: left;
                width: 80%;
                border-bottom: 1px solid #ddd
            }

            @media print {
                .headerInfo {
                    margin-bottom: 30px;
                }

                .col-sm-6 {
                    width: 50% !important;
                    float: left;
                }

                .userValue {
                    margin-bottom: 15px !important;
                }

                .userInfo {
                    width: 100%;
                    overflow: hidden;
                }

                .fullWidth {
                    width: 100%;
                }

            }
        </style>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12" style="margin-bottom: 25px;">
                    <?=reportheader($siteinfos,$schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12 headerInfo">
                    <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
                    <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_duration')?> : <?=($exam->duration) ? $exam->duration." ".$this->lang->line('onlineexamquestionreport_minute') : 'N/A'?></h4>
                </div>
                <?php if(count($questions)) { ?>
                <div class="col-sm-12 userInfo">
                    <div class="singleUser">
                        <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_name')?></div>
                        <div class="userValue">: </div>
                    </div>
                    <div class="singleUser">
                        <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_class')?></div>
                        <div class="userValue">: </div>
                    </div>
                    <div class="singleUser">
                        <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_roll')?></div>
                        <div class="userValue">: </div>
                    </div>
                    <div class="singleUser">
                        <div class="userLabel"><?=$this->lang->line('onlineexamquestionreport_subject')?></div>
                        <div class="userValue">: <?=isset($subjects[$exam->subjectID]) ? $subjects[$exam->subjectID] : '' ?></div>
                    </div>
                </div>
                <?php } ?>
                <div class="col-sm-12 fullWidth">
                    <div class="row">
                        <?php 
                        if(count($questions)) {
                            $f = false;
                            $i = 0;
                            $j = 0; 
                            echo '<div class="col-sm-6">';
                            foreach($questions as $question) { 
                                if(($j != 0) && (($j%25) == 0)) {
                                    $f = true;
                                }

                                if($f) {
                                    echo '</div>';
                                    echo '<div class="col-sm-6">';
                                    $f = false;
                                }

                                $optionCount = $question->totalOption;
                                $i++; $j++; ?>
                                <div class="clearfix">
                                    <div class="question-body">
                                        <label><b><?=$i?>.</b></label>
                                    </div>
                                    <div class="question-answer">
                                            <?php
                                                $oc = 1;
                                                $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                                                if(count($questionoptions)) {
                                                    $optionLabel = 'A';
                                                    foreach ($questionoptions as $option) {
                                                        if($optionCount >= $oc) { $oc++; ?>
                                                            <span><?=$optionLabel?></span>
                                                        <?php
                                                            $optionLabel++;
                                                        }
                                                    }
                                                } else {
                                                    $optionLabel = 'A';
                                                    if($question->typeNumber == 3) {
                                                        if($optionCount > 0) {
                                                            for ($k=1; $k <= $optionCount; $k++) { ?>
                                                            <div class="singleFil">
                                                                <div class="single_label"><?=$optionLabel?>.</div>
                                                                <div class="singleFilup">&nbsp;</div>
                                                            </div>
                                                            <?php
                                                            $optionLabel++;
                                                            }
                                                        }
                                                    }
                                                }
                                            ?>
                                    </div>
                                </div>
                                <?php
                            }
                            echo '</div>';
                        } else { ?>
                        <div class="col-sm-12">
                            <div class="callout callout-danger">
                                <p><b class="text-info"><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></b></p>
                            </div>
                        </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 text-center footerAll" style="margin-bottom: 25px;">
                    <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    <?php } elseif($typeID == 3) { ?>
        <style type="text/css">
            .clearfix {
                margin-bottom: 5px;
            }
            
            .question-body{
                font-size: 14px;
                display: inline-block;
            }

            .question-answer {
                margin-top: 0px;
                display: inline-block;
                font-weight: normal;
                font-size: 14px;
                width: auto;
                padding-left: 10px
            }

            .question-answer span {
                border: 1px solid #ddd;
                height: 20px !important;
                width: 20px !important;
                overflow: hidden;
                display: inline-block;
                vertical-align: middle;
                text-align: center;
                padding: 0px;
                border-radius: 10px;
                font-size: 12px;
            }

            .table tr td {
                width: 50%;
            }

            .question-body .lb-mark {
                float: right;
                font-size: 14px;
                text-align: right;
            }

            .questionimg {
                width: 40% !important;
                padding-left: 10px;
                padding-top: 5px;
            }

            .headerInfo {
                margin-bottom: 5px;
            }

            .single_label {
                display: inline-block;
            }

            .singleFilup {
                display: inline-block;
                border-bottom: 1px solid #ddd;
                width: 75%;
            }

            .singleFil {
                display: inline-block;
                width: 84px;
            }

            .singleFil .single_label {
                display: inline-block;
            }

            .singleFil .singleFilup {
                display: inline-block;
            }

            .leftQuestion {
                float: left;
                width: 50%;
                display: block;
            }

            .rightQuestion {
                float: right;
                width: 50%;
            }

            .checked {
                background: green;
                color: #fff;
            }

            @media print {
                .headerInfo {
                    margin-bottom: 30px;
                }

                .checked {
                    background: green !important;
                    color: #fff !important;
                }

                .col-sm-6 {
                    width: 50% !important;
                    float: left;
                }


            }
        </style>
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12" style="margin-bottom: 25px;">
                    <?=reportheader($siteinfos,$schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12 headerInfo">
                    <h4 class="pull-left text-bold"><?=$this->lang->line('onlineexamquestionreport_exam')?> : <?=$exam->name?></h4>
                    <h4 class="pull-right text-bold"><?=$this->lang->line('onlineexamquestionreport_duration')?> : <?=($exam->duration) ? $exam->duration." ".$this->lang->line('onlineexamquestionreport_minute') : 'N/A'?></h4>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <?php 
                        if(count($questions)) {
                            $f = false;
                            $i = 0;
                            $j = 0; 
                            echo '<div class="col-sm-6">';
                            foreach($questions as $question) {
                                    if(($j != 0) && (($j%25) == 0)) {
                                        $f = true;
                                    }

                                    if($f) {
                                        echo '</div>';
                                        echo '<div class="col-sm-6">';
                                        $f = false;
                                    }

                                    $optionCount = $question->totalOption;

                                    $questionAnswers = isset($answers[$question->questionBankID]) ? $answers[$question->questionBankID] : [];
                                    if($question->typeNumber == 1 || $question->typeNumber == 2) {
                                        $questionAnswers = pluck($questionAnswers, 'optionID');
                                    }

                                    $i++;
                                    $j++;
                                    ?>
                                    <div class="clearfix">
                                        <div class="question-body">
                                            <label><b><?=$i?>.</b></label>
                                        </div>
                                        <div class="question-answer">
                                                <?php
                                                    $oc = 1;
                                                    $questionoptions = isset($question_options[$question->questionBankID]) ? $question_options[$question->questionBankID] : [];
                                                    if(count($questionoptions)) {
                                                        $optionLabel = 'A';
                                                        foreach ($questionoptions as $option) {
                                                            $ansClass = '';
                                                            if(in_array($option->optionID, $questionAnswers)) {
                                                                $ansClass = 'checked';
                                                            }

                                                            if($optionCount >= $oc) { $oc++; ?>
                                                                <span class="<?=$ansClass?>"><?=$optionLabel?></span>
                                                            <?php
                                                                $optionLabel++;
                                                            }
                                                        }
                                                    } else {
                                                        $optionLabel = 'A';
                                                        if($question->typeNumber == 3) {
                                                            if($optionCount > 0) {
                                                                for ($k=0; $k < $optionCount; $k++) { ?>
                                                                <div class="singleFil">
                                                                    <div class="single_label"><?=$optionLabel?>.</div>
                                                                    <div class="singleFilup"><?=$questionAnswers[$k]->text?></div>
                                                                </div>
                                                                <?php
                                                                $optionLabel++;
                                                                }
                                                            }
                                                        }
                                                    }
                                                ?>
                                        </div>
                                    </div>
                                    <?php 
                                }
                            echo '</div>';
                        } else { ?>
                            <div class="col-sm-12">
                            <div class="callout callout-danger">
                                <p><b class="text-info"><?=$this->lang->line('onlineexamquestionreport_data_not_found')?></b></p>
                            </div>
                        </div>
                        <?php }
                        ?>
                    </div>
                </div>
                <div class="col-sm-12 text-center footerAll" style="margin-bottom: 25px;">
                    <?=reportfooter($siteinfos,$schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    <?php } ?>
    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('onlineexamreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('onlineexamquestionreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('onlineexamquestionreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("onlineexamquestionreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("onlineexamquestionreport_subject")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php
                    if(form_error('message'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("onlineexamquestionreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("onlineexamquestionreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here --> 

<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('onlineexamquestionreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('onlineexamquestionreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'          : $('#to').val(), 
            'subject'     : $('#subject').val(), 
            'message'     : $('#message').val(),
            'onlineExamID': "<?=$onlineExamID?>",
            'typeID'      : "<?=$typeID?>",
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");
        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('onlineexamquestionreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('onlineexamquestionreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('onlineexamquestionreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
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
                        location.reload();
                    }
                }
            });
        }
    });
</script>
