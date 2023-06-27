<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="box-body">
    <div class="row">
        <div class="col-sm-12">
            <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
        </div>

        <h3 style="margin-bottom: 0px;"><?=$this->lang->line('salaryreport_report_for')?> - <?=$this->lang->line('salaryreport_salary')?></h3>

        <?php if($fromdate != 0 && $todate != 0 ) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left" style="margin-top:5px">
                    <?=$this->lang->line('salaryreport_fromdate')?> : <?=date('d M Y', $fromdate)?></p>
                </h5>
                <h5 class="pull-right" style="margin-top:5px">
                    <?=$this->lang->line('salaryreport_todate')?> : <?=date('d M Y', $todate)?></p>
                </h5>
            </div>
        <?php } elseif($month != 0 ) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left" style="margin-top:5px">
                    <?=$this->lang->line('salaryreport_month')?> : <?=date('M Y', $month)?></p>
                </h5>
            </div>
        <?php } elseif($usertypeID != 0 && $userID != 0 ) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left" style="margin-top:5px">
                    <?php
                        echo $this->lang->line('salaryreport_role')." : ";
                        echo $usertypes[$usertypeID];
                    ?>
                </h5>
                <h5 class="pull-right" style="margin-top:5px">
                    <?php
                        echo $this->lang->line('salaryreport_user_name')." : ";
                        echo $allUserName[$usertypeID][$userID]->name;
                    ?>
                </h5>
            </div>
        <?php } elseif($usertypeID != 0) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left" style="margin-top:5px">
                    <?php
                        echo $this->lang->line('salaryreport_role')." : ";
                        echo $usertypes[$usertypeID];
                    ?>
                </h5>
            </div>
        <?php } elseif($usertypeID == 0) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left" style="margin-top:5px">
                    <?php
                        echo $this->lang->line('salaryreport_role')." : ";
                        echo $this->lang->line('salaryreport_alluser');
                    ?>
                </h5>
            </div>
        <?php } ?>

        <div class="col-sm-12">
        <?php if(count($salarys)) { ?>
            <table>
                <thead>
                <tr>
                    <th><?=$this->lang->line('slno')?></th>
                    <th><?=$this->lang->line('salaryreport_date')?></th>
                    <th><?=$this->lang->line('salaryreport_name')?></th>
                    <th><?=$this->lang->line('salaryreport_role')?></th>
                    <th><?=$this->lang->line('salaryreport_month')?></th>
                    <th><?=$this->lang->line('salaryreport_amount')?></th>
                </tr>
                </thead>
                <tbody>
                <?php $totalSalary = 0; $i=1; 
                foreach($salarys as $salary) { ?>
                    <tr>
                        <td><?=$i?></td>
                        <td><?=date('d M Y',strtotime($salary->create_date))?></td>
                        <td><?=isset($allUserName[$salary->usertypeID][$salary->userID]) ? $allUserName[$salary->usertypeID][$salary->userID]->name : ''?></td>
                        <td><?=isset($usertypes[$salary->usertypeID]) ? $usertypes[$salary->usertypeID] : ''?></td>
                        <td><?=date('M Y',strtotime('01-'.$salary->month))?></td>
                        <td>
                            <?php
                            echo number_format($salary->payment_amount,2);
                            $totalSalary += $salary->payment_amount;
                            ?>
                        </td>
                    </tr>
                    <?php $i++; } ?>
                    <tr>
                        <td colspan="5" class="grand-total"><?=$this->lang->line('salaryreport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                        <td class="text-bold"><?=number_format($totalSalary,2)?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="notfound">
                <b><?=$this->lang->line('salaryreport_data_not_found'); ?></b>
            </div>
        <?php } ?>
        </div><!-- row -->
        <div class="col-sm-12 text-center footerAll">
            <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
        </div>
    </div><!-- Body -->
</div><!-- Body -->
</body>
</html>