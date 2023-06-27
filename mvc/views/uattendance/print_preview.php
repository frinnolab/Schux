<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <div class="profileArea">
        <?php featureheader($siteinfos);?>
        <div class="mainArea">
            <div class="areaTop">
                <div class="studentImage">
                    <img class="studentImg" src="<?=pdfimagelink($user->photo)?>" alt="">
                </div>
                <div class="studentProfile">
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('uattendance_name')?></div>
                        <div class="single_value">: <?=$user->name?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('uattendance_type')?></div>
                        <div class="single_value">: <?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('uattendance_sex')?></div>
                        <div class="single_value">: <?=$user->sex?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('uattendance_dob')?></div>
                        <div class="single_value">: <?php if($user->dob) { echo date("d M Y", strtotime($user->dob)); } ?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('uattendance_phone')?></div>
                        <div class="single_value">: <?=$user->phone?></div>
                    </div>
                </div>
            </div>

            <div class="sattendanceArea">
                <h4><?=$this->lang->line("uattendance_information")?></h4>
                <table class="attendance_table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <?php
                                for($i=1; $i<=31; $i++) {
                                    echo  "<th>".$this->lang->line('uattendance_'.$i)."</th>";
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
                <p class="totalattendanceCount">
                    <?=$this->lang->line('uattendance_total_holiday')?>:<?=$holidayCount?>, 
                    <?=$this->lang->line('uattendance_total_weekenday')?>:<?=$weekendayCount?>, 
                    <?=$this->lang->line('uattendance_total_leaveday')?>:<?=$leavedayCount?>, 
                    <?=$this->lang->line('uattendance_total_present')?>:<?=$presentCount?>, 
                    <?=$this->lang->line('uattendance_total_latewithexcuse')?>:<?=$lateexcuseCount?>, 
                    <?=$this->lang->line('uattendance_total_late')?>:<?=$lateCount?>, 
                    <?=$this->lang->line('uattendance_total_absent')?>:<?=$absentCount?>
                </p>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos)?>
</body>
</html>
