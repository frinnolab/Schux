<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('meritstagereport/pdf/'.$examID.'/'.$classesID.'/'.$sectionID);
            echo btn_printReport('meritstagereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('meritstagereport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('meritstagereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('meritstagereport_report_for')?> - <?=$this->lang->line('meritstagereport_meritstage')?> </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">

            .mainmeritstagereport{
                margin: 0px;
                overflow: hidden;
                border:1px solid #ddd;
                max-width:794px;
                margin: 0px auto;
                padding:12px;
            }

            .terminal-headers{
                border-bottom: 1px solid #ddd;
                overflow: hidden;
                padding-bottom: 10px;
                vertical-align: middle;
                margin-bottom: 15px;
            }

            .terminal-logo {
                float: left;
            }

            .terminal-headers img{
                width: 60px;
                height: 60px;
            }

            .school-name h2{
                padding-top: 7px;
                padding-left: 20px;
                font-weight: bold;
                float: left;
            }

            .terminal-infos {
                width: 100%;
                overflow: hidden;
            }

            .terminal-infos h5 {
                font-weight: bold;
            }

            .school_address {
                width: 40%;
                float: left;
            }

            .mandatory_subjects{
                width: 30%;
                float: left;
                padding-left:15px;
            }

            .optinal_subjects{
                width: 30%;
                float: left;
                padding-left: 15px;
            }
        
            table {
                width: 100%;
            }

            table tr, table td, table th {
                border: 1px solid #ddd;
                padding: 3px;
                text-align: center;
            }

            .school_info p {
                margin: 1px;
                font-size: 14px;
            }

            .merit_info{ 
                margin-top: 15px;
            }

            .merit_info p{
                margin: 1px;
                font-size: 14px;
            }

            .school_info h3, .merit_info h3, .caption_table {
                font-weight: bold;
                line-height: 18px;
                margin: 5px 0px;
                font-size: 18px;
            }

            .terminal-contents {
                width: 100%;
                overflow: hidden;
                margin-top: 12px;
            }

            .terminal-contents table {
                width: 100%;
            }

            .terminal-contents table tr,.terminal-contents table td,.terminal-contents table th {
                border:1px solid #ddd;
                padding: 4px;
                text-align: center;
                font-size: 12px;
            }

            @media print {
                .mainmeritstagereport{
                    border:0px solid #ddd;
                    padding: 0px 20px;
                }
            }

            @media screen and (max-width: 480px) {

                .school_address {
                    width: 100%;
                }

                .mandatory_subjects {
                    width: 100%;
                    padding-left: 0px;
                    margin-top: 10px;
                }

                .optinal_subjects {
                    width: 100%;
                    padding-left: 0px;
                    margin-top: 10px;
                }

                table tr, table td, table th {
                    border: 1px solid #ddd;
                    padding: 3px;
                    text-align: center;
                }

                .school_info h3, .merit_info h3, .caption_table {
                    text-align: left;
                }

                .school-name h2 {
                    padding-left: 0px;
                    float: none;
                }      
            }
            
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="mainmeritstagereport">
                        <div class="terminal-headers">
                            <div class="terminal-logo">
                                <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" alt="">
                            </div>
                            <div class="school-name">
                                <h2><?=$siteinfos->sname?></h2>
                            </div>
                        </div>
                        <div class="terminal-infos">
                            <div class="school_address">
                                <div class="school_info">
                                    <h3><?=$siteinfos->sname?></h3>
                                    <p><?=$this->lang->line('meritstagereport_address');?>: <?=$siteinfos->address?></p>
                                    <p><?=$this->lang->line('meritstagereport_phone');?>: <?=$siteinfos->phone?></p>
                                    <p><?=$this->lang->line('meritstagereport_email');?>: <?=$siteinfos->email?></p>
                                </div>
                                <div class="merit_info">
                                    <h3><?=$this->lang->line('meritstagereport_order_merit');?></h3>
                                    <p><?=$this->lang->line('meritstagereport_academic_year');?>: <?=$schoolyearsessionobj->schoolyear?></p>
                                    <p><?=$this->lang->line('meritstagereport_exam');?>: <?=$examName?></p>
                                    <p><?=$this->lang->line('meritstagereport_class');?>: <?=isset($classes[$classesID]) ? $classes[$classesID] : ''?></p>
                                    <p><?=$this->lang->line('meritstagereport_section');?>: <?=isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('meritstagereport_all_section')?></p>
                                </div>
                            </div>
                            <div class="mandatory_subjects">
                                <table>
                                    <tr>
                                        <td class="caption_table" colspan="3"><?=$this->lang->line('meritstagereport_mandatory_subjects')?></td>
                                    </tr>
                                    <?php $mandatory_column = 0; if(count($subjects)) { foreach($subjects as $subject) { if($subject->type == 1) { ?>
                                    <tr>
                                        <td><?=$subject->subject_code?></td>
                                        <td><?=substr($subject->subject,0,3)?></td>
                                        <td><?=$subject->subject?></td>
                                    </tr>
                                    <?php $mandatory_column++; } } } ?>
                                </table>
                            </div>

                            <?php 
                                $optionalSubjectStatus = FALSE;
                                if(count($subjects)) { 
                                    foreach($subjects as $optionalSubject) {
                                        if($optionalSubject->type != 1) { 
                                            $optionalSubjectStatus = TRUE;
                                        }
                                    }
                                }
                            ?>

                            <div class="optinal_subjects">
                                <?php if($optionalSubjectStatus) { ?>
                                    <table>
                                        <tr>
                                            <td class="caption_table" colspan="3"><?=$this->lang->line('meritstagereport_optional_subjects')?></td>
                                        </tr>
                                        <?php $optional_column = 0; if(count($subjects)) { foreach($subjects as $subject) { if($subject->type != 1) { ?>
                                        <tr>
                                            <td><?=$subject->subject_code?></td>
                                            <td><?=substr($subject->subject,0,3)?></td>
                                            <td><?=$subject->subject?></td>
                                        </tr>
                                        <?php $optional_column++; } } } ?>
                                    </table>
                                <?php  } ?>
                            </div>
                        </div>

                        
                        <div class="terminal-contents meritstagereporttable">
                            <table>
                                <thead>
                                    <tr>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_slno')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_name')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_registerNO')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_roll')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_position')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_total_marks')?></th>
                                        <th rowspan="2"><?=$this->lang->line('meritstagereport_average')?></th>
                                        <th colspan="<?=$mandatory_column?>"><?=$this->lang->line('meritstagereport_mandatory_subjects')?></th>

                                        <?php if($optionalSubjectStatus) { ?>
                                        <th colspan="<?=$optional_column?>"><?=$this->lang->line('meritstagereport_optional_subjects')?></th>
                                        <?php } ?>
                                    </tr>
                                    <tr>
                                        <?php if(count($subjects)) { foreach($subjects as $subject) { if($subject->type == 1) { ?>
                                            <th><?=substr($subject->subject,0,3)?></th>
                                        <?php } } } ?>
                                        <?php if($optionalSubjectStatus) { ?>
                                            <?php if(count($subjects)) { foreach($subjects as $subject) { if($subject->type != 1) { ?>
                                                <th><?=substr($subject->subject,0,3)?></th>
                                            <?php } } } ?>
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0; if(count($studentPosition['studentClassPositionArray'])) { foreach($studentPosition['studentClassPositionArray'] as $studentID => $student) {
                                    if(isset($studentLists[$studentID])) { $i++;
                                ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=$studentLists[$studentID]->srname?></td>
                                        <td><?=$studentLists[$studentID]->srregisterNO?></td>
                                        <td><?=$studentLists[$studentID]->srroll?></td>
                                        <td>
                                            <?php 
                                                if(isset($studentPosition['studentClassPositionArray'][$studentID])) { 
                                                    echo addOrdinalNumberSuffix((int)array_search($studentID, array_keys($studentPosition['studentClassPositionArray'])) + 1);
                                                }
                                            ?>
                                        </td>
                                        <td><?=isset($studentPosition[$studentID]['totalSubjectMark']) ? ini_round($studentPosition[$studentID]['totalSubjectMark']) : 0 ?></td>
                                        <td><?=isset($studentPosition[$studentID]['classPositionMark']) ? ini_round($studentPosition[$studentID]['classPositionMark']) : 0 ?></td>
                                        <?php if(count($subjects)) { foreach($subjects as $subject) { if($subject->type == 1) { ?>
                                            <td><?=isset($studentPosition[$studentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$studentID]['subjectMark'][$subject->subjectID] : '0'?></td>
                                        <?php } } } ?>

                                        <?php if($optionalSubjectStatus) { ?>
                                            <?php if(count($subjects)) { foreach($subjects as $subject) { if($subject->type != 1) { ?>
                                                <td>
                                                    <?php
                                                        if($studentLists[$studentID]->sroptionalsubjectID == $subject->subjectID) {
                                                            echo isset($studentPosition[$studentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$studentID]['subjectMark'][$subject->subjectID] : 0;
                                                        }
                                                    ?>
                                                </td>
                                            <?php } } } ?>
                                        <?php } ?>
                                    </tr>
                                <?php } } } else { ?>
                                    <tr>
                                        <td style="font-weight: bold" colspan="<?=($mandatory_column+$optional_column+7)?>"><?=$this->lang->line('meritstagereport_data_not_found')?></td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('meritstagereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('meritstagereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('meritstagereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("meritstagereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("meritstagereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("meritstagereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("meritstagereport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    $('.meritstagereporttable').mCustomScrollbar({
        axis:"x"
    });

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('meritstagereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'examID'     : $('#examID').val(),
            'classesID'  : $('#classesID').val(),
            'sectionID'  : $('#sectionID').val(),
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('meritstagereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('meritstagereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('meritstagereport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('meritstagereport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        }

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('meritstagereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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