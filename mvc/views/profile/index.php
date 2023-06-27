<?php if(count($profile)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?=btn_add_pdf('profile/print_preview', $this->lang->line('pdf_preview'));?>
                <?=(($siteinfos->profile_edit == 1) || ($this->session->userdata('usertypeID') == 1 && $this->session->userdata('loginuserID') == 1)) ? btn_sm_edit('profile/edit', $this->lang->line('edit')) : '' ?> 
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li class="active"><?=$this->lang->line('profile')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($profile->photo)?>
                        <h3 class="profile-username text-center">
                            <?php if($profile->usertypeID == 3) {
                                echo $profile->srname;
                            } else {
                                echo $profile->name;
                            } ?>    
                        </h3>
                        <?php if($profile->usertypeID == 2) { ?>
                            <p class="text-muted text-center"><?=$profile->designation?></p>
                        <?php } else { ?>
                            <p class="text-muted text-center"><?=isset($usertypes[$profile->usertypeID]) ? $usertypes[$profile->usertypeID] : ''?></p>
                        <?php } ?>
                        <ul class="list-group list-group-unbordered">
                            <?php if($profile->usertypeID == 4) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                                </li>
                            <?php } elseif($profile->usertypeID == 3) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_registerNO')?></b> <a class="pull-right"><?=$profile->srregisterNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_roll')?></b> <a class="pull-right"><?=$profile->srroll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_classes')?></b> <a class="pull-right"><?=count($class) ? $class->classes : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_section')?></b> <a class="pull-right"><?=count($sectionn) ? $sectionn->section : ''?></a>
                                </li>
                            <?php } else { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_sex')?></b> <a class="pull-right"><?=$profile->sex?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_dob')?></b> <a class="pull-right"><?=date('d M Y',strtotime($profile->dob))?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('profile_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('profile_profile')?></a></li>

                        <?php if($profile->usertypeID == 2) { ?>
                            <li><a href="#routine" data-toggle="tab"><?=$this->lang->line('profile_routine')?></a></li>
                        <?php } ?>

                        <?php if($profile->usertypeID == 1) { ?>
                            <?php if(count($manage_salary)) { ?>
                                <li><a href="#salary" data-toggle="tab"><?=$this->lang->line('profile_salary')?></a></li>
                                <li><a href="#payment" data-toggle="tab"><?=$this->lang->line('profile_payment')?></a></li>
                            <?php } ?>
                            <li><a href="#document" data-toggle="tab"><?=$this->lang->line('profile_document')?></a></li>
                        <?php } elseif($profile->usertypeID == 3) { ?>


                            <?php if(count($parents)) { ?> 
                                <li><a href="#parents" data-toggle="tab"><?=$this->lang->line('profile_parents')?></a></li> 
                            <?php } ?>

                            <li><a href="#routine" data-toggle="tab"><?=$this->lang->line('profile_routine')?></a></li>
                            <li><a href="#attendance" data-toggle="tab"><?=$this->lang->line('profile_attendance')?></a></li>
                            <li><a href="#mark" data-toggle="tab"><?=$this->lang->line('profile_mark')?></a></li>
                            <li><a href="#invoice" data-toggle="tab"><?=$this->lang->line('profile_invoice')?></a></li>
                            <li><a href="#payment" data-toggle="tab"><?=$this->lang->line('profile_payment')?></a></li>
                            <li><a href="#document" data-toggle="tab"><?=$this->lang->line('profile_document')?></a></li>


                        <?php } elseif($profile->usertypeID == 4) { ?>
                            <li><a href="#children" data-toggle="tab"><?=$this->lang->line('profile_children')?></a></li>
                            <li><a href="#document" data-toggle="tab"><?=$this->lang->line('profile_document')?></a></li>
                        <?php } else { ?>
                            <li><a href="#attendance" data-toggle="tab"><?=$this->lang->line('profile_attendance')?></a></li>
                            <?php if(count($manage_salary)) { ?>
                                <li><a href="#salary" data-toggle="tab"><?=$this->lang->line('profile_salary')?></a></li>
                                <li><a href="#payment" data-toggle="tab"><?=$this->lang->line('profile_payment')?></a></li>
                            <?php } ?>
                            <li><a href="#document" data-toggle="tab"><?=$this->lang->line('profile_document')?></a></li>
                        <?php } ?>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="panel-body profile-view-dis">
                                <?php if($profile->usertypeID == 3) { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_studentgroup")?> </span>: <?=count($group) ? $group->group : '' ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_optionalsubject")?> </span>: <?=count($optionalsubject) ? $optionalsubject->subject : ''?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_dob")?> </span>: 
                                            <?php if($profile->dob) { echo date("d M Y", strtotime($profile->dob)); } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_sex")?> </span>: 
                                            <?=$profile->sex?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_bloodgroup")?> </span>: <?php if(isset($allbloodgroup[$profile->bloodgroup])) { echo $profile->bloodgroup; } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_religion")?> </span>: <?=$profile->religion?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_phone")?> </span>: <?=$profile->phone?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_state")?> </span>: <?=$profile->state?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_country")?> </span>: 
                                            <?php if(isset($allcountry[$profile->country])) { echo $allcountry[$profile->country]; } ?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_remarks")?> </span>: <?=$profile->remarks?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_extracurricularactivities")?> </span>: <?=$profile->extracurricularactivities?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>
                                    </div>
                                <?php } elseif($profile->usertypeID == 4) { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_father_name")?> </span>: <?=$profile->father_name?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_father_profession")?> </span>: <?=$profile->father_profession?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_mother_name")?> </span>: <?=$profile->mother_name?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_mother_profession")?> </span>: <?=$profile->mother_profession?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="row">
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_jod")?> </span>: <?=date("d M Y", strtotime($profile->jod))?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_religion")?> </span>: <?=$profile->religion?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$profile->email?></p>
                                        </div>
                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$profile->address?></p>
                                        </div>

                                        <div class="profile-view-tab">
                                            <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$profile->username?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <?php if($profile->usertypeID == 2) { ?>
                            <div class="tab-pane" id="routine">
                                <?php if(count($routines)) {
                                    $days = [
                                        0 => $this->lang->line('sunday'),
                                        1 => $this->lang->line('monday'),
                                        2 => $this->lang->line('tuesday'),
                                        3 => $this->lang->line('wednesday'),
                                        4 => $this->lang->line('thursday'),
                                        5 => $this->lang->line('friday'),
                                        6 => $this->lang->line('saturday'),
                                    ];

                                    $maxClass = 0;
                                    foreach ($routines as $routine) { 
                                        if(count($routine) > $maxClass) {
                                            $maxClass = count($routine);
                                    } } ?>
                                    
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <th class="text-center"><?php echo $this->lang->line('profile_day');?></th>
                                                <?php for($i=1; $i <= $maxClass; $i++) { ?>
                                                    <th class="text-center"><?=addOrdinalNumberSuffix($i)." ".$this->lang->line('profile_period');?></th>
                                                <?php } ?>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($days as $dayKey=> $day) { 
                                                    if(!in_array($dayKey, $routineweekends) && isset($routines[$dayKey])) { $i=0; ?>
                                                    <tr>
                                                        <td><?=$day?></td>
                                                        <?php foreach ($routines[$dayKey] as $routine) { $i++; ?>
                                                            <td class="text-center">
                                                                <p style="margin: 0 0 1px"><?=$routine->start_time;?>-<?=$routine->end_time;?></p>
                                                                <p style="margin: 0 0 1px">
                                                                    <span class="left"><?=$this->lang->line('profile_subject')?> :</span>
                                                                    <span class="right"> 
                                                                        <?=isset($subjects[$routine->subjectID]) ? $subjects[$routine->subjectID] : ''?>
                                                                    </span>
                                                                </p>
                                                                <p style="margin: 0 0 1px">
                                                                    <span class="left"><?=$this->lang->line('profile_class')?> :</span>
                                                                    <span class="right"> 
                                                                        <?=isset($classess[$routine->classesID]) ? $classess[$routine->classesID] : ''?>
                                                                    </span>
                                                                </p>
                                                                <p style="margin: 0 0 1px">
                                                                    <span class="left"><?=$this->lang->line('profile_section')?> :</span>
                                                                    <span class="right"> 
                                                                        <?=isset($sections[$routine->sectionID]) ? $sections[$routine->sectionID] : ''?>
                                                                    </span>
                                                                </p>
                                                                <p style="margin: 0 0 1px"><span class="left"><?= $this->lang->line('profile_room')?> : </span><span class="right"><?=$routine->room;?></span></p>
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
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <?php if($profile->usertypeID == 3) { ?>
                            <div class="tab-pane" id="attendance">
                                <?php
                                    $monthArray = array(
                                      "01" => "jan",
                                      "02" => "feb",
                                      "03" => "mar",
                                      "04" => "apr", 
                                      "05" => "may", 
                                      "06" => "jun",
                                      "07" => "jul",
                                      "08" => "aug",
                                      "09" => "sep",
                                      "10" => "oct", 
                                      "11" => "nov", 
                                      "12" => "dec"
                                    );
                                    
                                    if($setting->attendance == 'subject') { 
                                        if(count($attendancesubjects)) {
                                            foreach ($attendancesubjects as $subject) {
                                                $holidayCount = 0;
                                                $weekendayCount = 0;
                                                $leavedayCount = 0;
                                                $presentCount = 0;
                                                $lateexcuseCount = 0;
                                                $lateCount = 0;
                                                $absentCount = 0;
                                                
                                                if($subject->type == 1) {
                                                    echo "<h4>".$subject->subject."</h4>"; ?>
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="profileDIV">
                                                                <table class="attendance_table">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>#</th>
                                                                            <?php
                                                                                for($i=1; $i<=31; $i++) {
                                                                                   echo  "<th>".$this->lang->line('profile_'.$i)."</th>";
                                                                                }
                                                                            ?>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php
                                                                            $schoolyearstartingdate = $schoolyearsessionobj->startingdate;
                                                                            $schoolyearendingdate = $schoolyearsessionobj->endingdate;

                                                                            $allMonths = get_month_and_year_using_two_date($schoolyearstartingdate, $schoolyearendingdate);
                                                                            $holidaysArray = explode('","',$holidays);

                                                                        

                                                                            foreach($allMonths as $yearKey => $allMonth) {
                                                                                foreach($allMonth as $month) {
                                                                                    $monthAndYear = $month.'-'.$yearKey;
                                                                                    if(isset($attendances_subjectwisess[$subject->subjectID][$monthAndYear])) {
                                                                                        $attendanceMonthAndYear = $attendances_subjectwisess[$subject->subjectID][$monthAndYear];
                                                                                        echo "<tr>";
                                                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                                            for ($i=1; $i <= 31; $i++) { 
                                                                                                $acolumnname = 'a'.$i;
                                                                                                $d = sprintf('%02d',$i);

                                                                                                $date = $d."-".$month."-".$yearKey;
                                                                                                if(in_array($date, $holidaysArray)) {
                                                                                                    $holidayCount++;
                                                                                                    echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                                                } elseif (in_array($date, $getWeekendDays)) {
                                                                                                    $weekendayCount++;
                                                                                                    echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                                                } elseif(in_array($date, $leaveapplications)) {
                                                                                                    $leavedayCount++;
                                                                                                    echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                                                } else {
                                                                                                    $textcolorclass = '';
                                                                                                    $val = false;
                                                                                                    if(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'P') {
                                                                                                        $presentCount++;
                                                                                                        $textcolorclass = 'ini-bg-success';
                                                                                                    } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'LE') {
                                                                                                        $lateexcuseCount++;
                                                                                                        $textcolorclass = 'ini-bg-success';
                                                                                                    } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'L') {
                                                                                                        $lateCount++;
                                                                                                        $textcolorclass = 'ini-bg-success';
                                                                                                    } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'A') {
                                                                                                        $absentCount++;
                                                                                                        $textcolorclass = 'ini-bg-danger';
                                                                                                    } elseif((isset($attendanceMonthAndYear) && ($attendanceMonthAndYear->$acolumnname == NULL || $attendanceMonthAndYear->$acolumnname == ''))) {
                                                                                                        $textcolorclass = 'ini-bg-secondary';
                                                                                                        $defaultVal = 'N/A';
                                                                                                        $val = true;
                                                                                                    }

                                                                                                    if($val) {
                                                                                                        echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                                                    } else {
                                                                                                        echo "<td class='".$textcolorclass."'>".$attendanceMonthAndYear->$acolumnname."</td>";
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        echo "</tr>";
                                                                                    } else {
                                                                                        
                                                                                        echo "<tr>";
                                                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                                            for ($i=1; $i <= 31; $i++) { 
                                                                                                $acolumnname = 'a'.$i;
                                                                                                $d = sprintf('%02d',$i);

                                                                                                $date = $d."-".$month."-".$yearKey;
                                                                                                if(in_array($date, $holidaysArray)) {
                                                                                                    $holidayCount++;
                                                                                                    echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                                                } elseif (in_array($date, $getWeekendDays)) {
                                                                                                    $weekendayCount++;
                                                                                                    echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                                                } elseif(in_array($date, $leaveapplications)) {
                                                                                                    $leavedayCount++;
                                                                                                    echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                                                } else {
                                                                                                    $textcolorclass = 'ini-bg-secondary';
                                                                                                    echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                                                                }
                                                                                            }
                                                                                        echo "</tr>";
                                                                                    }
                                                                                }
                                                                            }
                                                                        ?>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                            <p class="totalattendanceCount">
                                                                <?=$this->lang->line('profile_total_holiday')?>:<?=$holidayCount?>, 
                                                                <?=$this->lang->line('profile_total_weekenday')?>:<?=$weekendayCount?>, 
                                                                <?=$this->lang->line('profile_total_leaveday')?>:<?=$leavedayCount?>, 
                                                                <?=$this->lang->line('profile_total_present')?>:<?=$presentCount?>, 
                                                                <?=$this->lang->line('profile_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                                                <?=$this->lang->line('profile_total_late')?>:<?=$lateCount?>, 
                                                                <?=$this->lang->line('profile_total_absent')?>:<?=$absentCount?>        
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <br/>
                                                <?php } else {
                                                    if($subject->subjectID == $profile->sroptionalsubjectID) {
                                                        echo "<h4>".$subject->subject."</h4>"; ?>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="profileDIV">
                                                                    <table class="attendance_table">
                                                                        <thead>
                                                                            <tr>
                                                                                <th>#</th>
                                                                                <?php
                                                                                    for($i=1; $i<=31; $i++) {
                                                                                       echo  "<th>".$this->lang->line('profile_'.$i)."</th>";
                                                                                    }
                                                                                ?>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                            <?php
                                                                                $schoolyearstartingdate = $schoolyearsessionobj->startingdate;
                                                                                $schoolyearendingdate = $schoolyearsessionobj->endingdate;

                                                                                $allMonths = get_month_and_year_using_two_date($schoolyearstartingdate, $schoolyearendingdate);
                                                                                $holidaysArray = explode('","',$holidays);

                                                                            

                                                                                foreach($allMonths as $yearKey => $allMonth) {
                                                                                    foreach($allMonth as $month) {
                                                                                        $monthAndYear = $month.'-'.$yearKey;
                                                                                        if(isset($attendances_subjectwisess[$subject->subjectID][$monthAndYear])) {
                                                                                            $attendanceMonthAndYear = $attendances_subjectwisess[$subject->subjectID][$monthAndYear];
                                                                                            echo "<tr>";
                                                                                                echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                                                for ($i=1; $i <= 31; $i++) { 
                                                                                                    $acolumnname = 'a'.$i;
                                                                                                    $d = sprintf('%02d',$i);

                                                                                                    $date = $d."-".$month."-".$yearKey;
                                                                                                    if(in_array($date, $holidaysArray)) {
                                                                                                        $holidayCount++;
                                                                                                        echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                                                    } elseif (in_array($date, $getWeekendDays)) {
                                                                                                        $weekendayCount++;
                                                                                                        echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                                                    } elseif(in_array($date, $leaveapplications)) {
                                                                                                        $leavedayCount++;
                                                                                                        echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                                                    } else {
                                                                                                        $textcolorclass = '';
                                                                                                        $val = false;
                                                                                                        if(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'P') {
                                                                                                            $presentCount++;
                                                                                                            $textcolorclass = 'ini-bg-success';
                                                                                                        } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'LE') {
                                                                                                            $lateexcuseCount++;
                                                                                                            $textcolorclass = 'ini-bg-success';
                                                                                                        } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'L') {
                                                                                                            $lateCount++;
                                                                                                            $textcolorclass = 'ini-bg-success';
                                                                                                        } elseif(isset($attendanceMonthAndYear) && $attendanceMonthAndYear->$acolumnname == 'A') {
                                                                                                            $absentCount++;
                                                                                                            $textcolorclass = 'ini-bg-danger';
                                                                                                        } elseif((isset($attendanceMonthAndYear) && ($attendanceMonthAndYear->$acolumnname == NULL || $attendanceMonthAndYear->$acolumnname == ''))) {
                                                                                                            $textcolorclass = 'ini-bg-secondary';
                                                                                                            $defaultVal = 'N/A';
                                                                                                            $val = true;
                                                                                                        }

                                                                                                        if($val) {
                                                                                                            echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                                                        } else {
                                                                                                            echo "<td class='".$textcolorclass."'>".$attendanceMonthAndYear->$acolumnname."</td>";
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            echo "</tr>";
                                                                                        } else {
                                                                                            
                                                                                            echo "<tr>";
                                                                                                echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                                                for ($i=1; $i <= 31; $i++) { 
                                                                                                    $acolumnname = 'a'.$i;
                                                                                                    $d = sprintf('%02d',$i);

                                                                                                    $date = $d."-".$month."-".$yearKey;
                                                                                                    if(in_array($date, $holidaysArray)) {
                                                                                                        $holidayCount++;
                                                                                                        echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                                                    } elseif (in_array($date, $getWeekendDays)) {
                                                                                                        $weekendayCount++;
                                                                                                        echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                                                    } elseif(in_array($date, $leaveapplications)) {
                                                                                                        $leavedayCount++;
                                                                                                        echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                                                    } else {
                                                                                                        $textcolorclass = 'ini-bg-secondary';
                                                                                                        echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                                                                    }
                                                                                                }
                                                                                            echo "</tr>";
                                                                                        }
                                                                                    }
                                                                                }
                                                                            ?>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                                <p class="totalattendanceCount">
                                                                    <?=$this->lang->line('profile_total_holiday')?>:<?=$holidayCount?>, 
                                                                    <?=$this->lang->line('profile_total_weekenday')?>:<?=$weekendayCount?>, 
                                                                    <?=$this->lang->line('profile_total_leaveday')?>:<?=$leavedayCount?>, 
                                                                    <?=$this->lang->line('profile_total_present')?>:<?=$presentCount?>, 
                                                                    <?=$this->lang->line('profile_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                                                    <?=$this->lang->line('profile_total_late')?>:<?=$lateCount?>, 
                                                                    <?=$this->lang->line('profile_total_absent')?>:<?=$absentCount?>        
                                                                </p>
                                                            </div>
                                                        </div>
                                                        <br/> 
                                                <?php }
                                                }
                                            } 
                                        } 
                                    } else { ?>
                                        <div class="profileDIV">
                                            <table class="attendance_table">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <?php
                                                            for($i=1; $i<=31; $i++) {
                                                               echo  "<th>".$this->lang->line('profile_'.$i)."</th>";
                                                            }
                                                        ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                        $holidayCount = 0;
                                                        $weekendayCount = 0;
                                                        $leavedayCount = 0;
                                                        $presentCount = 0;
                                                        $lateexcuseCount = 0;
                                                        $lateCount = 0;
                                                        $absentCount = 0;

                                                        $schoolyearstartingdate = $schoolyearsessionobj->startingdate;
                                                        $schoolyearendingdate = $schoolyearsessionobj->endingdate;
                                                        $allMonths = get_month_and_year_using_two_date($schoolyearstartingdate, $schoolyearendingdate);
                                                        $holidaysArray = explode('","', $holidays);

                                                        foreach($allMonths as $yearKey => $months) {
                                                            foreach ($months as $month) {
                                                                $monthyear = $month."-".$yearKey;
                                                                if(isset($attendancesArray[$monthyear])) {
                                                                    echo "<tr>";
                                                                    echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                    for ($i=1; $i <= 31; $i++) {    
                                                                        $acolumnname = 'a'.$i;
                                                                        $d = sprintf('%02d',$i);

                                                                        $date = $d."-".$month."-".$yearKey;
                                                                        if(in_array($date, $holidaysArray)) {
                                                                            $holidayCount++;
                                                                            echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                        } elseif (in_array($date, $getWeekendDays)) {
                                                                            $weekendayCount++;
                                                                            echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                        } elseif(in_array($date, $leaveapplications)) {
                                                                            $leavedayCount++;
                                                                            echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                        } else {
                                                                            $textcolorclass = '';
                                                                            $val = false;
                                                                            if(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'P') {
                                                                                $presentCount++;
                                                                                $textcolorclass = 'ini-bg-success';
                                                                            } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'LE') {
                                                                                $lateexcuseCount++;
                                                                                $textcolorclass = 'ini-bg-success';
                                                                            } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'L') {
                                                                                $lateCount++;
                                                                                $textcolorclass = 'ini-bg-success';
                                                                            } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'A') {
                                                                                $absentCount++;
                                                                                $textcolorclass = 'ini-bg-danger';
                                                                            } elseif((isset($attendancesArray[$monthyear]) && ($attendancesArray[$monthyear]->$acolumnname == NULL || $attendancesArray[$monthyear]->$acolumnname == ''))) {
                                                                                $textcolorclass = 'ini-bg-secondary';
                                                                                $defaultVal = 'N/A';
                                                                                $val = true;
                                                                            }

                                                                            if($val) {
                                                                                echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                            } else {
                                                                                echo "<td class='".$textcolorclass."'>".$attendancesArray[$monthyear]->$acolumnname."</td>";
                                                                            }

                                                                        }
                                                                    }
                                                                    echo "</tr>";
                                                                } else {
                                                                    $monthyear = $month."-".$yearKey;
                                                                    echo "<tr>";
                                                                    echo "<td>".ucwords($monthArray[$month])."</td>";
                                                                    for ($i=1; $i <= 31; $i++) {    
                                                                        $acolumnname = 'a'.$i;
                                                                        $d = sprintf('%02d',$i);

                                                                        $date = $d."-".$month."-".$yearKey;
                                                                        if(in_array($date, $holidaysArray)) {
                                                                            $holidayCount++;
                                                                            echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                        } elseif (in_array($date, $getWeekendDays)) {
                                                                            $weekendayCount++;
                                                                            echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                        } elseif(in_array($date, $leaveapplications)) {
                                                                            $leavedayCount++;
                                                                            echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                        } else {
                                                                            $textcolorclass = 'ini-bg-secondary';
                                                                            echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                                        }
                                                                    }
                                                                    echo "</tr>";
                                                                }
                                                            }
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <p class="totalattendanceCount">
                                            <?=$this->lang->line('profile_total_holiday')?>:<?=$holidayCount?>, 
                                            <?=$this->lang->line('profile_total_weekenday')?>:<?=$weekendayCount?>, 
                                            <?=$this->lang->line('profile_total_leaveday')?>:<?=$leavedayCount?>, 
                                            <?=$this->lang->line('profile_total_present')?>:<?=$presentCount?>, 
                                            <?=$this->lang->line('profile_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                            <?=$this->lang->line('profile_total_late')?>:<?=$lateCount?>, 
                                            <?=$this->lang->line('profile_total_absent')?>:<?=$absentCount?>        
                                        </p>
                                    <?php } ?>
                            </div>
                        <?php } else { ?>
                            <div class="tab-pane" id="attendance">
                                <div class="profileDIV">    
                                    <table class="attendance_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <?php
                                                    for($i=1; $i<=31; $i++) {
                                                       echo  "<th>".$this->lang->line('profile_'.$i)."</th>";
                                                    }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $monthArray = array(
                                                  "01" => "jan",
                                                  "02" => "feb",
                                                  "03" => "mar",
                                                  "04" => "apr",
                                                  "05" => "may",
                                                  "06" => "jun",
                                                  "07" => "jul",
                                                  "08" => "aug",
                                                  "09" => "sep",
                                                  "10" => "oct",
                                                  "11" => "nov",
                                                  "12" => "dec"
                                                );

                                                $holidayCount = 0;
                                                $weekendayCount = 0;
                                                $leavedayCount = 0;
                                                $presentCount = 0;
                                                $lateexcuseCount = 0;
                                                $lateCount = 0;
                                                $absentCount = 0;

                                                $schoolyearstartingdate = $schoolyearsessionobj->startingdate;
                                                $schoolyearendingdate = $schoolyearsessionobj->endingdate;
                                                $allMonths = get_month_and_year_using_two_date($schoolyearstartingdate, $schoolyearendingdate);
                                                $holidaysArray = explode('","', $holidays);

                                                foreach($allMonths as $yearKey => $months) {
                                                    foreach ($months as $month) {
                                                        $monthyear = $month."-".$yearKey;
                                                        if(isset($attendancesArray[$monthyear])) {
                                                            echo "<tr>";
                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                            for ($i=1; $i <= 31; $i++) {    
                                                                $acolumnname = 'a'.$i;
                                                                $d = sprintf('%02d',$i);

                                                                $date = $d."-".$month."-".$yearKey;
                                                                if(in_array($date, $holidaysArray)) {
                                                                    $holidayCount++;
                                                                    echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                } elseif (in_array($date, $getWeekendDays)) {
                                                                    $weekendayCount++;
                                                                    echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                } elseif(in_array($date, $leaveapplications)) {
                                                                    $leavedayCount++;
                                                                    echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                } else {
                                                                    $textcolorclass = '';
                                                                    $val = false;
                                                                    if(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'P') {
                                                                        $presentCount++;
                                                                        $textcolorclass = 'ini-bg-success';
                                                                    } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'LE') {
                                                                        $lateexcuseCount++;
                                                                        $textcolorclass = 'ini-bg-success';
                                                                    } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'L') {
                                                                        $lateCount++;
                                                                        $textcolorclass = 'ini-bg-success';
                                                                    } elseif(isset($attendancesArray[$monthyear]) && $attendancesArray[$monthyear]->$acolumnname == 'A') {
                                                                        $absentCount++;
                                                                        $textcolorclass = 'ini-bg-danger';
                                                                    } elseif((isset($attendancesArray[$monthyear]) && ($attendancesArray[$monthyear]->$acolumnname == NULL || $attendancesArray[$monthyear]->$acolumnname == ''))) {
                                                                        $textcolorclass = 'ini-bg-secondary';
                                                                        $defaultVal = 'N/A';
                                                                        $val = true;
                                                                    }

                                                                    if($val) {
                                                                        echo "<td class='".$textcolorclass."'>".$defaultVal."</td>";
                                                                    } else {
                                                                        echo "<td class='".$textcolorclass."'>".$attendancesArray[$monthyear]->$acolumnname."</td>";
                                                                    }

                                                                }
                                                            }
                                                            echo "</tr>";
                                                        } else {
                                                            $monthyear = $month."-".$yearKey;
                                                            echo "<tr>";
                                                            echo "<td>".ucwords($monthArray[$month])."</td>";
                                                            for ($i=1; $i <= 31; $i++) {    
                                                                $acolumnname = 'a'.$i;
                                                                $d = sprintf('%02d',$i);

                                                                $date = $d."-".$month."-".$yearKey;
                                                                if(in_array($date, $holidaysArray)) {
                                                                    $holidayCount++;
                                                                    echo "<td class='ini-bg-primary'>".'H'."</td>";
                                                                } elseif (in_array($date, $getWeekendDays)) {
                                                                    $weekendayCount++;
                                                                    echo "<td class='ini-bg-info'>".'W'."</td>";
                                                                } elseif(in_array($date, $leaveapplications)) {
                                                                    $leavedayCount++;
                                                                    echo "<td class='ini-bg-success'>".'LA'."</td>";
                                                                } else {
                                                                    $textcolorclass = 'ini-bg-secondary';
                                                                    echo "<td class='".$textcolorclass."'>".'N/A'."</td>";
                                                                }
                                                            }
                                                            echo "</tr>";
                                                        }
                                                    }
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <p class="totalattendanceCount">
                                    <?=$this->lang->line('profile_total_holiday')?>:<?=$holidayCount?>, 
                                    <?=$this->lang->line('profile_total_weekenday')?>:<?=$weekendayCount?>, 
                                    <?=$this->lang->line('profile_total_leaveday')?>:<?=$leavedayCount?>, 
                                    <?=$this->lang->line('profile_total_present')?>:<?=$presentCount?>, 
                                    <?=$this->lang->line('profile_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                    <?=$this->lang->line('profile_total_late')?>:<?=$lateCount?>, 
                                    <?=$this->lang->line('profile_total_absent')?>:<?=$absentCount?>        
                                </p>
                            </div>

                            <?php if(count($manage_salary)) {?>
                                <div class="tab-pane" id="salary">
                                    <?php if($manage_salary->salary == 1) { ?>
                                        <div class="row">
                                            <div class="col-sm-6" style="margin-bottom: 10px;">
                                                <div class="info-box">
                                                    <p style="margin:0 0 20px">
                                                        <span><?=$this->lang->line("profile_salary_grades")?></span>
                                                        <?=$salary_template->salary_grades?>
                                                    </p>

                                                    <p style="margin:0 0 20px">
                                                        <span><?=$this->lang->line("profile_basic_salary")?></span>
                                                        <?=number_format($salary_template->basic_salary, 2)?>
                                                    </p>

                                                    <p style="margin:0 0 20px">
                                                        <span><?=$this->lang->line("profile_overtime_rate")?></span>
                                                        <?=number_format($salary_template->overtime_rate, 2)?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="box" style="border: 1px solid #eee">
                                                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('profile_allowances')?></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-sm-12" id="allowances">
                                                                <div class="info-box">
                                                                    <?php 
                                                                        if(count($salaryoptions)) { 
                                                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                                if($salaryoption->option_type == 1) {
                                                                    ?>
                                                                        <p>
                                                                            <span><?=$salaryoption->label_name?></span>
                                                                            <?=number_format($salaryoption->label_amount, 2)?>
                                                                        </p>
                                                                    <?php        
                                                                                }
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-sm-6">
                                                <div class="box" style="border: 1px solid #eee;">
                                                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('profile_deductions')?></h3>
                                                    </div><!-- /.box-header -->
                                                    <!-- form start -->
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-sm-12" id="deductions">
                                                                <div class="info-box">
                                                                    <?php 
                                                                        if(count($salaryoptions)) { 
                                                                            foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                                if($salaryoption->option_type == 2) {
                                                                    ?>
                                                                        <p>
                                                                            <span><?=$salaryoption->label_name?></span>
                                                                            <?=number_format($salaryoption->label_amount, 2)?>
                                                                        </p>
                                                                    <?php        
                                                                                }
                                                                            }
                                                                        }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-8 col-sm-offset-4">
                                                <div class="box" style="border: 1px solid #eee;">
                                                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('profile_total_salary_details')?></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_gross_salary')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_total_deduction')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_net_salary')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($netsalary, 2)?></b></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } elseif($manage_salary->salary == 2) { ?>
                                        <div class="row">
                                            <div class="col-sm-6" style="margin-bottom: 10px;">
                                                <div class="info-box">
                                                    <p style="margin:0 0 20px">
                                                        <span><?=$this->lang->line("profile_salary_grades")?></span>
                                                        <?=$hourly_salary->hourly_grades?>
                                                    </p>

                                                    <p style="margin:0 0 20px">
                                                        <span><?=$this->lang->line("profile_hourly_rate")?></span>
                                                        <?=number_format($hourly_salary->hourly_rate, 2)?>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-sm-8 col-sm-offset-4">
                                                <div class="box" style="border: 1px solid #eee;">
                                                    <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                        <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('profile_total_salary_details')?></h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_gross_salary')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_total_deduction')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                            </tr>

                                                            <tr>
                                                                <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('profile_net_hourly_rate')?></td>

                                                                <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($netsalary, 2)?></b></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="tab-pane" id="payment">
                                    <div id="hide-table">
                                        <table class="table table-striped table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th><?=$this->lang->line('slno')?></th>
                                                    <th><?=$this->lang->line('profile_month')?></th>
                                                    <th><?=$this->lang->line('profile_date')?></th>
                                                    <th><?php if($manage_salary->salary == 2) { echo $this->lang->line('profile_net_salary_hourly'); } else { echo $this->lang->line('profile_net_salary'); } ?></th>
                                                    <th><?=$this->lang->line('profile_payment_amount')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $paymentTotal = 0; if(count($make_payments)) { $i = 1; foreach($make_payments as $make_payment) { ?>
                                                    <tr>
                                                        <td data-title="<?=$this->lang->line('slno')?>">
                                                            <?=$i;?>
                                                        </td>
                                                        <td data-title="<?=$this->lang->line('profile_month')?>">
                                                            <?php echo date("M Y", strtotime('1-'.$make_payment->month)); ?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('profile_date')?>">
                                                            <?php echo date("d M Y", strtotime($make_payment->create_date)); ?>
                                                        </td>

                                                        <td data-title="<?php if($manage_salary->salary == 2) { echo $this->lang->line('profile_net_salary_hourly'); } else { echo $this->lang->line('profile_net_salary'); } ?>">
                                                            <?php
                                                                if(isset($make_payment->total_hours)) {
                                                                    echo '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .') = '. (number_format($make_payment->total_hours * $make_payment->net_salary, 2)); 
                                                                } else {
                                                                    echo number_format($make_payment->net_salary, 2); 
                                                                }
                                                            ?>
                                                        </td>

                                                        <td data-title="<?=$this->lang->line('profile_payment_amount')?>">
                                                            <?php echo number_format($make_payment->payment_amount, 2); $paymentTotal += $make_payment->payment_amount; ?>
                                                        </td>
                                                    </tr>
                                                <?php $i++; }} ?>
                                                <tr>
                                                    <td colspan="4" data-title="<?=$this->lang->line('profile_total')?>">
                                                        <?php if($siteinfos->currency_code) { echo '<b>'. $this->lang->line('profile_total').' ('.$siteinfos->currency_code.')'. '</b>'; } else { echo '<b>'. $this->lang->line('profile_total') . '</b>'; }
                                                        ?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_payment_amount')?>">
                                                        <?=number_format($paymentTotal, 2)?>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php } ?>
                        
                            <div class="tab-pane" id="document">
                                <?php if(permissionChecker('profile_add')) { ?>
                                    <input class="btn btn-success btn-sm" style="margin-bottom: 10px" type="button" value="<?=$this->lang->line('profile_add_document')?>" data-toggle="modal" data-target="#documentupload">
                                <?php } ?>
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('profile_title')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_title')?>">
                                                        <?=$document->title?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=date('d M Y', strtotime($document->create_date))?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('action')?>">
                                                        <?php 
                                                            echo btn_download('profile/download_document/'.$document->documentID.'/'.$documentUserID.'/'.$profile->usertypeID, $this->lang->line('download'));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>

                        <?php if($profile->usertypeID == 3) { 
                            if(count($parents)) { ?>
                                <div class="tab-pane" id="parents">
                                    <div class="panel-body profile-view-dis">
                                        <div class="row">
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_guargian_name")?> </span>: <?=$parents->name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_father_name")?> </span>: <?=$parents->father_name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_mother_name")?> </span>: <?=$parents->mother_name?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_father_profession")?> </span>: <?=$parents->father_profession?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_mother_profession")?> </span>: <?=$parents->mother_profession?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_email")?> </span>: <?=$parents->email?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_phone")?> </span>: <?=$parents->phone?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_username")?> </span>: <?=$parents->username?></p>
                                            </div>
                                            <div class="profile-view-tab">
                                                <p><span><?=$this->lang->line("profile_address")?> </span>: <?=$parents->address?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="tab-pane" id="routine">
                                <?php
                                    if(count($routines)) { 
                                        $days = [
                                            0 => $this->lang->line('sunday'),
                                            1 => $this->lang->line('monday'),
                                            2 => $this->lang->line('tuesday'),
                                            3 => $this->lang->line('wednesday'),
                                            4 => $this->lang->line('thursday'),
                                            5 => $this->lang->line('friday'),
                                            6 => $this->lang->line('saturday'),
                                        ];

                                        $maxClass = 0; 
                                        foreach ($routines as $routine) { 
                                            if(count($routine) > $maxClass) {
                                                $maxClass = count($routine);
                                            }
                                        }
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-responsive">
                                            <thead>
                                                <th class="text-center"><?=$this->lang->line('profile_day');?></th>
                                                <?php for($i=1; $i <= $maxClass; $i++) { ?>
                                                    <th class="text-center"><?=addOrdinalNumberSuffix($i)." ".$this->lang->line('profile_period');?></th>
                                                <?php } ?>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($days as $dayKey=> $day) { 
                                                    if(!in_array($dayKey, $routineweekends) && isset($routines[$dayKey])) { $i=0; ?>
                                                        <tr>
                                                            <td><?=$day?></td>
                                                            <?php foreach ($routines[$dayKey] as $routine) { $i++; ?>
                                                                <td class="text-center">
                                                                    <p style="margin: 0 0 1px"><?=$routine->start_time;?>-<?=$routine->end_time;?></p>
                                                                    <p style="margin: 0 0 1px">
                                                                        <span class="left"><?=$this->lang->line('profile_subject')?> :</span>
                                                                        <span class="right"> 
                                                                            <?=isset($subjects[$routine->subjectID]) ? $subjects[$routine->subjectID]->subject : ''?>
                                                                        </span>
                                                                    </p>
                                                                    <p style="margin: 0 0 1px">
                                                                        <span class="left"><?=$this->lang->line('profile_teacher')?> :</span>
                                                                        <span class="right">
                                                                            <?=isset($teachers[$routine->teacherID]) ? $teachers[$routine->teacherID] : ''?>
                                                                        </span>
                                                                    </p>
                                                                    <p style="margin: 0 0 1px"><span class="left"><?= $this->lang->line('profile_room')?> : </span><span class="right"><?=$routine->room;?></span></p>
                                                                </td>
                                                            <?php } 
                                                            $j = ($maxClass - $i);  
                                                            if($i < $maxClass) {
                                                                for($i = 1; $i <= $j; $i++) {
                                                                    echo "<td class='text-center'>N/A</td>";
                                                            } } ?>
                                                        </tr> 
                                                <?php } } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                <?php } ?>
                            </div>

                            <div class="tab-pane" id="mark">
                                <?php 
                                    $optionalsubjectID = $profile->sroptionalsubjectID;
                                    if(count($marksettings)) {
                                        foreach ($marksettings as $examID => $marksetting) {
                                            echo '<div style="border-top:1px solid #23292F; border-left:1px solid #23292F; border-right:1px solid #23292F; border-bottom:1px solid #23292F;" class="box" id="e'.$examID.'">';
                                                echo '<div class="box-header" style="background-color:#FFFFFF;">';
                                                    echo '<h3 class="box-title" style="color:#23292F;">'; 
                                                        echo (isset($exams[$examID]) ? $exams[$examID] : '');
                                                    echo '</h3>';
                                                echo '</div>';

                                                echo '<div class="box-body mark-bodyID" style="border-top:1px solid #23292F;">';
                                                        echo "<table class=\"table table-striped table-bordered\" >";
                                                            echo "<thead>";
                                                                echo "<tr>";
                                                                    echo "<th class='text-center' rowspan='2' style='background-color:#395C7F;color:#fff;' data-title='".$this->lang->line("profile_subject")."'>";
                                                                        echo $this->lang->line("profile_subject");
                                                                    echo "</th>";

                                                                    foreach ($marksetting as $subjectID => $markpercentageArr) {
                                                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                                            $markpercentagetypelabel = isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->markpercentagetype : '';
                                                                            echo "<th colspan='2' class='text-center' style='background-color:#395C7F;color:#fff;' data-title='".$markpercentagetypelabel."'>";
                                                                                echo $markpercentagetypelabel;
                                                                            echo "</th>";
                                                                        }
                                                                        break;
                                                                    }
                                                                    echo "<th colspan='3' class='text-center ' style='background-color:#395C7F;color:#fff;' data-title='".$this->lang->line("profile_total")."'>";
                                                                        echo $this->lang->line("profile_total");
                                                                    echo "</th>";
                                                                echo "</tr>";
                                                                foreach ($marksetting as $subjectID => $markpercentageArr) {
                                                                    echo "<tr>";
                                                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {
                                                                            echo "<th class='text-center' data-title='".$this->lang->line('profile_obtained_mark')."'>";
                                                                                echo $this->lang->line("profile_obtained_mark");
                                                                            echo "</th>";

                                                                            echo "<th class='text-center' data-title='".$this->lang->line('profile_highest_mark')."'>";
                                                                                echo $this->lang->line("profile_highest_mark");
                                                                            echo "</th>";
                                                                        }
                                                                        echo "<th class='text-center' data-title='".$this->lang->line('profile_mark')."'>";
                                                                            echo $this->lang->line("profile_mark");
                                                                        echo "</th>";
                                                                        echo "<th class='text-center' data-title='".$this->lang->line('profile_point')."'>";
                                                                            echo $this->lang->line("profile_point");
                                                                        echo "</th>";
                                                                        echo "<th class='text-center' data-title='".$this->lang->line('profile_grade')."'>";
                                                                            echo $this->lang->line("profile_grade");
                                                                        echo "</th>";
                                                                    echo "</tr>";
                                                                    break;
                                                                }
                                                            echo "</thead>";
                                                            echo "<tbody>";
                                                            $totalMark           = 0;
                                                            $totalFinalMark      = 0;
                                                            $totalSubject        = 0;
                                                            $averagePoint        = 0;
                                                            $opmarkpercentageArr = [];
                                                            foreach ($marksetting as $subjectID => $markpercentageArr) {
                                                                if($subjectID == $optionalsubjectID) {
                                                                    $opmarkpercentageArr = $markpercentageArr;
                                                                }
                                                                if(!in_array($subjectID, $optionalsubjectArr)) {
                                                                    $totalSubject++;
                                                                    echo "<tr>";
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_subject')."'>";
                                                                             echo isset($subjects[$subjectID]) ? $subjects[$subjectID]->subject : '';
                                                                        echo "</td>";

                                                                        $subjectfinalmark = isset($subjects[$subjectID]) ? (int)$subjects[$subjectID]->finalmark : 0;
                                                                        $totalSubjectMark = 0;
                                                                        $percentageMark   = 0;
                                                                        foreach ($markpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                                                            $f = false;
                                                                            if(isset($markpercentageArr['own']) && in_array($markpercentageID, $markpercentageArr['own'])) {
                                                                                $f = true;
                                                                                $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                                                            }

                                                                            echo "<td class='text-black' data-title='".$this->lang->line('profile_mark')."'>";
                                                                                if(isset($marks[$examID][$subjectID][$markpercentageID]) && $f) {
                                                                                    echo $marks[$examID][$subjectID][$markpercentageID];
                                                                                    $totalSubjectMark += $marks[$examID][$subjectID][$markpercentageID];
                                                                                } else {
                                                                                    if($f) {
                                                                                        echo 'N/A';
                                                                                    }
                                                                                }
                                                                            echo "</td>";

                                                                            echo "<td class='text-black' data-title='".$this->lang->line('profile_highest_mark')."'>";
                                                                                if(isset($highestmarks[$examID][$subjectID][$markpercentageID]) && ($highestmarks[$examID][$subjectID][$markpercentageID] != -1) && $f) {
                                                                                    echo $highestmarks[$examID][$subjectID][$markpercentageID];
                                                                                } else {
                                                                                     if($f) {
                                                                                        echo 'N/A';
                                                                                    }
                                                                                }
                                                                            echo "</td>";
                                                                        }
                                                                        $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);


                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_mark')."'>";
                                                                            echo $totalSubjectMark;
                                                                            $totalMark        += $totalSubjectMark;
                                                                            $totalFinalMark   += $finalpercentageMark;
                                                                            $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                                        echo "</td>";
                                                                        
                                                                        if(count($grades)) {
                                                                            foreach ($grades as $grade) {
                                                                                if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                                                    echo "<td class='text-black' data-title='".$this->lang->line('profile_point')."'>";
                                                                                        echo $grade->point;
                                                                                        $averagePoint += $grade->point;
                                                                                    echo "</td>";
                                                                                    echo "<td class='text-black' data-title='".$this->lang->line('profile_grade')."'>";
                                                                                        echo $grade->grade;
                                                                                    echo "</td>";
                                                                                }
                                                                            }
                                                                        } else {
                                                                            echo "<td class='text-black' data-title='".$this->lang->line('profile_point')."'>";
                                                                                echo 'N/A';
                                                                            echo '</td>';
                                                                            echo "<td class='text-black' data-title='".$this->lang->line('profile_grade')."'>";
                                                                                echo 'N/A';
                                                                            echo '</td>';
                                                                        }
                                                                    echo "</tr>";
                                                                }
                                                            }

                                                            if(($optionalsubjectID > 0) && count($opmarkpercentageArr)) {
                                                                $totalSubject++;
                                                                echo "<tr>";
                                                                    echo "<td class='text-black' data-title='".$this->lang->line('profile_subject')."'>";
                                                                         echo isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->subject : '';
                                                                    echo "</td>";
                                                                    $subjectfinalmark  = isset($subjects[$optionalsubjectID]) ? $subjects[$optionalsubjectID]->finalmark : 0;

                                                                    $totalSubjectMark = 0;
                                                                    $percentageMark   = 0;
                                                                    foreach ($opmarkpercentageArr[(($settingmarktypeID==4) || ($settingmarktypeID==6)) ? 'unique' : 'own'] as $markpercentageID) {

                                                                        $f = false;
                                                                        if(isset($opmarkpercentageArr['own']) && in_array($markpercentageID, $opmarkpercentageArr['own'])) {
                                                                            $f = true;
                                                                            $percentageMark   += (isset($markpercentages[$markpercentageID]) ? $markpercentages[$markpercentageID]->percentage : 0);
                                                                        } 

                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_mark')."'>";
                                                                            if(isset($marks[$examID][$optionalsubjectID][$markpercentageID]) && $f) {
                                                                                echo $marks[$examID][$optionalsubjectID][$markpercentageID];
                                                                                $totalSubjectMark += $marks[$examID][$optionalsubjectID][$markpercentageID];
                                                                            } else {
                                                                                if($f) {
                                                                                    echo 'N/A';
                                                                                }
                                                                            }
                                                                        echo "</td>";

                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_highest_mark')."'>";
                                                                            if(isset($highestmarks[$examID][$optionalsubjectID][$markpercentageID]) && ($highestmarks[$examID][$optionalsubjectID][$markpercentageID] != -1) && $f) {
                                                                                echo $highestmarks[$examID][$optionalsubjectID][$markpercentageID];
                                                                            } else {
                                                                                if($f) {
                                                                                    echo 'N/A';
                                                                                }
                                                                            }
                                                                        echo "</td>";
                                                                    }
                                                                    $finalpercentageMark = convertMarkpercentage($percentageMark, $subjectfinalmark);


                                                                    echo "<td class='text-black' data-title='".$this->lang->line('profile_mark')."'>";
                                                                        echo $totalSubjectMark;
                                                                        $totalMark        += $totalSubjectMark;
                                                                        $totalFinalMark   += $finalpercentageMark;

                                                                        $totalSubjectMark  = markCalculationView($totalSubjectMark, $subjectfinalmark, $percentageMark);
                                                                    echo "</td>";
                                                                    
                                                                    if(count($grades)) {
                                                                        foreach ($grades as $grade) {
                                                                            if(($grade->gradefrom <= $totalSubjectMark) && ($grade->gradeupto >= $totalSubjectMark)) {
                                                                                echo "<td class='text-black' data-title='".$this->lang->line('profile_point')."'>";
                                                                                    echo $grade->point;
                                                                                    $averagePoint += $grade->point;
                                                                                echo "</td>";
                                                                                echo "<td class='text-black' data-title='".$this->lang->line('profile_grade')."'>";
                                                                                    echo $grade->grade;
                                                                                echo "</td>";
                                                                            }
                                                                        }
                                                                    } else {
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_point')."'>";
                                                                            echo 'N/A';
                                                                        echo '</td>';
                                                                        echo "<td class='text-black' data-title='".$this->lang->line('profile_grade')."'>";
                                                                            echo 'N/A';
                                                                        echo '</td>';
                                                                    }
                                                                echo "</tr>";
                                                            }
                                                            echo "</tbody>";
                                                        echo "</table>";

                                                        echo '<div class="box-footer" style="padding-left:0px;">';
                                                            echo '<p class="text-black">'. $this->lang->line('profile_total_marks').' : <span class="text-red text-bold">'. ini_round($totalFinalMark).'</span>';
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('profile_total_obtained_marks').' : <span class="text-red text-bold">'. ini_round($totalMark).'</span>';
                                                            $totalAverageMark = $totalMark / $totalSubject;
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('profile_total_average_marks').' : <span class="text-red text-bold">'. ini_round($totalAverageMark).'</span>';

                                                            $totalmarkpercentage  = markCalculationView($totalMark, $totalFinalMark);
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('profile_total_average_marks_percetage').' : <span class="text-red text-bold">'. ini_round($totalmarkpercentage) .'</span>';

                                                            $gpaAveragePoint = $averagePoint / $totalSubject;
                                                            echo '&nbsp;&nbsp;&nbsp;&nbsp;'.$this->lang->line('profile_gpa').' : <span class="text-red text-bold">'. ini_round($gpaAveragePoint) .'</span>';
                                                            echo '</p>';
                                                        echo '</div>';

                                                    echo '</div>';  
                                            echo "</div>";
                                        }
                                    }
                                ?>
                            </div>

                            <div class="tab-pane" id="invoice">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('profile_feetype')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_status')?></th>
                                                <th><?=$this->lang->line('profile_fees_amount')?></th>
                                                <th><?=$this->lang->line('profile_discount')?></th>
                                                <th><?=$this->lang->line('profile_paid')?></th>
                                                <th><?=$this->lang->line('profile_weaver')?></th>
                                                <th><?=$this->lang->line('profile_fine')?></th>
                                                <th><?=$this->lang->line('profile_due')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalInvoice = 0; $totalDiscount = 0; $totalPaid = 0; $totalWeaver = 0; $totalDue = 0; $totalFine = 0; if(count($invoices)) { $i = 1; foreach ($invoices as $invoice) { //dump($invoice); ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_feetype')?>">
                                                        <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : '' ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=!empty($invoice->date) ? date('d M Y', strtotime($invoice->date)) : ''?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_status')?>">
                                                        <?php 
                                                            $status = $invoice->paidstatus;
                                                            $setButton = '';
                                                            if($status == 0) {
                                                                $status = $this->lang->line('profile_notpaid');
                                                                $setButton = 'text-danger';
                                                            } elseif($status == 1) {
                                                                $status = $this->lang->line('profile_partially_paid');
                                                                $setButton = 'text-warning';
                                                            } elseif($status == 2) {
                                                                $status = $this->lang->line('profile_fully_paid');
                                                                $setButton = 'text-success';
                                                            }

                                                            echo "<span class='".$setButton."'>".$status."</span>";
                                                        ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_fees_amount')?>">
                                                        <?php $invoiceAmount = $invoice->amount;  echo number_format($invoiceAmount, 2); $totalInvoice += $invoiceAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_discount')?>">
                                                        <?php $discountAmount = 0; if($invoice->discount > 0) { $discountAmount = (($invoice->amount/100)*$invoice->discount); } echo number_format($discountAmount, 2); $totalDiscount += $discountAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_paid')?>">
                                                        <?php $paymentAmount = 0; if(isset($allpaymentbyinvoice[$invoice->invoiceID])) { $paymentAmount = $allpaymentbyinvoice[$invoice->invoiceID]; } echo number_format($paymentAmount, 2); $totalPaid += $paymentAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_weaver')?>">
                                                        <?php $weaverAmount = 0; if(isset($allweaverandpaymentbyinvoice[$invoice->invoiceID]['weaver'])) { $weaverAmount = $allweaverandpaymentbyinvoice[$invoice->invoiceID]['weaver']; } echo number_format($weaverAmount, 2); $totalWeaver += $weaverAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_fine')?>">
                                                        <?php $fineAmount = 0; if(isset($allweaverandpaymentbyinvoice[$invoice->invoiceID]['fine'])) { $fineAmount = $allweaverandpaymentbyinvoice[$invoice->invoiceID]['fine']; } echo number_format($fineAmount, 2); $totalFine += $fineAmount; ?>
                                                    </td>
                                                    
                                                    <td data-title="<?=$this->lang->line('profile_due')?>">
                                                        <?php $dueAmount = ((($invoiceAmount-$discountAmount) - $paymentAmount) - $weaverAmount); echo number_format($dueAmount, 2); $totalDue += $dueAmount; ?> 
                                                    </td>

                                                </tr>
                                                
                                            <?php $i++; } } ?>

                                            <tr>
                                                <td colspan="4" data-title="<?=$this->lang->line('profile_total')?>">
                                                    <?php if($siteinfos->currency_code) { echo '<b>'. $this->lang->line('profile_total').' ('.$siteinfos->currency_code.')'. '</b>'; } else { echo '<b>'. $this->lang->line('profile_total') .'</b>'; }
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_fees_amount')?>">
                                                    <?=number_format($totalInvoice, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_discount')?>">
                                                    <?=number_format($totalDiscount, 2)?>
                                                </td> 
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_paid')?>">
                                                    <?=number_format($totalPaid, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_weaver')?>">
                                                    <?=number_format($totalWeaver, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_fine')?>">
                                                    <?=number_format($totalFine, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_due')?>">
                                                    <?=number_format($totalDue, 2)?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="tab-pane" id="payment">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('profile_feetype')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('profile_paid')?></th>
                                                <th><?=$this->lang->line('profile_weaver')?></th>
                                                <th><?=$this->lang->line('profile_fine')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $totalPaymentPaid = 0; $totalPaymentWeaver = 0; $totalPaymentFine = 0; if(count($payments)) { $i = 1; foreach ($payments as $payment) {  ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_feetype')?>">
                                                        <?=isset($feetypes[$payment->feetypeID]) ? $feetypes[$payment->feetypeID] : '' ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=!empty($payment->paymentdate) ? date('d M Y', strtotime($payment->paymentdate)) : ''?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_paid')?>">
                                                        <?php $paymentpaidAmount = $payment->paymentamount; echo number_format($paymentpaidAmount, 2); $totalPaymentPaid += $paymentpaidAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_weaver')?>">
                                                        <?php $paymentWeaverAmount = $payment->weaver; echo number_format($paymentWeaverAmount, 2); $totalPaymentWeaver += $paymentWeaverAmount; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_fine')?>">
                                                        <?php $paymentFineAmount = $payment->fine; echo number_format($paymentFineAmount, 2); $totalPaymentFine += $paymentFineAmount; ?>
                                                    </td>

                                                </tr>
                                                
                                            <?php $i++; } } ?>

                                            <tr>
                                                <td colspan="3" data-title="<?=$this->lang->line('profile_total')?>">
                                                    <?php if($siteinfos->currency_code) { echo '<b>'. $this->lang->line('profile_total').' ('.$siteinfos->currency_code.')'. '</b>'; } else { echo '<b>'. $this->lang->line('profile_total') . '</b>'; }
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_paid')?>">
                                                    <?=number_format($totalPaymentPaid, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_weaver')?>">
                                                    <?=number_format($totalPaymentWeaver, 2)?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('profile_total')?> <?=$this->lang->line('profile_fine')?>">
                                                    <?=number_format($totalPaymentFine, 2)?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div> 

                            <div class="tab-pane" id="document">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('slno')?></th>
                                                <th><?=$this->lang->line('profile_title')?></th>
                                                <th><?=$this->lang->line('profile_date')?></th>
                                                <th><?=$this->lang->line('action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?php echo $i; ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_title')?>">
                                                        <?=$document->title?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_date')?>">
                                                        <?=date('d M Y', strtotime($document->create_date))?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('action')?>">
                                                        <?php    
                                                            echo btn_download('profile/download_document/'.$document->documentID.'/'.$profile->studentID.'/'.$profile->classesID, $this->lang->line('download'));
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <script type="text/javascript">
                                $('.mark-bodyID').mCustomScrollbar({
                                    axis:"x"
                                });
                            </script>
                        <?php } ?>

                        <?php if($profile->usertypeID == 4) { ?>
                            <div class="tab-pane" id="children">
                                <div id="hide-table">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_photo')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_name')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_roll')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_classes')?></th>
                                                <th class="col-sm-2"><?=$this->lang->line('profile_section')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(count($childrens)) {$i = 1; foreach($childrens as $children) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?=$i?>  
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('profile_photo')?>">
                                                        <?=profileimage($children->photo)?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_name')?>">
                                                        <?=$children->srname?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('profile_roll')?>">
                                                        <?=$children->srroll?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('profile_classes')?>">
                                                        <?=isset($classess[$children->srclassesID]) ? $classess[$children->srclassesID] : ''?>
                                                    </td> 
                                                    <td data-title="<?=$this->lang->line('profile_section')?>">
                                                        <?=isset($sections[$children->srsectionID]) ? $sections[$children->srsectionID] : ''?>
                                                    </td>

                                                </tr>

                                            <?php $i++; } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- email modal starts here -->
    <form class="form-horizontal" role="form" action="<?=base_url('profile/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                </div>
                <div class="modal-body">

                    <?php
                        if(form_error('to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="to" class="col-sm-2 control-label">
                            <?=$this->lang->line("to")?> <span class="text-red">*</span>
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
                            <?=$this->lang->line("subject")?> <span class="text-red">*</span>
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
                            <?=$this->lang->line("message")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
                </div>
            </div>
          </div>
        </div>
    </form>
    <!-- email end here -->

    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            window.location.reload();
        }

        function closeWindow() {
            location.reload();
        }


        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }


        $("#send_pdf").click(function(){
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var error = 0;

            $("#to_error").html("");
            if(to == "" || to == null) {
                error++;
                $("#to_error").html("");
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            }

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("");
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $('#send_pdf').attr('disabled','disabled');
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('profile/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&message=" + message,
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

        $('.profileDIV').each(function() {
            $(this).mCustomScrollbar({
                axis:"x"
            });
        });

    </script>
<?php } ?>
