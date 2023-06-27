<div class="box">
    <!-- form start -->
    <div class="box-body" style="margin-bottom: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
            </div>
            <div class="box-header bg-gray">
                <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
                    <?=$this->lang->line('feesreport_report_for')?> - 
                    <?=$this->lang->line('feesreport_fees');?>
                </h3>
            </div><!-- /.box-header -->
            
            <?php if($classesID >= 0 && $sectionID >= 0) { ?>
                <div class="col-sm-12">
                    <h5 class="pull-left">
                        <?php 
                            echo $this->lang->line('feesreport_class')." : ";
                            echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('feesreport_all_class');
                        ?>
                    </h5>                         
                    <h5 class="pull-right">
                        <?php
                           echo $this->lang->line('feesreport_section')." : ";
                           echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('feesreport_all_section');
                        ?>
                    </h5>
                </div>
            <?php } ?>
            <div class="col-sm-12">
                <?php if(count($getFeesReports)) { ?>
                <div class="table-responsive">
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <tr>
                                <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                <th rowspan="2"><?=$this->lang->line('feesreport_payment_date')?></th>
                                <th rowspan="2"><?=$this->lang->line('feesreport_name')?></th>
                                <th rowspan="2"><?=$this->lang->line('feesreport_registerNO')?></th>
                                <?php if(!($classesID > 0)) { ?>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_class')?></th>
                                <?php } ?>

                                <?php if(!($sectionID > 0)) { ?> 
                                    <th rowspan="2"><?=$this->lang->line('feesreport_section')?></th>
                                <?php } ?>
                                <th rowspan="2"><?=$this->lang->line('feesreport_roll')?></th>
                                <th rowspan="2"><?=$this->lang->line('feesreport_feetype')?></th>
                                <th colspan="3"><?=$this->lang->line('feesreport_paid')?></th>
                                <!-- <th><?=$this->lang->line('feesreport_weaver')?></th> -->
                                <th colspan="3"><?=$this->lang->line('feesreport_fine')?></th>
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
                                $totalPaidUSD = 0;
                                $totalPaidGBP = 0;
                                $totalPaidTZS = 0;
                                $totalWeaver = 0;
                                $totalFineTZS = 0;
                                $totalFineUSD = 0;
                                $totalFineGBP = 0;
                                $i = 0;
                                foreach($getFeesReports as $getFeesReport) {
                                    if(isset($weaverandfine[$getFeesReport->paymentID]) && (($weaverandfine[$getFeesReport->paymentID]->fine !='') || ($weaverandfine[$getFeesReport->paymentID]->weaver !='')) || $getFeesReport->paymentamount != '') { $i++;?>
                                    <tr>
                                        <td><?=$i?></td>

                                        <td><?=date('d M Y',strtotime($getFeesReport->paymentdate))?></td>
                                        
                                        <td><?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srname : '' ?></td>
                                        
                                        <td>
                                            <?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srregisterNO : '' ?>
                                        </td>

                                        <?php if(!($classesID > 0)) {  ?>
                                            <td> 
                                                <?php
                                                    if(isset($students[$getFeesReport->studentID])) {
                                                        $stclassID = $students[$getFeesReport->studentID]->srclassesID;
                                                        echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                    } 
                                                ?>
                                            </td>
                                        <?php } ?>
                                        

                                        <?php if(!($sectionID > 0)) { ?>
                                            <td>
                                                <?php
                                                    if(isset($students[$getFeesReport->studentID])) {
                                                        $stsectionID = $students[$getFeesReport->studentID]->srsectionID;
                                                        echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                    }
                                                ?>
                                            </td>
                                        <?php } ?>


                                        <td><?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srroll : '' ?></td>
                                        <td>
                                            <?php 
                                                if(isset($invoices[$getFeesReport->invoiceID])) {
                                                    $feetypeID = $invoices[$getFeesReport->invoiceID];
                                                    if(isset($feetypes[$feetypeID])) {
                                                        echo $feetypes[$feetypeID];
                                                    }
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($getFeesReport->currency == "USD") {
                                                    echo number_format($getFeesReport->paymentamount,2);
                                                    $totalPaidUSD += $getFeesReport->paymentamount;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if ($getFeesReport->currency == "GBP") {
                                                    echo number_format($getFeesReport->paymentamount,2);
                                                    $totalPaidGBP += $getFeesReport->paymentamount;
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($getFeesReport->currency == "TZS") {
                                                    echo number_format($getFeesReport->paymentamount,2);
                                                    $totalPaidTZS += $getFeesReport->paymentamount;
                                                } 
                                            ?>
                                        </td>
                                        <!-- <td>
                                            <?php 
                                                if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                    echo number_format($weaverandfine[$getFeesReport->paymentID]->weaver,2);
                                                    $totalWeaver += $weaverandfine[$getFeesReport->paymentID]->weaver; 
                                                } else {
                                                    echo number_format(0,2);
                                                }
                                            ?>
                                        </td> -->
                                        <td>
                                            <?php 
                                                if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                    if ($weaverandfine[$getFeesReport->paymentID]->currency == "GBP") {
                                                        echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        $totalFineGBP += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                } else {
                                                    echo number_format(0,2);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                    if ($weaverandfine[$getFeesReport->paymentID]->currency == "GBP") {
                                                        echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        $totalFineGBP += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                } else {
                                                    echo number_format(0,2);
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                    if ($weaverandfine[$getFeesReport->paymentID]->currency == "TZS") {
                                                        echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        $totalFineTZS += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                } else {
                                                    echo number_format(0,2);
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } } ?>
                                <tr>
                                    <?php 
                                        $colspan = 6;
                                        if($classesID == 0) {
                                            $colspan = 7;
                                        }

                                        if($sectionID == 0) {
                                            $colspan = 7;
                                        }

                                        if($classesID == 0 && $sectionID == 0) {
                                            $colspan = 8;
                                        }
                                    ?>
                                    <td class="grand-total" colspan="<?=$colspan?>"><?=$this->lang->line('feesreport_grand_total')?> <!--<?=isset($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?> --></td>
                                    <td class="rtext-bold"><?=number_format($totalPaidUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalPaidGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalPaidTZS,2)?></td>
                                    <!-- <td class="rtext-bold"><?=number_format($totalWeaver,2)?></td> -->
                                    <td class="rtext-bold"><?=number_format($totalFineUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalFineGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalFineTZS,2)?></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
                <?php } else { ?>
                    <div class="notfound">
                        <?php echo $this->lang->line('feesreport_data_not_found'); ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div>

