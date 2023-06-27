<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $generatepdfurl = base_url("searchpaymentfeesreport/pdf/".$gspaymentID);
            $generatexmlurl = base_url("searchpaymentfeesreport/xml/".$gspaymentID);
            echo btn_printReport('searchpaymentfeesreport', $this->lang->line('report_print'), 'printablediv='.$gspayment);
            echo btn_pdfPreviewReport('searchpaymentfeesreport',$generatepdfurl, $this->lang->line('report_pdf_preview'));
            echo btn_sentToMailReport('searchpaymentfeesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<?php if(($gspayment == "INV-G-") || ($gspayment == "inv-g-")) { ?>
        <div class="box">
            <div class="box-header bg-gray">
                <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('searchpaymentfeesreport_report_for')?> - <?=$this->lang->line('searchpaymentfeesreport')?>  </h3>
            </div>
        <?php if(count($globalpayments)) { ?>
            <div id="printablediv">
                <!-- form start -->
                <style type="text/css">
                    .global .table > tbody > tr > td {
                        border: 1px lightslategrey !important;
                    }
                    .global .table > thead > tr > th {
                        background-color: #00acac !important;
                        font-weight: bold !important;
                        color: white !important;
                        border: 0px !important;
                    }
                    .global .table .textright {
                        text-align: right !important;
                    }
                    .global .table .boldandred {
                        color: red !important;
                        font-weight: bold !important;
                    }

                    .reportrowbg {
                        background: #ccc;
                        color: #000;
                    }
                    #example1 tr {
                        border-bottom: 1px solid #ddd !important;
                    }

                    @media print {
                        .border {
                            border: 0px solid #ddd !important;
                        }
                    }
                </style>
                <div class="box-body global">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 border">
                            <!-- <div class="row"> -->
                                <div class="col-sm-12">
                                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                                    <hr>
                                </div>
                                <?php if(count($globalpayments)) { ?>
                                <div class="col-sm-12">
                                    <table class="table info">
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
                                </div>
                                <div class="col-sm-12" style="margin-top:0px">
                                    <table id="example1" class="table">
                                        <tr class="reportrowbg">
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
                                                <tr class="reportrowbg">
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
                                                <tr class="reportrowbg">
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
                                        <?php }?>

                                        </tbody>
                                    </table>
                                </div>
                                <?php } else { ?>
                                    <div class="col-sm-12">
                                        <div class="callout callout-danger">
                                            <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
                                        </div>
                                    </div>
                                <?php } ?>
                                <div class="col-sm-12 text-center footerAll">
                                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div><!-- row -->
                </div><!-- Body -->
            </div>
        <?php } else { ?>
            <div class="callout callout-danger">
                <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
            </div>
        <?php } ?>
        </div>
<?php } elseif (($gspayment == "INV-S-") || ($gspayment == "inv-s-")) { ?>
        <div class="box">
            <div class="box-header bg-gray">
                <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('searchpaymentfeesreport_report_for')?> - <?=$this->lang->line('searchpaymentfeesreport')?>  </h3>
            </div><!-- /.box-header -->
        <?php if(count($singlepayments)) {?>
            <div id="printablediv">
                <section class="content invoice" >
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="page-header">
                                <?php
                                if($siteinfos->photo) {
                                    $array = array(
                                        "src" => base_url('uploads/images/'.$siteinfos->photo),
                                        'width' => '25px',
                                        'height' => '25px',
                                        'class' => 'img-circle',
                                        'style' => 'margin-top:-10px'
                                    );
                                    echo img($array);
                                }
                                ?>
                                <?php  echo $siteinfos->sname; ?>
                                <small class="pull-right">
                                    <?=$this->lang->line('searchpaymentfeesreport_create_date').' : '.date('d M Y')?>
                                </small>
                            </h2>
                        </div>
                    </div>

                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                            <?php if(count($siteinfos)) { ?>
                                <?=$this->lang->line("searchpaymentfeesreport_from"); ?>
                                <address>
                                    <strong><?=$siteinfos->sname?></strong><br>
                                    <?=$siteinfos->address?><br>
                                    <?=$this->lang->line("searchpaymentfeesreport_phone"). " : ". $siteinfos->phone?><br>
                                    <?=$this->lang->line("searchpaymentfeesreport_email"). " : ". $siteinfos->email?><br>
                                </address>
                            <?php } ?>
                        </div>
                        <?php if(count($studentinfo)) { ?>
                            <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                                <?=$this->lang->line("searchpaymentfeesreport_to"); ?>
                                <address >
                                    <strong><?=$studentinfo->srname?></strong><br>
                                    <?=$this->lang->line("searchpaymentfeesreport_roll"). " : ". $studentinfo->srroll?><br>
                                    <?=$this->lang->line("searchpaymentfeesreport_classes"). " : ". $studentinfo->srclasses?><br>
                                    <?=$this->lang->line("searchpaymentfeesreport_registerNO"). " : ". $studentinfo->srregisterNO?><br>
                                    <?php if(count($student)) { ?>
                                        <?=$this->lang->line("searchpaymentfeesreport_email"). " : ". $student->email ?><br>
                                    <?php } ?>
                                </address>
                            </div>
                            <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                                <b><?=$this->lang->line("searchpaymentfeesreport_invoice_number")." : "."INV-S-".$globalpaymentID?></b>
                                <br>
                                <?=$this->lang->line('searchpaymentfeesreport_payment_method'). " : "."<span>".$paymenttype."</span>"?>
                            </div>
                        <?php } ?>
                    </div>

                    <br />
                    <div class="row">
                        <?php if(count($singlepayments)) { ?>
                        <div class="col-xs-12 col-sm-12">
                            <div class="table-responsive">
                            <table class="table table-bordered product-style">
                                <thead>
                                    <tr>
                                        <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                        <th class="col-lg-3"><?=$this->lang->line('searchpaymentfeesreport_fees_type')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('searchpaymentfeesreport_amount')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('searchpaymentfeesreport_weaver')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('searchpaymentfeesreport_fine')?></th>
                                        <th class="col-lg-2"><?=$this->lang->line('searchpaymentfeesreport_sub_total')?></th>
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
                                        if($singlepayment->paymentamount > 0 || $singlepayment->weaver > 0 || $singlepayment->fine > 0) { $paymentDate = $singlepayment->paymentdate; $paymentUserTypeID = $singlepayment->usertypeID; $paymentUserID = $singlepayment->userID; 
                                    ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('searchpaymentfeesreport_fees_type')?>"><?=isset($feetypes[$singlepayment->feetypeID]) ? $feetypes[$singlepayment->feetypeID] : ''?></td>
                                            <td data-title="<?=$this->lang->line('searchpaymentfeesreport_amount')?>"><?=number_format($singlepayment->paymentamount,2)?></td>
                                            <td data-title="<?=$this->lang->line('searchpaymentfeesreport_weaver')?>"><?=number_format($singlepayment->weaver,2)?></td>
                                            <td data-title="<?=$this->lang->line('searchpaymentfeesreport_fine')?>"><?=number_format($singlepayment->fine,2)?></td>
                                            <td data-title="<?=$this->lang->line('searchpaymentfeesreport_sub_total')?>"><?=number_format(($singlepayment->paymentamount+$singlepayment->fine),2)?></td>
                                            <?php 
                                                $paymentamount += $singlepayment->paymentamount;
                                                $weaver += $singlepayment->weaver;
                                                $fine += $singlepayment->fine;
                                                $subtotal += ($singlepayment->paymentamount+$singlepayment->fine);
                                            ?>
                                        </tr>
                                    <?php $i++; } } ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="2"><span class="pull-right"><b><?=$this->lang->line('searchpaymentfeesreport_total')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                        <td><b><?=number_format($paymentamount, 2)?></b></td>
                                        <td><b><?=number_format($weaver, 2)?></b></td>
                                        <td><b><?=number_format($fine, 2)?></b></td>
                                        <td><b><?=number_format($subtotal, 2)?></b></td>
                                    </tr>
                                </tfoot>
                            </table>
                            </div>
                        </div>
                        
                        <div class="col-sm-3 col-xs-12 pull-right">
                            <div class="well well-sm">
                                <p>
                                    <?=$this->lang->line('searchpaymentfeesreport_create_by')?> : <?=getNameByUsertypeIDAndUserID($paymentUserTypeID, $paymentUserID)?>
                                    <br>
                                    <?=$this->lang->line('searchpaymentfeesreport_date')?> : <?=date('d M Y', strtotime($paymentDate))?>
                                </p>
                            </div>
                        </div>

                        <?php } else { ?>
                            <div class="callout callout-danger">
                                <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
                            </div>
                        <?php } ?>
                    </div>
                </section>
            </div>
        <?php } else { ?>
            <div class="callout callout-danger">
                <p><b class="text-info"><?=$this->lang->line('searchpaymentfeesreport_data_not_found')?></b></p>
            </div>
        <?php } ?>
        </div>
<?php } ?>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('searchpaymentfeesreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('searchpaymentfeesreport_close')?></span></button>
                    <h4 class="modal-title"><?=$this->lang->line('searchpaymentfeesreport_mail')?></h4>
                </div>
                <div class="modal-body">

                    <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("searchpaymentfeesreport_to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php
                if(form_error('subject'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                <label for="subject" class="col-sm-2 control-label">
                    <?=$this->lang->line("searchpaymentfeesreport_subject")?> <span class="text-red">*</span>
                </label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                </div>
                <span class="col-sm-4 control-label" id="subject_error">
                    </span>

            </div>

            <?php
            if(form_error('message'))
                echo "<div class='form-group has-error' >";
            else
                echo "<div class='form-group' >";
            ?>
            <label for="message" class="col-sm-2 control-label">
                <?=$this->lang->line("searchpaymentfeesreport_message")?>
            </label>
            <div class="col-sm-6">
                <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
        <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("searchpaymentfeesreport_send")?>" />
    </div>
    </div>
    </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('searchpaymentfeesreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var field = {
            'to'            : $('#to').val(),
            'subject'       : $('#subject').val(),
            'message'       : $('#message').val(),
            'gspaymentID'   :'<?=$gspaymentID?>'
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");
        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('searchpaymentfeesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('searchpaymentfeesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('searchpaymentfeesreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        $.each(response, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value)
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
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>
