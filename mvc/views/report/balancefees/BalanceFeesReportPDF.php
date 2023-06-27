<div class="box">
    <!-- form start -->
    <div class="box-body" style="margin-bottom: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
            </div>
            <div class="box-header bg-gray">
                <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
                    <?=$this->lang->line('balancefeesreport_report_for')?> - 
                    <?=$this->lang->line('balancefeesreport_balancefees');?>
                </h3>
            </div><!-- /.box-header -->
            <?php if($classesID >= 0 || $sectionID >= 0 ) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left">
                    <?php 
                        echo $this->lang->line('balancefeesreport_class')." : ";
                        echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('balancefeesreport_all_class');
                    ?>
                </h5>                         
                <h5 class="pull-right">
                    <?php
                       echo $this->lang->line('balancefeesreport_section')." : ";
                       echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('balancefeesreport_all_section');
                    ?>
                </h5>                        
            </div>
            <?php } 
            if(count($students)) { ?>
                <div class="col-sm-12">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                    <th rowspan="2"><?=$this->lang->line('balancefeesreport_name')?></th>
                                    <th rowspan="2"><?=$this->lang->line('balancefeesreport_registerNO')?></th>
                                    <?php if($classesID == 0) { ?>
                                        <th rowspan="2"><?=$this->lang->line('balancefeesreport_class')?></th>
                                    <?php } ?>
                                    <?php if($sectionID == 0) { ?>
                                        <th rowspan="2"><?=$this->lang->line('balancefeesreport_section')?></th>
                                    <?php } ?>
                                    <th rowspan="2"><?=$this->lang->line('balancefeesreport_roll')?></th>
                                    <th colspan="3"><?=$this->lang->line('balancefeesreport_fees_amount')?></th>
                                    <th colspan="3"><?=$this->lang->line('balancefeesreport_discount')?> </th>
                                    <th colspan="3"><?=$this->lang->line('balancefeesreport_paid')?> </th>
                                    <!-- <th><?=$this->lang->line('balancefeesreport_weaver')?> </th> -->
                                    <th colspan="3"><?=$this->lang->line('balancefeesreport_balance') ?></th>
                                </tr>
                                <tr>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
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
                                    $totalAmountUSD = 0;
                                    $totalAmountGBP = 0;
                                    $totalAmountTZS = 0;

                                    $totalDiscountUSD = 0;
                                    $totalDiscountGBP = 0;
                                    $totalDiscountTZS = 0;

                                    $totalPaymentsUSD = 0;
                                    $totalPaymentsGBP = 0;
                                    $totalPaymentsTZS = 0;

                                    $totalWeaver = 0;
                                    
                                    $totalBalanceUSD = 0;
                                    $totalBalanceGBP = 0;
                                    $totalBalanceTZS = 0;
                                    $i=0;
                                    foreach($students as $student) { 
                                        $i++;
                                        ?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=$student->srname?></td>
                                            <td><?=$student->srregisterNO?></td>
                                            <?php if($classesID == 0) { ?>
                                                <td><?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?></td>
                                            <?php } ?>

                                            <?php if($sectionID == 0) { ?>
                                                <td><?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?></td>
                                            <?php } ?>
                                            <td><?=$student->srroll?></td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['amountUSD']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountUSD'],2) : number_format(0, 2);
                                                    $AmountUSD = $totalAmountAndDiscount[$student->srstudentID]['amountUSD'];
                                                    $totalAmountUSD += $totalAmountAndDiscount[$student->srstudentID]['amountUSD'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['amountGBP']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountGBP'],2) : number_format(0, 2);
                                                    $AmountGBP = $totalAmountAndDiscount[$student->srstudentID]['amountGBP'];
                                                    $totalAmountGBP += $totalAmountAndDiscount[$student->srstudentID]['amountGBP'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['amountTZS']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountTZS'],2) : number_format(0, 2);
                                                    $AmountTZS = $totalAmountAndDiscount[$student->srstudentID]['amountTZS'];
                                                    $totalAmountTZS += $totalAmountAndDiscount[$student->srstudentID]['amountTZS'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['discountUSD']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountUSD'],2) : number_format(0, 2);
                                                    $DiscountUSD = $totalAmountAndDiscount[$student->srstudentID]['discountUSD'];
                                                    $totalDiscountUSD += $totalAmountAndDiscount[$student->srstudentID]['discountUSD'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['discountGBP']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountGBP'],2) : number_format(0, 2);
                                                    $DiscountGBP = $totalAmountAndDiscount[$student->srstudentID]['discountGBP'];
                                                    $totalDiscountGBP += $totalAmountAndDiscount[$student->srstudentID]['discountGBP'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalAmountAndDiscount[$student->srstudentID]['discountTZS']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountTZS'],2) : number_format(0, 2);
                                                    $DiscountTZS = $totalAmountAndDiscount[$student->srstudentID]['discountTZS'];
                                                    $totalDiscountTZS += $totalAmountAndDiscount[$student->srstudentID]['discountTZS'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalPayment[$student->srstudentID]['paymentUSD']) ? number_format($totalPayment[$student->srstudentID]['paymentUSD'],2) : number_format(0, 2);
                                                    $PaymentUSD = $totalPayment[$student->srstudentID]['paymentUSD'];
                                                    $totalPaymentsUSD += $totalPayment[$student->srstudentID]['paymentUSD'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalPayment[$student->srstudentID]['paymentGBP']) ? number_format($totalPayment[$student->srstudentID]['paymentGBP'],2) : number_format(0, 2);
                                                    $PaymentGBP = $totalPayment[$student->srstudentID]['paymentGBP'];
                                                    $totalPaymentsGBP += $totalPayment[$student->srstudentID]['paymentGBP'];
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    echo isset($totalPayment[$student->srstudentID]['paymentTZS']) ? number_format($totalPayment[$student->srstudentID]['paymentTZS'],2) : number_format(0, 2);
                                                    $PaymentTZS = $totalPayment[$student->srstudentID]['paymentTZS'];
                                                    $totalPaymentsTZS += $totalPayment[$student->srstudentID]['paymentTZS'];
                                                ?>
                                            </td>
                                            <!-- <td>
                                                <?=isset($totalweavar[$student->srstudentID]['weaver']) ? number_format($totalweavar[$student->srstudentID]['weaver'],2) : number_format(0, 2)?>
                                            </td> -->
                                            <td>
                                                <?php 
                                                    // $Amount = 0;
                                                    // $Discount = 0;
                                                    // $Payment = 0;
                                                    // $Weaver = 0;

                                                    // if(isset($totalAmountAndDiscount[$student->srstudentID]['amount'])) {
                                                    //     $Amount = $totalAmountAndDiscount[$student->srstudentID]['amount'];
                                                    //     $totalAmount += $Amount;
                                                    // }

                                                    // if(isset($totalAmountAndDiscount[$student->srstudentID]['discount'])) {
                                                    //     $Discount = $totalAmountAndDiscount[$student->srstudentID]['discount'];
                                                    //     $totalDiscount += $Discount;
                                                    // }

                                                    // if(isset($totalPayment[$student->srstudentID]['payment'])) {
                                                    //     $Payment = $totalPayment[$student->srstudentID]['payment'];
                                                    //     $totalPayments += $Payment;
                                                    // }

                                                    // if(isset($totalweavar[$student->srstudentID]['weaver'])) {
                                                    //     $Weaver = $totalweavar[$student->srstudentID]['weaver'];
                                                    //     $totalWeaver += $Weaver;
                                                    // }

                                                    // $Balance = ($Amount - $Discount) - ($Payment+$Weaver);
                                                    // $totalBalance += $Balance;

                                                    $BalanceUSD = ($AmountUSD - $DiscountUSD) - ($PaymentUSD);
                                                        $totalBalanceUSD += $BalanceUSD;
                                                    echo number_format($BalanceUSD,2);

                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $BalanceGBP = ($AmountGBP - $DiscountGBP) - ($PaymentGBP);
                                                    $totalBalanceGBP += $BalanceGBP;
                                                    echo number_format($BalanceGBP,2);
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    $BalanceTZS = ($AmountTZS - $DiscountTZS) - ($PaymentTZS);
                                                    $totalBalanceTZS += $BalanceTZS;
                                                    echo number_format($BalanceTZS,2);
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>       
                                <tr>
                                    <?php 
                                        $colspan = 4;
                                        if($classesID == 0) {
                                            $colspan = 5;
                                        }

                                        if($sectionID == 0) {
                                            $colspan = 5;
                                        }

                                        if($classesID == 0 && $sectionID == 0) {
                                            $colspan = 6;
                                        }
                                    ?>
                                    <td class="grand-total" colspan="<?=$colspan?>"><?=$this->lang->line('balancefeesreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?> --></td>
                                    <td class="rtext-bold"><?=number_format($totalAmountUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalAmountGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalAmountTZS,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalDiscountUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalDiscountGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalDiscountTZS,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalPaymentsUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalPaymentsGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalPaymentsTZS,2)?></td>
                                    <!-- <td class="rtext-bold"><?=number_format($totalWeaver,2)?></td> -->
                                    <td class="rtext-bold"><?=number_format($totalBalanceUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalBalanceGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalBalanceTZS,2)?></td>
                                </tr>                             
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } else { ?>
                <div class="col-sm-12">
                    <div class="notfound">
                        <p><b class="text-info"><?=$this->lang->line('report_data_not_found')?></b></p>
                    </div>
                </div>
            <?php } ?>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div>

