<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
<?=reportheader($siteinfos, $schoolyearsessionobj, true)?>
<h3 style="margin-bottom: 0px;"><?=$this->lang->line('productsalereport_report_for')?> - <?=$this->lang->line('productsalereport_productsale')?> </h3>
    <?php if($fromdate != '' && $todate != '' ) { ?>
        <div>
            <h5 class="pull-left">
                <?=$this->lang->line('productsalereport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
            </h5>
            <h5 class="pull-right">
                <?=$this->lang->line('productsalereport_todate')?> : <?=date('d M Y',strtotime($todate))?></p>
            </h5>
        </div>
    <?php } elseif($statusID != 0 ) { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('productsalereport_status')." : ";
                    if($statusID == 1) {
                        echo $this->lang->line("productsalereport_pending");
                    } elseif($statusID == 2) {
                        echo $this->lang->line("productsalereport_partial");
                    } elseif($statusID == 3) {
                        echo $this->lang->line("productsalereport_fully_paid");
                    } elseif($statusID == 4) {
                        echo $this->lang->line("productsalereport_refund");
                    }
                ?>
            </h5>
        </div>
    <?php } elseif($reference_no != '0') { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('productsalereport_referenceNo')." : ";
                    echo $reference_no;
                ?>
            </h5>
        </div>
    <?php } elseif($productsalecustomertypeID != 0 && $productsalecustomerID != 0 ) { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('productsalereport_role')." : ";
                    echo isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : '';
                ?>
            </h5>
            <h5 class="pull-right">
                <?php
                    echo $this->lang->line('productsalereport_user')." : ";
                    if(isset($users[3][$productsalecustomerID])) {
                        $userName = isset($users[3][$productsalecustomerID]->name) ? $users[3][$productsalecustomerID]->name : $users[3][$productsalecustomerID]->srname;
                        echo $userName;
                    }
                ?>
            </h5>
        </div>
    <?php } else { ?>
        <div>
            <h5 class="pull-left">
                <?php
                    echo $this->lang->line('productsalereport_role')." : ";
                     echo isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : $this->lang->line('productsalereport_all');
                ?>
            </h5>
        </div>
    <?php } ?>
    <div style="margin-top:0px">
        <?php if (count($productsales)) { ?>
            <table id="example1" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th><?=$this->lang->line('slno')?></th>
                        <th><?=$this->lang->line('productsalereport_referenceNo')?></th>
                        <th><?=$this->lang->line('productsalereport_role')?></th>
                        <th><?=$this->lang->line('productsalereport_user')?></th>
                        <th><?=$this->lang->line('productsalereport_product_item')?></th>
                        <th><?=$this->lang->line('productsalereport_product_item_quantity')?></th>
                        <th><?=$this->lang->line('productsalereport_date')?></th>
                        <th><?=$this->lang->line('productsalereport_total')?></th>
                        <th><?=$this->lang->line('productsalereport_paid')?></th>
                        <th><?=$this->lang->line('productsalereport_balance')?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $i=1;
                        foreach($productsales as $productsale) { ?>
                            <tr>
                                <td><?=$i?></td>
                                <td>
                                    <?=$productsale['productsalereferenceno'];?>
                                </td>
                                <td>
                                    <?=$productsale['productsalecustomertype'];?>
                                </td>
                                <td>
                                   <?=$productsale['productsalecustomerName'];?>
                                </td>
                                <td>
                                   <?=$productsale['productname'];?>
                                </td>
                                <td>
                                   <?=$productsale['productsalequantity'];?>
                                </td>
                                <td>
                                    <?=$productsale['productsaledate'];?>
                                </td>
                                <td>
                                    <?=number_format($productsale['productsaleprice'],2);?>
                                </td>
                                <td>
                                    <?=number_format($productsale['productsalepaidamount'],2);?>
                                </td>
                                <td>
                                    <?=number_format($productsale['productsalebalanceamount'],2);?>
                                </td>

                            </tr>
                        <?php $i++; }  ?>
                        <tr>
                            <td colspan="7" class="text-right text-bold"><?=$this->lang->line('productsalereport_grandtotal')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                            <td class="text-bold"><?=number_format($totalproductsaleprice,2)?></td>
                            <td class="text-bold"><?=number_format($totalproductsalepaidamount,2)?></td>
                            <td class="text-bold"><?=number_format($totalproductsalebalanceamount,2)?></td>
                        </tr>
                </tbody>
            </table>
        <?php } else { ?>
        <div class="notfound">
            <?=$this->lang->line('productsalereport_data_not_found')?>
        </div>
        <?php } ?>
    </div>
<?=reportfooter($siteinfos, $schoolyearsessionobj)?>
</body>
</html>