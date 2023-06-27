<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('onlineadmissionreport/pdf/'.$schoolyearID.'/'.$classesID.'/'.$status.'/'.$phone.'/'.$admissionID);
            echo btn_printReport('onlineadmissionreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('onlineadmissionreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('onlineadmissionreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('onlineadmissionreport_report_for')?> - <?=$this->lang->line('onlineadmissionreport_onlineadmission')?> 
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">
            @media only screen and (max-width: 600px) {
                h5.singlelabel {
                    text-align: left;
                }
            }
        </style>
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="reportPage-header">
                        <span class="header" id="headerImage"><p class="bannerLogo"><img src="<?=base_url('uploads/images/'.$siteinfos->photo)?>"></p></span>
                        <p class="title"><?=$siteinfos->sname?></p>
                        <p class="title-desc"><?=$siteinfos->address?></p>
                        <p class="title-desc"><?=$this->lang->line('topbar_academic_year'). ' : '. $schoolyearName?></p>
                    </div> 
                </div>

                <div class="col-sm-12">
                    <div class="row">
                    <?php if(((int)$admissionID) && ($admissionID > 0)) { ?>
                        <div class="col-sm-12">
                            <h5 class="pull-left"><?=$this->lang->line('onlineadmissionreport_admissionID')?> : <?=$admissionID?></h5>   
                        </div>
                    <?php } elseif(((int)$schoolyearID) && ($schoolyearID > 0)) { ?>
                            <div class="col-sm-4 text-left">
                                <h5 class="singlelabel"><?=$this->lang->line('onlineadmissionreport_academicyear')?> : <?=$schoolyearName?></h5>
                            </div>
                            <div class="col-sm-4 text-center">
                                <?php $f = FALSE; if((int)$classesID && $status != '10') { $f = TRUE;?>
                                    <h5 class="singlelabel"><?=$this->lang->line('onlineadmissionreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>
                                <?php } else {
                                    echo "<h5></h5>";
                                } ?>
                            </div>
                            <div class="col-sm-4 text-right">
                                <?php if($f && ($status !='10')) { ?>
                                    <h5 class="singlelabel"><?=$this->lang->line('onlineadmissionreport_status')?> : <?=isset($checkstatus[$status]) ? $checkstatus[$status] : ''?></h5>
                                <?php } elseif((int)$classesID) { ?>
                                    <h5 class="singlelabel"><?=$this->lang->line('onlineadmissionreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></h5>
                                <?php } elseif(((int)$status || $status == 0)) { ?>
                                    <h5 class="singlelabel"><?=$this->lang->line('onlineadmissionreport_status')?> : <?=isset($checkstatus[$status]) ? $checkstatus[$status] : ''?></h5>
                                <?php } else {
                                    echo "<h5></h5>";
                                } ?>
                            </div>
                    <?php } ?>
                    </div>
                </div>
                <div class="col-sm-12">
                    <?php if(count($onlineadmissions)) { ?>
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th> 
                                        <th><?=$this->lang->line('onlineadmissionreport_photo')?></th>
                                        <th><?=$this->lang->line('onlineadmissionreport_name')?></th>
                                        <?php if($admissionID == 0) { ?>
                                            <th><?=$this->lang->line('onlineadmissionreport_admissionID')?></th>
                                        <?php } ?>
                                        <?php if(!(int)$classesID) { ?>
                                            <th><?=$this->lang->line('onlineadmissionreport_class')?></th>
                                        <?php } ?>
                                        <th><?=$this->lang->line('onlineadmissionreport_gender')?></th>
                                        <?php if($phone == 1) { ?>
                                            <th><?=$this->lang->line('onlineadmissionreport_phone')?></th>
                                        <?php }?>
                                        <?php if($status == 10) { ?>
                                            <th><?=$this->lang->line('onlineadmissionreport_status')?></th>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $j = 0; foreach($onlineadmissions as $onlineadmission) { $j++; ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$j?></td>
                                            <td data-title="<?=$this->lang->line('onlineadmissionreport_photo')?>"><?=profileimage($onlineadmission->photo)?></td>
                                            <td data-title="<?=$this->lang->line('onlineadmissionreport_name')?>"><?=$onlineadmission->name?></td>
                                            <?php if($admissionID == 0) { ?>
                                                <td data-title="<?=$this->lang->line('onlineadmissionreport_admissionID')?>">
                                                    <?php 
                                                        $admissionIDlen = strlen($onlineadmission->onlineadmissionID);
                                                        $boxLimit = 8;

                                                        if($admissionIDlen >= $boxLimit) {
                                                            $boxLimit += 2;
                                                        }

                                                        $zerolength = ($boxLimit - $admissionIDlen);
                                                        if($zerolength > 0) {
                                                            for($i=1; $i <= $zerolength; $i++) {
                                                                echo 0;
                                                            }
                                                        }
                                                        $admissionIDArray = str_split($onlineadmission->onlineadmissionID);
                                                        if(count($admissionIDArray)) {
                                                            foreach ($admissionIDArray as $value) {
                                                                echo $value;
                                                            }
                                                        }
                                                    ?>
                                                </td>
                                            <?php } ?>

                                            <?php if(!(int)$classesID) { ?>
                                                <td data-title="<?=$this->lang->line('onlineadmissionreport_class')?>">
                                                    <?=isset($classes[$onlineadmission->classesID]) ? $classes[$onlineadmission->classesID] : ''?>
                                                </td>
                                            <?php } ?>
                                            <td data-title="<?=$this->lang->line('onlineadmissionreport_gender')?>"><?=$onlineadmission->sex?></td> 
                                            <?php if($phone == 1) { ?>
                                                <td data-title="<?=$this->lang->line('onlineadmissionreport_phone')?>"><?=$onlineadmission->phone?></td>
                                            <?php } ?>
                                            <?php if($status == 10) { ?>
                                                <td data-title="<?=$this->lang->line('onlineadmissionreport_status')?>"><?=isset($checkstatus[$onlineadmission->status]) ? $checkstatus[$onlineadmission->status] : ''?>
                                                </td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                        <div class="callout callout-danger" style="margin-top: 8px">
                            <p><b class="text-info"><?=$this->lang->line('onlineadmissionreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
                
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('onlineadmissionreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('onlineadmissionreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('onlineadmissionreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("onlineadmissionreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("onlineadmissionreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("onlineadmissionreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("onlineadmissionreport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('onlineadmissionreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'schoolyearID': <?=$schoolyearID?>,
            'classesID'   : <?=$classesID?>,
            'status'      : <?=$status?>,
            'phone'       : <?=$phone?>,
            'admissionID' : <?=$admissionID?>,
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('onlineadmissionreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('onlineadmissionreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('onlineadmissionreport/send_pdf_to_mail')?>",
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