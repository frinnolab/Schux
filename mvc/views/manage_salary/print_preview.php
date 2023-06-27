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
                        <div class="single_label"><?=$this->lang->line('manage_salary_name')?></div>
                        <div class="single_value">: <?=$user->name?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('manage_salary_type')?></div>
                        <div class="single_value">: <?=$usertype->usertype;?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('manage_salary_gender')?></div>
                        <div class="single_value">: <?=$user->sex?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('manage_salary_dob')?></div>
                        <div class="single_value">: <?php if($user->dob) { echo date("d M Y", strtotime($user->dob)); } ?></div>
                    </div>
                    <div class="singleItem">
                        <div class="single_label"><?=$this->lang->line('manage_salary_phone')?></div>
                        <div class="single_value">: <?=$user->phone?></div>
                    </div>
                </div>
            </div>
            
            <div class="managesalaryArea">
                <table class="table table-bordered">
                    <?php if($manage_salary->salary == 1) { ?>
                        <tr>
                            <td><?=$this->lang->line("manage_salary_salary_grades")?></td>
                            <td><?=$salary_template->salary_grades?></td>
                            <td rowspan="3" colspan="2"></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line("manage_salary_basic_salary")?></td>
                            <td><?=number_format($salary_template->basic_salary, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line("manage_salary_overtime_rate")?></td>
                            <td><?=number_format($salary_template->overtime_rate, 2)?></td>
                        </tr>
                        <tr>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('manage_salary_allowances')?></td>
                            <td class="text-bold" colspan="2"><?=$this->lang->line('manage_salary_deductions')?></td>
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
                        <?php } ?>
                        
                        <tr>
                            <td class="text-bold text-center" colspan="4"><?=$this->lang->line('manage_salary_total_salary_details')?></td>
                        </tr>
                        <tr>
                            <td rowspan="3" colspan="2"></td>
                            <td><?=$this->lang->line('manage_salary_gross_salary')?></td>
                            <td><?=number_format($grosssalary, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('manage_salary_total_deduction')?></td>
                            <td><?=number_format($totaldeduction, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('manage_salary_net_salary')?></td>
                            <td><?=number_format($netsalary, 2)?></td>
                        </tr>
                    <?php } elseif($manage_salary->salary == 2) { ?>
                        <tr>
                            <td><?=$this->lang->line("manage_salary_salary_grades")?></td>
                            <td><?=$hourly_salary->hourly_grades?></td>
                            <td rowspan="2" colspan="2"></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line("hourly_template_hourly_rate")?></td>
                            <td><?=number_format($hourly_salary->hourly_rate, 2)?></td>
                        </tr>
                        <tr>
                            <td class="text-bold text-center" colspan="4"><?=$this->lang->line('manage_salary_total_salary_details')?></td>
                        </tr>
                        <tr>
                            <td rowspan="3" colspan="2"></td>
                            <td><?=$this->lang->line('manage_salary_gross_salary')?></td>
                            <td><?=number_format($grosssalary, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('manage_salary_total_deduction')?></td>
                            <td><?=number_format($totaldeduction, 2)?></td>
                        </tr>
                        <tr>
                            <td><?=$this->lang->line('manage_salary_net_salary')?></td>
                            <td><?=number_format($netsalary, 2)?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </div>
    <?php featurefooter($siteinfos);?>
</body>
</html>