<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
    <body>
        <?php if((int)$gspaymentID && (($gspayment == "INV-G-") || ($gspayment == "inv-g-"))) { ?>
            <div class="gbody">
                <?=reportheader($siteinfos, $schoolyearsessionobj,true)?>
                <?php if(count($globalpayments)) { ?>
                    <hr>
                    <table class="global-tableinfo">
                        <tr>
                            <td><b><?=$this->lang->line('searchpaymentfeesreport_invoice_number')?> : </b>INV-G-<?=count($globalpayments) ? $globalpayments[0]->globalpaymentID : ''?></td>
                            <td><b><?=$this->lang->line('searchpaymentfeesreport_clearance')?> : </b><?=count($globalpayments) ? $globalpayments[0]->clearancetype : '' ?></td>
                            <td><b><?=$this->lang->line('searchpaymentfeesreport_date')?> : </b> <?=count($globalpayments) ? date('d M Y', strtotime($globalpayments[0]->paymentdate)) : '' ?></td>
                        </tr>
                        <tr>
                            <td><b><?=$this->lang->line('searchpaymentfeesreport_name')?> : </b> <?=count($studentinfo) ? $studentinfo->srname : ''?></td>

                            <td><b><?=$this->lang->line('searchpaymentfeesreport_classes')?> : </b> <?=count($studentinfo) ? $studentinfo->srclasses  : ''?>, <b><?=$this->lang->line('searchpaymentfeesreport_roll')?> : </b> <?=count($studentinfo) ? $studentinfo->srroll : ''?>, <b><?=$this->lang->line('searchpaymentfeesreport_section')?> : </b><?=count($studentinfo) ? $studentinfo->srsection  : ''?></td>
                            <td><b><?=$this->lang->line('searchpaymentfeesreport_group')?> : </b><?=count($groups[$studentinfo->srstudentgroupID]) ? $groups[$studentinfo->srstudentgroupID] : '' ?></td>
                        </tr>
                        <tr>
                            <td colspan="3" ><center><b><span><?=$this->lang->line('searchpaymentfeesreport_student_copy')?></span></b></center></td>
                        </tr>
                    </table>

                    <div style="margin-top:0px" class="global-table">
                        <table>
                            <tbody>
                                <tr>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_fees_type')?></th>
                                    <th class="textright"><?=$this->lang->line('searchpaymentfeesreport_amount')?></th>
                                </tr>
                                <?php $paymentedPaidAmount= 0; 
                                if(count($globalpayments)) { foreach($globalpayments as $payment) { ?>
                                    <tr>
                                        <?php if (isset($payment->paymentamount)) { 
                                        $paymentedPaidAmount += $payment->paymentamount ?>
                                        <td>
                                            <?=isset($payment->feetype) ? $payment->feetype : ''?>
                                        </td>
                                        <td class="textright">
                                            <?=isset($payment->paymentamount) ? $payment->paymentamount : ''?>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php } } ?>
                                <tr>
                                    <td class="boldandred"><?=$this->lang->line('searchpaymentfeesreport_total')?></td>
                                    <td class="boldandred textright"><?=$paymentedPaidAmount?></td>
                                </tr>

                                <?php $paymentedFineAmount=0; $paymentedFineStatus=FALSE;
                                $j = TRUE;
                                if(count($globalpayments)) { foreach($globalpayments as $payment) {
                                    if(isset($weaverandfines[$payment->paymentID]) && $weaverandfines[$payment->paymentID]->fine) { ?>
                                    <?php if($j) { ?>
                                        <tr>
                                            <th><?=$this->lang->line('searchpaymentfeesreport_fine')?></th>
                                            <th></th>
                                        </tr>
                                    <?php } $j = FALSE; ?>
                                        <tr>
                                            <?php $paymentedFineStatus=TRUE; 
                                            $paymentedFineAmount += isset($weaverandfines[$payment->paymentID]) ? $weaverandfines[$payment->paymentID]->fine : 0; ?>

                                            <td><?=isset($weaverandfines[$payment->paymentID]->fine) ? $feetypes[$payment->feetypeID] : ''?></td>
                                            <td class="textright"><?=isset($weaverandfines[$payment->paymentID]) ? $weaverandfines[$payment->paymentID]->fine : 0?></td>
                                        </tr>
                                    <?php } 
                                    } if($paymentedFineStatus) { ?>
                                        <tr>
                                            <td class="boldandred"><?=$this->lang->line('searchpaymentfeesreport_fine_total')?></td>
                                            <td class="boldandred textright"><?=$paymentedFineAmount?></td>
                                        </tr>
                                <?php } } ?>

                                <tr>
                                    <td class="boldandred"><?=$this->lang->line('searchpaymentfeesreport_grand_total')?></td>
                                    <td class="boldandred textright"><?=$paymentedPaidAmount+$paymentedFineAmount?></td>
                                </tr>

                                <?php 
                                    $i=1; 
                                    $paymentedWeaverAmount = 0;
                                    $paymentedWeaverStatus = FALSE;
                                    $w = TRUE;
                                    if(count($globalpayments)) { foreach($globalpayments as $payment) { ?>
                                        <?php 
                                        if(isset($weaverandfines[$payment->paymentID]) && $weaverandfines[$payment->paymentID]->weaver) { 
                                            if($w) { ?>
                                        <tr>
                                            <th><?=$this->lang->line('searchpaymentfeesreport_weaver')?></th>
                                            <th></th>
                                        </tr>
                                        <?php } $w = FALSE;
                                            $paymentedWeaverStatus=TRUE;
                                            $paymentedWeaverAmount += isset($weaverandfines[$payment->paymentID]) ? $weaverandfines[$payment->paymentID]->weaver : 0; ?>

                                        <tr>
                                            <td><?=isset($weaverandfines[$payment->paymentID]->weaver) ? $feetypes[$payment->feetypeID] : ''?></td>
                                            <td class="textright"><?=isset($weaverandfines[$payment->paymentID]) ? $weaverandfines[$payment->paymentID]->weaver : 0?></td>
                                        </tr>
                                        <?php } ?>
                                    <?php $i++; } } ?>
                                <?php if ($paymentedWeaverStatus){ ?>
                                <tr>
                                    <td class="boldandred"><?=$this->lang->line('searchpaymentfeesreport_weaver_total')?></td>
                                    <td class="boldandred textright"><?=$paymentedWeaverAmount?></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div class="notfound">
                        <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
                    </div>
                <?php } ?>
                <div class="text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div>
        <?php } elseif ((int)$gspaymentID && (($gspayment == "INV-S-") || ($gspayment == "inv-s-"))) { ?>
            <?php if(count($singlepayments)) { ?>
                <div>
                    <table width="100%">
                        <tr>
                            <td widht="5%">
                                <h2>
                                    <?php
                                        if($siteinfos->photo) {
                                            $array = array(
                                                "src" => base_url('uploads/images/'.$siteinfos->photo),
                                                'width' => '25px',
                                                'height' => '25px',
                                                'style' => 'margin-top:-8px'
                                            );
                                            echo img($array);
                                        }
                                    ?>
                                </h2>
                            </td>
                            <td widht="75%">
                                <h3 class="top-site-header-title"><?php  echo $siteinfos->sname; ?></h3>
                            </td>
                            <td class="20%">
                                <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("searchpaymentfeesreport_create_date")." : ". date("d M Y"); ?></h5>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <table width="100%">
                        <tr>
                            <td width="33%">
                                <table>
                                    <tbody>
                                        <tr>
                                            <th class="site-header-title-float"><?php  echo $this->lang->line("searchpaymentfeesreport_from"); ?></th>
                                        </tr>
                                        <?php if(count($siteinfos)) { ?>
                                            <tr>
                                                <td><?=$siteinfos->sname?></td>
                                            </tr>
                                            <tr>
                                                <td><?=$siteinfos->address?></td>
                                            </tr>
                                            <tr>
                                                <td><?=$this->lang->line("searchpaymentfeesreport_phone"). " : ". $siteinfos->phone?></td>
                                            </tr>
                                            <tr>
                                                <td><?=$this->lang->line("searchpaymentfeesreport_email"). " : ". $siteinfos->email?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td width="33%">
                                <table >
                                    <tbody>
                                        <tr>
                                            <th class="site-header-title-float"><?php  echo $this->lang->line("searchpaymentfeesreport_to"); ?></th>
                                        </tr>
                                        <tr>
                                            <td><?php  echo $studentinfo->srname; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php  echo $this->lang->line("searchpaymentfeesreport_roll"). " : ". $studentinfo->srroll; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php  echo $this->lang->line("searchpaymentfeesreport_classes"). " : ". $studentinfo->srclasses; ?></td>
                                        </tr>
                                        <tr>
                                            <td><?php  echo $this->lang->line("searchpaymentfeesreport_registerNO"). " : ". $studentinfo->srregisterNO; ?></td>
                                        </tr>
                                        <?php if(count($student)) { ?>
                                            <tr>
                                              <td><?=$this->lang->line("searchpaymentfeesreport_email"). " : ". $student->email?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </td>
                            <td width="34%" style="vertical-align: text-top;">
                                <table>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <?=$this->lang->line("searchpaymentfeesreport_invoice_number")." : "."<b>INV-S-".$gspaymentID."</b>"?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <?=$this->lang->line('searchpaymentfeesreport_payment_method'). " : "."<span>".$paymenttype."</span>"?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                    <br>
                    <?php if(count($singlepayments)) { ?>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th ><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_feetype')?></th>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_amount')?></th>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_weaver')?></th>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_fine')?></th>
                                    <th><?=$this->lang->line('searchpaymentfeesreport_sub_total')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $paymentUserTypeID = 0; 
                                $paymentUserID = 0;
                                $paymentDate = date('Y-m-d');
                                $paymentamount = 0;
                                $weaver = 0;
                                $fine = 0;
                                $subtotal = 0;
                                $i=1; 
                                foreach($singlepayments as $singlepayment) { 
                                    if($singlepayment->paymentamount > 0 || $singlepayment->weaver > 0 || $singlepayment->fine > 0) { 
                                        $paymentDate = $singlepayment->paymentdate;
                                        $paymentUserTypeID = $singlepayment->usertypeID; 
                                        $paymentUserID = $singlepayment->userID; ?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=isset($feetypes[$singlepayment->feetypeID]) ? $feetypes[$singlepayment->feetypeID] : ''?></td>
                                        <td><?=number_format($singlepayment->paymentamount,2)?></td>
                                        <td><?=number_format($singlepayment->weaver,2)?></td>
                                        <td><?=number_format($singlepayment->fine,2)?></td>
                                        <td><?=number_format(($singlepayment->paymentamount+$singlepayment->fine),2)?></td>
                                        <?php 
                                            $paymentamount += $singlepayment->paymentamount;
                                            $weaver += $singlepayment->weaver;
                                            $fine += $singlepayment->fine;
                                            $subtotal += ($singlepayment->paymentamount+$singlepayment->fine);

                                        ?>
                                    </tr>
                                <?php $i++; } } ?>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><b><?=$this->lang->line('searchpaymentfeesreport_total')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
                                        <td><b><?=number_format($paymentamount,2)?></b></td>
                                        <td><b><?=number_format($weaver,2)?></b></td>
                                        <td><b><?=number_format($fine,2)?></b></td>
                                        <td><b><?=number_format($subtotal,2)?></b></td>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>
                        <table width="100%">
                            <tr>
                                <td width="65%" >
                                    <p></p>
                                </td>
                                <td width="35%">
                                    <table>
                                        <tr>
                                            <td><?=$this->lang->line('searchpaymentfeesreport_create_by')?> : <?=getNameByUsertypeIDAndUserID($paymentUserTypeID, $paymentUserID)?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('searchpaymentfeesreport_date')?> : <?=date('d M Y', strtotime($paymentDate))?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <?php } else { ?>
                            <br/>
                            <div class="notfound">
                                <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
                            </div>
                        <?php } ?>
                </div>
            <?php } else { ?>
                <div class="notfound">
                    <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="notfound">
                <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
            </div>
        <?php } ?>
    </body>
</html>