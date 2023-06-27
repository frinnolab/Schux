<?php if(count($profile)) { ?>
<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <?php if(!permissionChecker('user_view') && permissionChecker('user_add')) { echo btn_sm_add('user/add', $this->lang->line('add_user')); } ?>

            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?php
                echo btn_add_pdf('user/print_preview/'.$profile->userID, $this->lang->line('pdf_preview')) 
            ?>

            <?php if(permissionChecker('user_edit')) { echo btn_sm_edit('user/edit/'.$profile->userID, $this->lang->line('edit')); } 
            ?>
            <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("user/index")?>"><?=$this->lang->line('menu_user')?></a></li>
                <li class="active"><?=$this->lang->line('view')?></li>
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
                    <h3 class="profile-username text-center"><?=$profile->name?></h3>
                    <p class="text-muted text-center"><?=isset($usertypes[$profile->usertypeID]) ? $usertypes[$profile->usertypeID] : ''?></p>
                    <ul class="list-group list-group-unbordered">
                        <li class="list-group-item" style="background-color: #FFF">
                            <b><?=$this->lang->line('user_sex')?></b> <a class="pull-right"><?=$profile->sex?></a>
                        </li>
                        <li class="list-group-item" style="background-color: #FFF">
                            <b><?=$this->lang->line('user_dob')?></b> <a class="pull-right"><?=date('d M Y',strtotime($profile->dob))?></a>
                        </li>
                        <li class="list-group-item" style="background-color: #FFF">
                            <b><?=$this->lang->line('user_phone')?></b> <a class="pull-right"><?=$profile->phone?></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-sm-9">
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('user_profile')?></a></li>
                    <?php if((permissionChecker('user_add') && permissionChecker('user_delete'))  || ($this->session->userdata('usertypeID') == $profile->usertypeID && $this->session->userdata('loginuserID') == $profile->userID)) {  ?>
                        <li><a href="#attendance" data-toggle="tab"><?=$this->lang->line('user_attendance')?></a></li>
                        <?php if(count($manage_salary)) {?>
                            <li><a href="#salary" data-toggle="tab"><?=$this->lang->line('user_salary')?></a></li>
                            <li><a href="#payment" data-toggle="tab"><?=$this->lang->line('user_payment')?></a></li>
                        <?php } ?>
                        <li><a href="#document" data-toggle="tab"><?=$this->lang->line('user_document')?></a></li>
                    <?php } ?>
                </ul>

                <div class="tab-content">
                    <div class="active tab-pane" id="profile">
                        <div class="panel-body profile-view-dis">
                            <div class="row">
                                <div class="profile-view-tab">
                                    <p><span><?=$this->lang->line("user_jod")?> </span>: <?=date("d M Y", strtotime($profile->jod))?></p>
                                </div>
                                <div class="profile-view-tab">
                                    <p><span><?=$this->lang->line("user_religion")?> </span>: <?=$profile->religion?></p>
                                </div>
                                <div class="profile-view-tab">
                                    <p><span><?=$this->lang->line("user_email")?> </span>: <?=$profile->email?></p>
                                </div>
                                <div class="profile-view-tab">
                                    <p><span><?=$this->lang->line("user_address")?> </span>: <?=$profile->address?></p>
                                </div>

                                <div class="profile-view-tab">
                                    <p><span><?=$this->lang->line("user_username")?> </span>: <?=$profile->username?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    

                    <?php if((permissionChecker('user_add') && permissionChecker('user_delete'))  || ($this->session->userdata('usertypeID') == $profile->usertypeID && $this->session->userdata('loginuserID') == $profile->userID)) {  ?>

                        <div class="tab-pane" id="attendance">
                            <div id="userDIV">
                                <table class="attendance_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <?php
                                                for($i=1; $i<=31; $i++) {
                                                   echo  "<th>".$this->lang->line('user_'.$i)."</th>";
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
                                <?=$this->lang->line('user_total_holiday')?>:<?=$holidayCount?>, 
                                <?=$this->lang->line('user_total_weekenday')?>:<?=$weekendayCount?>, 
                                <?=$this->lang->line('user_total_leaveday')?>:<?=$leavedayCount?>, 
                                <?=$this->lang->line('user_total_present')?>:<?=$presentCount?>, 
                                <?=$this->lang->line('user_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                                <?=$this->lang->line('user_total_late')?>:<?=$lateCount?>, 
                                <?=$this->lang->line('user_total_absent')?>:<?=$absentCount?>
                            </p>
                        </div>

                        <?php if(count($manage_salary)) {?>
                            <div class="tab-pane" id="salary">
                                <?php if($manage_salary->salary == 1) { ?>
                                    <div class="row">
                                        <div class="col-sm-6" style="margin-bottom: 10px;">
                                            <div class="info-box">
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("user_salary_grades")?></span>
                                                    <?=$salary_template->salary_grades?>
                                                </p>

                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("user_basic_salary")?></span>
                                                    <?=number_format($salary_template->basic_salary, 2)?>
                                                </p>

                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("user_overtime_rate")?></span>
                                                    <?=number_format($salary_template->overtime_rate, 2)?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="box" style="border: 1px solid #eee">
                                                <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                    <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('user_allowances')?></h3>
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
                                                    <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('user_deductions')?></h3>
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
                                                    <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('user_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_gross_salary')?></td>

                                                            <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_total_deduction')?></td>

                                                            <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_net_salary')?></td>

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
                                                    <span><?=$this->lang->line("user_salary_grades")?></span>
                                                    <?=$hourly_salary->hourly_grades?>
                                                </p>

                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("user_hourly_rate")?></span>
                                                    <?=number_format($hourly_salary->hourly_rate, 2)?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-8 col-sm-offset-4">
                                            <div class="box" style="border: 1px solid #eee;">
                                                <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                    <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('user_total_salary_details')?></h3>
                                                </div>
                                                <div class="box-body">
                                                    <table class="table table-bordered">
                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_gross_salary')?></td>

                                                            <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_total_deduction')?></td>

                                                            <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                        </tr>

                                                        <tr>
                                                            <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('user_net_hourly_rate')?></td>

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
                                                <th><?=$this->lang->line('user_month')?></th>
                                                <th><?=$this->lang->line('user_date')?></th>
                                                <th><?php if($manage_salary->salary == 2) { echo $this->lang->line('user_net_salary_hourly'); } else { echo $this->lang->line('user_net_salary'); } ?></th>
                                                <th><?=$this->lang->line('user_payment_amount')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $paymentTotal = 0; if(count($make_payments)) { $i = 1; foreach($make_payments as $make_payment) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>">
                                                        <?=$i;?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('user_month')?>">
                                                        <?php echo date("M Y", strtotime('1-'.$make_payment->month)); ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('user_date')?>">
                                                        <?php echo date("d M Y", strtotime($make_payment->create_date)); ?>
                                                    </td>

                                                    <td data-title="<?php if($manage_salary->salary == 2) { echo $this->lang->line('user_net_salary_hourly'); } else { echo $this->lang->line('user_net_salary'); } ?>">
                                                        <?php
                                                            if(isset($make_payment->total_hours)) {
                                                                echo '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .') = '. (number_format($make_payment->total_hours * $make_payment->net_salary, 2)); 
                                                            } else {
                                                                echo number_format($make_payment->net_salary, 2); 
                                                            }
                                                        ?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('user_payment_amount')?>">
                                                        <?php echo number_format($make_payment->payment_amount, 2); $paymentTotal += $make_payment->payment_amount; ?>
                                                    </td>
                                                </tr>
                                            <?php $i++; }} ?>
                                            <tr>
                                                <td colspan="4" data-title="<?=$this->lang->line('user_total')?>">
                                                    <?php if($siteinfos->currency_code) { echo '<b>'. $this->lang->line('user_total').' ('.$siteinfos->currency_code.')'. '</b>'; } else { echo '<b>'. $this->lang->line('user_total') . '</b>'; }
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('user_total')?> <?=$this->lang->line('user_payment_amount')?>">
                                                    <?=number_format($paymentTotal, 2)?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        <?php } ?>
                    
                        <div class="tab-pane" id="document">
                            <?php if(permissionChecker('user_add')) { ?>
                                <input class="btn btn-success btn-sm" style="margin-bottom: 10px" type="button" value="<?=$this->lang->line('user_add_document')?>" data-toggle="modal" data-target="#documentupload">
                            <?php } ?>
                            <div id="hide-table">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('slno')?></th>
                                            <th><?=$this->lang->line('user_title')?></th>
                                            <th><?=$this->lang->line('user_date')?></th>
                                            <th><?=$this->lang->line('action')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(count($documents)) { $i = 1; foreach ($documents as $document) {  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('slno')?>">
                                                    <?php echo $i; ?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('user_title')?>">
                                                    <?=$document->title?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('user_date')?>">
                                                    <?=date('d M Y', strtotime($document->create_date))?>
                                                </td>

                                                <td data-title="<?=$this->lang->line('action')?>">
                                                    <?php 
                                                        if((permissionChecker('user_add') && permissionChecker('user_delete')) || ($this->session->userdata('usertypeID') == $profile->usertypeID && $this->session->userdata('loginuserID') == $profile->userID)) {
                                                            echo btn_download('user/download_document/'.$document->documentID.'/'.$profile->userID.'/'.$profile->usertypeID, $this->lang->line('download'));
                                                        }

                                                        if(permissionChecker('user_add') && permissionChecker('user_delete')) {
                                                            echo btn_delete_show('user/delete_document/'.$document->documentID.'/'.$profile->userID.'/'.$profile->usertypeID, $this->lang->line('delete'));
                                                        } 
                                                    ?>
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

<?php if(permissionChecker('user_add')) { ?>
        <form id="documentUploadDataForm" class="form-horizontal" enctype="multipart/form-data" role="form" action="<?=base_url('user/documentUpload');?>" method="post">
            <div class="modal fade" id="documentupload">
              <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('user_document_upload')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group" >
                            <label for="title" class="col-sm-2 control-label">
                                <?=$this->lang->line("user_title")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="title_error">
                            </span>
                        </div>

                        <div class="form-group">
                           <label for="file" class="col-sm-2 control-label">
                                <?=$this->lang->line("user_file")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <div class="input-group image-preview">
                                    <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                            <span class="fa fa-remove"></span>
                                            <?=$this->lang->line('user_clear')?>
                                        </button>
                                        <div class="btn btn-success image-preview-input">
                                            <span class="fa fa-repeat"></span>
                                            <span class="image-preview-input-title">
                                            <?=$this->lang->line('user_file_browse')?></span>
                                            <input type="file" id="file" name="file"/>
                                        </div>
                                    </span>
                                </div>
                            </div>
                            <span class="col-sm-4 control-label" id="file_error">
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                        <input type="button" id="uploadfile" class="btn btn-success" value="<?=$this->lang->line("user_upload")?>" />
                    </div>
                </div>
              </div>
            </div>
        </form>


        <script type="text/javascript">
            $(document).on('click', '#uploadfile', function() {
                var title = $('#title').val();
                var file = $('#file').val();
                var error = 0;

                if(title == '' || title == null) {
                    error++;
                    $('#title_error').html("<?=$this->lang->line('user_title_required')?>");
                    $('#title_error').parent().addClass('has-error');
                } else {
                    $('#title_error').html('');
                    $('#title_error').parent().removeClass('has-error');
                }

                if(file == '' || file == null) {
                    error++;
                    $('#file_error').html("<?=$this->lang->line('user_file_required')?>");
                    $('#file_error').parent().addClass('has-error');
                } else {
                    $('#file_error').html('');
                    $('#file_error').parent().removeClass('has-error');
                }

                if(error == 0) {
                    var userID = "<?=$profile->userID?>";
                    var usertypeID = "<?=$profile->usertypeID?>";
                    var formData = new FormData($('#documentUploadDataForm')[0]);
                    formData.append("userID", userID);
                    formData.append("usertypeID", usertypeID);
                    $.ajax({
                        type: 'POST',
                        dataType: "json",
                        url: "<?=base_url('user/documentUpload')?>",
                        data: formData,
                        async: false,
                        dataType: "html",
                        success: function(data) {
                            var response = jQuery.parseJSON(data);
                            if(response.status) {
                                $('#title_error').html();
                                $('#title_error').parent().removeClass('has-error');

                                $('#file_error').html();
                                $('#file_error').parent().removeClass('has-error');
                                location.reload();
                            } else {
                                if(response.errors['title']) {
                                    $('#title_error').html(response.errors['title']);
                                    $('#title_error').parent().addClass('has-error');
                                } else {
                                    $('#title_error').html();
                                    $('#title_error').parent().removeClass('has-error');
                                }
                                
                                if(response.errors['file']) {
                                    $('#file_error').html(response.errors['file']);
                                    $('#file_error').parent().addClass('has-error');
                                } else {
                                    $('#file_error').html();
                                    $('#file_error').parent().removeClass('has-error');
                                }
                            }
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }
            });     

            $(function() {
                var closebtn = $('<button/>', {
                    type:"button",
                    text: 'x',
                    id: 'close-preview',
                    style: 'font-size: initial;',
                });
                closebtn.attr("class","close pull-right");

                $('.image-preview').popover({
                    trigger:'manual',
                    html:true,
                    title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                    content: "There's no image",
                    placement:'bottom'
                });

                $('.image-preview-clear').click(function(){
                    $('.image-preview').attr("data-content","").popover('hide');
                    $('.image-preview-filename').val("");
                    $('.image-preview-clear').hide();
                    $('.image-preview-input input:file').val("");
                    $(".image-preview-input-title").text("<?=$this->lang->line('user_file_browse')?>");
                });

                $(".image-preview-input input:file").change(function (){
                    var img = $('<img/>', {
                        id: 'dynamic',
                        width:250,
                        height:200,
                        overflow:'hidden'
                    });

                    var file = this.files[0];
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(".image-preview-input-title").text("<?=$this->lang->line('user_file_browse')?>");
                        $(".image-preview-clear").show();
                        $(".image-preview-filename").val(file.name);
                    }
                    reader.readAsDataURL(file);
                });
            });
        </script>
<?php } ?>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('teacher/send_mail');?>" method="post">
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
            $("#to_error").html("<?=$this->lang->line('mail_valid');?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var to = $('#to').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var userID = "<?=$profile->userID?>";
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
                url: "<?=base_url('user/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&userID=" + userID+ "&message=" + message,
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

    $('#userDIV').mCustomScrollbar({
        axis:"x"
    });

</script>

<?php } ?>