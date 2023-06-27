<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $birthdaydatetime = ($birthdaydate != '') ? strtotime($birthdaydate) : '0';
            $pdf_preview_uri = base_url('studentreport/pdf/'.$reportfor.'/'.$bloodID.'/'.$country.'/'.$transport.'/'.$hostel.'/'.$gender.'/'.$birthdaydatetime.'/'.$classesID.'/'.$sectionID);
            $xml_preview_uri = base_url('studentreport/xlsx/'.$reportfor.'/'.$bloodID.'/'.$country.'/'.$transport.'/'.$hostel.'/'.$gender.'/'.$birthdaydatetime.'/'.$classesID.'/'.$sectionID);
            echo btn_printReport('studentreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('studentreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('studentreport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('studentreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('studentreport_report_for')?> - <?=ucwords($reportfor)?> ( <?=$reportTitle?> ) </h3>
    </div><!-- /.box-header -->

    <div id="printablediv">
            <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12">
                    <h5 class="pull-left">
                        <?=$this->lang->line('studentreport_class')?> : <?=isset($classes[$classesID]) ? $classes[$classesID]->classes : $this->lang->line('studentreport_select_all_class')?>
                    </h5>  
                    <h5 class="pull-right">
                        <?=$this->lang->line('studentreport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID]->section : $this->lang->line('studentreport_select_all_section')?>
                    </h5>  
                </div>
                <div class="col-sm-12">
                    <?php if(count($students)) { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('studentreport_slno')?></th>
                                    <th><?=$this->lang->line('studentreport_photo')?></th>
                                    <th><?=$this->lang->line('studentreport_name')?></th>
                                    <th><?=$this->lang->line('studentreport_register')?></th>
                                    <?php if($classesID == 0) { ?>
                                        <th><?=$this->lang->line('studentreport_class')?></th>
                                    <?php } if($sectionID == 0 || $sectionID == '') { ?>
                                    <th><?=$this->lang->line('studentreport_section')?></th>
                                    <?php } ?>
                                    <th><?=$this->lang->line('studentreport_roll')?></th>
                                    <th><?=$this->lang->line('studentreport_email')?></th>
                                    <th><?=$this->lang->line('studentreport_phone')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1;
                                    $flag = 0;
                                    foreach($students as $student) {
                                ?>
                                    <tr>
                                        <td data-title="#">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('studentreport_photo')?>">
                                            <?=profileimage($student->photo)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentreport_name')?>">
                                            <?php echo $student->srname; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentreport_register')?>">
                                            <?php echo $student->srregisterNO; ?>
                                        </td>
                                        <?php if($classesID == 0) { ?>
                                            <td data-title="<?=$this->lang->line('studentreport_class')?>">
                                                <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID]->classes : '' ?>
                                            </td>
                                        <?php } if($sectionID == 0 || $sectionID == '') { ?>
                                            <td data-title="<?=$this->lang->line('studentreport_section')?>">
                                                <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID]->section : '' ?>
                                            </td>
                                        <?php } ?>
                                        <td data-title="<?=$this->lang->line('studentreport_roll')?>">
                                            <?php echo $student->srroll; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentreport_email')?>">
                                            <?php echo $student->email; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('studentreport_phone')?>">
                                            <?php echo $student->phone; ?>
                                        </td>
                                   </tr>
                                <?php $i++; } ?>
                            </tbody>
                        </table>
                    </div>
                   <?php } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('studentreport_student_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('studentreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('studentreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('studentreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("studentreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("studentreport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('studentreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'reportfor'  : '<?=$reportfor?>',
            'bloodID'    : '<?=$bloodID?>',
            'country'    : '<?=$country?>',
            'transport'  : '<?=$transport?>',
            'hostel'     : '<?=$hostel?>',
            'gender'     : '<?=$gender?>',
            'birthdaydate': '<?=$birthdaydate?>',
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
            $("#to_error").html("<?=$this->lang->line('studentreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('studentreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if(response.to) {
                            $("#to_error").html("<?=$this->lang->line('studentreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('studentreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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
