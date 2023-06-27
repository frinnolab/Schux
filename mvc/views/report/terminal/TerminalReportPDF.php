<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
 <?php if(count($studentLists)) { 
    // $remarks = array(
    //     'A*' => 'Outstanding', 'A' => 'Excellent', 
    //     'B' => 'Very Good', 'C' => 'Good', 
    //     'D' => 'Satisfactory', 'E' => 'Acceptable', 
    //     'F' => 'Partially Acceptable', 'G' => 'Insufficient', 'U' => 'Very Insufficient'
    // ); 
    foreach($studentLists as $student) { ?>
    <div class="mainterminalreport">
        <div class="terminal-headers" style="flex-direction: row;">
            <div class="terminal-logo" style="width: 33.33%;">
                <img src="<?=base_url("uploads/images/$siteinfos->photo")?>" alt="" height="150">
            </div>
            <div class="school-name" style="width: 66.67%; text-align: right;">
                <div>
                    <h3><?=$siteinfos->sname?></h3>
                </div>
                <div>
                    <h3><?=$classes_segragations[$student->srclassesID]?></h3>
                </div>
                <div>
                    <p><?=$siteinfos->address?></p>
                </div>
            </div>
        </div>
        <div class="terminal-infos">
        </div>
        <div class="terminal-contents">
            <h4 style="text-align: center;"><?=$this->lang->line('terminalreport_terminal_report')?></h4>
            <table>
                <tr>
                    <td colspan="4"> <strong> <?=$classes[$student->srclassesID]?> <?=$this->lang->line('terminalreport_report_card')?></strong> </td>
                </tr>
                <tr>
                    <td colspan="4"> <strong><?=$examName?></strong> </td>
                </tr>
                <tr>
                    <td colspan="1"> <?=$this->lang->line('terminalreport_candidate_name')?> </td>
                    <td colspan="3"> <strong><?=$student->srname?></strong> </td>
                </tr>
                <tr>
                    <td colspan="1"> <?=$this->lang->line('terminalreport_roll_no')?> </td>
                    <td colspan="3"> <strong><?=$student->srroll?></strong> </td>
                </tr>
                <tr>
                    <td colspan="1"> <?=$this->lang->line('terminalreport_reg_no')?> </td>
                    <td colspan="3"> <strong><?=$student->srregisterNO?></strong> </td>
                </tr>
                <tbody>
                    <tr>
                        <th><?=$this->lang->line('terminalreport_subjects');?></th>
                        <?php
                            reset($markpercentagesArr);
                            $firstindex          = key($markpercentagesArr);
                            $uniquepercentageArr = isset($markpercentagesArr[$firstindex]) ? $markpercentagesArr[$firstindex] : [];
                            $markpercentages     = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            if(count($markpercentages)) { 
                                foreach($markpercentages as $markpercentageID) {
                                    $markpercentage = isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype : '';
                                    // echo "<th>".$markpercentage."</th>";
                            } }
                        ?>
                        <th><?=$this->lang->line('terminalreport_total')?></th>
                        <th><?=$this->lang->line('terminalreport_marks_obtained')?></th>
                        <!-- <th><?=$this->lang->line('terminalreport_position_subject');?></th> -->
                        <th><?=$this->lang->line('terminalreport_grade');?></th>
                        <!-- <th><?=$this->lang->line('terminalreport_remarks');?></th> -->
                    </tr>
                </tbody>
                <tbody>
                    <?php if(count($subjects)) { foreach($subjects as $subject) {
                        
                        if(isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID])) {
                            $uniquepercentageArr =  isset($markpercentagesArr[$subject->subjectID]) ? $markpercentagesArr[$subject->subjectID] : []; ?>
                            <tr>
                                <td><?=$subject->subject?></td>
                                <?php 
                                $percentageMark  = 0;
                                if(count($markpercentages)) { 
                                    foreach($markpercentages as $markpercentageID) {
                                        $f = false;
                                        if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                            $f = true;
                                            $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                        } 
                                        ?>
                                        <td>100</td>
                                        <td>
                                            <?php 
                                                if(isset($studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID]) && $f) {
                                                    echo $studentPosition[$student->srstudentID]['markpercentageMark'][$subject->subjectID][$markpercentageID];
                                                } else {
                                                    if($f) {
                                                        echo 'Absent';
                                                    }
                                                }
                                            ?>    
                                        </td>
                                <?php } } ?>
                                <!-- <td><?=isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0'?></td> -->
                                <!-- <td>
                                    <?=isset($studentPosition['studentSubjectPositionMark'][$subject->subjectID]) ? addOrdinalNumberSuffix((int)array_search($student->srstudentID, array_keys($studentPosition['studentSubjectPositionMark'][$subject->subjectID])) + 1) : '';?>
                                </td> -->
                                <?php
                                    $subjectMark = isset($studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID]) ? $studentPosition[$student->srstudentID]['subjectMark'][$subject->subjectID] : '0';
                                $subjectMark = markCalculationView($subjectMark, $subject->finalmark, $percentageMark);
                                if(count($grades)) { foreach($grades as $grade) { 
                                    if(($grade->gradefrom <= $subjectMark) && ($grade->gradeupto >= $subjectMark) && $grade->classID == $student->srclassesID) { ?>
                                        <td><?=$grade->grade?></td>
                                        <!-- <td><?=$grade->note?></td> -->
                                        <!-- <td><?=$remarks[$grade->grade]?></td> -->
                                <?php } } } ?>
                            </tr>
                        <?php } ?>
                    <?php } }  ?>
                    <tr>
                        <td><b><?=$this->lang->line('terminalreport_total')?></b></td>
                        <?php if(count($markpercentages)) { 
                            foreach($markpercentages as $markpercentageID) { ?>
                                <td><b><?=isset($studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID]) ? $studentPosition[$student->srstudentID]['markpercentagetotalmark'][$markpercentageID] : '0'?></b></td>
                        <?php } } ?>
                        <!-- <td><b><?=isset($studentPosition[$student->srstudentID]['totalSubjectMark']) ? $studentPosition[$student->srstudentID]['totalSubjectMark'] : '0'?></b></td> -->
                        <td></td>
                        <!-- <td></td> -->
                        <td></td>
                    </tr>
                    
                    <tr>
                        <td colspan="<?=($col-4)?>"><b><?=$this->lang->line('terminalreport_mark_average')?> : <?=isset($studentPosition[$student->srstudentID]['classPositionMark']) ? ini_round($studentPosition[$student->srstudentID]['classPositionMark']) : '0.00'?></b></td>
                        <td colspan="3"><b><?=$this->lang->line('terminalreport_class_average')?> :
                            <?php 
                                if(isset($studentPosition[$student->srstudentID]['classPositionMark']) && $studentPosition[$student->srstudentID]['classPositionMark'] > 0 && isset($studentPosition['totalStudentMarkAverage'])) {
                                    echo ini_round($studentPosition['totalStudentMarkAverage'] / count($studentLists));
                                } else {
                                    echo "0.00";
                                }
                            ?></b>
                        </td>
                    </tr>
                    <?php
                        if (substr($examName, 0, 6) == 'term 3') { ?>
                            <tr>
                                <td colspan="2"><b><?=$this->lang->line('terminalreport_promoted_to');?></b></td>
                                <td colspan="<?=($col-3)?>"></td>
                            </tr>
                    <?php } ?>
                    <!-- <tr>
                        <td colspan="2"><?=$this->lang->line('terminalreport_attendance');?></td>
                        <td colspan="<?=($col-2)?>"><?=isset($attendance[$student->srstudentID]) ? $attendance[$student->srstudentID] : '0'?></td>
                    </tr> -->
                    <tr>
                        <td colspan="2"><?=$this->lang->line('terminalreport_class_teacher_remarks')?></td>
                        <td colspan="<?=($col-4)?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?=$this->lang->line('terminalreport_principal_remarks')?></td>
                        <td colspan="<?=($col-4)?>"></td>
                    </tr>
                    <tr>
                        <td colspan="2"><?=$this->lang->line('terminalreport_academic_head')?></td>
                        <td colspan="<?=($col-4)?>"></td>
                    </tr>
                    <tr>
                        <td colspan="<?=($col - 2)?>"><?=$this->lang->line('terminalreport_interpretation')?> :
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
                                }}?>
                            </b>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <p style="page-break-after: always;">&nbsp;</p>
    <?php } } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('terminalreport_data_not_found'); ?>
        </div>
    <?php } ?>
</body>
</html>