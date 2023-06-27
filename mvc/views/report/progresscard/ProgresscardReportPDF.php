<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?php if(count($students)) { foreach($students as $student) { ?>
    <div class="mainprogresscardreport">
        <div class="progresscard-headers">
            <div class="progresscard-logo">
                <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" alt="">
            </div>
            <div class="school-name">
                <h2><?=$siteinfos->sname?></h2>
            </div>
        </div>
        <div class="progresscard-infos">
            <div class="school-address">
                <h4><b><?=$siteinfos->sname?></b></h4>
                <p><?=$siteinfos->address?></p>
                <p><?=$this->lang->line('progresscardreport_phone')?> : <?=$siteinfos->phone?></p>
                <p><?=$this->lang->line('progresscardreport_email')?> : <?=$siteinfos->email?></p>
            </div>
            <div class="student-profile">
                <h4><b><?=$student->srname?></b></h4>
                <p><?=$this->lang->line('progresscardreport_academic_year')?> : <b><?=$schoolyearsessionobj->schoolyear;?></b>
                <p><?=$this->lang->line('progresscardreport_reg_no')?> : <b><?=$student->srregisterNO?></b>, <?=$this->lang->line('progresscardreport_class')?> : <b><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></b></p>
                <p><?=$this->lang->line('progresscardreport_section')?> : <b><?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></b>, <?=$this->lang->line('progresscardreport_roll_no')?> : <b><?=$student->srroll?></b></p>  
                <p><?=$this->lang->line('progresscardreport_group')?> : <b><?=isset($groups[$student->srstudentgroupID]) ? $groups[$student->srstudentgroupID] : ''?></b></p> 
            </div>
            <div class="student-profile-img">
                <img class="profileimg" src="<?=imagelink($student->photo)?>" alt="">
            </div>
        </div>
        <div class="progresscard-contents">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2"><?=$this->lang->line('progresscardreport_subjects')?></th>
                        <?php if(count($settingExam)) { foreach($settingExam as $examID) {

                            $markpercentagesArr  = isset($markpercentagesclassArr[$examID]) ? $markpercentagesclassArr[$examID] : [];
                            reset($markpercentagesArr);
                            $firstindex          = key($markpercentagesArr);
                            $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                            $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            ?>
                            <th colspan="<?=count($markpercentages)?>"><?=isset($exams[$examID]) ? $exams[$examID] : ''?></th>
                        <?php } } ?>
                        <th rowspan="2"><?=$this->lang->line('progresscardreport_total')?></th>
                        <th rowspan="2"><?=$this->lang->line('progresscardreport_grade')?></th>
                        <!-- <th rowspan="2"><?=$this->lang->line('progresscardreport_point')?></th> -->
                    </tr>
                    <tr>
                        <?php 
                        $i = 0;
                        // $totalColumn = 4;
                        $totalColumn = 3;
                        $leftColumn  = 0;
                        if(count($settingExam)) { foreach($settingExam as $examID) { $i++;
                            $markpercentagesArr  = isset($markpercentagesclassArr[$examID]) ? $markpercentagesclassArr[$examID] : [];
                            reset($markpercentagesArr);
                            $firstindex          = key($markpercentagesArr);
                            $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                            $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];

                            if($i == 1) {
                                $leftColumn  = count($markpercentages) + 1;
                                // $leftColumn  = count($markpercentages);
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
                    $totalAllSubjectMark      = 0; 
                    $totalAllSubjectFinalMark = 0;
                    // $total_gpa_point = 0;
                    if(count($mandatorySubjects)) { foreach($mandatorySubjects  as $mandatorySubject) { 
                        $totalSubjectMark = 0; $totalGradeSubjectMark=0 ?>
                        <tr>
                            <td><?=$mandatorySubject->subject?></td>
                            <?php 
                            if(count($settingExam)) { foreach($settingExam as $examID) {
                                $examTotalSubjectMark = 0;

                                $uniquepercentageArr = isset($markpercentagesclassArr[$examID][$mandatorySubject->subjectID]) ? $markpercentagesclassArr[$examID][$mandatorySubject->subjectID] : [];
                                $markpercentages     = [];
                                if(count($uniquepercentageArr)) {
                                    $markpercentages = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
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
                                        if(isset($markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID])) {
                                            $mark = $markArray[$examID][$student->srstudentID]['markpercentageMark'][$mandatorySubject->subjectID][$markpercentageID];
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
                            $subjectGradeMark          = $totalGradeSubjectMark / count($settingExam);

                            if(count($grades)) { foreach($grades as $grade) { 
                                if(($grade->gradefrom <= floor($subjectGradeMark)) && ($grade->gradeupto >= floor($subjectGradeMark)) && $grade->classID == $student->srclassesID) { ?>
                                    <td><?=$grade->grade?></td>
                                    <!-- <td>
                                        <?php
                                            echo $grade->point;
                                            $total_gpa_point += $grade->point;
                                        ?>
                                    </td> -->
                            <?php } } } ?>
                        </tr>
                    <?php } ?>
                    <?php if(($student->sroptionalsubjectID > 0) && isset($optionalSubjects[$student->sroptionalsubjectID])) { 
                        $totalSubjectMark = 0; $totalGradeSubjectMark = 0;?>
                        <tr>
                            <td><?=$optionalSubjects[$student->sroptionalsubjectID]->subject?></td>
                            <?php if(count($settingExam)) { foreach($settingExam as $examID) {
                                $examTotalSubjectMark  = 0;

                                $opuniquepercentageArr = isset($markpercentagesclassArr[$examID][$student->sroptionalsubjectID]) ? $markpercentagesclassArr[$examID][$student->sroptionalsubjectID] : [];

                                $markpercentages     = [];
                                if(count($opuniquepercentageArr)) {
                                    $markpercentages = $opuniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                                }

                                $percentageMark = 0;
                                if(count($markpercentages)) { foreach($markpercentages as $markpercentageID) { 
                                    if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
                                        $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                    }
                                    ?>
                                <td>
                                    <?php
                                        $mark   = 0;
                                        if(isset($markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID])) {
                                            $mark = $markArray[$examID][$student->srstudentID]['markpercentageMark'][$student->sroptionalsubjectID][$markpercentageID];
                                        }
                                        echo ($mark) ? $mark : '';
                                        $totalSubjectMark     += $mark;
                                        $examTotalSubjectMark += $mark;
                                    ?>
                                </td>
                            <?php } }
                            $totalGradeSubjectMark += markCalculationView($examTotalSubjectMark, $optionalSubjects[$student->sroptionalsubjectID]->finalmark, $percentageMark);
                            } } ?>
                            <td><?=$totalSubjectMark?></td>
                            <?php
                            $totalAllSubjectMark      += $totalSubjectMark;
                            $subjectGradeMark          = $totalGradeSubjectMark / count($settingExam);

                            if(count($grades)) { foreach($grades as $grade) { 
                                if(($grade->gradefrom <= floor($subjectGradeMark)) && ($grade->gradeupto >= floor($subjectGradeMark)) && $grade->classID == $student->srclassesID) { ?>
                                    <td><?=$grade->grade?></td>
                                    <!-- <td>
                                        <?php
                                            echo $grade->point;
                                            $total_gpa_point += $grade->point;
                                        ?>
                                    </td> -->
                            <?php } } } ?>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_total_mark')?> </td>
                        <td colspan="<?=$totalColumn-$leftColumn?>"><b><?=ini_round($totalAllSubjectMark)?></b></td>
                    </tr>
                    <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_average_mark')?> </td>
                        <td colspan="<?=$totalColumn-$leftColumn?>">
                            <b>
                                <?php
                                    $tSubject     = $totalSubject;
                                    if($student->sroptionalsubjectID > 0) {
                                        $tSubject = $tSubject + 1;
                                    }
                                    $totalAllSubject = $tSubject * count($settingExam);
                                    echo ini_round($totalAllSubjectMark / $totalAllSubject);
                                ?>
                            </b>
                        </td>
                    </tr>
                    <!-- <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_gpa')?></td>
                        <td colspan="<?=$totalColumn-$leftColumn?>">
                            <?php 
                                echo ini_round($total_gpa_point / $tSubject);
                            ?>
                        </td>
                    </tr> -->
                    <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_from_teacher_remarks')?></td>
                        <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                    </tr>
                    <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_house_teacher_remarks')?></td>
                        <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                    </tr>
                    <tr>
                        <td colspan="<?=$leftColumn?>"><?=$this->lang->line('progresscardreport_principal_remarks')?></td>
                        <td colspan="<?=$totalColumn-$leftColumn?>"></td>
                    </tr>

                    <tr>
                        <td colspan="<?=$totalColumn?>">
                            <?=$this->lang->line('progresscardreport_interpretation')?> :
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
    <div class="notfound">
        <p><?=$this->lang->line('progresscardreport_data_not_found')?></p>
    </div>
<?php } ?>
</body>
</html>