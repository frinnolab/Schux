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
          <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("productpurchase_create_date")." : ". date("d M Y"); ?></h5>
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
                    <th class="site-header-title-float"><?php  echo $this->lang->line("productpurchase_from"); ?></th>
                </tr>
                <?php if(count($productsupplier)) { ?>
                    <tr>
                        <td><?=$productsupplier->productsuppliercompanyname?></td>
                    </tr>
                    <tr>
                        <td><?=$productsupplier->productsupplieraddress?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("productpurchase_phone"). " : ". $productsupplier->productsupplierphone?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("productpurchase_email"). " : ". $productsupplier->productsupplieremail?></td>
                    </tr>
                <?php } ?>
            </tbody>
          </table>
        </td>
        <td width="33%">
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float"><?php  echo $this->lang->line("productpurchase_to"); ?></th>
                  </tr>

                  <tr>
                      <td><?=$siteinfos->sname?></td>
                  </tr>
                  <?php if(count($productwarehouse)) { ?>
                      <tr>
                          <td><?=$this->lang->line("productpurchase_warehouse_name"). " : ". $productwarehouse->productwarehousename?></td>
                      </tr>
                      <tr>
                          <td><?=$productwarehouse->productwarehouseaddress?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("productpurchase_phone"). " : ". $productwarehouse->productwarehousephone?></td>
                      </tr>
                      <tr>
                          <td><?=$this->lang->line("productpurchase_email"). " : ". $productwarehouse->productwarehouseemail?></td>
                      </tr>
                  <?php } ?>
              </tbody>
            </table>
        </td>
        <td width="34%" style="vertical-align: text-top;">
          <table>
            <tbody>
              <tr>
                <td><?php echo $this->lang->line("productpurchase_referenceno"). " : " . $productpurchase->productpurchasereferenceno; ?></td>
              </tr>
              <tr>
                <td>
                  <?php 
                      $status = $productpurchase->productpurchasestatus;
                      $setButton = '';
                      if($status == 0) {
                          $status = $this->lang->line('productpurchase_pending');
                          $setButton = 'text-red';
                      } elseif($status == 1) {
                          $status = $this->lang->line('productpurchase_partial_paid');
                          $setButton = 'text-yellow';
                      } elseif($status == 2) {
                          $status = $this->lang->line('productpurchase_fully_paid');
                          $setButton = 'text-green';
                      }
                      echo $this->lang->line('productpurchase_payment_status'). " : ".$status;
                  ?>
                  <?php if($productpurchase->productpurchaserefund == 1) { ?>
                      <p class="refund"><?=$this->lang->line('productpurchase_refund')?></p>
                  <?php } ?>
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
            <th><?=$this->lang->line('productpurchase_description')?></th>
            <th><?=$this->lang->line('productpurchase_unit_price')?></th>
            <th><?=$this->lang->line('productpurchase_quantity')?></th>
            <th><?=$this->lang->line('productpurchase_subtotal')?></th>
        </tr>
      </thead>
      <tbody>
          <?php $subtotal = 0; $totalsubtotal = 0; if(count($productpurchaseitems)) { $i=1; foreach ($productpurchaseitems as $productpurchaseitem) { $subtotal = ($productpurchaseitem->productpurchaseunitprice * $productpurchaseitem->productpurchasequantity); $totalsubtotal += $subtotal; ?>
            <tr>
                <td data-title="<?=$this->lang->line('slno')?>">
                    <?php echo $i; ?>
                </td>
                
                <td data-title="<?=$this->lang->line('productpurchase_description')?>">
                    <?=isset($products[$productpurchaseitem->productID]) ? $products[$productpurchaseitem->productID] : ''?>
                </td>
                
                <td data-title="<?=$this->lang->line('productpurchase_unit_price')?>">
                    <?=number_format($productpurchaseitem->productpurchaseunitprice, 2)?>
                </td>

                <td data-title="<?=$this->lang->line('productpurchase_quantity')?>">
                    <?=number_format($productpurchaseitem->productpurchasequantity, 2)?>
                </td>
                <td data-title="<?=$this->lang->line('productpurchase_subtotal')?>">
                    <?=number_format($subtotal, 2)?>
                </td>
            </tr>
          <?php $i++; } } ?>
      </tbody>
      <tfoot>
          <tr>
              <td class="pull-right" colspan="4"><b><?=$this->lang->line('productpurchase_subtotal_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($totalsubtotal, 2)?></b></td>
          </tr>
          <tr>
              <td colspan="4" class="pull-right"><b><?=$this->lang->line('productpurchase_discount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($productpurchase->purchase_product_discount, 2)?></b></td>
          </tr>
          <tr>
            <td colspan="4" class="pull-right"><b><?=$this->lang->line('productpurchase_vat')?>(<?= $productpurchase->purchase_product_vat_percentage ?>%)</td>
            <td><b><?=number_format($productpurchase->purchase_product_vat_amount,2)?></b></td>
          </tr>
          <tr>
              <td colspan="4" class="pull-right"><b><?=$this->lang->line('productpurchase_total_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($totalsubtotal + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount, 2)?></b></td>
          </tr>
          <tr>
              <td class="pull-right" colspan="4"><b><?=$this->lang->line('productpurchase_paid')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($productpurchasepaid->productpurchasepaidamount, 2)?></b></td>
          </tr> 
          <tr>
            <td colspan="4" class="pull-right"><b><?=$this->lang->line('productpurchase_balance');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
            <td><b><?=number_format(($totalsubtotal + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount - $productpurchasepaid->productpurchasepaidamount), 2)?></b></td>
          </tr>
      </tfoot>
    </table>
   
    <table width="100%">
        <tr>
            <td width="65%" >
                <p><?=$productpurchase->productpurchasedescription?></p>
            </td>
            <td width="35%">
                <table>
                    <tr>
                        <td><?=$this->lang->line('productpurchase_create_by')?> : <?=$createuser?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('productpurchase_date')?> : <?=date('d M Y', strtotime($productpurchase->create_date))?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>