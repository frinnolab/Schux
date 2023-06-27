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
            <div class="col-sm-12">
                <h3> <?=$this->lang->line('transactionreport_report_for')?> - <?=$this->lang->line("transactionreport_option_".$pdfoption)?> </h3>
            </div>
            <div class="col-sm-12">
                <h5 class="pull-left"><?=$this->lang->line('transactionreport_fromdate')?> : <?=date('d M Y',$fromdate)?></h5>                         
                <h5 class="pull-right"><?=$this->lang->line('transactionreport_todate')?> : <?=date('d M Y',$todate)?></h5>
            </div>
            <div class="col-sm-12">
            <?php if($pdfoption == 1) { ?>
                <div id="fees_collection_details" class="tab-pane active">
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_date')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_name')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_registerNO')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_class')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_section')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_roll')?></th>
                                    <th rowspan="2"><?=$this->lang->line('transactionreport_feetype')?></th>
                                    <th colspan="3"><?=$this->lang->line('transactionreport_paid')?></th>
                                    <!-- <th><?=$this->lang->line('transactionreport_weaver')?></th> -->
                                    <th colspan="3"><?=$this->lang->line('transactionreport_fine')?></th>
                                </tr>
                                <tr>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalamountUSD = 0;
                                    $totalamountGBP = 0;
                                    $totalamountTZS = 0;

                                    $totalweaver = 0;

                                    $totalfineUSD   = 0;
                                    $totalfineGBP   = 0;
                                    $totalfineTZS   = 0;
                                    if(count($get_payments)) { $i=1; foreach($get_payments as $get_payment) {
                                         if(isset($weaverandfine[$get_payment->paymentID]) && (($weaverandfine[$get_payment->paymentID]->weaver != '') || ($weaverandfine[$get_payment->paymentID]->fine != '')) || $get_payment->paymentamount != '') { ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=date('d M Y',strtotime($get_payment->paymentdate))?></td>
                                        <td><?=isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srname : ''?></td>
                                        <td><?=isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srregisterNO : ''?></td>
                                        <td>
                                            <?php
                                                if(isset($students[$get_payment->studentID])) {
                                                    if(isset($classes[$students[$get_payment->studentID]->srclassesID])) {
                                                        echo $classes[$students[$get_payment->studentID]->srclassesID];
                                                    }  
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if(isset($students[$get_payment->studentID])) {
                                                    if(isset($sections[$students[$get_payment->studentID]->srsectionID])) {
                                                        echo $sections[$students[$get_payment->studentID]->srsectionID];
                                                    }  
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?=isset($students[$get_payment->studentID]) ? $students[$get_payment->studentID]->srroll : ''?>
                                        </td>
                                        <td><?=isset($feetypes[$get_payment->feetypeID]) ? $feetypes[$get_payment->feetypeID] : ''?></td>
                                        <td>
                                        <?php
                                            if ($get_payment->currency == "USD") {
                                                $amount = $get_payment->paymentamount;
                                                echo number_format($amount,2); 
                                                $totalamountUSD +=$amount;
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php
                                            if ($get_payment->currency == "GBP") {
                                                $amount = $get_payment->paymentamount;
                                                echo number_format($amount,2); 
                                                $totalamountGBP +=$amount;
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php
                                            if ($get_payment->currency == "TZS") {
                                                $amount = $get_payment->paymentamount;
                                                echo number_format($amount,2); 
                                                $totalamountTZS +=$amount;
                                            }
                                        ?>
                                        </td>
                                        <!-- <td>
                                        <?php 
                                            if(isset($weaverandfine[$get_payment->paymentID])) {
                                                $weaver = $weaverandfine[$get_payment->paymentID]->weaver;
                                                echo number_format($weaver,2);
                                                $totalweaver += $weaver;
                                            } else {
                                                echo number_format(0,2);
                                            }
                                        ?>
                                        </td> -->
                                        <td>
                                        <?php 
                                            if ($weaverandfine[$get_payment->paymentID]->currency == "USD") {
                                                if(isset($weaverandfine[$get_payment->paymentID])) {
                                                    $fine = $weaverandfine[$get_payment->paymentID]->fine;
                                                    echo number_format($fine,2);
                                                    $totalfineUSD +=$fine;
                                                } else{
                                                    echo number_format(0,2);
                                                }
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            if ($weaverandfine[$get_payment->paymentID]->currency == "GBP") {
                                                if(isset($weaverandfine[$get_payment->paymentID])) {
                                                    $fine = $weaverandfine[$get_payment->paymentID]->fine;
                                                    echo number_format($fine,2);
                                                    $totalfineGBP +=$fine;
                                                } else{
                                                    echo number_format(0,2);
                                                }
                                            }
                                        ?>
                                        </td>
                                        <td>
                                        <?php 
                                            if ($weaverandfine[$get_payment->paymentID]->currency == "TZS") {
                                                if(isset($weaverandfine[$get_payment->paymentID])) {
                                                    $fine = $weaverandfine[$get_payment->paymentID]->fine;
                                                    echo number_format($fine,2);
                                                    $totalfineTZS +=$fine;
                                                } else{
                                                    echo number_format(0,2);
                                                }
                                            }
                                        ?>
                                        </td>
                                    </tr>
                                <?php $i++; } } } ?>
                                <tr>
                                    <td colspan="8" class="grand-total"><?=$this->lang->line('transactionreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?> --></td>
                                    <td class="rtext-bold"><?=number_format($totalamountUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalamountGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalamountTZS,2)?></td>
                                    <!-- <td class="rtext-bold"><?=number_format($totalweaver,2)?></td> -->
                                    <td class="rtext-bold"><?=number_format($totalfineUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalfineGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalfineTZS,2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } elseif($pdfoption == 2) { ?>
                <div id="income_details" class="tab-pane">
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('transactionreport_name')?></th>
                                    <th><?=$this->lang->line('transactionreport_date')?></th>
                                    <th><?=$this->lang->line('transactionreport_amount')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalincome = 0;
                                if(count($incomes)) {
                                    $i = 1;
                                    foreach($incomes as $income) { ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$income->name?></td>
                                            <td><?=date('d M Y',strtotime($income->date))?></td>
                                            <td>
                                                <?php 
                                                    $amount = $income->amount;
                                                    echo number_format($amount,2);
                                                    $totalincome += $amount;
                                                ?>
                                            </td>
                                        </tr>
                                <?php $i++; } } ?>
                                <tr>
                                    <td colspan="3" class="grand-total"><?=$this->lang->line('transactionreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?> --></td>
                                    <td class="rtext-bold"> <?=number_format($totalincome,2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } elseif($pdfoption == 3) { ?>
                <div id="expense_details" class="tab-pane">
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('transactionreport_name')?></th>
                                    <th><?=$this->lang->line('transactionreport_date')?></th>
                                    <th><?=$this->lang->line('transactionreport_amount')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $totalexpense = 0; $i=1; if(count($expenses)) { foreach($expenses as $expense) {?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=$expense->expense?></td>
                                        <td><?=date('d M Y',strtotime($expense->date))?></td>
                                        <td>
                                        <?php
                                            $amount = $expense->amount;
                                            echo number_format($amount,2);
                                            $totalexpense += $amount; 
                                        ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                <tr>
                                     <td colspan="3" class="grand-total"><?=$this->lang->line('transactionreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?> --></td>
                                    <td class="rtext-bold"><?=number_format($totalexpense,2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php } elseif ($pdfoption) { ?>
                 <table>
                    <thead>
                        <tr>
                            <th><?=$this->lang->line('slno')?></th>
                            <th><?=$this->lang->line('transactionreport_date')?></th>
                            <th><?=$this->lang->line('transactionreport_name')?></th>
                            <th><?=$this->lang->line('transactionreport_type')?></th>
                            <th><?=$this->lang->line('transactionreport_month')?></th>
                            <th><?=$this->lang->line('transactionreport_amount')?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $totalSalary = 0; $i=1; if(count($salarys)) { foreach($salarys as $salary) {?>
                            <tr>
                                <td><?=$i?></td>
                                <td><?=date('d M Y',strtotime($salary->create_date))?></td>
                                <td><?=isset($allUserName[$salary->usertypeID][$salary->userID]) ? $allUserName[$salary->usertypeID][$salary->userID]->name : ''?></td>
                                <td><?=isset($usertypes[$salary->usertypeID]) ? $usertypes[$salary->usertypeID] : ''?></td>
                                <td><?=date('F Y',strtotime('01-'.$salary->month))?></td>
                                <td>
                                    <?php 
                                        echo number_format($salary->payment_amount,2);
                                        $totalSalary += $salary->payment_amount;
                                    ?>
                                </td>
                            </tr>
                        <?php $i++; } } ?>
                        <tr>
                             <td colspan="5" class="grand-total"><?=$this->lang->line('transactionreport_grand_total')?><!-- <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?> --></td>
                            <td class="text-bold"><?=number_format($totalSalary,2)?></td>
                        </tr>
                    </tbody>
                </table>
            <?php } else { ?>
                <div class="notfound">
                    <?=$this->lang->line('transactionreport_data_not_found')?>
                </div>
            <?php } ?>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>