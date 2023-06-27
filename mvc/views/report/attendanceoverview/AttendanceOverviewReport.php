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
?>
<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $monthdays = $monthID;
            $monthday = explode('-',$monthdays);
            $monthID = '01-'.$monthID;
            if($siteinfos->attendance == 'subject') {
                $pdfurl = 'attendanceoverviewreport/pdf/'.$usertype.'/'.$classesID.'/'.$sectionID.'/'.$subjectID.'/'.$userID.'/'.strtotime($monthID);
                $xmlurl = 'attendanceoverviewreport/xlsx/'.$usertype.'/'.$classesID.'/'.$sectionID.'/'.$subjectID.'/'.$userID.'/'.strtotime($monthID);
            } else {
                $pdfurl = 'attendanceoverviewreport/pdf/'.$usertype.'/'.$classesID.'/'.$sectionID.'/'.$userID.'/'.strtotime($monthID);
                $xmlurl = 'attendanceoverviewreport/xlsx/'.$usertype.'/'.$classesID.'/'.$sectionID.'/'.$userID.'/'.strtotime($monthID);
            }

            $pdf_preview_uri = base_url($pdfurl);
            $xml_preview_uri = base_url($xmlurl);

            echo btn_printReport('attendanceoverviewreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('attendanceoverviewreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('attendanceoverviewreport', $xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('attendanceoverviewreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <!-- form start -->
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('attendanceoverviewreport_reportfor')?> <?=$this->lang->line('attendanceoverviewreport_type')?> - <?=$attendanceoverviewreport_reportfor?> ( <?php if($usertype == 1 && $siteinfos->attendance == 'subject') { if(isset($subjects[$subjectID])) { echo $subjects[$subjectID]." , "; } } ?> <?=$monthArray[$monthday[0]].' '.$monthday[1]?> ) </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($usertype == '1') { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left">
                                    <?php 
                                        echo $this->lang->line('attendanceoverviewreport_class')." : ";
                                        echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('balancefeesreport_all_class');
                                    ?>
                                </h5>                         
                                <h5 class="pull-right">
                                    <?php
                                       echo $this->lang->line('attendanceoverviewreport_section')." : ";
                                       echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('attendanceoverviewreport_select_all_section');
                                    ?>
                                </h5>                        
                            </div>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12"></div>
                <?php }?>
                <div class="col-sm-12">
                    <?php if(count($users)) {
                        $getDayOfMonth = date('t', mktime(0, 0, 0, $monthday[0], 1, $monthday[1]));  ?>
                        <div id="hide-table">
                            <table class="attendance_table">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('attendanceoverviewreport_slno')?></th>
                                        <th>
                                        <?php   
                                            if($usertype =='1') {
                                                echo $this->lang->line('attendanceoverviewreport_student');
                                            } elseif ($usertype == '2') {
                                                echo $this->lang->line('attendanceoverviewreport_teacher');
                                            } elseif($usertype == '3') {
                                                echo $this->lang->line('attendanceoverviewreport_user');
                                            }
                                        ?> / <?=$this->lang->line('attendanceoverviewreport_date')?></th>
                                        <?php if($usertype == 1) { ?>
                                            <td><?=$this->lang->line('attendanceoverviewreport_roll')?></td>                            
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
                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_slno')?>"><?=$i?></td>
                                        <td data-title="
                                        <?php echo $this->lang->line('attendanceoverviewreport_date')." / ";
                                            if($usertype =='1') {
                                                echo $this->lang->line('attendanceoverviewreport_student');
                                            } elseif ($usertype == '2') {
                                                echo $this->lang->line('attendanceoverviewreport_teacher');
                                            } elseif($usertype == '3') {
                                                echo $this->lang->line('attendanceoverviewreport_user');
                                            }
                                        ?>">
                                            <?=($usertype == 1) ? $user->srname : $user->name?>
                                        </td>
                                        <?php if($usertype == 1) { ?>
                                            <td data-title="<?=$this->lang->line('attendanceoverviewreport_roll')?>"><?=$user->srroll?></td>                            
                                        <?php } ?>
                                        <?php if($usertype == 1) {
                                            $userleaveapplications = isset($leaveapplications[$user->srstudentID]) ? $leaveapplications[$user->srstudentID] : [];
                                            if($siteinfos->attendance == 'subject') {
                                                if(isset($attendances[$user->srstudentID])) {
                                                    for($j=1; $j <= $getDayOfMonth; $j++) { $atten = "a".$j; 
                                                        $currentDate = sprintf("%02d", $j).'-'.$monthdays;
                                                    ?>
                                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                        <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                                    }
                                                                    elseif($attendances[$user->teacherID]->$atten == 'P') {
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
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                                                    }
                                                                    elseif($attendances[$user->userID]->$atten == 'P') {
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
                                                    <td data-title="<?=$this->lang->line('attendanceoverviewreport_'."$j")?>">
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
                                        <td data-title="H"><span class="ini-text-holiday"><?=$holidayCount?></span></td>
                                        <td data-title="W"><span class="ini-text-weekenday"><?=$weekendayCount?></span></td>
                                        <td data-title="W"><span class="ini-text-weekenday"><?=$leavedayCount?></span></td>
                                        <td data-title="P"><span class="ini-text-present"><?=$presentCount?></span></td>
                                        <td data-title="LE"><span class="ini-text-lateex"><?=$lateexcuseCount?></span></td>
                                        <td data-title="L"><span class="ini-text-late"><?=$lateCount?></span></td>
                                        <td data-title="A"><span class="ini-text-absent"><?=$absentCount?></span></td>
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
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>

            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('attendanceoverviewreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('attendanceoverviewreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('attendanceoverviewreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("attendanceoverviewreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendanceoverviewreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("attendanceoverviewreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("attendanceoverviewreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'usertype'   : '<?=$usertype?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'subjectID'  : '<?=$subjectID?>',
            'userID'     : '<?=$userID?>',
            'monthID'    : '<?=$monthdays?>'
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('attendanceoverviewreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        $.each(response, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value)
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
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>