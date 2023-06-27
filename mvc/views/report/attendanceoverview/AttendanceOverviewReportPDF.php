<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?php
    $monthArray = array(
      "01" => $this->lang->line('attendanceoverviewreport_january'),
      "02" => $this->lang->line('attendanceoverviewreport_february'),
      "03" => $this->lang->line('attendanceoverviewreport_march'),
      "04" => $this->lang->line('attendanceoverviewreport_april'),
      "05" => $this->lang->line('attendanceoverviewreport_may'),
      "06" => $this->lang->line('attendanceoverviewreport_june'),
      "07" => $this->lang->line('attendanceoverviewreport_july'),
      "08" => $this->lang->line('attendanceoverviewreport_august'),
      "09" => $this->lang->line('attendanceoverviewreport_september'),
      "10" => $this->lang->line('attendanceoverviewreport_october'),
      "11" => $this->lang->line('attendanceoverviewreport_november'),
      "12" => $this->lang->line('attendanceoverviewreport_december'),
    );

    $monthdays = $monthID;
    $monthday = explode('-',$monthdays);
?>
    <!-- form start -->
    <div class="col-sm-12">
        <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
    </div>
    <h3 class="box-title text-navy"> <?=$this->lang->line('attendanceoverviewreport_reportfor')?> <?=$this->lang->line('attendanceoverviewreport_type')?> - <?=$attendanceoverviewreport_reportfor?> ( <?php if($usertype ==1 && $siteinfos->attendance == 'subject') { if(isset($subjects[$subjectID])) { echo $subjects[$subjectID]." , "; } } ?> <?=$monthArray[$monthday[0]].' '.$monthday[1]?> ) </h3>
    <?php if($usertype == '1') { ?>
        <div class="col-sm-12">
            <h5 class="pull-left"><?=$this->lang->line('attendanceoverviewreport_class');?> : <?=isset($classes[$classesID]) ? $classes[$classesID] :  ''?></h5>
            <?php if($sectionID == 0) {?>
                <h5 class="pull-right"><?=$this->lang->line('attendanceoverviewreport_section')?> : <?=$this->lang->line('attendanceoverviewreport_select_all_section');?></h5>
            <?php } else { ?>
                <h5 class="pull-right"><?=$this->lang->line('attendanceoverviewreport_section')?> : <?=isset($sections[$sectionID]) ? $sections[$sectionID] : '' ?></h5>
            <?php } ?>
        </div>
    <?php } else { ?>
        <div class="col-sm-12"></div>
    <?php }?>
    <div class="col-sm-12">
        <?php if(count($users)) { 
            $getDayOfMonth = date('t', mktime(0, 0, 0, $monthday[0], 1, $monthday[1])); ?>
            <div id="hide-table">
                <table class="attendance_table">
                    <thead>
                        <tr>
                            <th><?=$this->lang->line('attendanceoverviewreport_slno')?></th>
                            <th style="width:140px">
                            <?php
                                if($usertype =='1') {
                                    echo $this->lang->line('attendanceoverviewreport_student');
                                } elseif ($usertype == '2') {
                                    echo $this->lang->line('attendanceoverviewreport_teacher');
                                } elseif($usertype == '3') {
                                    echo $this->lang->line('attendanceoverviewreport_user');
                                }
                            ?>
                            /
                            <?=$this->lang->line('attendanceoverviewreport_date')?></th>
                            <?php if($usertype == 1) { ?>
                                <th><?=$this->lang->line('attendanceoverviewreport_roll')?></th>                            
                            <?php } ?>
                            <?php for($i=1; $i <= $getDayOfMonth; $i++) { ?>
                                <th><?=$this->lang->line('attendanceoverviewreport_'."$i")?></th>
                            <?php } ?>
                            <th><?=$this->lang->line('attendanceoverviewreport_h')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_w')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_la')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_p')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_le')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_l')?></th>
                            <th><?=$this->lang->line('attendanceoverviewreport_a')?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $i = 0; foreach($users  as $user) { 
                        $holidayCount = 0;
                        $weekendayCount = 0;
                        $leavedayCount = 0;
                        $presentCount = 0;
                        $lateexcuseCount = 0;
                        $lateCount = 0;
                        $absentCount = 0;

                        $i++;?>
                        <tr>
                            <td><?=$i?></td>
                            <td class="text-left">
                                <span><?=($usertype == 1) ? $user->srname : $user->name?></span>
                            </td>
                            <?php if($usertype == 1) { ?>
                                <td><?=$user->srroll?></td>                            
                            <?php } ?>
                            <?php if($usertype == 1) {
                                $userleaveapplications = isset($leaveapplications[$user->srstudentID]) ? $leaveapplications[$user->srstudentID] : [];
                                if($siteinfos->attendance == 'subject') {
                                    if(isset($attendances[$user->srstudentID])) {
                                        for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                            $currentDate = sprintf("%02d", $j).'-'.$monthdays; ?>
                                            <td>
                                                <?php 
                                                    if(in_array($currentDate, $getHolidays)) {
                                                        echo "<span class='ini-text-holiday'>H</span>";
                                                        $holidayCount++;
                                                    } else {
                                                        if(in_array($currentDate, $getWeekendDays)) {
                                                            echo "<span class='ini-text-weekenday'>W</span>";
                                                            $weekendayCount++;
                                                        } elseif(in_array($currentDate, $userleaveapplications)) {
                                                            echo "<span class='ini-text-present'>LA</span>";
                                                            $leavedayCount++;
                                                        } else {
                                                            if($attendances[$user->srstudentID]->$atten == NULL) { 
                                                                echo "<span class='ini-text-not-assign'>N/A</span>";
                                                            }
                                                            elseif($attendances[$user->srstudentID]->$atten == 'P') {
                                                                echo "<span class='ini-text-present'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $presentCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'LE') {
                                                                echo "<span class='ini-text-lateex'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $lateexcuseCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'L') {
                                                                echo "<span class='ini-text-late'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $lateCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'A') {
                                                                echo "<span class='ini-text-absent'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $absentCount++;
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        <?php }
                                    } else {
                                        for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                            $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                        ?>
                                            <td>
                                                <?php 
                                                    if(in_array($currentDate, $getHolidays)) {
                                                        echo "<span class='ini-text-holiday'>H</span>";
                                                        $holidayCount++;
                                                    } else {
                                                        if(in_array($currentDate, $getWeekendDays)) {
                                                            echo "<span class='ini-text-weekenday'>W</span>";
                                                            $weekendayCount++;
                                                        } elseif(in_array($currentDate, $userleaveapplications)) {
                                                            echo "<span class='ini-text-present'>LA</span>";
                                                            $leavedayCount++;
                                                        } else {
                                                            echo "<span class='ini-text-not-assign'>N/A</span>";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        <?php }
                                    }
                                } else {
                                    if(isset($attendances[$user->srstudentID])) {
                                        for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                            $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                        ?>
                                            <td>
                                                <?php
                                                    if(in_array($currentDate, $getHolidays)) {
                                                        echo "<span class='ini-text-holiday'>H</span>";
                                                        $holidayCount++;
                                                    } else {
                                                        if(in_array($currentDate, $getWeekendDays)) {
                                                            echo "<span class='ini-text-weekenday'>W</span>";
                                                            $weekendayCount++;
                                                        } elseif(in_array($currentDate, $userleaveapplications)) {
                                                            echo "<span class='ini-text-present'>LA</span>";
                                                            $leavedayCount++;
                                                        } else {
                                                            if($attendances[$user->srstudentID]->$atten == NULL) { 
                                                                echo "<span class='ini-text-not-assign'>N/A</span>";
                                                            }
                                                            elseif($attendances[$user->srstudentID]->$atten == 'P') {
                                                                echo "<span class='ini-text-present'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $presentCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'LE') {
                                                                echo "<span class='ini-text-lateex'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $lateexcuseCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'L') {
                                                                echo "<span class='ini-text-late'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $lateCount++;
                                                            } elseif($attendances[$user->srstudentID]->$atten == 'A') {
                                                                echo "<span class='ini-text-absent'>".$attendances[$user->srstudentID]->$atten."</span>";
                                                                $absentCount++;
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        <?php }
                                    } else {
                                        for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                            $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                        ?>
                                            <td>
                                                <?php 
                                                    if(in_array($currentDate, $getHolidays)) {
                                                        echo "<span class='ini-text-holiday'>H</span>";
                                                        $holidayCount++;
                                                    } else {
                                                        if(in_array($currentDate, $getWeekendDays)) {
                                                            echo "<span class='ini-text-weekenday'>W</span>";
                                                            $weekendayCount++;
                                                        } elseif(in_array($currentDate, $userleaveapplications)) {
                                                            echo "<span class='ini-text-present'>LA</span>";
                                                            $leavedayCount++;
                                                        } else {
                                                            echo "<span class='ini-text-not-assign'>N/A</span>";
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        <?php }
                                    }
                                }
                            } elseif($usertype == 2) {
                                $userleaveapplications = isset($leaveapplications[$user->teacherID]) ? $leaveapplications[$user->teacherID] : [];
                                if(isset($attendances[$user->teacherID])) {
                                    for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                    ?>
                                        <td>
                                            <?php 
                                                if(in_array($currentDate, $getHolidays)) {
                                                    echo "<span class='ini-text-holiday'>H</span>";
                                                    $holidayCount++;
                                                } else {
                                                    if(in_array($currentDate, $getWeekendDays)) {
                                                        echo "<span class='ini-text-weekenday'>W</span>";
                                                        $weekendayCount++;
                                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                                        echo "<span class='ini-text-present'>LA</span>";
                                                        $leavedayCount++;
                                                    } else {
                                                        if($attendances[$user->teacherID]->$atten == NULL) { 
                                                            echo "<span class='ini-text-not-assign'>N/A</span>";
                                                        } elseif($attendances[$user->teacherID]->$atten == 'P') {
                                                            echo "<span class='ini-text-present'>".$attendances[$user->teacherID]->$atten."</span>";
                                                            $presentCount++;
                                                        } elseif($attendances[$user->teacherID]->$atten == 'LE') {
                                                            echo "<span class='ini-text-lateex'>".$attendances[$user->teacherID]->$atten."</span>";
                                                            $lateexcuseCount++;
                                                        } elseif($attendances[$user->teacherID]->$atten == 'L') {
                                                            echo "<span class='ini-text-late'>".$attendances[$user->teacherID]->$atten."</span>";
                                                            $lateCount++;
                                                        } elseif($attendances[$user->teacherID]->$atten == 'A') {
                                                            echo "<span class='ini-text-absent'>".$attendances[$user->teacherID]->$atten."</span>";
                                                            $absentCount++;
                                                        }
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php }
                                } else {
                                    for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                    ?>
                                        <td>
                                            <?php 
                                                if(in_array($currentDate, $getHolidays)) {
                                                    echo "<span class='ini-text-holiday'>H</span>";
                                                    $holidayCount++;
                                                } else {
                                                    if(in_array($currentDate, $getWeekendDays)) {
                                                        echo "<span class='ini-text-weekenday'>W</span>";
                                                        $weekendayCount++;
                                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                                        echo "<span class='ini-text-present'>LA</span>";
                                                        $leavedayCount++;
                                                    } else {
                                                        echo "<span class='ini-text-not-assign'>N/A</span>";
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php }
                                }
                            } elseif($usertype == 3) {
                                $userleaveapplications = isset($leaveapplications[$user->userID]) ? $leaveapplications[$user->userID] : [];
                                if(isset($attendances[$user->userID])) {
                                    for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                    ?>
                                        <td>
                                            <?php 
                                                if(in_array($currentDate, $getHolidays)) {
                                                    echo "<span class='ini-text-holiday'>H</span>";
                                                    $holidayCount++;
                                                } else {
                                                    if(in_array($currentDate, $getWeekendDays)) {
                                                        echo "<span class='ini-text-weekenday'>W</span>";
                                                        $weekendayCount++;
                                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                                        echo "<span class='ini-text-present'>LA</span>";
                                                        $leavedayCount++;
                                                    } else {
                                                        if($attendances[$user->userID]->$atten == NULL) { 
                                                            echo "<span class='ini-text-not-assign'>N/A</span>";
                                                        } elseif($attendances[$user->userID]->$atten == 'P') {
                                                            echo "<span class='ini-text-present'>".$attendances[$user->userID]->$atten."</span>";
                                                            $presentCount++;
                                                        } elseif($attendances[$user->userID]->$atten == 'LE') {
                                                            echo "<span class='ini-text-lateex'>".$attendances[$user->userID]->$atten."</span>";
                                                            $lateexcuseCount++;
                                                        } elseif($attendances[$user->userID]->$atten == 'L') {
                                                            echo "<span class='ini-text-late'>".$attendances[$user->userID]->$atten."</span>";
                                                            $lateCount++;
                                                        } elseif($attendances[$user->userID]->$atten == 'A') {
                                                            echo "<span class='ini-text-absent'>".$attendances[$user->userID]->$atten."</span>";
                                                            $absentCount++;
                                                        }
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php }
                                } else {
                                    for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                    ?>
                                        <td>
                                            <?php 
                                                if(in_array($currentDate, $getHolidays)) {
                                                    echo "<span class='ini-text-holiday'>H</span>";
                                                    $holidayCount++;
                                                } else {
                                                    if(in_array($currentDate, $getWeekendDays)) {
                                                        echo "<span class='ini-text-weekenday'>W</span>";
                                                        $weekendayCount++;
                                                    } elseif(in_array($currentDate, $userleaveapplications)) {
                                                        echo "<span class='ini-text-present'>LA</span>";
                                                        $leavedayCount++;
                                                    } else {
                                                        echo "<span class='ini-text-not-assign'>N/A</span>";
                                                    }
                                                }
                                            ?>
                                        </td>
                                    <?php }
                                }
                            } ?>
                            <td><span class="ini-text-holiday"><?=$holidayCount?></span></td>
                            <td><span class="ini-text-weekenday"><?=$weekendayCount?></span></td>
                            <td><span class="ini-text-present"><?=$leavedayCount?></span></td>
                            <td><span class="ini-text-present"><?=$presentCount?></span></td>
                            <td><span class="ini-text-lateex"><?=$lateexcuseCount?></span></td>
                            <td><span class="ini-text-late"><?=$lateCount?></span></td>
                            <td><span class="ini-text-absent"><?=$absentCount?></span></td>
                        </tr>
                    <?php } ?>         
                    </tbody>
                </table>
            </div>
        </div>
        <?php } else { ?>
        <div class="callout callout-danger">
            <p><b class="text-info"><?=$this->lang->line('attendanceoverviewreport_data_not_found')?></b></p>
        </div>
        <?php } ?>
    <div class="col-sm-12 text-center footerAll">
        <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
    </div>
</body>
</html>