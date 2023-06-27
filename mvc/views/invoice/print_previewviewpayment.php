

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
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
					<h5 class="top-site-header-create-title"><?php  echo $this->lang->line("invoice_create_date")." : ". date("d M Y"); ?></h5>
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
	                    		<th class="site-header-title-float"><?php  echo $this->lang->line("invoice_from"); ?></th>
	                		</tr>
			                <?php if(count($siteinfos)) { ?>
			                    <tr>
			                        <td><?=$siteinfos->sname?></td>
			                    </tr>
			                    <tr>
			                        <td><?=$siteinfos->address?></td>
			                    </tr>
			                    <tr>
			                        <td><?=$this->lang->line("invoice_phone"). " : ". $siteinfos->phone?></td>
			                    </tr>
			                    <tr>
			                        <td><?=$this->lang->line("invoice_email"). " : ". $siteinfos->email?></td>
			                    </tr>
			                <?php } ?>
	            		</tbody>
	          		</table>
	        	</td>
	        	<td width="33%">
	            	<table >
	              		<tbody>
	              			<tr>
			                    <th class="site-header-title-float"><?php  echo $this->lang->line("invoice_to"); ?></th>
			                </tr>
			                <tr>
			                    <td><?php  echo $studentrelation->srname; ?></td>
			                </tr>
			                <tr>
			                    <td><?php  echo $this->lang->line("invoice_roll"). " : ". $studentrelation->srroll; ?></td>
			                </tr>
			                <tr>
			                    <td><?php  echo $this->lang->line("invoice_classesID"). " : ". $studentrelation->srclasses; ?></td>
			                </tr>
			                <tr>
			                    <td><?php  echo $this->lang->line("student_registerNO"). " : ". $studentrelation->srregisterNO; ?></td>
			                </tr>
			                <?php if(count($student)) { ?>
				                <tr>
				                  <td><?=$this->lang->line("invoice_email"). " : ". $student->email?></td>
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
		                			<?=$this->lang->line("invoice_invoice_number").' : '.'<b>INV-S-'.$globalpayment->globalpaymentID.'</b>'?>
		                		</td>
		              		</tr>
		              		<tr>
		                		<td>
				                  	<?=$this->lang->line('invoice_paymentmethod').' : '.$paymenttype?>
		                		</td>
		              		</tr>
		            	</tbody>
		          	</table>
	        	</td>
	      	</tr>
	    </table>
	    <br>

	    <table class="table table-bordered">
	      	<thead>
		        <tr>
		            <th><?=$this->lang->line('slno')?></th>
		            <th><?=$this->lang->line('invoice_feetype')?></th>
		            <th><?=$this->lang->line('invoice_amount')?></th>
		            <th><?=$this->lang->line('invoice_weaver')?></th>
		            <th><?=$this->lang->line('invoice_fine')?></th>
		            <th><?=$this->lang->line('invoice_subtotal')?></th>
		        </tr>
	      	</thead>
	      	<tbody>
	          	<?php $totalamount = 0; $totalamount = 0; $totalweaver = 0; $totalfine = 0; $totalsubtotal = 0; $paymentDate = date('Y-m-d'); $paymentUserTypeID = 0; $paymentUserID = 0; $i = 1; if(count($payments)) { foreach($payments as $payment) { if($payment->paymentamount != '' || (isset($weaverandfines[$payment->paymentID]))) { $paymentDate = $payment->paymentdate; $paymentUserTypeID = $payment->usertypeID; $paymentUserID = $payment->userID;  ?>
		            <tr>
		                <td data-title="<?=$this->lang->line('slno')?>">
                            <?php echo $i; ?>
                        </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_feetype')?>">
		                    <?php
                                if(isset($invoices[$payment->invoiceID])) {
                                    if(isset($feetypes[$invoices[$payment->invoiceID]->feetypeID])) {
                                        echo $feetypes[$invoices[$payment->invoiceID]->feetypeID];
                                    }
                                }
                            ?>
		                </td>
		                
		                <td data-title="<?=$this->lang->line('invoice_amount')?>">
		                    <?php
                                $totalamount += $payment->paymentamount;
                                echo number_format($payment->paymentamount, 2);
                            ?>
		                </td>

		                <td data-title="<?=$this->lang->line('invoice_weaver')?>">
		                    <?php
                                if(isset($weaverandfines[$payment->paymentID])) {
                                    $totalweaver += $weaverandfines[$payment->paymentID]->weaver;
                                    echo number_format($weaverandfines[$payment->paymentID]->weaver, 2);
                                } else {
                                    echo number_format(0, 2);
                                }
                            ?>
		                </td>
		                <td data-title="<?=$this->lang->line('invoice_fine')?>">
		                   <?php
                                if(isset($weaverandfines[$payment->paymentID])) {
                                    $totalfine += $weaverandfines[$payment->paymentID]->fine;
                                    echo number_format($weaverandfines[$payment->paymentID]->fine, 2);
                                } else {
                                    echo number_format(0, 2);
                                }
                            ?>
		                </td>
		                <td data-title="<?=$this->lang->line('invoice_subtotal')?>">
		                    <?php
                                $subtotal = $payment->paymentamount;
                                if(isset($weaverandfines[$payment->paymentID])) {
                                    $subtotal += $weaverandfines[$payment->paymentID]->fine;
                                }
                                $totalsubtotal += $subtotal;
                                echo number_format($subtotal, 2);
                            ?>
		                </td>
		            </tr>
	          	<?php $i++; } } } ?>
	      	</tbody>

	      	<tfoot>
                <tr>
                    <td colspan="2"><span class="pull-right"><b><?=$this->lang->line('invoice_total')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                    <td><b><?=number_format($totalamount, 2)?></b></td>
                    <td><b><?=number_format($totalweaver, 2)?></b></td>
                    <td><b><?=number_format($totalfine, 2)?></b></td>
                    <td><b><?=number_format($totalsubtotal, 2)?></b></td>
                </tr>
            </tfoot>
	    </table>

	    <table width="100%">
        	<tr>
	            <td width="65%" >
	                <p><?=$globalpayment->invoicedescription?></p>
	            </td>
	            <td width="35%">
	                <table>
	                    <tr>
	                        <td><?=$this->lang->line('invoice_create_by')?> : <?=getNameByUsertypeIDAndUserID($paymentUserTypeID, $paymentUserID)?></td>
	                    </tr>
	                    <tr>
	                        <td><?=$this->lang->line('invoice_date')?> : <?=date('d M Y', strtotime($paymentDate))?></td>
	                    </tr>
	                </table>
	            </td>
	        </tr>
	    </table>
  	</div>
</body>
</html>