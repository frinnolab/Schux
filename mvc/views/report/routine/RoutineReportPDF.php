<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <div><?=reportheader($siteinfos, $schoolyearsessionobj, true)?></div>
    <div><h3> <?=$this->lang->line('routinereport_report_for')?> - <?=$this->lang->line('routinereport_routine')?> ( <?=ucwords($routinefor)?> ) </h3></div>
    <div>
    <?php if(($routinefor == 'student') && count($classes) && isset($sections[$get_section])) { ?>
        <p class="pull-left"><?=$this->lang->line('routinereport_class')?> : <?=isset($classes->classes) ? $classes->classes : ''?></p>                         
        <p class="pull-right"><?=$this->lang->line('routinereport_section')?> : <?=isset($sections[$get_section]) ? $sections[$get_section] : '' ?></p>
    <?php } elseif(($routinefor == 'teacher') && count($teacher)) { ?>
        <p class="pull-left"><?=$this->lang->line('routinereport_name')?> : <?=$teacher->name?></p>   
        <p class="pull-right"><?=$this->lang->line('routinereport_designation')?> : <?=$teacher->designation?></p>
    <?php } ?>
    </div>
    <div>
        <?php if(count($routines)) {
            $maxClass = 0; 
            foreach ($routines as $routineKey => $routine) { 
                if(count($routine) > $maxClass) {
                    $maxClass = count($routine);
                }
            }
            
            $days = [
                0 => $this->lang->line('sunday'),
                1 => $this->lang->line('monday'),
                2 => $this->lang->line('tuesday'),
                3 => $this->lang->line('wednesday'),
                4 => $this->lang->line('thursday'),
                5 => $this->lang->line('friday'),
                6 => $this->lang->line('saturday'),
            ];
        ?>    
        <div>
            <table>
                <thead>
                    <tr>
                        <th><?=$this->lang->line('routinereport_day');?></th>
                        <?php for($i=1; $i <= $maxClass; $i++) { ?>
                            <th><?= addOrdinalNumberSuffix($i)." ".$this->lang->line('routinereport_period');?></th>
                        <?php } ?>
                    </tr>
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
            <div class="notfound">
                <?php echo $this->lang->line('routinereport_data_not_found'); ?>
            </div>
       <?php } ?>
    </div>
    <div><?=reportfooter($siteinfos, $schoolyearsessionobj, true)?></div>
    </div>
</body>
</html>