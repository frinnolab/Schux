<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
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
                                <img class="profileimg" src="<?=imagelink($student->photo)?>" alt="">
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
                    <div class="notfound">
                        <p><b class="text-info"><?=$this->lang->line('studentsessionreport_data_not_found')?></b></p>
                    </div>
                <?php } ?>
</body>
</html>