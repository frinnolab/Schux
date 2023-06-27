<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('routinereport/pdf/'.$routinefor.'/'.$teacherID.'/'.$get_classes.'/'.$get_section);
            $xml_preview_uri = base_url('routinereport/xlsx/'.$routinefor.'/'.$teacherID.'/'.$get_classes.'/'.$get_section);
            echo btn_printReport('routinereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('routinereport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('routinereport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('routinereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('routinereport_report_for')?> <?=$this->lang->line('routinereport_routine')?> - 
        <?php if($routinefor == 'student') { 
            echo $this->lang->line('routinereport_student');
        } elseif($routinefor == 'teacher') { 
            echo $this->lang->line('routinereport_teacher');
        } ?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12">
                    <?php if(count($routines)) {
                        if(($routinefor == 'student') && count($classes) && isset($sections[$get_section])) { ?>
                            <h5 class="pull-left"><?=$this->lang->line('routinereport_class')?> : <?=isset($classes->classes) ? $classes->classes : ''?></h5>
                            <h5 class="pull-right"><?=$this->lang->line('routinereport_section')?> : <?=isset($sections[$get_section]) ? $sections[$get_section] : '' ?></h5>
                        <?php } elseif(($routinefor == 'teacher') && count($teacher)) { ?>
                            <h5 class="pull-left"><?=$this->lang->line('routinereport_name')?> : <?=$teacher->name?></h5>                         
                            <h5 class="pull-right"><?=$this->lang->line('routinereport_designation')?> : <?=$teacher->designation?></h5>
                    <?php } } ?>
                </div>

                <div class="col-sm-12">
                    <?php 
                        $days = [
                            0 => $this->lang->line('sunday'),
                            1 => $this->lang->line('monday'),
                            2 => $this->lang->line('tuesday'),
                            3 => $this->lang->line('wednesday'),
                            4 => $this->lang->line('thursday'),
                            5 => $this->lang->line('friday'),
                            6 => $this->lang->line('saturday'),
                        ];
                    
                        if(count($routines)) {
                            $maxClass = 0; 
                            foreach ($routines as $routine) { 
                                if(count($routine) > $maxClass) {
                                    $maxClass = count($routine);
                                }
                            } ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <th><?php echo $this->lang->line('routinereport_day');?></th>
                                    <?php for($i=1; $i <= $maxClass; $i++) { ?>
                                        <th><?= addOrdinalNumberSuffix($i)." ".$this->lang->line('routinereport_period');?></th>
                                    <?php } ?>
                                </thead>
                                <tbody>
                                    <?php foreach ($days as $dayKey=> $day) { 
                                        if(!in_array($dayKey, $weekends) && isset($routines[$dayKey])) { $i=0; ?>
                                        <tr>
                                            <td><?=$day?></td>
                                            <?php foreach ($routines[$dayKey] as $routine) { $i++; ?>
                                                <td class="text-center">
                                                    <p><?=$routine->start_time;?>-<?=$routine->end_time;?></p>
                                                    <p>
                                                        <span class="left"><?=$this->lang->line('routinereport_subject')?> :</span>
                                                        <span class="right"><?=isset($subjects[$routine->subjectID]) ? $subjects[$routine->subjectID] : ''?></span>
                                                    </p>
                                                    <?php if($routinefor == 'student') { ?>
                                                        <p>
                                                            <span class="left"><?=$this->lang->line('routinereport_teacher')?> :</span>
                                                            <span class="right"><?=isset($teachers[$routine->teacherID]) ? $teachers[$routine->teacherID] : ''?></span>
                                                        </p>
                                                    <?php } elseif($routinefor == 'teacher') { ?>
                                                        <p>
                                                            <span class="left"><?=$this->lang->line('routinereport_class')?> :</span>
                                                            <span class="right"><?=isset($classes[$routine->classesID]) ? $classes[$routine->classesID] : ''?></span>
                                                        </p>
                                                        <p>
                                                            <span class="left"><?= $this->lang->line('routinereport_section')?> :</span>
                                                            <span class="right"><?=isset($sections[$routine->sectionID]) ? $sections[$routine->sectionID] : ''?></span>
                                                        </p>
                                                    <?php }?>
                                                    <p><span class="left"><?=$this->lang->line('routinereport_room')?> : </span><span class="right"><?=$routine->room;?></span></p>
                                                </td>
                                            <?php } $j = ($maxClass - $i);  
                                            if($i < $maxClass) {
                                                for($i = 1; $i <= $j; $i++) {
                                                    echo "<td class='text-center'>N/A</td>";
                                            } } ?>
                                        </tr> 
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } else {  ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('routinereport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                    <div class="col-sm-12 text-center footerAll">
                        <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('routinereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('routinereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('routinereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("routinereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("routinereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("routinereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("routinereport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('routinereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'routinefor' : '<?=$routinefor?>',
            'teacherID'  : '<?=$teacherID?>',
            'classesID'  : '<?=$get_classes?>',
            'sectionID'  : '<?=$get_section?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('routinereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('routinereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('routinereport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('routinereport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 
                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('routinereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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