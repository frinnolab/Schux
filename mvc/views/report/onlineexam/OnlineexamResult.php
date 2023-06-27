<?php if(count($onlineExamUserStatus)) { ?>
<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $onlineExamUserStatusID = count($onlineExamUserStatus) ? $onlineExamUserStatus->onlineExamUserStatus : 0; 
            echo btn_printReport('onlineexamreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('onlineexamreport',  base_url('onlineexamreport/pdf/'.$onlineExamUserStatusID), $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('onlineexamreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('onlineexamreport_report_for')?> <?=$this->lang->line('onlineexamreport_onlineexam')?></h3>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12" style="margin-bottom: 25px;">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>

                <div class="col-sm-6">
                   <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px black solid">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("onlineexamreport_examinformation")?></h3>
                            <ol class="breadcrumb">
                                <li><i class="fa fa-info fa-2x"></i></li>
                            </ol>
                        </div>
                        <div class="box-body">               
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td colspan="2">
                                            <span class="text-blue">
                                                <?=$this->lang->line('onlineexamreport_exam')?> : <?=count($onlineexam) ? $onlineexam->name : ''?>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span class='text-blue'>
                                                <?php
                                                    echo $this->lang->line('onlineexamreport_status'). ' : ';  
                                                    if($onlineExamUserStatus->statusID == 5) {
                                                        echo $this->lang->line('onlineexamreport_passed');
                                                    } else {
                                                        echo $this->lang->line('onlineexamreport_failed');
                                                    }
                                                ?>
                                            </span>
                                        </td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_rank')?> : <?=$rank?></span></td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_question')?> : <?=$onlineExamUserStatus->totalQuestion?></span></td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_answer')?> : <?=$onlineExamUserStatus->totalAnswer?></span></td>
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_current_answer')?> : <?=$onlineExamUserStatus->totalCurrectAnswer?></span></td>  
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_mark')?> : <?=$onlineExamUserStatus->totalMark?></span></td> 
                                    </tr>
                                    <tr>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_totle_obtained_mark')?> : <?=$onlineExamUserStatus->totalObtainedMark?></span></td>
                                        <td><span class='text-blue'><?=$this->lang->line('onlineexamreport_total_percentage')?> : <?=$onlineExamUserStatus->totalPercentage?>%</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="box box-solid " style="border: 1px #ccc solid; border-left: 2px red solid">
                        <div class="box-header bg-gray with-border">
                            <h3 class="box-title text-navy"><?=$this->lang->line("onlineexamreport_studentinformation")?></h3>
                            <ol class="breadcrumb">
                                <li><i class="fa icon-teacher fa-2x"></i></li>
                            </ol>
                        </div>
                        <div class="box-body">
                            <?php if(count($student)) { ?>
                                <section class="panel">
                                    <div class="profile-db-head bg-maroon-light">
                                        <a>
                                            <?=img(imagelink($student->photo))?>
                                        </a>
                                        <h1><?=$student->srname?></h1>
                                    </div>
                                    <table class="table table-hover">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-sitemap text-maroon-light"></i>
                                                </td>
                                                <td><?=$this->lang->line('onlineexamreport_classes')?></td>
                                                <td><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-star text-maroon-light"></i>
                                                </td>
                                                <td><?=$this->lang->line('onlineexamreport_section')?></td>
                                                <td><?=isset($section[$student->srsectionID]) ? $section[$student->srsectionID] : ''?></td>
                                            </tr>
                                            <?php if($onlineexam->subjectID > 0) { ?>
                                                <tr>
                                                    <td>
                                                        <i class="fa fa-sitemap text-maroon-light"></i>
                                                    </td>
                                                    <td><?=$this->lang->line('onlineexamreport_subject')?></td>
                                                    <td><?=count($subject) ? $subject->subject : ''?></td>
                                                </tr>
                                            <?php } ?>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-phone text-maroon-light" ></i>
                                                </td>
                                                <td><?=$this->lang->line('onlineexamreport_phone')?></td>
                                                <td><?=$student->phone?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class="fa fa-envelope text-maroon-light"></i>
                                                </td>
                                                <td><?=$this->lang->line('onlineexamreport_email')?></td>
                                                <td><?=$student->email?></td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <i class=" fa fa-globe text-maroon-light"></i>
                                                </td>
                                                <td><?=$this->lang->line('onlineexamreport_address')?></td>
                                                <td><?=$student->address?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </section>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('onlineexamreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('onlineexamreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('onlineexamreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("onlineexamreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("onlineexamreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("onlineexamreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("onlineexamreport_send")?>" />
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
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('onlineexamreport_hotline')?> : <?=$siteinfos->phone?></center>";
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
            $("#to_error").html("<?=$this->lang->line('onlineexamreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'        : $('#to').val(), 
            'subject'   : $('#subject').val(), 
            'message'   : $('#message').val(),
            'id'        : "<?=count($onlineExamUserStatus) ? $onlineExamUserStatus->onlineExamUserStatus : 0;?>",
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('onlineexamreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('onlineexamreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('onlineexamreport/send_pdf_to_mail')?>",
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

<?php } ?>