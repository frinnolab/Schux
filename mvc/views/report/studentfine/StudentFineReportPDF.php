<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
</head>
<body>
    <div style="margin-bottom: 50px;">
        <div class="row">
                <div class="col-sm-12">
	                <?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
	            </div>
	            <div class="col-sm-12" style="margin-bottom: -12px">
	                <h3><?=$this->lang->line('studentfinereport_report_for')?> - <?=$this->lang->line('studentfinereport_studentfine')?>  </h3>
	            </div>
                <?php if($fromdate !='' && $todate !='') { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left"><?=$this->lang->line('studentfinereport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></h5>                         
                                <h5 class="pull-right"><?=$this->lang->line('studentfinereport_todate')?> : <?=date('d M Y',strtotime($todate))?></h5>                        
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <div class="col-sm-12">
                <?php if(count($studentfines)) { ?>
                    <div id="hide-table">
                        <table class="table table-bordered">
                            <thead>
                            	<tr>
	                                <th><?=$this->lang->line('studentfinereport_slno');?></th>
	                                <th><?=$this->lang->line('studentfinereport_date');?></th>
	                                <th><?=$this->lang->line('studentfinereport_name');?></th>
	                                <th><?=$this->lang->line('studentfinereport_registerNO');?></th>
	                                <th><?=$this->lang->line('studentfinereport_class');?></th>
	                                <th><?=$this->lang->line('studentfinereport_section');?></th>
                                    <th><?=$this->lang->line('studentfinereport_roll');?></th>
	                                <th><?=$this->lang->line('studentfinereport_feetype');?></th>
	                                <th><?=$this->lang->line('studentfinereport_fine');?></th>
	                            </tr>
                            </thead>
                            <tbody>
                                <?php $total_fine = 0; $i=0; foreach($studentfines as $studentfine) { $i++;?>
                                    <tr>
                                        <td><?=$i?></td>
                                        <td><?=date('d M Y',strtotime($studentfine->paymentdate))?></td>
                                        <td><?=$studentfine->srname?></td>
                                        <td><?=$studentfine->srregisterNO?></td>
                                        <td>
                                            <?=isset($classes[$studentfine->srclassesID]) ? $classes[$studentfine->srclassesID] : ' '?>
                                        </td>
                                        <td>
                                            <?=isset($sections[$studentfine->srsectionID]) ? $sections[$studentfine->srsectionID] : ' '?>
                                        </td>
                                        <td><?=$studentfine->srroll?></td>
                                        <td><?=$studentfine->feetypes?></td>
                                        <td>
                                            <?php
                                                echo number_format($studentfine->fine,2);
                                                $total_fine += $studentfine->fine;
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                    <tr>
                                        <td colspan="8" style="font-weight: bold" class="text-right"><?=$this->lang->line('studentfinereport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                        <td style="font-weight: bold;"><?=number_format($total_fine,2)?></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                <?php } else {  ?>
                    <div class="notfound">
                        <p><b class="text-info"><?=$this->lang->line('studentfinereport_data_not_found')?></b></p>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12 text-center footerAll">
                <?=reportfooter($siteinfos, $schoolyearsessionobj, true)?>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</body>
</html>