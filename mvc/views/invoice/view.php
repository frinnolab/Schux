
<?php if(count($maininvoice)) { ?>
    <div class="well">
        <div class="row">

            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('invoice/print_preview/'.$maininvoice->maininvoiceID, $this->lang->line('pdf_preview')) 
                ?>
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php
                        if(permissionChecker('invoice_edit')) {
                            if($maininvoice->maininvoicestatus != 1 && $maininvoice->maininvoicestatus != 2) {
                                echo btn_sm_edit('invoice/edit/'.$maininvoice->maininvoiceID, $this->lang->line('edit'));
                            }
                        }
                    ?>
                <?php } ?>
                <?php
                    if($maininvoice->maininvoicestatus != 2) {
                        echo btn_payment('invoice/payment/'.$maininvoice->maininvoiceID, $this->lang->line('payment')); 
                    }
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>                
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
    	<section class="content invoice" >
    		<div class="row">
    		    <div class="col-xs-12">
    		        <h2 class="text-center page-header">
    		            <?php
    	                    if($siteinfos->photo) {
    		                    $array = array(
    		                        "src" => base_url('uploads/images/'.$siteinfos->photo),
    		                        'width' => '50px',
    		                        'height' => '50px',
    		                        'class' => 'img-circle',
                                    'style' => 'margin-top:-10px',
    		                    );
                                // echo img($array);
                                echo "<img src=".base_url('uploads/images/'.$siteinfos->photo)." width='50px' height='50px' style='margin-top: -10px'>";
    		                } 
    	                ?>
    		            <!-- <small class="pull-right"><?=$this->lang->line('invoice_create_date').' : '.date('d M Y')?></small> -->
                    </h2>
                    <!-- <h2 class="text-center page-header">
                        <?php  echo $siteinfos->sname; ?>
                    </h2> -->
                    <address class="text-center" style="font-size: 14px; margin-top:-10px">
                        <strong><?=$siteinfos->sname?></strong><br>
                        <?=$siteinfos->address?><br>
                    </address>
    		    </div><!-- /.col -->
    		</div>
    		<div class="row invoice-info">
    		    <!-- <div class="col-sm-4 invoice-col" style="font-size: 16px;">
    				<?php  echo $this->lang->line("invoice_from"); ?>
    				<address>
    					<strong><?=$siteinfos->sname?></strong><br>
    					<?=$siteinfos->address?><br>
    					<?=$this->lang->line("invoice_phone"). " : ". $siteinfos->phone?><br>
    					<?=$this->lang->line("invoice_email"). " : ". $siteinfos->email?><br>
    				</address> -->
    	            

    		    <!-- </div>/.col -->
    		    <div class="col-lg-8 col-md-8 col-sm-8 invoice-col" style="font-size: 14px;">
    	        	<?=$this->lang->line("invoice_to")?>
    	        	<address>
    	        		<strong><?=$maininvoice->srname?></strong><br>
    	        		<!-- <?=$this->lang->line("invoice_roll"). " : ". $maininvoice->srroll?><br> -->
    	        		<?=$this->lang->line("invoice_classesID"). " : ". $maininvoice->srclasses?><br>
    	        		<?=$this->lang->line("student_registerNO"). " : ". $student->registerNO?><br>
    	        		<!-- <?php if(count($student)) { echo $this->lang->line("invoice_email"). " : ". $student->email; } ?><br> -->
    	        	</address>
    		    </div><!-- /.col -->
    		    <div class="col-lg-4 col-md-4 col-sm-4 invoice-col text-right" style="font-size: 14px;">
    		        <b><?= $maininvoice->maininvoicetype == "profoma" ? $this->lang->line("invoice_profoma").$maininvoice->maininvoiceID : $this->lang->line("invoice_invoice").$maininvoice->maininvoiceID?></b><br>
                    <?php 
                        $status = $maininvoice->maininvoicestatus;
                        $setButton = '';
                        if($status == 0) {
                            $status = $this->lang->line('invoice_notpaid');
                            $setButton = 'text-red';
                        } elseif($status == 1) {
                            $status = $this->lang->line('invoice_partially_paid');
                            $setButton = 'text-yellow';
                        } elseif($status == 2) {
                            $status = $this->lang->line('invoice_fully_paid');
                            $setButton = 'text-green';
                        }

                        echo $this->lang->line('invoice_status'). " : ". "<span class='".$setButton."'>".$status."</span>";
                    ?>

                    <div class="well well-sm">
                        <p>
                            <?=$this->lang->line('invoice_create_by')?> : <?=$createuser?>
                            <br>
                            <?=$this->lang->line('invoice_date')?> : <?=date('d M Y', strtotime($maininvoice->maininvoicecreate_date))?>
                        </p>
                    </div>

    		    </div>
    		</div>
            <br />

            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered product-style">
                            <thead>
                                <tr>
                                    <th class="col-lg-2" ><?=$this->lang->line('slno')?></th>
                                    <th class="col-lg-4"><?=$this->lang->line('invoice_feetype')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('invoice_amount')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('invoice_discount')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('invoice_subtotal')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $subtotal = 0; 
                                    $totalsubtotal = 0; 
                                    $i = 1; 
                                    if(count($invoices)) { foreach($invoices as $invoice) { $discount = 0; if($invoice->discount > 0) { $discount = (($invoice->amount/100) * $invoice->discount); } $subtotal = ($invoice->amount - $discount); $totalsubtotal += $subtotal; ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('invoice_feetype')?>">
                                            <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : ''?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('invoice_amount')?>">
                                            <?=number_format($invoice->amount, 2)?> <?= $invoice->currency ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('invoice_discount')?>">
                                            <?=number_format($discount, 2)?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('invoice_subtotal')?>">
                                            <?=number_format($subtotal, 2)?>
                                        </td>
                                    </tr>
                                <?php $i++; } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (USD)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[0], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (GBP)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[1], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (TZS)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[2], 2)?></b></td>
                                </tr>
                                <?php if ($maininvoice->maininvoicetype == 'invoice') { ?>
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (USD)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[0], 2)?></b></td>
                                </tr> 
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (TZS)</b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (GBP)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[1], 2)?></b></td>
                                </tr> 
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (TZS)</b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (TZS)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[2], 2)?></b></td>
                                </tr> 
                                <!-- <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_weaver')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($grandtotalandpayment['totalweaver'], 2)?></b></td>
                                </tr> -->
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (USD)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[0] - explode(",",$grandtotalandpayment['totalpayment'])[0], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (GBP)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[1] - explode(",",$grandtotalandpayment['totalpayment'])[1], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (TZS)</b></span></td>
                                    <td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[2] - explode(",",$grandtotalandpayment['totalpayment'])[2], 2)?></b></td>
                                </tr>
                                <tr>
                                    <!-- <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td> -->
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (USD)</b></span></td>
                                    <td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (GBP)</b></span></td>
                                    <td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (TZS)</b></span></td>
                                    <td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
                                </tr>
                                <?php } ?>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered product-style">
                            <thead>
                                <tr>
                                    <th class="col-lg-2" ><?=$this->lang->line('invoice_installments')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <ul class="text-left">
                                        <?php if(count($invoices)) { foreach($invoices as $invoice) { $discount = 0; if($invoice->discount > 0) { $discount = (($invoice->amount/100) * $invoice->discount); } $subtotal = ($invoice->amount - $discount); $totalsubtotal += $subtotal; ?>
                                            <li>
                                            <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : ''?> : <?=number_format($invoice->amount, 2)?> <?= $invoice->currency ?> <strong><?=$this->lang->line('due_date')?> :</strong> <?= date('d M Y', strtotime($invoice->due_date)); ?>
                                            </li>
                                        <?php } } ?>
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered product-style">
                            <thead>
                                <tr>
                                    <th class="col-lg-2" ><?=$this->lang->line('invoice_terms')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <ol class="text-left">
                                            <li>
                                                Payment should be banked at:
                                                <ul>
                                                    <?php if($maininvoice->srclasses != 'SEN') { ?>
                                                        <li>
                                                            Diamond Trust Bank (DTB), Arusha City Branch – JAFFERY PRIMARY SCHOOL -USD AC NO. 0163615005. TZS 7163615002.
                                                        </li>
                                                        <li>
                                                            Bank of Baroda (T) Ltd, Arusha Branch – JAFFERY PRIMARY SCHOOL- USD AC NO. 96020200000167. TZS 96020200000166.                                                        
                                                        </li>
                                                        <li>
                                                            Exim Bank (Tanzania) Limited, Arusha Branch - JAFFERY PRIMARY SCHOOL- USD AC NO. 0780262221. TZS 0780262017.                                                        
                                                        </li>
                                                    <?php } else { ?>
                                                        <li>
                                                            Diamond Trust Bank (DTB), Arusha City Branch – JAFFERY PRIMARY SCHOOL -USD AC NO. 0163615007. TZS 7163615003.
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            </li>
                                            <li>
                                                Payment should be done not later than the installments date.
                                            </li>
                                            <li>
                                                All payment slips should be submitted to the accounts department before the due date and receipt collected for the payment done.
                                            </li>
                                            <li>
                                                All paying slips should bear the name and class of the child
                                            </li>
                                            <li>
                                                For family discount contact the accounts department. Discount applies to TZS fees only.
                                            </li>
                                            <li>
                                                Fees once paid are not refundable
                                            </li>
                                        </ol>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- <div class="col-sm-3 col-xs-12 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?=$this->lang->line('invoice_create_by')?> : <?=$createuser?>
                            <br>
                            <?=$this->lang->line('invoice_date')?> : <?=date('d M Y', strtotime($maininvoice->maininvoicecreate_date))?>
                        </p>
                    </div>
                </div> -->
            </div>


    		<!-- this row will not appear when printing -->
    	</section><!-- /.content -->
    </div>
    <!-- email modal starts here -->
    <form class="form-horizontal" role="form" action="<?=base_url('teacher/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                </div>
                <div class="modal-body">
                
                    <?php 
                        if(form_error('to')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="to" class="col-sm-2 control-label">
                            <?=$this->lang->line("to")?> <span class="text-red">*</span>
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
                            <?=$this->lang->line("subject")?> <span class="text-red">*</span>
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
                            <?=$this->lang->line("message")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="message" name="message" style="resize: vertical;" value="<?=set_value('message')?>" ></textarea>
                        </div>
                    </div>

                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
                </div>
            </div>
          </div>
        </div>
    </form>
    <!-- email end here -->
    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            window.location.reload();
        }
        function closeWindow() {
            location.reload(); 
        }

        function check_email(email) {
            var status = false;     
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }


        $("#send_pdf").click(function(){
            var field = {
                'to'                : $('#to').val(), 
                'subject'           : $('#subject').val(), 
                'message'           : $('#message').val(),
                'id'                : "<?=$maininvoice->maininvoiceID;?>",
            };

            var to = $('#to').val();
            var subject = $('#subject').val();
            var error = 0;

            $("#to_error").html("");
            $("#subject_error").html("");

            if(to == "" || to == null) {
                error++;
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            }

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $('#send_pdf').attr('disabled','disabled');
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('invoice/send_mail')?>",
                    data: field,
                    dataType: "html",
                    success: function(data) {
                        var response = JSON.parse(data);
                        if (response.status == false) {
                            $('#send_pdf').removeAttr('disabled');
                            if(response.to) {
                                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
                            } 
                            
                            if(response.subject) {
                                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
                            }
                            
                            if(response.message) {
                                toastr["error"](response.message)
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
                            location.reload();
                        }
                    }
                });
            }
        });
    </script>
<?php } ?>
