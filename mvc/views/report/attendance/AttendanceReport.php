<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            if($siteinfos->attendance == 'subject') {
                $pdfurl = 'attendancereport/pdf/'.$typeSortForm.'/'.$classesID.'/'.$sectionID.'/'.$subjectID.'/'.strtotime($date);
            } else {
                $pdfurl = 'attendancereport/pdf/'.$typeSortForm.'/'.$classesID.'/'.$sectionID.'/'.strtotime($date);
            }

            if($siteinfos->attendance == 'subject') {
                $xmlurl = 'attendancereport/xlsx/'.$typeSortForm.'/'.$classesID.'/'.$sectionID.'/'.$subjectID.'/'.strtotime($date);
            } else {
                $xmlurl = 'attendancereport/xlsx/'.$typeSortForm.'/'.$classesID.'/'.$sectionID.'/'.strtotime($date);
            }

            $pdf_preview_uri = base_url($pdfurl);
            $xml_preview_uri = base_url($xmlurl);

            echo btn_printReport('attendancereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('attendancereport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('attendancereport', $xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('attendancereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <!-- form start -->
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('attendancereport_report_for')?> <?=$this->lang->line('attendancereport_attendance')?> - <?=$attendancetype?> ( <?=date('d M Y',strtotime($date))?> ) </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php if($classesID == 0) {?>
                                <h5 class="pull-left"><?=$this->lang->line('attendancereport_class')?> : <?=$this->lang->line('attendancereport_select_all_classes');?></h5>
                            <?php } else { ?>
                                <h5 class="pull-left"><?=$this->lang->line('attendancereport_class')?> : <?=isset($class) ? $class->classes :  ''?></h5>
                            <?php } ?>
                            <?php if($sectionID == 0) {?>
                                <h5 class="pull-right"><?=$this->lang->line('attendancereport_section')?> : <?=$this->lang->line('attendancereport_select_all_section');?></h5>
                            <?php } else { ?>
                                <h5 class="pull-right"><?=$this->lang->line('attendancereport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID]->section : '' ?></h5>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <?php $attendancedate = date('d-m-Y',strtotime($date));
                    if(count($students)) { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('attendancereport_slno')?></th>
                                    <th><?=$this->lang->line('attendancereport_photo')?></th>
                                    <th><?=$this->lang->line('attendancereport_name')?></th>
                                    <th><?=$this->lang->line('attendancereport_registerNo')?></th>
                                    <?php  if($sectionID == 0 ) { ?>
                                        <th><?=$this->lang->line('attendancereport_section')?></th>
                                    <?php } ?>
                                    <th><?=$this->lang->line('attendancereport_roll')?></th>
                                    <th><?=$this->lang->line('attendancereport_email')?></th>
                                    <th><?=$this->lang->line('attendancereport_phone')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i = 1;
                                    $flag = 0;
                                    foreach($students as $student) {
                                        $userleaveapplications = isset($leaveapplications[$student->srstudentID]) ? $leaveapplications[$student->srstudentID] : [];

                                        if((in_array($attendancedate, $userleaveapplications) && ($typeSortForm != 'LA'))) {
                                            continue;
                                        } elseif(($typeSortForm == 'LA') && (!in_array($attendancedate, $userleaveapplications))) {
                                            continue;
                                        } elseif(isset($attendances[$student->srstudentID])) {
                                            $attendanceDay = $attendances[$student->srstudentID]->$day;
                                            if($typeSortForm == 'P' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                                continue;
                                            } elseif($typeSortForm == 'LE' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='P' || $attendanceDay =='L' )) {
                                                continue;
                                            } elseif($typeSortForm == 'L' && ($attendanceDay == 'A' || $attendanceDay == NULL || $attendanceDay =='LE' || $attendanceDay =='P' )) {
                                                continue;
                                            } elseif($typeSortForm == 'A' && ($attendanceDay == 'P' || $attendanceDay =='LE' || $attendanceDay =='L' )) {
                                                continue;
                                            } 
                                        } elseif($typeSortForm == 'P' || $typeSortForm == 'LE' || $typeSortForm == 'L') {
                                            continue;
                                        }
                                        $flag = 1;
                                ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('attendancereport_slno')?>">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('attendancereport_photo')?>">
                                            <?=profileimage($student->photo)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('attendancereport_name')?>">
                                            <?=$student->srname; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('attendancereport_registerNo')?>">
                                            <?=$student->srregisterNO; ?>
                                        </td>
                                        <?php
                                            if($sectionID == 0 ) { ?>
                                            <td data-title="<?=$this->lang->line('attendancereport_section')?>">
                                                <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID]->section : ''; ?>
                                            </td>
                                        <?php } ?>
                                        <td data-title="<?=$this->lang->line('attendancereport_roll')?>">
                                            <?=$student->srroll; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('attendancereport_email')?>">
                                            <?=$student->email; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('attendancereport_phone')?>">
                                            <?=$student->phone; ?>
                                        </td>
                                   </tr>
                                <?php $i++; }
                                    if(!$flag) { ?>
                                    <tr>
                                        <td data-title="#" colspan="8">
                                            <?=$this->lang->line('attendancereport_student_not_found')?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('attendancereport_student_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('attendancereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('attendancereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('attendancereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("attendancereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendancereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendancereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("attendancereport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('attendancereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'attendancetype' : '<?=$typeSortForm?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'subjectID'  : '<?=$subjectID?>',
            'date'       : '<?=$date?>'
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('attendancereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('attendancereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancereport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('attendancereport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('attendancereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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