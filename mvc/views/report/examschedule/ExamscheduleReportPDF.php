<div class="box">
    <div id="printablediv">
        <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
                </div>
                <div class="box-header bg-gray">
                    <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('examschedulereport_report_for')?> - <?=$this->lang->line('examschedulereport_examschedule')?> ( <?=isset($exams[$examID]) ? $exams[$examID] : ''?> ) </h3>
                </div><!-- /.box-header -->
                <div class="col-sm-12">
                    <?php if($classesID >= 0 && $sectionID >=0) { ?>
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
                    <?php } ?>
                </div>
                <div class="col-sm-12">
                <?php if(count($examschedule_reports)) {?>
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
                                            <th><?=date('d M Y',strtotime($exam_date))?></th>
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
                                                                        if($j < $subject_count) { ?>
                                                                            <hr style="height:2px;color:#ddd;margin:5px">
                                                                           <?php $j++;
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
                <?php } else { ?>
                    <div class="notfound">
                        <p><?=$this->lang->line('examschedulereport_data_not_found')?></p>
                    </div>
                <?php } ?>
                </div>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->

    </div>
</div>
