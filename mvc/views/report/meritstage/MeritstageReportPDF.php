<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
</head>
<body>
	<div class="mainmeritstagereport">
        <div class="meritstage_headers">
            <div class="meritstage_logo">
                <img class="logo" src="<?=base_url("uploads/images/$siteinfos->photo")?>" alt="">
            </div>
            <div class="school_name">
                <h2><?=$siteinfos->sname?></h2>
            </div>
        </div>
        <div class="meritstage_infos">
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
                        <?php $optional_column = 0; 
                        if(count($subjects)) { foreach($subjects as $subject) { if($subject->type != 1) { ?>
                        <tr>
                            <td><?=$subject->subject_code?></td>
                            <td><?=substr($subject->subject,0,3)?></td>
                            <td><?=$subject->subject?></td>
                        </tr>
                        <?php $optional_column++; } } } ?>
                    </table>
                <?php } ?>
            </div>
        </div>

        <div class="meritstage_contents">
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
                        <td><?=isset($studentPosition[$studentID]['classPositionMark']) ? ini_round($studentPosition[$studentID]['classPositionMark']) : '' ?></td>
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
</body>
</html>