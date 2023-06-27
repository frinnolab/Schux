<div class="box">
    <!-- form start -->
    <div class="box-body" style="margin-bottom: 50px;">
        <div class="row">
            <div class="col-sm-12">
                <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
            </div>
            <div class="box-header bg-gray">
                <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
                    <?=$this->lang->line('duefeesreport_report_for')?> - 
                    <?=$this->lang->line('duefeesreport_duefees');?>
                </h3>
            </div><!-- /.box-header -->
            <?php if($classesID >= 0 || $sectionID >= 0 ) { ?>
            <div class="col-sm-12">
                <h5 class="pull-left">
                    <?php 
                        echo $this->lang->line('duefeesreport_class')." : ";
                        echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('duefeesreport_all_class');
                    ?>
                </h5>                         
                <h5 class="pull-right">
                    <?php
                       echo $this->lang->line('duefeesreport_section')." : ";
                       echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('duefeesreport_all_section');
                    ?>
                </h5>                        
            </div>
           <?php } ?>
                <div class="col-sm-12">
                <?php if(count($getDueFeesReports)) { ?>
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_invoice_date')?></th>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_name')?></th>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_registerNO')?></th>
                                    <?php if($classesID == 0) { ?>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_class')?></th>
                                    <?php } ?>
                                    <?php if($sectionID == 0) { ?>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_section')?></th>
                                    <?php } ?>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_roll')?></th>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_feetype')?></th>
                                    <th rowspan="2"><?=$this->lang->line('duefeesreport_discount')?> (%) </th>
                                    <th colspan="3"><?=$this->lang->line('duefeesreport_due') ?></th>
                                </tr>
                                <tr>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $totalDueUSD = 0;
                                    $totalDueGBP = 0;
                                    $totalDueTZS = 0; 
                                    $i = 0; 
                                    foreach($getDueFeesReports as $getDueFeesReport) { if($sectionID > 0) { if(isset($students[$getDueFeesReport->studentID]) && $students[$getDueFeesReport->studentID]->srsectionID == $sectionID) { $i++;?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=date('d M Y',strtotime($getDueFeesReport->create_date))?></td>
                                            <td><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '' ?></td>
                                            <td>
                                                <?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '' ?>
                                            </td>
                                            <?php 
                                                if($classesID == 0) { 
                                                    echo " <td>";
                                                    if(isset($students[$getDueFeesReport->studentID])) {
                                                        $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
                                                        echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                    } 
                                                    echo "</td>";
                                                } 
                                            ?>

                                            <?php 
                                                if($sectionID == 0) { 
                                                    echo " <td>";
                                                    if(isset($students[$getDueFeesReport->studentID])) {
                                                        $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
                                                        echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                    } 
                                                    echo "</td>";
                                                } 
                                            ?>

                                            <td><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '' ?></td>
                                            <td>
                                                <?php 
                                                    if(isset($feetypes[$getDueFeesReport->feetypeID])) {
                                                        echo $feetypes[$getDueFeesReport->feetypeID];
                                                    }
                                                ?>
                                            </td>
                                            <td><?=number_format($getDueFeesReport->discount, 2);?></td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "USD") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueUSD += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueUSD += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "GBP") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueGBP += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueGBP += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "TZS") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueTZS += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueTZS += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                        </tr>
                                    <?php } } else { $i++;?>
                                        <tr>
                                            <td><?=$i?></td>
                                            <td><?=date('d M Y',strtotime($getDueFeesReport->create_date))?></td>
                                            <td><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '' ?></td>
                                            <td>
                                                <?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '' ?>
                                            </td>
                                            <?php 
                                                if($classesID == 0) { 
                                                    echo " <td>";
                                                    if(isset($students[$getDueFeesReport->studentID])) {
                                                        $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
                                                        echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                    } 
                                                    echo "</td>";
                                                } 
                                            ?>

                                            <?php 
                                                if($sectionID == 0) { 
                                                    echo " <td>";
                                                    if(isset($students[$getDueFeesReport->studentID])) {
                                                        $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
                                                        echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                    } 
                                                    echo "</td>";
                                                } 
                                            ?>
                                            <td><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '' ?></td>
                                            <td>
                                                <?php 
                                                    if(isset($feetypes[$getDueFeesReport->feetypeID])) {
                                                        echo $feetypes[$getDueFeesReport->feetypeID];
                                                    }
                                                ?>
                                            </td>
                                            <td><?=number_format($getDueFeesReport->discount, 2);?></td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "USD") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueUSD += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueUSD += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "GBP") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueGBP += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueGBP += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                            <td>
                                                <?php
                                                    if ($getDueFeesReport->currency == "TZS") {
                                                        $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                        if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                            $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueTZS += $due;
                                                        } else {
                                                            $due = ($getDueFeesReport->amount - $discount);
                                                            echo number_format($due,2);
                                                            $totalDueTZS += $due;
                                                        }
                                                    }
                                                ?>    
                                            </td>
                                        </tr>
                                <?php  } } ?>
                                
                                <tr>
                                    <?php 
                                        $colspan = 7;
                                        if($classesID == 0) {
                                            $colspan = 8;
                                        }

                                        if($sectionID == 0) {
                                            $colspan = 8;
                                        }

                                        if($classesID == 0 && $sectionID == 0) {
                                            $colspan = 9;
                                        }
                                    ?>

                                    <td class="grand-total" colspan="<?=$colspan?>">
                                        <?=$this->lang->line('duefeesreport_grand_total')?><!-- (<?=!empty($siteinfos->currency_code) ? $siteinfos->currency_code : '0'?>) -->
                                    </td>
                                    <td class="rtext-bold"><?=number_format($totalDueUSD,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalDueGBP,2)?></td>
                                    <td class="rtext-bold"><?=number_format($totalDueTZS,2)?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="notfound">
                        <?php echo $this->lang->line('duefeesreport_data_not_found'); ?>
                    </div>
                <?php } ?>
                </div>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div>

