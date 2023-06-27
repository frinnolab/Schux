

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
  	<div>
    	<table width="100%">
      		<tr width="100%">
				<td class="text-center" width="5%">
					<h2>
					  <?php
							if($siteinfos->photo) {
								$array = array(
									"src" => base_url('uploads/images/'.$siteinfos->photo),
									'width' => '55px',
									'height' => '55px',
									'style' => 'margin-top:-8px'
								);
								echo img($array);
							}
						?>
					</h2>
				</td>
        		<td class="text-center" width="95%">
					  <h3 class="top-site-header-title"><?php  echo $siteinfos->sname; ?></h3>
					  <div>
					  	<?=$siteinfos->address?>
					  </div>
        		</td>
			</tr>
		</table>
	    <table width="100%">
	      	<tr>
	        	<td width="60%">
	            	<table >
	              		<tbody>
	              			<tr>
			                    <th class="site-header-title-float"><?php  echo $this->lang->line("invoice_to"); ?></th>
			                </tr>
			                <tr>
			                    <td><?php  echo $maininvoice->srname; ?></td>
			                </tr>
			                <tr>
			                    <td><?php  echo $this->lang->line("invoice_classesID"). " : ". $maininvoice->srclasses; ?></td>
			                </tr>
			                <tr>
			                    <td><?php  echo $this->lang->line("student_registerNO"). " : ". $student->unique_number; ?></td>
			                </tr>
	              		</tbody>
	            	</table>
	        	</td>
	        	<td width="40%" style="vertical-align: text-top; text-align: right;">
		          	<table>
		            	<tbody>
		              		<tr>
		                		<td>
		                			<?= $maininvoice->maininvoicetype == "profoma" ? $this->lang->line("invoice_profoma").$maininvoice->maininvoiceID : $this->lang->line("invoice_invoice").$maininvoice->maininvoiceID?>
		                		</td>
		              		</tr>
		              		<tr>
		                		<td>
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

				                        echo $this->lang->line('invoice_status'). " : ". "<span class='".$setButton."'>".$status."</span>";;
				                    ?>
		                		</td>
							  </tr>
							  <tr>
								  <td width="35%">
									<table>
										<tr>
											<td><?=$this->lang->line('invoice_create_by')?> : <?=$createuser?></td>
										</tr>
										<tr>
											<td><?=$this->lang->line('invoice_date')?> : <?=date('d M Y', strtotime($maininvoice->maininvoicecreate_date))?></td>
										</tr>
									</table>
								</td>
							  </tr>
		            	</tbody>
		          	</table>
	        	</td>
	      	</tr>
	    </table>
	    <table class="table table-bordered" width="100%" style="margin-bottom: 0;">
	      	<thead>
		        <tr>
		            <th><?=$this->lang->line('slno')?></th>
		            <th><?=$this->lang->line('invoice_feetype')?></th>
		            <th><?=$this->lang->line('due_date')?></th>
		            <th><?=$this->lang->line('invoice_amount')?></th>
		            <th><?=$this->lang->line('invoice_discount')?></th>
		            <th><?=$this->lang->line('invoice_subtotal')?></th>
		        </tr>
	      	</thead>
	      	<tbody>
	          	<?php $subtotal = 0; $totalsubtotal = 0; $i = 1; if(count($invoices)) { foreach($invoices as $invoice) { $discount = 0; if($invoice->discount > 0) { $discount = (($invoice->amount/100) * $invoice->discount); } $subtotal = ($invoice->amount - $discount); $totalsubtotal += $subtotal;  ?>
		            <tr>
		                <td data-title="<?=$this->lang->line('slno')?>">
		                    <?php echo $i; ?>
		                </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_feetype')?>">
		                    <?=isset($feetypes[$invoice->feetypeID]) ? $feetypes[$invoice->feetypeID] : ''?>
						</td>
						
		                <td data-title="<?=$this->lang->line('due_date')?>">
						<?= date('d M Y', strtotime($invoice->due_date)) ?>
		                </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_amount')?>">
		                    <?=number_format($invoice->amount, 2)?> <?=$invoice->currency?>
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
			<tbody>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (USD)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[0], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (GBP)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[1], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_totalamount')?> (TZS)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[2], 2)?></b></td>
				</tr>
				<?php if ($maininvoice->maininvoicetype == 'invoice') { ?>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (USD)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[0], 2)?></b></td>
				</tr> 
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (GBP)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[1], 2)?></b></td>
				</tr> 
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_paid')?> (TZS)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['totalpayment'])[2], 2)?></b></td>
				</tr> 
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (USD)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[0] - explode(",",$grandtotalandpayment['totalpayment'])[0], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (GBP)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[1] - explode(",",$grandtotalandpayment['totalpayment'])[1], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_balance');?> (TZS)</b></span></td>
					<td><b><?=number_format(explode(",",$grandtotalandpayment['grandtotal'])[2] - explode(",",$grandtotalandpayment['totalpayment'])[1], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (USD)</b></span></td>
					<td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (GBP)</b></span></td>
					<td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
				</tr>
				<tr>
					<td colspan="5"><span class="pull-right"><b><?=$this->lang->line('invoice_fine');?> (TZS)</b></span></td>
					<td><b><?=number_format($grandtotalandpayment['totalfine'], 2)?></b></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
		<table class="installment-table table-bordered" width="100%" style="margin-top: 0;">
			<!-- Terms and Conditions -->
			<thead>
				<tr>
					<th colspan="5" class="col-lg-2" style="line-height:0.5;" ><?=$this->lang->line('invoice_terms')?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td colspan="5">
						<ol  style="text-align:left;">
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
								Payment should be deposited on or before the installments due date.
							</li>
							<li>
								All paying slips should be submitted to the accounts department before the due date and receipt collected for the payment done.
							</li>
							<li>
								All paying slips should bear the name and class of the child.
							</li>
							<li>
								For family discount contact the accounts department. Discount applies to TZS fees only.
							</li>
							<li>
								Fees once paid are not refundable under any circumstances.
							</li>
						</ol>
					</td>
				</tr>
			</tbody>
			<!-- End of Terms and Conditions -->
		</table>
  	</div>
</body>
</html>