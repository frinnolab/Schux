<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('marksheetreport/pdf/'.$examID.'/'.$classesID.'/'.$sectionID);
            echo btn_printReport('marksheetreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('marksheetreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('marksheetreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('marksheetreport_report_for')?> - <?=$this->lang->line('marksheetreport_marksheet');?></h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <style type="text/css">
        .marksheetreult {
            padding-top: 8px !important;
        }

        .marksheetreult span {
            width:80px;
            display: inline-block;
            line-height: 24px;
        }

        .examName {
            text-align: center;
            margin : 0px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 15px;
        }

    </style>
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="mainmarksheetreport">
                    <div class="col-sm-12">
                        <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                    </div>
                    <?php if($classesID > 0) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left"><?=$this->lang->line('marksheetreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>                         
                        <h5 class="pull-right"><?=$this->lang->line('marksheetreport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('marksheetreport_all_section')?></h5>                        
                    </div>
                    <br/>
                    <?php } ?>
                    <div class="col-sm-12">
                        <div class="marksheetreult">
                            <?php if(count($studentGrades)) { 
                                foreach($studentGrades as $studentRoll => $studentPoint) { ?>
                                    <span><?=$studentRoll?> [<?=$studentPoint?>] , </span>
                            <?php } } else { ?>
                            <div class="callout callout-danger">
                                <p><b class="text-info"><?=$this->lang->line('marksheetreport_data_not_found')?></b></p>
                            </div>
                        <?php } ?>
                        </div>
                    </div>
                    <div class="col-sm-12 text-center report-footer footerAll">
                        <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                    </div>
                </div>  
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('marksheetreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('marksheetreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('marksheetreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("marksheetreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("marksheetreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("marksheetreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("marksheetreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('marksheetreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'examID'     : '<?=$examID?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('marksheetreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('marksheetreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('marksheetreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('marksheetreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        }

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('marksheetreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
                        }
                        
                        if(response.message) {
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>