<!DOCTYPE html>
<html lang="en">
    <head>
    </head>
<body>
    
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('tabulationsheetreport_report_for')?> - 
            <?=$this->lang->line('tabulationsheetreport_tabulationsheet');?>
        </h3>
    </div><!-- /.box-header -->
    <div class="col-sm-12">
        <h5 class="pull-left">
            <?php 
                echo $this->lang->line('tabulationsheetreport_class')." : ";
                echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('tabulationsheetreport_all_class');
            ?>
        </h5>                         
        <h5 class="pull-right">
            <?php
               echo $this->lang->line('tabulationsheetreport_section')." : ";
               echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('tabulationsheetreport_all_section');
            ?>
        </h5>                        
    </div>
    <?php if(count($marks)) { ?>
    <div class="maintabulationsheetreport">
        <table>
            <thead>
                <tr>
                    <th rowspan="2"><?=$this->lang->line('tabulationsheetreport_name')?></th>
                    <th rowspan="2"><?=$this->lang->line('tabulationsheetreport_roll')?></th>
                    <?php if(count($mandatorysubjects)) { foreach ($mandatorysubjects as $mandatorysubject) { ?>
                        <th colspan="<?=(count($markpercentages) +1)?>"><?=$mandatorysubject->subject?></th>
                    <?php } } ?>

                    <?php if(count($optionalsubjects)) { ?>
                        <th colspan="<?=(count($markpercentages) +1) ?>">
                        <?php 
                        $i = 1; 
                        if(count($optionalsubjects)) {
                            foreach ($optionalsubjects as $optionalsubject) {
                                $expSub = explode(' ', $optionalsubject->subject);
                                if(count($optionalsubjects) == $i) { 
                                    echo $expSub[0]; 
                                } else { 
                                    echo $expSub[0].'/';
                                }
                                $i++; 
                        } } ?>
                        </th>
                    <?php } ?>
                    <!-- <th rowspan="2"><?=$this->lang->line('tabulationsheetreport_gpa')?></th> -->
                </tr>

                <tr>
                <?php if(count($mandatorysubjects)) { foreach($mandatorysubjects as $mandatorysubject) {
                    if(count($markpercentages)) { foreach ($markpercentages as $markpercentageID) { ?>
                        <th><?=isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype[0] : ''?></th>
                    <?php } } ?>
                    <th><?=$this->lang->line('tabulationsheetreport_total')?></th>
                <?php } } ?>

                <?php if(count($optionalsubjects)) { foreach ($optionalsubjects as $optionalsubject) {
                     if(count($markpercentages)) { foreach ($markpercentages as $markpercentageID) { ?>
                        <th><?=isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->markpercentagetype[0] : ''?></th>
                    <?php } } break; } ?> 
                    <th><?=$this->lang->line('tabulationsheetreport_total')?></th>
                <?php } ?>
                </tr>
            </thead>

            <tbody>
            <?php $studentCount = []; 
                if(count($students)) { foreach($students as $student) { $totalGrade = 0; ?>
                <tr>
                    <td><?=$student->srname?></td>
                    <td><?=$student->srroll?></td>
                    <?php if(count($mandatorysubjects)) { 
                        foreach ($mandatorysubjects as $mandatorysubject) { 
                            $subjectTotal         = 0; 
                            $optionalSubjectTotal = 0;
                            $uniquepercentageArr  = isset($markpercentagesArr[$mandatorysubject->subjectID]) ? $markpercentagesArr[$mandatorysubject->subjectID] : [];
                            $markpercentages      = $uniquepercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'];
                            $percentageMark       = 0;
                            if(count($markpercentages)) { 
                                foreach ($markpercentages as $markpercentageID) { 
                                    $f = false;
                                    if(isset($uniquepercentageArr['own']) && in_array($markpercentageID, $uniquepercentageArr['own'])) {
                                        $f = true;
                                        $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                    } ?>
                            <td>
                                <?php
                                    if(isset($marks[$student->srstudentID][$mandatorysubject->subjectID][$markpercentageID]) && $f) {
                                        if($marks[$student->srstudentID][$mandatorysubject->subjectID][$markpercentageID] > 0) {
                                            echo $marks[$student->srstudentID][$mandatorysubject->subjectID][$markpercentageID];
                                            $subjectTotal += $marks[$student->srstudentID][$mandatorysubject->subjectID][$markpercentageID];
                                        } else {
                                            echo 0;
                                        }
                                    } else {
                                        echo 0;
                                    }
                                ?>
                            </td>
                        <?php } } ?>
                        <td>
                            <?php 
                                echo $subjectTotal;
                                $subjectTotal = markCalculationView($subjectTotal, $mandatorysubject->finalmark, $percentageMark);
                                if(count($grades)) {
                                    foreach ($grades as $grade) {
                                        if($grade->gradefrom <= $subjectTotal && $grade->gradeupto >= $subjectTotal) {
                                            $totalGrade += $grade->point;
                                            break;
                                        }
                                    }
                                }
                            ?>
                        </td>
                    <?php } } ?>

                    <?php if(count($optionalsubjects)) { foreach ($optionalsubjects as $optionalsubject) { 
                        if((int)$student->sroptionalsubjectID) {
                            if($student->sroptionalsubjectID == $optionalsubject->subjectID) {
                                $opuniquepercentageArr = [];
                                $opuniquepercentageArr = isset($markpercentagesArr[$student->sroptionalsubjectID]) ? $markpercentagesArr[$student->sroptionalsubjectID] : [];

                                $percentageMark  = 0;
                                if(count($markpercentages)) { foreach ($markpercentages as $markpercentageID) { 
                                    $f = false;
                                    if(isset($opuniquepercentageArr['own']) && in_array($markpercentageID, $opuniquepercentageArr['own'])) {
                                        $f = true;
                                        $percentageMark   += isset($percentageArr[$markpercentageID]) ? $percentageArr[$markpercentageID]->percentage : 0;
                                    } ?>
                                    <td>
                                        <?php
                                            if(isset($marks[$student->srstudentID][$optionalsubject->subjectID][$markpercentageID]) && $f) {
                                                if($marks[$student->srstudentID][$optionalsubject->subjectID][$markpercentageID] > 0) {
                                                    echo $marks[$student->srstudentID][$optionalsubject->subjectID][$markpercentageID];
                                                    $optionalSubjectTotal += $marks[$student->srstudentID][$optionalsubject->subjectID][$markpercentageID];
                                                } else {
                                                    echo 0;
                                                }
                                            } else {
                                                echo 0;
                                            }
                                        ?>
                                    </td>
                                    <?php $studentCount[$student->srstudentID] = TRUE; 
                                } } ?>
                                <td>
                                    <?php
                                        echo $optionalSubjectTotal;
                                        $optionalSubjectTotal = markCalculationView($optionalSubjectTotal, $optionalsubject->finalmark, $percentageMark);
                                        if(count($grades)) {
                                            foreach ($grades as $grade) {
                                                if($grade->gradefrom <= $optionalSubjectTotal && $grade->gradeupto >= $optionalSubjectTotal) {
                                                    $totalGrade += $grade->point;
                                                    break;
                                                }
                                            }
                                        }
                                    ?>
                                </td>
                        <?php } } else { 
                            if(!isset($studentCount[$student->srstudentID])) { 
                                $studentCount[$student->srstudentID] = TRUE; 
                                if(count($markpercentages)) { foreach ($markpercentages as $markpercentageID) { ?>
                                    <td><?php echo 0; ?></td>
                                <?php } } ?>
                                <td><?=0?></td>
                        <?php } } } } ?>

                    <!-- <td>
                        <?php
                            $optSub = 0;
                            $manSub = count($mandatorysubjects);
                            if($student->sroptionalsubjectID != 0) {
                                $optSub = 1;
                            }

                            $avg      = 0;
                            $totalSub = $manSub+$optSub;
                            if($totalSub > 0) {
                                $avg = ($totalGrade/$totalSub);
                            }
                            echo ini_round($avg);
                        ?>
                    </td> -->
                </tr>
            <?php } } ?>
            </tbody>
        </table>
    </div>
    <?php } else { ?>
        <div class="notfound">
            <?php echo $this->lang->line('tabulationsheetreport_data_not_found'); ?>
        </div>
    <?php } ?>
    <div class="col-sm-12 text-center footerAll">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    
</body>
</html>