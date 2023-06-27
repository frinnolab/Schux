<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-balance-scale"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_global_payment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="col-sm-12">

                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    
                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="classesID" class="control-label">
                                                <?=$this->lang->line('global_classes')?> <span class="text-red">*</span>
                                            </label>
                                            <?php
                                                $classArray = array("0" => $this->lang->line("global_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $classArray[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $classArray, set_value("classesID", $set_classesID), "id='classesID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="sectionID" class="control-label"><?=$this->lang->line('global_section')?></label>
                                            <?php
                                                $sectionArray = array('0' => $this->lang->line("global_select_section"));
                                                if($sections != 0) {
                                                    foreach ($sections as $section) {
                                                        $sectionArray[$section->sectionID] = $section->section;
                                                    }
                                                }

                                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $set_sectionID), "id='sectionID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('studentID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="studentID" class="control-label">
                                                <?=$this->lang->line('global_student')?> <span class="text-red">*</span>
                                            </label>

                                            <?php
                                                $studentArray = array('0' => $this->lang->line("global_select_student"));
                                                if(count($students)) {
                                                    foreach ($students as $student) {
                                                        $studentArray[$student->srstudentID] = $student->srname.' - '.$this->lang->line('global_roll').' - '.$student->srroll;
                                                    }
                                                }

                                                echo form_dropdown("studentID", $studentArray, set_value("studentID", $set_studentID), "id='studentID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2 col-xs-12">
                                <div class="row">
                                    <div class="col-md-12 col-xs-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12 col-xs-12 global_payment_search"><?=$this->lang->line('global_payment_search')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="col-sm-12" >
                    <?php if(count($single_student)) { ?>
                        <div class="col-sm-12 feesPaymentInfo">
                            <div class="col-sm-3 info">
                                <img class="img" src="<?=base_url('uploads/images/'.$single_student->photo)?>">
                                <table class="table table-striped table-bordered">
                                    <tbody>
                                        <tr>
                                            <td><?=$this->lang->line('global_register_no')?></td><td><?=$single_student->srregisterNO?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('global_name')?></td><td><?=$single_student->srname?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('global_classes')?></td>
                                            <td>
                                                <?php if(count($single_classes)) { echo $single_classes->classes; } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('global_roll')?></td><td><?=$single_student->srroll?></td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('global_section')?></td>
                                            <td>
                                                <?php if(count($single_section)) { echo $single_section->section; } ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><?=$this->lang->line('global_group')?></td>
                                            <td>
                                                <?php if(count($single_group)) { echo $single_group->group; } ?>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="vl"></div>

                            <div class="col-sm-9 invoice-table-responsive">
                                <div class="table-responsive">
                                    <table id="" class="table table-striped table-bordered table-hover dataTable no-footer">
                                        <thead>
                                            <tr>
                                                <th><?=$this->lang->line('global_invoice_number')?></th>
                                                <th><?=$this->lang->line('global_total_pay')?></th>
                                                <th><?=$this->lang->line('global_weaver')?></th>
                                                <th><?=$this->lang->line('global_fine')?></th>
                                                <th><?=$this->lang->line('global_total_collection')?></th>
                                                <th><?=$this->lang->line('global_clearance')?></th>
                                                <th><?=$this->lang->line('global_payment_date')?></th>
                                                <th><?=$this->lang->line('action')?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $invoice_total = 0; $invoice_weaver = 0; $invoice_fine = 0; $invoice_paid_fine = 0; if(count($globalpayments)) { foreach ($globalpayments as $globalpayment) { ?>

                                                <?php
                                                    $tpaid = 0;
                                                    $tfine = 0;
                                                    if(isset($paidpayments['paid'][$globalpayment->globalpaymentID])) {
                                                        if($paidpayments['paid'][$globalpayment->globalpaymentID] > 0) {
                                                            $invoice_total += $paidpayments['paid'][$globalpayment->globalpaymentID];
                                                            $tpaid = $paidpayments['paid'][$globalpayment->globalpaymentID];
                                                        }    
                                                    }

                                                    if(isset($paidpayments['weaver'][$globalpayment->globalpaymentID])) {
                                                        if($paidpayments['weaver'][$globalpayment->globalpaymentID] > 0) {
                                                            $invoice_weaver += $paidpayments['weaver'][$globalpayment->globalpaymentID];
                                                        }    
                                                    }

                                                    if(isset($paidpayments['fine'][$globalpayment->globalpaymentID])) {
                                                        if($paidpayments['fine'][$globalpayment->globalpaymentID] > 0) {
                                                            $invoice_fine += $paidpayments['fine'][$globalpayment->globalpaymentID];
                                                            $tfine = $paidpayments['fine'][$globalpayment->globalpaymentID];
                                                        }    
                                                    }

                                                    $tpaidandfine = ($tpaid+$tfine);
                                                    $invoice_paid_fine += $tpaidandfine;
                                                ?>
                                                <tr>
                                                    <td><?='INV-G-'.$globalpayment->globalpaymentID?></td>
                                                    <td><?=isset($paidpayments['paid'][$globalpayment->globalpaymentID]) ? $paidpayments['paid'][$globalpayment->globalpaymentID] : 0 ?></td>
                                                    <td><?=isset($paidpayments['weaver'][$globalpayment->globalpaymentID]) ? $paidpayments['weaver'][$globalpayment->globalpaymentID] : 0 ?></td>
                                                    <td><?=isset($paidpayments['fine'][$globalpayment->globalpaymentID]) ? $paidpayments['fine'][$globalpayment->globalpaymentID] : 0 ?></td>
                                                    <td><?=$tpaidandfine?></td>
                                                    <td><?=ucfirst($globalpayment->clearancetype)?></td>
                                                    <td><?=isset($paidpayments['paiddate'][$globalpayment->globalpaymentID]) ? date('d-M-Y', strtotime($paidpayments['paiddate'][$globalpayment->globalpaymentID])) : '' ?></td>
                                                    <td>
                                                        <span data-toggle="modal" data-target="#invoice-view-<?=$globalpayment->globalpaymentID?>">
                                                            <button class="btn btn-success btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('view')?>">
                                                                <i class='fa fa-check-square-o'></i>
                                                            </button>
                                                        </span>
                                                    </td>
                                                </tr>

                                            <?php } } ?>
                                            <tr>
                                                <td><b><?=$this->lang->line('global_total')?></b></td>
                                                <td><b><?=$invoice_total?></b></td>
                                                <td><b><?=$invoice_weaver?></b></td>
                                                <td><b><?=$invoice_fine?></b></td>
                                                <td><b><?=$invoice_paid_fine?></b></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                            <table class="table table-striped table-bordered" style="margin-top: 10px">
                                <thead>
                                    <tr>
                                        <td style="border-bottom-width: 1px;"><?=$this->lang->line('global_invoice_name')?></td>
                                        <td style="border-bottom-width: 1px;"><input class="form-control" id="invoicename" type="text" name="invoicename" value="<?=$single_student->srregisterNO.'-'.$single_student->srname?>"></td>

                                        <td style="border-bottom-width: 1px;"><?=$this->lang->line('global_description')?></td>
                                        <td style="border-bottom-width: 1px;"><input class="form-control" id="invoicedescription" type="text" name="invoicedescription"></td>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td><?=$this->lang->line('global_invoice_number')?></td>
                                        <td><input class="form-control" id="invoicenumber" type="text" name="invoicenumber" value="INV-G-<?=(count($globalpayment_max) > 0) ? $globalpayment_max->globalpaymentID+1 : '1'?>" readonly></td>
                                        <td><?=$this->lang->line('global_payment_year')?></td>
                                        <td><input class="form-control" id="paymentyear" type="text" name="paymentyear" value="<?=date('Y')?>"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <?php if(count($invoices)) { ?>

                            <div class="col-sm-12" style="padding-left: 0px; padding-right: 0px;">
                                <div class="table-responsive" style="margin-top:10px !important">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <td>#</td>
                                                <td><?=$this->lang->line('global_fees_name')?></td>
                                                <td><?=$this->lang->line('global_fees_amount')?></td>
                                                <td><?=$this->lang->line('global_due')?></td>
                                                <td><?=$this->lang->line('global_paid_amount')?></td>
                                                <td><?=$this->lang->line('global_weaver')?></td>
                                                <td><?=$this->lang->line('global_fine')?></td>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php 
                                                $total = 0;
                                                $totalDue = 0;
                                                if(count($invoices)) { 
                                                    $i=1; 
                                                    foreach ($invoices as $invoice) {
                                                        $total += number_format($invoice->amount, 2, '.', '');

                                                        if($invoice->discount > 0) {
                                                            $total = number_format(($total - (($invoice->amount/100)*$invoice->discount)), 2, '.', '');
                                                        }

                                                        $payment = 0;
                                                        if(isset($payments[$invoice->invoiceID])) {
                                                            $payment = number_format($payments[$invoice->invoiceID], 2, '.', '');
                                                        }

                                                        $due = number_format(($invoice->amount - $payment), 2, '.', '');

                                                        if($invoice->discount > 0) {
                                                            $due = number_format(($due - (($invoice->amount/100)*$invoice->discount)), 2, '.', '');
                                                        }

                                                        if(isset($weavers[$invoice->invoiceID])) {
                                                            $due -= number_format($weavers[$invoice->invoiceID], 2, '.', '');
                                                        }

                                                        $totalDue += $due;

                                            ?>
                                                
                                                        <tr>
                                                            <td style="width:10%"><?=$i?></td>
                                                            <td style="width:10%"><?php if(isset($feetypes[$invoice->feetypeID])) { echo $feetypes[$invoice->feetypeID]; } ?></td>
                                                            <td style="width:10%"><?php if($invoice->discount > 0) { echo ($invoice->amount - (($invoice->amount/100)*$invoice->discount)); } else { echo $invoice->amount;  }  ?></td>
                                                            <td id="_due_<?=$i-1?>" style="width:10%"><?=$due?></td>
                                                            <td style="width:10%">
                                                                <?php
                                                                    if($due == 0) {
                                                                        echo 'Paid';
                                                                        echo '<input style="display:none" name="paid-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control paid_amount _paid_'.($i -1).'" type="text">';
                                                                    } elseif($due < 0) {
                                                                        echo  'Over Due';
                                                                        echo '<input style="display:none" name="paid-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control paid_amount _paid_'.($i -1).'" type="text">';
                                                                    } else {
                                                                        echo '<input name="paid-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control paid_amount _paid_'.($i -1).'" type="text">';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td style="width:10%">
                                                                <?php
                                                                    if($due == 0 || $due < 0) {
                                                                        echo '';
                                                                        echo '<input style="display:none" name="weaver-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control weaver _weaver_'.($i -1).'" type="text">';
                                                                    } else {
                                                                        echo '<input name="weaver-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control weaver _weaver_'.($i -1).'" type="text">';
                                                                    }
                                                                ?>
                                                            </td>
                                                            <td style="width:10%">
                                                                <?php
                                                                    if($due == 0 || $due < 0) {
                                                                        echo '';
                                                                        echo '<input style="display:none" name="fine-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control fine" type="text">';
                                                                    } else {
                                                                        echo '<input name="fine-'.$invoice->invoiceID.'-'.$invoice->feetypeID.'" class="form-control fine" type="text">';
                                                                    }
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php $i++; } ?>
                                                <tr>
                                                    <td></td>
                                                    <td><b><?=$this->lang->line('global_total')?></b></td>
                                                    <td><?=$total?></td>
                                                    <td><?=$totalDue?></td>
                                                    <td id="set_paid_amount">0</td>
                                                    <td id="set_weaver">0</td>
                                                    <td id="set_fine">0</td>
                                                </tr>

                                                <tr>
                                                    <td colspan="2" style="width:10%"></td>
                                                    <td colspan="2" style="width:10%"><?=$this->lang->line('global_total_collection').' ('.$this->lang->line('global_paid').'+'.$this->lang->line('global_fine').')'?></td>
                                                    <td colspan="3" style="width:10%" id="TottalCollection">0</td>
                                                </tr>

                                                <tr>
                                                    <td></td>
                                                    <td><?=$this->lang->line('global_payment_status')?></td>
                                                    <td>
                                                        <select class="form-control" id="payment_status">
                                                            <option value="paid"><?=$this->lang->line('global_paid')?></option>
                                                            <option value="partial"><?=$this->lang->line('global_partial')?></option>
                                                            <option value="unpaid"><?=$this->lang->line('global_unpaid')?></option>
                                                        </select>
                                                    </td>

                                                    <td><?=$this->lang->line('global_payment_type')?></td>
                                                    <td>
                                                        <select class="form-control" id="payment_type">
                                                            <option value="cash"><?=$this->lang->line('global_cash')?></option>
                                                            <option value="chaque"><?=$this->lang->line('global_chaque')?></option>
                                                        </select>
                                                    </td>
                                                    <td colspan="2"></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <button id="add_payment" type="submit" class="btn btn-success col-md-2" style="margin-top: 20px;"><?=$this->lang->line('global_submit')?></button>
                        <?php } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php $paymented_status = TRUE; $paymented_invoice_total = 0; $paymented_invoice_weaver = 0; $paymented_invoice_fine = 0; if(count($globalpayments)) { foreach ($globalpayments as $globalpayment) { ?>
    <div class="modal fade" id="invoice-view-<?=$globalpayment->globalpaymentID?>">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <?=$this->lang->line('global_view_invoice')?>
                </div>
                <div id="printDiv-<?=$globalpayment->globalpaymentID?>">
                    <style type="text/css">
                        .assign-fee-payment .table {
                            width: 100%;
                            margin-bottom: 20px;
                        }
                        .assign-fee-payment table {
                            font-size: 12px;
                            max-width: 100%;
                            background-color: transparent;
                        }
                        .assign-fee-payment table {
                            border-collapse: collapse;
                            border-spacing: 0;
                        }

                        .assign-fee-payment .table > thead > tr > th {
                            vertical-align: bottom;
                            border-bottom: 2px solid #ddd;
                        }

                        .assign-fee-payment th {
                            text-align: left;
                        }

                        .assign-fee-payment {
                          font-family: sans-serif;
                          background-color: #fff !important; 
                          border: 1px solid #ccc;
                          color: #000;
                          padding: 10px;
                        }

                        .assign-fee-payment .logo {
                          height: 50px;
                          width: 50px;
                          text-align: center;
                          margin-left: auto;
                          margin-right: auto;
                          margin-bottom: 0px;
                          margin-top: 0px;
                        }

                        .assign-fee-payment .header .logo img {
                            height: 50px;
                            width: 50px;
                        }

                        .assign-fee-payment .title {
                          font-size: 16px;
                          text-align: center;
                          margin-left: auto;
                          margin-right: auto;
                          margin-bottom: 0px;
                          margin-top: 0px;
                        }

                        .assign-fee-payment .title-desc {
                          font-size: 14px;
                          text-align: center;
                          margin-left: auto;
                          margin-right: auto;
                          margin-bottom: 0px;
                          margin-top: 0px;
                        }

                        .assign-fee-payment .info td {
                          border-top: none !important;
                          font-size: 12px;
                          color: #000;
                         }

                        .assign-fee-payment th, .assign-fee-payment td {
                          padding: 1px !important;
                        }

                        .assign-fee-payment th {
                          background-color: #ccc !important;
                          padding: 1px !important;
                        }

                        .assign-fee-payment .textright {
                          text-align: right;
                        }

                        .assign-fee-payment .boldandred {
                          font-weight: bold;
                          color: red !important;
                        }

                        .assign-fee-payment .footer .logo {
                          width: 20px;
                          height: 20px;
                          text-align: center;
                          margin-left: auto;
                          margin-right: auto;
                          margin-bottom: 0px;
                          margin-top: 0px;
                        }

                        .assign-fee-payment .footer .logo img {
                            height: 20px;
                            width: 20px;
                        }

                        .assign-fee-payment .copyright {
                          text-align: center;
                          margin-left: auto;
                          margin-right: auto;
                          margin-bottom: 0px;
                          margin-top: 0px;
                          font-size: 12px;
                        }

                        .assign-fee-payment hr {
                          margin-top: 5px;
                          margin-bottom: 5px;
                          border-top: 1px solid #eee;
                        }
                    </style>
                    <div class="modal-body" >
                        <div class="assign-fee-payment">
                            <span class="header"><p class="logo"><img src="<?=base_url("uploads/images/$siteinfos->photo")?>"></p></span>
                            <p class="title"><?=$siteinfos->sname?></p>
                            <p class="title-desc"><?=$siteinfos->address?></p>
                            <hr>

                            <table class="table info">
                                <tr>
                                    <td><b><?=$this->lang->line('global_invoice_number')?>: </b>INV-G-<?=$globalpayment->globalpaymentID?></td>
                                    <td><b><?=$this->lang->line('global_clearance')?>: </b><?=strtoupper($globalpayment->clearancetype)?></td>
                                    <td><b><?=$this->lang->line('global_date')?>: </b><?=isset($paidpayments['paiddate'][$globalpayment->globalpaymentID]) ? date('d-M-Y', strtotime($paidpayments['paiddate'][$globalpayment->globalpaymentID])) : '' ?></td>
                                </tr>
                                <tr>
                                    <td><b><?=$this->lang->line('global_name')?>: </b><?=count($single_student) ? $single_student->srname : ''?> </td>
                                    <td><b><?=$this->lang->line('global_classes')?>: </b><?=count($single_classes) ? $single_classes->classes : ''?>, <b><?=$this->lang->line('global_roll')?>: </b><?=count($single_student) ? $single_student->srroll : ''?>, <b><?=$this->lang->line('global_section')?>: </b><?=count($single_section) ? $single_section->section : ''?></td>
                                    <td><b><?=$this->lang->line('global_group')?>: </b><?=count($single_group) ? $single_group->group : ''?></td>
                                </tr>

                                <tr>
                                    <td colspan="3" ><center><b><span><?=$this->lang->line('global_student_copy')?></span></b></center></td>
                                </tr>
                            </table>


                            <table class="table">
                                <thead>
                                    <?php if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if($paymented->paymentamount > 0) { ?>

                                                <tr>
                                                    <th><?=$this->lang->line('global_fees_name')?></th>
                                                    <th class="textright"><?=$this->lang->line('global_amount')?></th>
                                                </tr>
                                            <?php break; } ?>
                                        <?php } ?>
                                    <?php } } ?>
                                </thead>
                                <tbody>
                                    <?php $paymentedPaidAmount = 0; $paymentedPaidStatus = FALSE; if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if($paymented->paymentamount > 0) { $paymentedPaidStatus = TRUE; ?>
                                                <?php $paymentedPaidAmount += $paymented->paymentamount; ?>
                                                <tr>
                                                    <td><?=isset($feetypes[$invoicefeetype[$paymented->invoiceID]]) ? $feetypes[$invoicefeetype[$paymented->invoiceID]] : ''?></td>
                                                    <td class="textright"><?=$paymented->paymentamount?></td>
                                                </tr>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } } ?>

                                    <?php if($paymentedPaidStatus) { ?>
                                        <tr>
                                            <td class="boldandred"><?=$this->lang->line('global_total')?></td>
                                            <td class="boldandred textright"><?=$paymentedPaidAmount?></td>
                                        </tr>
                                    <?php } ?>


                                    <?php if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->fine > 0 ) { ?>
                                                <tr>
                                                    <th colspan="2"><?=$this->lang->line('global_fine')?></th>
                                                </tr>
                                            <?php break; } ?>
                                        <?php } ?>
                                    <?php } } } ?>

                                    <?php $paymentedFineAmount = 0; $paymentedFineStatus = FALSE; if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->fine > 0 ) { $paymentedFineStatus = TRUE; ?>
                                                <?php $paymentedFineAmount += isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->fine : 0; ?>
                                                
                                                <tr>
                                                    <td><?=isset($feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]]) ? $feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]] : ''?></td>
                                                    <td class="textright"><?=isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->fine : 0 ?></td>
                                                </tr>
                                            <?php } ?> 
                                        <?php } ?>
                                    <?php } } } ?>

                                    <?php if($paymentedFineStatus) { ?>
                                        <tr>
                                            <td><?=$this->lang->line('global_total_fine')?></td>
                                            <td class="textright"><?=$paymentedFineAmount?></td>
                                        </tr>
                                        <tr>
                                            <td class="boldandred"><?=$this->lang->line('global_grand_total')?></td>
                                            <td class="boldandred textright"><?=$paymentedPaidAmount+$paymentedFineAmount?></td>
                                        </tr>
                                    <?php } ?>

                                    <?php if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->weaver > 0 ) { ?>
                                                <tr>
                                                    <th colspan="2"><?=$this->lang->line('global_weaver')?></th>
                                                </tr>
                                            <?php break; } ?>
                                        <?php } ?>
                                    <?php } } } ?>

                                    <?php $paymentedWeaverAmount = 0; $paymentedWeaverStatus = FALSE; if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                        <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                            <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->weaver > 0 ) { $paymentedWeaverStatus = TRUE; ?>
                                                <?php $paymentedWeaverAmount += isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->weaver : 0; ?>
                                                
                                                <tr>
                                                    <td><?=isset($feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]]) ? $feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]] : ''?></td>
                                                    <td class="textright"><?=isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->weaver : 0 ?></td>
                                                </tr>
                                            <?php } ?> 
                                        <?php } ?>
                                    <?php } } } ?>

                                    <?php if($paymentedWeaverStatus) { ?>
                                        <tr>
                                            <td class="boldandred"><?=$this->lang->line('global_total_weaver')?></td>
                                            <td class="boldandred textright"><?=$paymentedWeaverAmount?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                            <hr>
                            <div class="footer">
                                <p class="logo"><img src="<?=base_url("uploads/images/$siteinfos->photo")?>"></p>
                                <p class="copyright"><?=$siteinfos->footer?> | <?=$this->lang->line('global_hotline')?> : <?=$siteinfos->phone?></p>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" onclick="javascript:printDiv('printDiv-<?=$globalpayment->globalpaymentID?>')"><?=$this->lang->line('global_print')?></button>
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                </div>

            </div>
        </div>
    </div>
<?php } } ?>

<?php $paymentGenerateStatus = $this->session->flashdata('paymentGenerateStatus'); if($paymentGenerateStatus) { ?>
    <?php $paymented_status = TRUE; $paymented_invoice_total = 0; $paymented_invoice_weaver = 0; $paymented_invoice_fine = 0; if(count($globalpayments)) { foreach ($globalpayments as $globalpayment) { ?>
        <?php if($globalpayment->globalpaymentID == $this->session->flashdata('paymentGenerateGlobalLastID')) { ?>
            <div class="modal fade in flashModel" style="display: block" aria-hidden="false" id="invoice-submit-view-<?=$globalpayment->globalpaymentID?>">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close flashClose" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <?=$this->lang->line('global_view_invoice')?>
                        </div>
                        <div id="printDiv-submit-<?=$globalpayment->globalpaymentID?>">
                            <style type="text/css">
                                .assign-fee-payment .table {
                                    width: 100%;
                                    margin-bottom: 20px;
                                }
                                .assign-fee-payment table {
                                    font-size: 12px;
                                    max-width: 100%;
                                    background-color: transparent;
                                }
                                .assign-fee-payment table {
                                    border-collapse: collapse;
                                    border-spacing: 0;
                                }

                                .assign-fee-payment .table > thead > tr > th {
                                    vertical-align: bottom;
                                    border-bottom: 2px solid #ddd;
                                }

                                .assign-fee-payment th {
                                    text-align: left;
                                }

                                .assign-fee-payment {
                                  font-family: sans-serif;
                                  background-color: #fff !important; 
                                  border: 1px solid #ccc;
                                  color: #000;
                                  padding: 10px;
                                }

                                .assign-fee-payment .logo {
                                  height: 50px;
                                  width: 50px;
                                  text-align: center;
                                  margin-left: auto;
                                  margin-right: auto;
                                  margin-bottom: 0px;
                                  margin-top: 0px;
                                }

                                .assign-fee-payment .header .logo img {
                                    height: 50px;
                                    width: 50px;
                                }

                                .assign-fee-payment .title {
                                  font-size: 16px;
                                  text-align: center;
                                  margin-left: auto;
                                  margin-right: auto;
                                  margin-bottom: 0px;
                                  margin-top: 0px;
                                }

                                .assign-fee-payment .title-desc {
                                  font-size: 14px;
                                  text-align: center;
                                  margin-left: auto;
                                  margin-right: auto;
                                  margin-bottom: 0px;
                                  margin-top: 0px;
                                }

                                .assign-fee-payment .info td {
                                  border-top: none !important;
                                  font-size: 12px;
                                  color: #000;
                                 }

                                .assign-fee-payment th, .assign-fee-payment td {
                                  padding: 1px !important;
                                }

                                .assign-fee-payment th {
                                  background-color: #ccc !important;
                                  padding: 1px !important;
                                }

                                .assign-fee-payment .textright {
                                  text-align: right;
                                }

                                .assign-fee-payment .boldandred {
                                  font-weight: bold;
                                  color: red !important;
                                }

                                .assign-fee-payment .footer .logo {
                                  width: 20px;
                                  height: 20px;
                                  text-align: center;
                                  margin-left: auto;
                                  margin-right: auto;
                                  margin-bottom: 0px;
                                  margin-top: 0px;
                                }

                                .assign-fee-payment .footer .logo img {
                                    height: 20px;
                                    width: 20px;
                                }

                                .assign-fee-payment .copyright {
                                  text-align: center;
                                  margin-left: auto;
                                  margin-right: auto;
                                  margin-bottom: 0px;
                                  margin-top: 0px;
                                  font-size: 12px;
                                }

                                .assign-fee-payment hr {
                                  margin-top: 5px;
                                  margin-bottom: 5px;
                                  border-top: 1px solid #eee;
                                }
                            </style>
                            <div class="modal-body" >
                                <div class="assign-fee-payment">
                                    <span class="header"><p class="logo"><img src="<?=base_url("uploads/images/$siteinfos->photo")?>"></p></span>
                                    <p class="title"><?=$siteinfos->sname?></p>
                                    <p class="title-desc"><?=$siteinfos->address?></p>
                                    <hr>

                                    <table class="table info">
                                        <tr>
                                            <td><b><?=$this->lang->line('global_invoice_number')?>: </b>INV-G-<?=$globalpayment->globalpaymentID?></td>
                                            <td><b><?=$this->lang->line('global_clearance')?>: </b><?=strtoupper($globalpayment->clearancetype)?></td>
                                            <td><b><?=$this->lang->line('global_date')?>: </b><?=isset($paidpayments['paiddate'][$globalpayment->globalpaymentID]) ? date('d-M-Y', strtotime($paidpayments['paiddate'][$globalpayment->globalpaymentID])) : '' ?></td>
                                        </tr>
                                        <tr>
                                            <td><b><?=$this->lang->line('global_name')?>: </b><?=count($single_student) ? $single_student->srname : ''?> </td>
                                            <td><b><?=$this->lang->line('global_classes')?>: </b><?=count($single_classes) ? $single_classes->classes : ''?>, <b><?=$this->lang->line('global_roll')?>: </b><?=count($single_student) ? $single_student->srroll : ''?>, <b><?=$this->lang->line('global_section')?>: </b><?=count($single_section) ? $single_section->section : ''?></td>
                                            <td><b><?=$this->lang->line('global_group')?>: </b><?=count($single_group) ? $single_group->group : ''?></td>
                                        </tr>

                                        <tr>
                                            <td colspan="3"><center><b><span><?=$this->lang->line('global_student_copy')?></span></b></center></td>
                                        </tr>
                                    </table>


                                    <table class="table">
                                        <thead>
                                            <?php if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if($paymented->paymentamount > 0) { ?>

                                                        <tr>
                                                            <th><?=$this->lang->line('global_fees_name')?></th>
                                                            <th class="textright"><?=$this->lang->line('global_amount')?></th>
                                                        </tr>
                                                    <?php break; } ?>
                                                <?php } ?>
                                            <?php } } ?>
                                        </thead>
                                        <tbody>
                                            <?php $paymentedPaidAmount = 0; $paymentedPaidStatus = FALSE; if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if($paymented->paymentamount > 0) { $paymentedPaidStatus = TRUE; ?>
                                                        <?php $paymentedPaidAmount += $paymented->paymentamount; ?>
                                                        <tr>
                                                            <td><?=isset($feetypes[$invoicefeetype[$paymented->invoiceID]]) ? $feetypes[$invoicefeetype[$paymented->invoiceID]] : ''?></td>
                                                            <td class="textright"><?=$paymented->paymentamount?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            <?php } } ?>

                                            <?php if($paymentedPaidStatus) { ?>
                                                <tr>
                                                    <td class="boldandred"><?=$this->lang->line('global_total')?></td>
                                                    <td class="boldandred textright"><?=$paymentedPaidAmount?></td>
                                                </tr>
                                            <?php } ?>


                                            <?php if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->fine > 0 ) { ?>
                                                        <tr>
                                                            <th colspan="2"><?=$this->lang->line('global_fine')?></th>
                                                        </tr>
                                                    <?php break; } ?>
                                                <?php } ?>
                                            <?php } } } ?>

                                            <?php $paymentedFineAmount = 0; $paymentedFineStatus = FALSE; if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->fine > 0 ) { $paymentedFineStatus = TRUE; ?>
                                                        <?php $paymentedFineAmount += isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->fine : 0; ?>
                                                        
                                                        <tr>
                                                            <td><?=isset($feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]]) ? $feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]] : ''?></td>
                                                            <td class="textright"><?=isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->fine : 0 ?></td>
                                                        </tr>
                                                    <?php } ?> 
                                                <?php } ?>
                                            <?php } } } ?>

                                            <?php if($paymentedFineStatus) { ?>
                                                <tr>
                                                    <td><?=$this->lang->line('global_total_fine')?></td>
                                                    <td class="textright"><?=$paymentedFineAmount?></td>
                                                </tr>
                                                <tr>
                                                    <td class="boldandred"><?=$this->lang->line('global_grand_total')?></td>
                                                    <td class="boldandred textright"><?=$paymentedPaidAmount+$paymentedFineAmount?></td>
                                                </tr>
                                            <?php } ?>

                                            <?php if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->weaver > 0 ) { ?>
                                                        <tr>
                                                            <th colspan="2"><?=$this->lang->line('global_weaver')?></th>
                                                        </tr>
                                                    <?php break; } ?>
                                                <?php } ?>
                                            <?php } } } ?>

                                            <?php $paymentedWeaverAmount = 0; $paymentedWeaverStatus = FALSE; if(count($weaverandfines)) { if(count($paymenteds)) { foreach ($paymenteds as $paymented) { ?>
                                                <?php if($globalpayment->globalpaymentID == $paymented->globalpaymentID) { ?>
                                                    <?php if(isset($weaverandfines[$paymented->paymentID]) && $weaverandfines[$paymented->paymentID]->weaver > 0 ) { $paymentedWeaverStatus = TRUE; ?>
                                                        <?php $paymentedWeaverAmount += isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->weaver : 0; ?>
                                                        
                                                        <tr>
                                                            <td><?=isset($feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]]) ? $feetypes[$invoicefeetype[$weaverandfines[$paymented->paymentID]->invoiceID]] : ''?></td>
                                                            <td class="textright"><?=isset($weaverandfines[$paymented->paymentID]) ? $weaverandfines[$paymented->paymentID]->weaver : 0 ?></td>
                                                        </tr>
                                                    <?php } ?> 
                                                <?php } ?>
                                            <?php } } } ?>

                                            <?php if($paymentedWeaverStatus) { ?>
                                                <tr>
                                                    <td class="boldandred"><?=$this->lang->line('global_total_weaver')?></td>
                                                    <td class="boldandred textright"><?=$paymentedWeaverAmount?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>

                                    <hr>
                                    <div class="footer">
                                        <p class="logo"><img src="<?=base_url("uploads/images/$siteinfos->photo")?>"></p>
                                        <p class="copyright"><?=$siteinfos->footer?> | <?=$this->lang->line('global_hotline')?> : <?=$siteinfos->phone?></p>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-success" onclick="javascript:printDiv('printDiv-submit-<?=$globalpayment->globalpaymentID?>')"><?=$this->lang->line('global_print')?></button>
                            <button type="button" class="btn btn-default flashClose" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                        </div>

                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } } ?>

    <script type="text/javascript">
        $('.flashClose').click(function() {
            $('.flashModel').css('display', 'none');
            $('.flashModel').attr('aria-hidden', true);
            $('.flashModel').removeClass('in');
            $('.flashModel').removeClass('flashModel');
        }); 
    </script>
<?php }  ?>

<script type="text/javascript">

$('.select2').select2();

function printDiv(divID) {
    var data = $('#'+divID).html();

    var myWindow = window.open("", "_blank", "width=600,height=auto");
    myWindow.document.write(data);
    myWindow.print();
    myWindow.close();
}
    
$("#classesID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#sectionID').val(0);
            $('#studentID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('global_payment/sectioncall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('global_payment/studentcall')?>",
                data: {"classesID" : id},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    }
});

$("#sectionID").change(function() {
    var id = $(this).val();
    var classesID = $('#classesID').val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#sectionID').val(0);
        } else {
            if(classesID === '0') {
                $('#classesID').val(0);
            } else {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('global_payment/studentcall')?>",
                    data: {"classesID" : classesID, "sectionID" : id},
                    dataType: "html",
                    success: function(data) {
                       $('#studentID').html(data);
                    }
                });
            }
        }
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('global_payment/studentcall')?>",
            data: {"classesID" : classesID, "sectionID" : id},
            dataType: "html",
            success: function(data) {
               $('#studentID').html(data);
            }
        });
    }
});

function isInt(data) {
    var val = data;
    if(isNumeric(val)) {
        return true;
    } else {
        return false;
    }
}

function parseSentenceForNumber(sentence){
    var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
    return matches && matches[0] || null;
}


function isNumeric(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}

function floatChecker(value) {
    var val = value;
    if(isNumeric(val)) {
        return true;
    } else {
        return false;
    }
}

var globalPaid = 0;
var globalFine = 0;
var globalWeaver = 0;
$(document).on("keyup", ".paid_amount", function() {
    var paid_amount_value = parseSentenceForNumber($(this).val());
    var sum = 0;

    if($(this).val() != 0 && $(this).val() != '.') { 
        if(floatChecker($(this).val())) {
            if(parseFloat(paid_amount_value)) {
                $('input[name^=paid]').removeClass('errorClass');
                var input_weaver_value = 0;
                var original_weaver_value = 0;

                $(".paid_amount").each(function(i, valu) {
                    var original_value = $('#_due_'+i).text();
                    var weaver_value = $('._weaver_'+i).val();

                    var input_value = $(this).val();
                    if(input_value != '') {
                        if(input_value != 0) {
                            var input_value = parseFloat(input_value);
                            if(input_value >= 0 && $(this).val().length <= 10) {
                                var input_value = parseFloat($(this).val());

                                if(isInt(input_value)) {
                                    if(weaver_value != "") {
                                        weaver_value = parseFloat(weaver_value);
                                        input_weaver_value = weaver_value + input_value;
                                    } else {
                                        input_weaver_value = input_value;
                                    }

                                    original_value = parseFloat(original_value);
                                    if(input_weaver_value <= original_value) {
                                        sum += input_value;
                                    } else {
                                        if(weaver_value != "") {
                                            weaver_value = parseFloat(weaver_value);
                                            original_weaver_value = (original_value - weaver_value);
                                        } else {
                                            original_weaver_value = original_value;
                                        }

                                        if(original_weaver_value == 0) {
                                            $(this).val('');
                                        } else {
                                            $(this).val(parseFloat(original_weaver_value).toFixed(2));
                                        }


                                        sum += original_weaver_value;
                                        var message = 'Collection + Weaver can not cross amount '+original_value; 
                                        toastr["error"](message)
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
                                } else {
                                    if(weaver_value != "") {
                                        weaver_value = parseFloat(weaver_value);
                                        original_weaver_value = original_value - weaver_value;
                                    } 
                                    else {
                                        original_weaver_value = original_value;
                                    }
                                    $(this).val('');
                                    sum += original_weaver_value;
                                }
                            } else {
                                if(isInt(input_value)) {
                                    $(this).val(original_value-weaver_value);
                                } else {
                                    $(this).val('');
                                }
                            }
                        } else {
                            $(this).val('');
                        }
                    } 
                });
                $("#set_paid_amount").html(parseFloat(sum).toFixed(2));
                globalPaid = sum;
                $("#TottalCollection").html(parseFloat(globalPaid+globalFine).toFixed(2));   
            } else {
                $(this).val(paid_amount_value);
            }
        } else {
            $(this).val(paid_amount_value);
            $(".paid_amount").each(function(i, valu) {
                var paidVal = $('._paid_'+ i).val();
                if(paidVal != '') { 
                    sum += parseFloat(paidVal);
                }
            });
            globalPaid = sum;
            $("#set_paid_amount").html(sum);
            $("#TottalCollection").html(globalPaid+globalFine);
        }
    } else {
        $(".paid_amount").each(function(i, valu) {
            var paidVal = $('._paid_'+ i).val();
            if(paidVal != '') { 
                sum += parseFloat(paidVal);
            }
        });
        globalPaid = sum;
        $("#set_paid_amount").html(sum);
        if(globalPaid > 0 && globalFine > 0) {
            $("#TottalCollection").html(parseFloat(globalPaid+globalFine).toFixed(2));
        } else if(globalPaid > 0) {
            $("#TottalCollection").html(parseFloat(globalPaid).toFixed(2));
        } else if(globalFine > 0) {
            $("#TottalCollection").html(parseFloat(globalFine).toFixed(2));
        }

        if((($(this).val()[0] == 0 || $(this).val()[0] == '.') && $(this).val()[1] != 0)) {
            $(this).val($(this).val());
        } else {
            $(this).val('');
        }
    }  
});

$(document).on("keyup", ".weaver", function() {
    var weaver_amount_value = parseSentenceForNumber($(this).val());
    var sum = 0;

    if($(this).val() != 0 && $(this).val() != '.') {
        if(floatChecker($(this).val())) {
            if(parseFloat(weaver_amount_value)) {
                $('input[name^=weaver]').removeClass('errorClass');
                var input_paid_value = 0;
                var original_paid_value = 0;
                $(".weaver").each(function(i, valu) {
                    var original_value = $('#_due_'+i).text();
                    var paid_value = $('._paid_'+i).val();

                    var input_value = $(this).val();
                    if(input_value != '') {
                        if(input_value != 0) {
                            var input_value = parseFloat(input_value);
                            if(input_value >= 0 && $(this).val().length <= 10) {
                                var input_value = parseFloat($(this).val());

                                if(isInt(input_value)) {
                                    if(paid_value != "") {
                                        paid_value = parseFloat(paid_value);
                                        input_paid_value = paid_value + input_value;
                                    } else {
                                        input_paid_value = input_value;
                                    }

                                    original_value = parseFloat(original_value);
                                    if(input_paid_value <= original_value) {
                                        sum += input_value;
                                        globalWeaver = sum;
                                    } else {

                                        if(paid_value != "") {
                                            paid_value = parseFloat(paid_value);
                                            original_paid_value = (original_value - paid_value);
                                        } else {
                                            original_paid_value = original_value;
                                        }

                                        if(original_paid_value == 0) {
                                            $(this).val('');
                                        } else {
                                            $(this).val(parseFloat(original_paid_value).toFixed(2));
                                        }

                                        sum += original_paid_value;
                                        globalWeaver = sum;
                                        var message = 'Collection + Weaver can not cross amount '+original_value; 
                                        toastr["error"](message)
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
                                } else {
                                    if(paid_value != "") {
                                        paid_value = parseFloat(paid_value);
                                        original_paid_value = original_value - paid_value;
                                    } 
                                    else {
                                        original_paid_value = original_value;
                                    }

                                    $(this).val(original_paid_value);
                                    sum += original_paid_value;
                                    globalWeaver = sum;
                                }
                            } else {
                                if(isInt(input_value)) {
                                    $(this).val(original_value-paid_value);
                                } else {
                                    $(this).val('');
                                }
                            }
                        } else {
                            $(this).val('');
                        }
                    }
                });
                $("#set_weaver").html(parseFloat(sum).toFixed(2));
            } else {
                $(this).val(weaver_amount_value);
            }
        } else {
            $(".weaver").each(function(i, valu) {
                var weaverVal = $('._weaver_'+i).val();
                if(weaverVal != '') { 
                    sum += parseFloat(weaverVal);
                }
            });
            $("#set_weaver").html(sum);
            $(this).val(weaver_amount_value);
        }
    } else {
        $(".weaver").each(function(i, valu) {
            var weaverVal = $('._weaver_'+i).val();
            if(weaverVal != '') { 
                sum += parseFloat(weaverVal);
            }
        });
        $("#set_weaver").html(sum);
        if((($(this).val()[0] == 0 || $(this).val()[0] == '.') && $(this).val()[1] != 0)) {
            $(this).val($(this).val());
        } else {
            $(this).val('');
        }
    }
});

$(document).on("keyup", ".fine", function() {
    var fine_amount_value = parseSentenceForNumber($(this).val());
    var sum = 0;

    if($(this).val() != 0 && $(this).val() != '.') {
        if(floatChecker($(this).val())) {
            if(parseFloat(fine_amount_value)) {
                $('input[name^=fine]').removeClass('errorClass');
                $(".fine").each(function(){
                    var input_value = parseFloat($(this).val());
                    if(isInt(input_value)) {
                        if(input_value >= 0 &&  $(this).val().length <= 10) {
                            if(isInt(input_value)) {
                                var input_value = parseFloat(input_value);
                                sum += input_value;
                            } else {
                                $(this).val('');
                            }
                        } else {
                            $(this).val('');
                        }
                    } else {
                        $(this).val('');
                    }
                });
                $("#set_fine").html(parseFloat(sum).toFixed(2));
                globalFine = sum;

                $("#TottalCollection").html(parseFloat(globalPaid+globalFine).toFixed(2));
            } else {
                $(this).val(fine_amount_value);
            }
        } else {
            $(".fine").each(function(i, valu) {
                var fineVal = $(this).val();
                if(fineVal != '') { 
                    sum += parseFloat(fineVal);
                }
            });
            globalFine = sum;
            $("#set_fine").html(sum);
            $("#TottalCollection").html(globalPaid+globalFine);
            $(this).val(fine_amount_value);
        }
    } else {
        $(".fine").each(function(i, valu) {
            var fineVal = $(this).val();
            if(fineVal != '') { 
                sum += parseFloat(fineVal);
            }
        });
        globalFine = sum;
        $("#set_fine").html(sum);
        if(globalPaid > 0 && globalFine > 0) {
            $("#TottalCollection").html(parseFloat(globalPaid+globalFine).toFixed(2));
        } else if(globalPaid > 0) {
            $("#TottalCollection").html(parseFloat(globalPaid).toFixed(2));
        } else if(globalFine > 0) {
            $("#TottalCollection").html(parseFloat(globalPaid).toFixed(2));
        }

        if((($(this).val()[0] == 0 || $(this).val()[0] == '.') && $(this).val()[1] != 0)) {
            $(this).val($(this).val());
        } else {
            $(this).val('');
        }
    }
});

$(document).on("keyup", "#paymentyear", function() {
    var input_value = parseInt($(this).val());
    if(isInt(input_value)) {
        if($(this).val().length > 0 && $(this).val().length <= 4) {
            if($(this).val().length == 4) {
                var str = "01/05/" + $(this).val();
                var year = str.match(/\/(\d{4})/)[1];
                var currYear = new Date().toString().match(/(\d{4})/)[1];
                if (year >= 1500 && year <= currYear) {
                    $(this).removeClass('errorClass');
                } else {
                    $(this).addClass('errorClass');
                }
            }
        } else {
            $(this).val('');
        }
    } else {
        $(this).val('');
    }
});


$('#add_payment').on('click',function(e){
    var error = 0;
    var invoicename            = $('#invoicename'); 
    var invoicedescription     = $('#invoicedescription'); 
    var invoicenumber          = $('#invoicenumber'); 
    var paymentyear            = $('#paymentyear'); 
    var payment_status         = $('#payment_status'); 
    var payment_type           = $('#payment_type'); 

    if(invoicename.val() == '') {
        invoicename.addClass('errorClass');
        error++;
    } else if(invoicename.val().length > 127)  {
        invoicename.addClass('errorClass');
        error++;
    } else {
        invoicename.removeClass('errorClass');
    }

    if(invoicedescription.val().length > 127) {
        invoicedescription.addClass('errorClass');
        error++;
    } else {
        invoicedescription.removeClass('errorClass');
    }

    if(invoicenumber.val() == '') {
        invoicenumber.addClass('errorClass');
        error++;
    } else {
        invoicenumber.removeClass('errorClass');
    }

    if(paymentyear.val() == '') {
        paymentyear.addClass('errorClass');
        error++;
    } else if(paymentyear.val().length > 4 || paymentyear.val().length <= 3)  {
        paymentyear.addClass('errorClass');
        error++;
    } else {
        paymentyear.removeClass('errorClass');
    }

    var classesID           = <?=$set_classesID?>;
    var studentID           = <?=$set_studentID?>;
    invoicename             = invoicename.val(); 
    invoicedescription      = invoicedescription.val(); 
    invoicenumber           = invoicenumber.val(); 
    paymentyear             = paymentyear.val(); 
    payment_status          = payment_status.val(); 
    payment_type            = payment_type.val();

    var paid = $('input[name^=paid]').map(function(){
        return { paidFieldID: this.name , value: this.value };
    }).get();

    var weaver = $('input[name^=weaver]').map(function(){
        return { weaverFieldID: this.name , value: this.value };
    }).get();

    var fine = $('input[name^=fine]').map(function(){
        return { fineFieldID: this.name , value: this.value };
    }).get();

    if(globalPaid == 0 && globalFine == 0 && globalWeaver == 0) {
        $('input[name^=paid]').addClass('errorClass');
        $('input[name^=weaver]').addClass('errorClass');
        $('input[name^=fine]').addClass('errorClass');
        error++;
    } else {
        $('input[name^=paid]').removeClass('errorClass');
        $('input[name^=weaver]').removeClass('errorClass');
        $('input[name^=fine]').removeClass('errorClass');        
    }

    if(error == 0) {
        $(this).attr("disabled", "disabled");
        $.ajax({
            type: 'POST',
            url: "<?=base_url('global_payment/paymentSend')?>",
            data: {
                "classesID" : classesID, 
                "studentID" : studentID, 
                'invoicename' : invoicename,
                'invoicedescription' : invoicedescription,
                'invoicenumber' : invoicenumber,
                'paymentyear' : paymentyear,
                'payment_status' : payment_status,
                'payment_type' : payment_type,
                "paid" : paid,
                "weaver" : weaver,
                "fine" : fine
            },
            dataType: "html",
            success: function(data) {
                var response = jQuery.parseJSON(data);

                if(response.status) {
                    window.location.reload();
                } else {
                    $(this).removeAttr("disabled");
                    $(this).removeAttr("disabled", '');
                    if(response.paid) {
                        $('input[name^=paid]').addClass('errorClass');
                        $('input[name^=weaver]').addClass('errorClass');
                        $('input[name^=fine]').addClass('errorClass');
                    } else {
                        $('input[name^=paid]').removeClass('errorClass');
                        $('input[name^=weaver]').removeClass('errorClass');
                        $('input[name^=fine]').removeClass('errorClass');
                    }

                    if(response.invoicename) {
                        $('#invoicename').addClass('errorClass');
                    } else {
                        $('#invoicename').removeClass('errorClass');
                    }

                    if(response.invoicenumber) {
                        $('#invoicenumber').addClass('errorClass');
                    } else {
                        $('#invoicenumber').removeClass('errorClass');
                    }

                    if(response.paymentyear) {
                        $('#paymentyear').addClass('errorClass');
                    } else {
                        $('#paymentyear').removeClass('errorClass');
                    }

                    if(response.invoicedescription) {
                        $('#invoicedescription').addClass('errorClass');
                    } else {
                        $('#invoicedescription').removeClass('errorClass');
                    }
                }
            }
        });  
    }
});

</script>