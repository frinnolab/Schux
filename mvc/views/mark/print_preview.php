<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?php
        $optionalsubjectID = $student->sroptionalsubjectID;
        $opmarkpercentageArr = [];
        if(count($marksettings)) {
            foreach ($marksettings as $examID => $marksetting) {
    ?>
    <table class="table table-striped table-bordered">
        <th>
            <tr>
                <td style="border:none" width="30%">
                    <div class="siteLogo">
                        <img class="siteLogoimg" src="<?= base_url('uploads/images/' . $siteinfos->photo) ?>" alt="">
                    </div>
                </td>
                <td colspan="2" class="text-center" style="border:none">
                    <div class="siteTitle">
                        <h2><?= $siteinfos->sname ?></h2>
                        <address>
                            <?= $siteinfos->address ?><br/>
                        </address>
                    </div>
                </td>
            </tr>
        </th>
        <tbody>
            <tr>
                <td colspan="4" class="text-center">
                    <strong><?=count($classes) ? $classes->classes : ''?> Report Card</strong>
                </td>
            </tr>
            <tr>
                <td colspan="4" class="text-center">
                    <strong><?=(isset($exams[$examID]) ? $exams[$examID] : '')?></strong>
                </td>
            </tr>
            <tr>
                <td><?=$this->lang->line('mark_candidate')?></td>
                <td colspan="3" ><?=$student->srname?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('mark_roll')?></td>
                <td colspan="3" ><?=$student->srroll?></td>
            </tr>
            <tr>
                <td><?=$this->lang->line('mark_unique_number')?></td>
                <td colspan="3" ><?=$student->unique_number?></td>
            </tr>
            <tr>
                <th><?=$this->lang->line('mark_subject')?></th>
                <th><?=$this->lang->line('mark_max_mark')?></th>
                <th><?=$this->lang->line('mark_obtained_mark')?></th>
                <th><?=$this->lang->line('mark_grade')?></th>
            </tr>

            <?php
                foreach ($marksetting as $subjectID => $markpercentageArr) {
                    if($subjectID == $optionalsubjectID) {
                        $opmarkpercentageArr = $markpercentageArr;
                    }
                    if(!in_array($subjectID, $optionalsubjectArr)) {
            ?>

            <tr>
                <td>
                    <?=isset($subjects[$subjectID]) ? $subjects[$subjectID]->subject : '';?>
                </td>
                <?php
                    $subjectfinalmark = isset($subjects[$subjectID]) ? (int)$subjects[$subjectID]->finalmark : 0;
                    $totalSubjectMark = 0;
                    $percentageMark   = 0;

                    foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                        $f = false;
                        if(isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                            $f = true;
                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                        }
                ?>
                <td>
                    <?=$markpercentages[$markpercentageID]->percentage;?>
                </td>
                <td>
                    <?php
                        if(isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                            $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                            echo $totalSubjectMark;
                        } else {
                            if($f) {
                                $totalSubjectMark;
                            }
                        }
                        $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                        
                        $totalMark        += $totalSubjectMark;
                        $totalFinalMark   += $finalpercentageMark;
                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                        // echo $totalSubjectMark;
                    ?>
                </td>
                <td>
                    <?php
                        if(count($grades)) {
                            foreach ($grades as $grade) {
                                if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark) && ($grade->classID == $student->classesID)) {
                                    echo $grade->grade;
                                }
                            }
                        }
                    ?>
                </td>
                
                <?php } ?>
            </tr>

            <?php
                }
            }
                if(($optionalsubjectID > 0) && count($opmarkpercentageArr)) {
                    $totalSubject++;
            ?>
            <tr>
                <td>
                    <?=isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->subject : '';?>
                </td>
                <?php
                    $subjectfinalmark = isset($subjects[$optionalsubjectID]) ? (int)$subjects[$optionalsubjectID]->finalmark : 0;
                    $totalSubjectMark = 0;
                    $percentageMark   = 0;

                    foreach ($opmarkpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                        $f = false;
                        if(isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own'])) {
                            $f = true;
                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                        }
                ?>
                <td>
                    <?php
                        if(isset($highestmarks[$examID][$optionalsubjectID][$markpercentageID]) && ($highestmarks[$examID][$optionalsubjectID][$markpercentageID] != -1) && $f) {
                            echo $highestmarks[$examID][$optionalsubjectID][$markpercentageID];
                        } else {
                             if($f) {
                                echo 'N/A';
                            }
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if(isset($marks[$examID][$optionalsubjectID][$markpercentageID]) && $f) {
                            $totalSubjectMark += $marks[$examID][$optionalsubjectID][$markpercentageID];
                        } else {
                            if($f) {
                                $totalSubjectMark;
                            }
                        }
                        $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);

                        
                        $totalMark        += $totalSubjectMark;
                        $totalFinalMark   += $finalpercentageMark;
                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                        echo $totalSubjectMark;
                    ?>
                </td>
                <td>
                    <?php
                        if(count($grades)) {
                            foreach ($grades as $grade) {
                                if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark) && ($grade->classID == $student->classesID)) {
                                    echo $grade->grade;
                                }
                            }
                        }
                    ?>
                </td>
                
                <?php } ?>
            </tr>

            <?php } ?>

            <tr>
                <td class="tfoot"><?=$this->lang->line('mark_teacher_signature')?></td>
                <td colspan="3" class="tfoot"></td>
            </tr>
            <tr>
                <td class="tfoot"><?=$this->lang->line('mark_head_teacher_signature')?></td>
                <td colspan="3" class="tfoot"></td>
            </tr>
            <tr>
                <td class="tfoot"><?=$this->lang->line('mark_remarks')?></td>
                <td colspan="3" class="tfoot"></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped table-bordered">
        <tr>
            <?php
                foreach ($grades as $grade) {
                    if($grade->classID == $student->srclassesID) {
                        echo "<td>".$grade->grade."</td>";
                        echo "<td>".$grade->gradefrom." - ".$grade->gradeupto."</td>";
                    }
                }
            ?>
        </tr>
    </table>
    <p style="page-break-after: always"></p>
    <?php
        }}
    ?>
</body>
</html>
