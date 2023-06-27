<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <?=reportheader($siteinfos, $schoolyearsessionobj, true); ?>
        <h3 style="margin-bottom: 0px;"><?=$this->lang->line('productpurchasereport_report_for')?> - <?=$this->lang->line('productpurchasereport_product_purchase')?> </h3>
        <div>
            <?php if($fromdate != '' && $todate != '' ) { ?>
                <h5 class="pull-left">
                    <?=$this->lang->line('productpurchasereport_fromdate')?> : <?=date('d M Y',$fromdate)?></p>
                </h5>
                <h5 class="pull-right">
                    <?=$this->lang->line('productpurchasereport_todate')?> : <?=date('d M Y',$todate)?></p>
                </h5>
            <?php } elseif($statusID != 0 ) { ?>
                <h5 class="pull-left">
                    <?php
                        echo $this->lang->line('productpurchasereport_status')." : ";
                        if($statusID == 1) {
                            echo $this->lang->line("productpurchasereport_pending");
                        } elseif($statusID == 2) {
                            echo $this->lang->line("productpurchasereport_partial");
                        } elseif($statusID == 3) {
                            echo $this->lang->line("productpurchasereport_fully_paid");
                        } elseif($statusID == 4) {
                            echo $this->lang->line("productpurchasereport_refund");
                        }
                    ?>
                </h5>
            <?php } elseif($reference_no != '0') { ?>
                <h5 class="pull-left">
                    <?php
                        echo $this->lang->line('productpurchasereport_referenceNo')." : ";
                        echo $reference_no;
                    ?>
                </h5>
            <?php } elseif($productsupplierID != 0 && $productwarehouseID != 0 ) { ?>
                <h5 class="pull-left">
                    <?php
                        echo $this->lang->line('productpurchasereport_supplier')." : ";
                        echo isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                    ?>
                </h5>
                <h5 class="pull-right">
                    <?php
                        echo $this->lang->line('productpurchasereport_warehouse')." : ";
                        echo isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';
                    ?>
                </h5>
            <?php } elseif($productsupplierID != 0) { ?>
                <h5 class="pull-left">
                    <?php
                        echo $this->lang->line('productpurchasereport_supplier')." : ";
                        echo isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                    ?>
                </h5>
            <?php } elseif($productwarehouseID != 0) { ?>
                <h5 class="pull-left">
                    <?php
                        echo $this->lang->line('productpurchasereport_warehouse')." : ";
                        echo isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';
                    ?>
                </h5>
            <?php } ?>
        </div>
        <?php if (count($productpurchases)) { ?>
            <table>
                <thead>
                    <tr>
                        <th><?=$this->lang->line('slno')?></th>
                        <th><?=$this->lang->line('productpurchasereport_referenceNo')?></th>
                        <th><?=$this->lang->line('productpurchasereport_supplier')?></th>
                        <th><?=$this->lang->line('productpurchasereport_warehouse')?></th>
                        <th><?=$this->lang->line('productpurchasereport_date')?></th>
                        <th><?=$this->lang->line('productpurchasereport_total')?></th>
                        <th><?=$this->lang->line('productpurchasereport_paid')?></th>
                        <th><?=$this->lang->line('productpurchasereport_balance')?></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i=1;
                    foreach($productpurchases as $productpurchase) { ?>
                        <tr>
                            <td><?=$i?></td>
                            <td><?=$productpurchase['reference_no'];?></td>
                            <td><?=$productpurchase['supplier'];?></td>
                            <td><?=$productpurchase['warehouse'];?></td>
                            <td><?=$productpurchase['date'];?></td>
                            <td><?=number_format($productpurchase['total'],2);?></td>
                            <td><?=number_format($productpurchase['paid'],2);?></td>
                            <td><?=number_format($productpurchase['balance'],2);?></td>
                        </tr>
                    <?php $i++; } ?>
                    <tr>
                        <td colspan="5" class="text-right text-bold"><?=$this->lang->line('productpurchasereport_grandtotal')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                        <td class="text-bold"><?=number_format($totalproductpurchaseprice,2)?></td>
                        <td class="text-bold"><?=number_format($totalproductpurchasepaidamount,2)?></td>
                        <td class="text-bold"><?=number_format($totalproductpurchasebalanceamount,2)?></td>
                    </tr>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="notfound">
                <?=$this->lang->line('productpurchasereport_data_not_found')?>
            </div>
        <?php } ?>
    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
</body>
</html>