<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            
            $pdf_preview_uri = base_url('examschedulereport/pdf/'.$examID.'/'.$classesID.'/'.$sectionID);
            $xml_preview_uri = base_url('examschedulereport/xlsx/'.$examID.'/'.$classesID.'/'.$sectionID);

            echo btn_printReport('examschedulereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('examschedulereport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('examschedulereport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('examschedulereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('examschedulereport_report_for')?> - <?=$this->lang->line('examschedulereport_examschedule')?> ( <?=isset($exams[$examID]) ? $exams[$examID] : ''?> ) </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($classesID >= 0 && $sectionID >= 0 ) { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left">
                                    <?php echo $this->lang->line('examschedulereport_class');?> : 
                                    <?=isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('examschedulereport_all_classes')?>
                                </h5>
                                <h5 class="pull-right">
                                    <?php echo $this->lang->line('examschedulereport_section');?> : 
                                    <?php 
                                        if($sectionID == 0) { 
                                            echo $this->lang->line('examschedulereport_all_section');
                                        } else {
                                            echo isset($section[$sectionID]) ? $section[$sectionID] : '';
                                        }
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12">
                    <?php if(count($examschedule_reports)) {?>
                        <div class="examschedule-table-responsive">
                           <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <?php if($classesID == 0 && $sectionID == 0) { ?>
                                            <th><?php echo $this->lang->line('examschedulereport_class');?></th>
                                            <th><?php echo $this->lang->line('examschedulereport_section');?></th>
                                        <?php } elseif ($classesID != 0 && $sectionID == 0) { ?>
                                            <th><?php echo $this->lang->line('examschedulereport_section');?></th>
                                        <?php } ?>

                                        <?php
                                            if(count($exam_dates)) {
                                                foreach($exam_dates as $exam_date) { ?>
                                                    <th><?=date('d M Y',strtotime($exam_date));?></th>
                                                <?php 
                                                }
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $classArray = [];
                                        $allClassStatus = TRUE;
                                        $allSectionStatus = TRUE;

                                        $classStatus = FALSE;
                                        $sectionStatus = FALSE;

                                        if($classesID != 0) {
                                            $allClassStatus = FALSE;
                                        }

                                        if($sectionID != 0) {
                                            $allSectionStatus = FALSE;
                                        }

                                        if(isset($classes)) {
                                            foreach($classes as $classesKey => $classesValue) {
                                                if($allClassStatus == FALSE && $classesID == $classesKey) {
                                                    $classStatus = TRUE;
                                                } elseif($allClassStatus) {
                                                    $classStatus = TRUE;
                                                }

                                                if($classStatus) {
                                                    if(isset($allSections[$classesKey])) {
                                                        foreach($allSections[$classesKey] as $sectionKey => $section) {

                                                            if($allSectionStatus == FALSE && $sectionID == $sectionKey) { 
                                                                $sectionStatus = TRUE;
                                                            } elseif($allSectionStatus) {
                                                                $sectionStatus = TRUE;
                                                            }

                                                            if($sectionStatus) {
                                                                echo '<tr>';
                                                                if($classesID == 0 && $sectionID == 0) {
                                                                    if(!in_array($classesKey, $classArray)) {
                                                                        $rowspanforclass = 1;
                                                                        if(isset($allSections[$classesKey])) {
                                                                            $rowspanforclass = count($allSections[$classesKey]);
                                                                        }
                                                                        
                                                                        echo '<td rowspan="'.$rowspanforclass.'">'.(isset($classesValue) ? $classesValue : '').'</td>';
                                                                        $classArray[] = $classesKey;
                                                                    }
                                                                    echo '<td>'.$section.'</td>';
                                                                } elseif ($classesID != 0 && $sectionID == 0) {
                                                                    echo '<td>'.$section.'</td>';
                                                                }
                                                                
                                                                if(isset($exam_dates)) {
                                                                    foreach($exam_dates as $exam_date) {
                                                                        echo "<td class='text-center'>";
                                                                        if(isset($examreports[$classesKey][$sectionKey][$exam_date])) {
                                                                            $examscheduledatas = $examreports[$classesKey][$sectionKey][$exam_date];
                                                                            $subject_count = count($examscheduledatas);
                                                                            $j=1;
                                                                            foreach($examscheduledatas as $examscheduledata) {
                                                                               echo $this->lang->line('examschedulereport_subject'). " :" .(isset($subjects[$examscheduledata->subjectID]) ? $subjects[$examscheduledata->subjectID] : '')."<br/>";
                                                                               
                                                                               echo $this->lang->line('examschedulereport_exam_time'). " : " .$examscheduledata->examfrom.'-'.$examscheduledata->examto."<br/>";

                                                                               echo $this->lang->line('examschedulereport_room'). " :" .$examscheduledata->room."<br/>";
                                                                                if($j < $subject_count) {
                                                                                    echo "<hr/ style='margin:5px;'>";
                                                                                    $j++;
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo "N/A";
                                                                        }  
                                                                        echo "</td>";
                                                                    }
                                                                }
                                                                
                                                                echo '</tr>';
                                                            }

                                                            $sectionStatus = FALSE;

                                                        } 
                                                    }   
                                                }

                                                $classStatus = FALSE; 
                                            } 
                                        }
                                    ?>
                                </tbody>                
                           </table>
                        </div>
                    <?php } else { ?>
                        <br/>
                        <br/>
                        <div class="callout callout-danger" style="display: block">
                            <p><b class="text-info"><?=$this->lang->line('examschedulereport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
                <hr/>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->

    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('examschedulereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('examschedulereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('examschedulereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("examschedulereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("examschedulereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("examschedulereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("examschedulereport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">   
    $('.examschedule-table-responsive').mCustomScrollbar({
        axis:"x"
    });
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('examschedulereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            $("#to_error").html("<?=$this->lang->line('examschedulereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('examschedulereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }
        

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('examschedulereport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('examschedulereport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('examschedulereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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
