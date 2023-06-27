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
                            <div class="single_label"><?=$this->lang->line('make_payment_name')?></div>
                            <div class="single_value">: <?=$user->name?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('make_payment_type')?></div>
                            <div class="single_value">: <?=$usertype->usertype;?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('make_payment_gender')?></div>
                            <div class="single_value">: <?=$user->sex?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('make_payment_dob')?></div>
                            <div class="single_value">: <?php if($user->dob) { echo date("d M Y", strtotime($user->dob)); } ?></div>
                        </div>
                        <div class="singleItem">
                            <div class="single_label"><?=$this->lang->line('make_payment_phone')?></div>
                            <div class="single_value">: <?=$user->phone?></div>
                        </div>
                    </div>
                </div>
                <div class="managesalaryArea">
                    <table class="table table-bordered">
                        <?php 
                            $rowspan = 4;
                            if(isset($make_payment->total_hours)) { ?>
                                <tr>
                                    <td><?=$this->lang->line("make_payment_salary_grades")?></td>
                                    <td><?=$persent_salary_template->hourly_grades?></td>
                                    <td rowspan="<?=$rowspan+2?>" colspan="2"></td>
                                </tr>
                                <tr>
                                    <td><?=$this->lang->line("make_payment_hourly_rate")?></td>
                                    <td><?=number_format($persent_salary_template->hourly_rate, 2)?></td>
                                </tr>
                        <?php } else {
                            if($make_payment->salaryID == 1) { ?>
                            <tr>
                                <td><?=$this->lang->line("make_payment_salary_grades")?></td>
                                <td><?=$persent_salary_template->salary_grades?></td>
                                <td rowspan="<?=$rowspan+3?>" colspan="2"></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line("make_payment_basic_salary")?></td>
                                <td><?=number_format($persent_salary_template->basic_salary, 2)?></td>
                            </tr>
                            <tr>
                                <td><?=$this->lang->line("make_payment_overtime_rate")?></td>
                                <td><?=number_format($persent_salary_template->overtime_rate, 2)?></td>
                            </tr>
                        <?php } } ?>

                        <tr>
                            <td><?=$this->lang->line("make_payment_month")?></td>
                            <td><?=date('M Y', strtotime('1-'.$make_payment->month))?></td>
                        </tr>

                        <tr>
                            <td><?=$this->lang->line("make_payment_date")?></td>
                            <td><?=date('d M Y', strtotime($make_payment->create_date))?></td>
                        </tr>

                        <tr>
                            <td><?=$this->lang->line("make_payment_payment_method")?></td>
                            <td><?=isset($paymentMethod[$make_payment->payment_method]) ? $paymentMethod[$make_payment->payment_method] : ''?></td>
                        </tr>

                        <tr>
                            <td><?=$this->lang->line("make_payment_comments")?></td>
                            <td><?=$make_payment->comments?></td>
                        </tr>
          
                        <?php if($make_payment->salaryID == 1) { ?>
                            <tr>
                                <td colspan="2" class="text-bold"><?=$this->lang->line('make_payment_allowances');?></td>
                                <td colspan="2" class="text-bold"><?=$this->lang->line('make_payment_deductions');?></td>
                            </tr>
                            <?php 
                                if(count($salaryoptions)) {
                                    $allowanceVal = 0;
                                    $deductionVal = 0;
                                    $max = 0;
                                    $allowanceArray = [];
                                    $deductionArray = [];
                                    foreach ($salaryoptions as $salaryoption) {
                                        if($salaryoption->option_type == 1) {
                                            $allowanceVal++;
                                            $allowanceArray[] = $salaryoption;      
                                        } elseif($salaryoption->option_type == 2) {
                                            $deductionVal++;
                                            $deductionArray[] = $salaryoption;
                                        }
                                    }

                                    if($allowanceVal > $deductionVal) {
                                        $max = $allowanceVal;
                                    } else {
                                        $max = $deductionVal;
                                    } ?>
                                    
                                    <tr>
                                        <td class="subtable" colspan="2">
                                            <table>
                                                    <?php for($i=0;$i<$max;$i++) { 
                                                        if(isset($allowanceArray[$i])) { ?>
                                                        <tr>
                                                            <td><?=$allowanceArray[$i]->label_name?></td>
                                                            <td><?=number_format($allowanceArray[$i]->label_amount,2)?></td>
                                                        </tr>
                                                    <?php } } ?>
                                            </table>
                                        </td>
                                        <td class="subtable" colspan="2">
                                            <table>
                                                    <?php for($i=0;$i<$max;$i++) { 
                                                        if(isset($deductionArray[$i])) { ?>
                                                <tr>
                                                            <td><?=$deductionArray[$i]->label_name?></td>
                                                            <td><?=number_format($deductionArray[$i]->label_amount,2)?></td>
                                                </tr>
                                                    <?php } } ?>
                                            </table>
                                        </td>
                                    </tr>
                            <?php } } ?>
                        <tr>
                            <td class="text-bold text-center" colspan="4"><?=$this->lang->line('make_payment_total_salary_details')?></td>
                        </tr>
                        <?php 
                            $rowspan = 4;
                            if(isset($make_payment->total_hours)) {
                                $rowspan = 5;
                            }
                        ?>
                        <tr>
                            <td rowspan="<?=$rowspan?>" colspan="2"></td>
                            <td><?=$this->lang->line('make_payment_gross_salary')?></td>
                            <?php 
                                $total_gross_salary = 0;
                                if($make_payment->salaryID == 1) {
                                    $total_gross_salary = ($make_payment->gross_salary+$persent_salary_template->basic_salary);
                                } else {
                                    $total_gross_salary = $make_payment->gross_salary;
                                }
                            ?>
                            <td><?=number_format($total_gross_salary, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('make_payment_total_deduction')?></td>
                            <td><?=number_format($make_payment->total_deduction, 2)?></td>
                        </tr>

                        <?php 
                            if(isset($make_payment->total_hours)) {
                                $net_hourly_rate = ($make_payment->total_hours * $make_payment->net_salary);
                                $net_hourly_rate_grp = '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .')';
                        ?>
                            <tr>
                                <td><?=$this->lang->line('make_payment_total_hours')?></td>
                                <td><?=number_format($make_payment->total_hours, 2)?></td>
                            </tr>
                        <?php } ?>

                        <?php if(isset($make_payment->total_hours)) { ?>
                            <tr>
                                <td><?=$this->lang->line('make_payment_net_hourly_rate')?> <span class="text-red"><?=$net_hourly_rate_grp?></span></td>
                                <td><?=number_format($net_hourly_rate, 2)?></td>
                            </tr>
                        <?php } else { ?>
                            <tr>
                                <td><?=$this->lang->line('make_payment_net_salary')?></td>
                                <td class="text-bold"><?=number_format($make_payment->net_salary, 2)?></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td class="text-bold" ><?=$this->lang->line('make_payment_payment_amount')?></td>
                            <td class="text-bold" ><?=number_format($make_payment->payment_amount, 2)?></td>
                        </tr>
                    </table>
                </div>
            </div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>