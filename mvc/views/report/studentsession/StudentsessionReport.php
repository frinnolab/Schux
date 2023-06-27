<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('studentsessionreport/pdf/'.$studentID);
            echo btn_printReport('studentsessionreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('studentsessionreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('studentsessionreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('studentsessionreport_report_for')?> - <?=$this->lang->line('studentsessionreport_student_session')?></h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">
            .mainstudentsessionreport{
                margin: 0px;
                overflow: hidden;
                border:1px solid #ddd;
                max-width:794px;
                margin: 0px auto;
                margin-bottom: 10px;
                padding:30px;
            }

            .studentsession-headers{
                border-bottom: 1px solid #ddd;
                overflow: hidden;
                padding-bottom: 10px;
                vertical-align: middle;
                margin-bottom: 4px;
            }

            .studentsession-logo {
                float: left;
            }

            .studentsession-headers img{
                width: 60px;
                height: 60px;
            }

            .school-name h2{
                float: left;
                padding-left: 20px;
                padding-top: 7px;
                font-weight: bold;
            }

            .studentsession-infos {
                width: 100%;
                overflow: hidden;
            }

            .studentsession-infos h3{
                padding: 2px 0px;
                margin: 0px;
            }

            .studentsession-infos p{
                margin-bottom: 3px;
                font-size: 15px;
            }

            .school-address{
                float: left;
                width: 40%;
            }

            .student-profile {
                float: left;
                width: 40%;

            }

            .student-profile-img {
                float: left;
                width: 20%;
                text-align: right;
            }

            .student-profile-img img {
                width: 120px;
                height: 120px;
                border: 1px solid #ddd;
                margin-top: 5px;
                margin-right: 2px;
            }

             @media screen and (max-width: 480px) {
                .school-name h2{
                    padding-left: 0px;
                    float: none;
                }

                .school-address {
                    width: 100%;
                }

                .student-profile {
                    width: 100%;
                } 

                .student-profile-img  {
                    margin-top: 10px;
                    width: 100%;
                }

                .student-profile-img img {
                    width: 100%;
                    height: 100%;
                    margin: 10px 0px;
                }
            }

            .studentsession-contents {
                width: 100%;
                overflow: hidden;
                margin-top: 10px;
            }

            .studentsession-contents table {
                width: 100%;
            }

            .studentsession-contents table tr,.studentsession-contents table td,.studentsession-contents table th {
                border:1px solid #ddd;
                padding: 8px 1px;
                font-size: 14px;
                text-align: center;
            }

            @media print {
                .mainstudentsessionreport{
                    border:0px solid #ddd;
                    padding: 0px 20px;
                }

                .student-profile-img img {
                    margin-right: 5px !important;
                }

                .studentsession-contents table td,.studentsession-contents table th {
                    font-size: 12px;
                }
            }
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                <?php if(count($students)) { foreach($students as $schoolyearID => $student) { ?>
                    <div class="mainstudentsessionreport">
                        <div class="studentsession-headers">
                            <div class="studentsession-logo">
                                <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" alt="">
                            </div>
                            <div class="school-name">
                                <h2><?=$siteinfos->sname?></h2>
                            </div>
                        </div>
                        <div class="studentsession-infos">
                            <div class="school-address">
                                <h4><b><?=$siteinfos->sname?></b></h4>
                                <p><?=$siteinfos->address?></p>
                                <p><?=$this->lang->line('studentsessionreport_phone')?> : <?=$siteinfos->phone?></p>
                                <p><?=$this->lang->line('studentsessionreport_email')?> : <?=$siteinfos->email?></p>
                            </div>
                            <div class="student-profile">
                                <h4><b><?=$student->srname?></b></h4>
                                <p><?=$this->lang->line('studentsessionreport_academic_year')?> : <b><?=isset($schoolyears[$schoolyearID]) ? $schoolyears[$schoolyearID] : []?></b>
                                <p><?=$this->lang->line('studentsessionreport_reg_no')?> : <b><?=$student->srregisterNO?></b>, <?=$this->lang->line('studentsessionreport_class')?> : <b><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></b></p>
                                <p><?=$this->lang->line('studentsessionreport_section')?> : <b><?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></b>, <?=$this->lang->line('studentsessionreport_roll_no')?> : <b><?=$student->srroll?></b></p>  
                                <p><?=$this->lang->line('studentsessionreport_group')?> : <b><?=isset($groups[$student->srstudentgroupID]) ? $groups[$student->srstudentgroupID] : ''?></b></p> 
                            </div>
                            <div class="student-profile-img">
                                <img src="<?=imagelink($student->photo)?>" alt="">
                            </div>
                        </div>
                        <div class="studentsession-contents studentsessionreporttable">
                            <table>
                                <thead>
                                    <tr>
                                        <th rowspan="2"><?=$this->lang->line('studentsessionreport_subjects')?></th>
                                        <?php 

                                        $markpercentagesexamArr = isset($markpercentagesmainArr[$student->srclassesID]) ? $markpercentagesmainArr[$student->srclassesID] : [];

                                        if(count($markpercentagesexamArr)) { foreach($markpercentagesexamArr as $examID => $markpercentagessubjectArr) {
                                            reset($markpercentagessubjectArr);
                                            $firstindex          = key($markpercentagessubjectArr);
                                            $uniquepercentageArr = isset($markpercentagessubjectArr[$firstindex]) ? $markpercentagessubjectArr[$firstindex] : [];
                                            $uniqueandown        = (($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own';
                                            $markpercentages     = isset($uniquepercentageArr[$uniqueandown]) ? $uniquepercentageArr[$uniqueandown] : ''; ?>
                                            <th colspan="<?=count($markpercentages)?>"><?=isset($exams[$examID]) ? $exams[$examID] : ''?></th>
                                        <?php } } ?>
                                        <th rowspan="2"><?=$this->lang->line('studentsessionreport_total')?></th>
                                        <th rowspan="2"><?=$this->lang->line('studentsessionreport_grade')?></th>
                                        <!-- <th rowspan="2"><?=$this->lang->line('studentsessionreport_point')?></th> -->
                                    </tr>
                                    <tr>
                                        <?php 
                                        $i = 0;
                                        // $totalColumn = 4;
                                        $totalColumn = 3;
                                        $leftColumn  = 0;

                                        if(count($markpercentagesexamArr)) { foreach($markpercentagesexamArr as $examID => $markpercentagessubjectArr) {
                                            reset($markpercentagessubjectArr);
                                            $firstindex          = key($markpercentagessubjectArr);
                                            $uniquepercentageArr = isset($markpercentagessubjectArr[$firstindex]) ? $markpercentagessubjectArr[$firstindex] : [];
                                            $uniqueandown        = (($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own';
                                            $markpercentages     = isset($uniquepercentageArr[$uniqueandown]) ? $uniquepercentageArr[$uniqueandown] : ''; 
                                            
                                            if($i == 1) {
                                                $leftColumn  = count($markpercentages) + 1;
                                            }
                                            if(count($markpercentages)) { foreach($markpercentages as $markpercentageID) { $totalColumn++; ?>
                                                <th>
                                                    <?=isset($percentageArr[$markpercentageID]) ? substr($percentageArr[$markpercentageID]->markpercentagetype, 0, 2) : '';?>
                                                </th>
                                        <?php } } } } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php 

                                // $totalGpaPoint        = 0;
                                $totalAllSubjectMark  = 0;

                                if(isset($mandatorySubjects[$student->srclassesID]) && count($mandatorySubjects[$student->srclassesID])) { foreach($mandatorySubjects[$student->srclassesID]  as $mandatorySubject) { 
                                    $totalSubjectMark = 0; $totalGradeSubjectMark = 0 ?>
                                    <tr>
                                        <td><?=$mandatorySubject->subject?></td>
                                        <?php 
                                        if(count($markpercentagesexamArr)) { foreach($markpercentagesexamArr as $examID => $markpercentagessubjectArr) {
                                            $examTotalSubjectMark = 0;

                                            $uniquepercentageArr = isset($markpercentagessubjectArr[$mandatorySubject->subjectID]) ? $markpercentagessubjectArr[$mandatorySubject->subjectID] : [];
                                            $markpercentages     = [];
                                            if(count($uniquepercentageArr)) {
                                                $uniqueandown    = (($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own';
                                                $markpercentages = isset($uniquepercentageArr[$uniqueandown]) ? $uniquepercentageArr[$uniqueandown] : '';
                                            }

                                            $percentageMark      = 0;
                                            if(count($markpercentages)) { foreach($markpercentages as $markpercentageID) {
                                                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                    $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                                }

                                            ?>
                                            <td>
                                                <?php
                                                    $mark = 0;
                                                    if(isset($retMark[$schoolyearID][$student->srclassesID][$examID][$mandatorySubject->subjectID][$markpercentageID])) {
                                                        $mark = $retMark[$schoolyearID][$student->srclassesID][$examID][$mandatorySubject->subjectID][$markpercentageID];
                                                    }
                                                    echo ($mark) ? $mark : '';
                                                    $totalSubjectMark     += $mark;
                                                    $examTotalSubjectMark += $mark;
                                                ?>
                                            </td>
                                        <?php } }
                                        $totalGradeSubjectMark += markCalculationView($examTotalSubjectMark, $mandatorySubject->finalmark, $percentageMark);
                                        } } ?>
                                        <td><?=$totalSubjectMark?></td>
                                        <?php
                                        $totalAllSubjectMark      += $totalSubjectMark;
                                        $subjectGradeMark          = $totalGradeSubjectMark / count($markpercentagesexamArr);

                                        if(count($grades)) { foreach($grades as $grade) { 
                                            if(($grade->gradefrom <= floor($subjectGradeMark)) && ($grade->gradeupto >= floor($subjectGradeMark)) && $grade->classID == $student->srclassesID) { ?>
                                                <td><?=$grade->grade?></td>
                                                <!-- <td>
                                                    <?php
                                                        echo $grade->point;
                                                        $totalGpaPoint += $grade->point;
                                                    ?>
                                                </td> -->
                                        <?php } } } ?>
                                    </tr>
                                <?php } ?>
                                <?php if(($student->sroptionalsubjectID > 0) && isset($optionalSubjects[$student->srclassesID][$student->sroptionalsubjectID])) { 
                                    $totalSubjectMark = 0; $totalGradeSubjectMark = 0;?>
                                    <tr>
                                        <td><?=$optionalSubjects[$student->srclassesID][$student->sroptionalsubjectID]->subject?></td>
                                        <?php 
                                        if(count($markpercentagesexamArr)) { foreach($markpercentagesexamArr as $examID => $markpercentagessubjectArr) {
                                            $examTotalSubjectMark = 0;

                                            $uniquepercentageArr = isset($markpercentagessubjectArr[$student->sroptionalsubjectID]) ? $markpercentagessubjectArr[$student->sroptionalsubjectID] : [];

                                            $markpercentages     = [];
                                            if(count($uniquepercentageArr)) {
                                                $uniqueandown    = (($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own';
                                                $markpercentages = isset($uniquepercentageArr[$uniqueandown]) ? $uniquepercentageArr[$uniqueandown] : '';
                                            }

                                            $percentageMark      = 0;
                                            if(count($markpercentages)) { foreach($markpercentages as $markpercentageID) {
                                                if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                                    $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                                }

                                            ?>
                                            <td>
                                                <?php
                                                    $mark = 0;
                                                    if(isset($retMark[$schoolyearID][$student->srclassesID][$examID][$student->sroptionalsubjectID][$markpercentageID])) {
                                                        $mark = $retMark[$schoolyearID][$student->srclassesID][$examID][$student->sroptionalsubjectID][$markpercentageID];
                                                    }
                                                    echo ($mark) ? $mark : '';
                                                    $totalSubjectMark     += $mark;
                                                    $examTotalSubjectMark += $mark;
                                                ?>
                                            </td>
                                        <?php } }
                                        $totalGradeSubjectMark += markCalculationView($examTotalSubjectMark, $optionalSubjects[$student->srclassesID][$student->sroptionalsubjectID]->finalmark, $percentageMark);
                                        } } ?>
                                        <td><?=$totalSubjectMark?></td>
                                        <?php
                                        $totalAllSubjectMark      += $totalSubjectMark;
                                        $subjectGradeMark          = $totalGradeSubjectMark / count($markpercentagesexamArr);

                                        if(count($grades)) { foreach($grades as $grade) { 
                                            if(($grade->gradefrom <= floor($subjectGradeMark)) && ($grade->gradeupto >= floor($subjectGradeMark)) && $grade->classID == $student->srclassesID) { ?>
                                                <td><?=$grade->grade?></td>
                                                <!-- <td>
                                                    <?php
                                                        echo $grade->point;
                                                        $totalGpaPoint += $grade->point;
                                                    ?>
                                                </td> -->
                                        <?php } } } ?>
                                    </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_total_mark')?> </td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>"><b><?=ini_round($totalAllSubjectMark)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_average_mark')?> </td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>">
                                        <b>
                                            <?php
                                                $tSubject     = count($mandatorySubjects[$student->srclassesID]);
                                                if($student->sroptionalsubjectID > 0) {
                                                    $tSubject = $tSubject + 1;
                                                }
                                                $totalAllSubject = $tSubject * count($markpercentagesexamArr);
                                                echo ini_round($totalAllSubjectMark / $totalAllSubject);
                                            ?>
                                        </b>
                                    </td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_gpa')?></td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>">
                                        <?php 
                                            echo ini_round($totalGpaPoint / $tSubject);
                                        ?>
                                    </td>
                                </tr> -->
                                <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_from_teacher_remarks')?></td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                                </tr>
                                <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_house_teacher_remarks')?></td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                                </tr>
                                <tr>
                                    <td colspan="<?=$leftColumn?>"><?=$this->lang->line('studentsessionreport_principal_remarks')?></td>
                                    <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                                </tr>

                                <tr>
                                    <td colspan="<?=$totalColumn?>">
                                        <?=$this->lang->line('studentsessionreport_interpretation')?> :
                                        <b>
                                            <?php if(count($grades)) { $i = 1; foreach($grades as $grade) { 
                                                if ($grade->classID == $student->srclassesID) {
                                                    if(count($grades) == $i) {
                                                        // echo $grade->gradefrom.'-'.$grade->gradeupto." = ".$grade->point." [".$grade->grade."]";
                                                        echo $grade->gradefrom.'-'.$grade->gradeupto." [".$grade->grade."]";
                                                    } else {
                                                        // echo $grade->gradefrom.'-'.$grade->gradeupto." = ".$grade->point." [".$grade->grade."], ";
                                                        echo $grade->gradefrom.'-'.$grade->gradeupto." [".$grade->grade."], ";
                                                    }
                                                }
                                                $i++;
                                            } } ?>
                                        </b>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                            </table>
                        </div>
                    </div>
                    <p style="page-break-after: always;">&nbsp;</p>
                <?php } } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('studentsessionreport_data_not_found')?></b></p>
                    </div>
                <?php } ?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('studentsessionreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('studentsessionreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('studentsessionreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("studentsessionreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentsessionreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentsessionreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("studentsessionreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">

    $('.studentsessionreporttable').mCustomScrollbar({
        axis:"x"
    });
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('studentsessionreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'studentID'  : '<?=$studentID?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('studentsessionreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('studentsessionreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentsessionreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('studentsessionreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        }

                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('studentsessionreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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