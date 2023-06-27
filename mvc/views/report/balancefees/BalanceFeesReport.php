<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            
            $pdf_preview_uri = base_url('balancefeesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID);
            $xml_preview_uri = base_url('balancefeesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID);

            echo btn_printReport('balancefeesreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('balancefeesreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            // echo btn_xmlReport('balancefeesreport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('balancefeesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('balancefeesreport_report_for')?> - 
            <?=$this->lang->line('balancefeesreport_balancefees');?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($classesID >= 0 || $sectionID >= 0 ) { ?>
                    <div class="col-sm-12">
                        <div class="row">
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
                        </div>
                    </div>
                <?php }  else { ?>
                    <div class="col-sm-12" style="margin-top: 15px;"></div>
                <?php } 
                if(count($students)) { ?>
                    <div class="col-sm-12">
                        <div id="hide-table">
                            <table class="table table-bordered">
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
                                        foreach($students as $student) { $i++; ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_name')?>">
                                                    <?=$student->srname?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_registerNO')?>">
                                                    <?=$student->srregisterNO?>
                                                </td>
                                                <?php if($classesID == 0) { ?>
                                                    <td data-title="<?=$this->lang->line('balancefeesreport_class')?>">
                                                        <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?>
                                                    </td>
                                                <?php } ?>

                                                <?php if($sectionID == 0) { ?>
                                                    <td data-title="<?=$this->lang->line('balancefeesreport_section')?>">
                                                        <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?>
                                                    </td>
                                                <?php } ?>

                                                <td data-title="<?=$this->lang->line('balancefeesreport_roll')?>">
                                                    <?=$student->srroll?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_fees_amount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['amountUSD']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountUSD'],2) : number_format(0, 2);
                                                        $AmountUSD = $totalAmountAndDiscount[$student->srstudentID]['amountUSD'];
                                                        $totalAmountUSD += $totalAmountAndDiscount[$student->srstudentID]['amountUSD'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_fees_amount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['amountGBP']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountGBP'],2) : number_format(0, 2);
                                                        $AmountGBP = $totalAmountAndDiscount[$student->srstudentID]['amountGBP'];
                                                        $totalAmountGBP += $totalAmountAndDiscount[$student->srstudentID]['amountGBP'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_fees_amount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['amountTZS']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['amountTZS'],2) : number_format(0, 2);
                                                        $AmountTZS = $totalAmountAndDiscount[$student->srstudentID]['amountTZS'];
                                                        $totalAmountTZS += $totalAmountAndDiscount[$student->srstudentID]['amountTZS'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_discount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['discountUSD']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountUSD'],2) : number_format(0, 2);
                                                        $DiscountUSD = $totalAmountAndDiscount[$student->srstudentID]['discountUSD'];
                                                        $totalDiscountUSD += $totalAmountAndDiscount[$student->srstudentID]['discountUSD'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_discount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['discountGBP']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountGBP'],2) : number_format(0, 2);
                                                        $DiscountGBP = $totalAmountAndDiscount[$student->srstudentID]['discountGBP'];
                                                        $totalDiscountGBP += $totalAmountAndDiscount[$student->srstudentID]['discountGBP'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_discount')?>">
                                                    <?php
                                                        echo isset($totalAmountAndDiscount[$student->srstudentID]['discountTZS']) ? number_format($totalAmountAndDiscount[$student->srstudentID]['discountTZS'],2) : number_format(0, 2);
                                                        $DiscountTZS = $totalAmountAndDiscount[$student->srstudentID]['discountTZS'];
                                                        $totalDiscountTZS += $totalAmountAndDiscount[$student->srstudentID]['discountTZS'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_paid')?>">
                                                    <?php
                                                        echo isset($totalPayment[$student->srstudentID]['paymentUSD']) ? number_format($totalPayment[$student->srstudentID]['paymentUSD'],2) : number_format(0, 2);
                                                        $PaymentUSD = $totalPayment[$student->srstudentID]['paymentUSD'];
                                                        $totalPaymentsUSD += $totalPayment[$student->srstudentID]['paymentUSD'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_paid')?>">
                                                    <?php
                                                        echo isset($totalPayment[$student->srstudentID]['paymentGBP']) ? number_format($totalPayment[$student->srstudentID]['paymentGBP'],2) : number_format(0, 2);
                                                        $PaymentGBP = $totalPayment[$student->srstudentID]['paymentGBP'];
                                                        $totalPaymentsGBP += $totalPayment[$student->srstudentID]['paymentGBP'];
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_paid')?>">
                                                    <?php
                                                        echo isset($totalPayment[$student->srstudentID]['paymentTZS']) ? number_format($totalPayment[$student->srstudentID]['paymentTZS'],2) : number_format(0, 2);
                                                        $PaymentTZS = $totalPayment[$student->srstudentID]['paymentTZS'];
                                                        $totalPaymentsTZS += $totalPayment[$student->srstudentID]['paymentTZS'];
                                                    ?>
                                                </td>
                                                <!-- <td data-title="<?=$this->lang->line('balancefeesreport_weaver')?>">
                                                    <?=isset($totalweavar[$student->srstudentID]['weaver']) ? number_format($totalweavar[$student->srstudentID]['weaver'],2) : number_format(0, 2)?>
                                                </td> -->
                                                <td data-title="<?=$this->lang->line('balancefeesreport_balance')?>">
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
                                                        $BalanceUSD = ($AmountUSD - $DiscountUSD) - ($PaymentUSD);
                                                        $totalBalanceUSD += $BalanceUSD;
                                                        
                                                        echo number_format($BalanceUSD,2);
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_balance')?>">
                                                    <?php 
                                                        $BalanceGBP = ($AmountGBP - $DiscountGBP) - ($PaymentGBP);
                                                        $totalBalanceGBP += $BalanceGBP;
                                                        echo number_format($BalanceGBP,2);
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('balancefeesreport_balance')?>">
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
                                        <td data-title="<?=$this->lang->line('balancefeesreport_grand_total')?>" class="text-right text-bold" colspan="<?=$colspan?>"><?=$this->lang->line('balancefeesreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?> --> </td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_fees_amount')?>" class="text-bold"><?=number_format($totalAmountUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_fees_amount')?>" class="text-bold"><?=number_format($totalAmountGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_fees_amount')?>" class="text-bold"><?=number_format($totalAmountTZS,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_discount')?>" class="text-bold"><?=number_format($totalDiscountUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_discount')?>" class="text-bold"><?=number_format($totalDiscountGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_discount')?>" class="text-bold"><?=number_format($totalDiscountTZS,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_paid')?>" class="text-bold"><?=number_format($totalPaymentsUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_paid')?>" class="text-bold"><?=number_format($totalPaymentsGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_paid')?>" class="text-bold"><?=number_format($totalPaymentsTZS,2)?></td>
                                        <!-- <td data-title="<?=$this->lang->line('balancefeesreport_total_weaver')?>" class="text-bold"><?=number_format($totalWeaver,2)?></td> -->
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_balance')?>" class="text-bold"><?=number_format($totalBalanceUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_balance')?>" class="text-bold"><?=number_format($totalBalanceGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('balancefeesreport_total_balance')?>" class="text-bold"><?=number_format($totalBalanceTZS,2)?></td>
                                    </tr>                             
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <br/>
                    <div class="col-sm-12">
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('report_data_not_found')?></b></p>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('balancefeesreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('balancefeesreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('balancefeesreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("balancefeesreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("balancefeesreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("balancefeesreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("balancefeesreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('balancefeesreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $("#send_pdf").click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'studentID'  : '<?=$studentID?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('balancefeesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('balancefeesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('balancefeesreport/send_pdf_to_mail')?>",
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
