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
          <h5 class="top-site-header-create-title"><?php  echo $this->lang->line("productsale_create_date")." : ". date("d M Y"); ?></h5>
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
                    <th class="site-header-title-float"><?php  echo $this->lang->line("productsale_from"); ?></th>
                </tr>
                <?php if(count($siteinfos)) { ?>
                    <tr>
                        <td><?=$siteinfos->sname?></td>
                    </tr>
                    <tr>
                        <td><?=$siteinfos->address?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("productsale_phone"). " : ". $siteinfos->phone?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line("productsale_email"). " : ". $siteinfos->email?></td>
                    </tr>
                <?php } ?>
            </tbody>
          </table>
        </td>
        <td width="33%">
            <table >
              <tbody>
                  <tr>
                      <th class="site-header-title-float"><?php  echo $this->lang->line("productsale_to"); ?></th>
                  </tr>
                  <?php if(count($user)) { ?>
                      <?php if(isset($user->name)) { ?>
                          <tr>
                              <td><?=$user->name?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></td>
                          </tr>
                          <tr>
                              <td><?=$user->address?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_phone"). " : ". $user->phone?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_email"). " : ". $user->email?></td>
                          </tr>
                      <?php } else { ?>
                          <tr>
                              <td><?=$user->srname?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[3]) ? $usertypes[3] : ''?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_phone"). " : "?></td>
                          </tr>
                          <tr>
                              <td><?=$this->lang->line("productsale_email"). " : "?></td>
                          </tr>
                      <?php } ?>
                  <?php } ?>
              </tbody>
            </table>
        </td>
        <td width="34%" style="vertical-align: text-top;">
          <table>
            <tbody>
              <tr>
                <td><?php echo $this->lang->line("productsale_referenceno"). " : " . $productsale->productsalereferenceno; ?></td>
              </tr>
              <tr>
                <td>
                  <?php 
                      $status = $productsale->productsalestatus;
                      $setButton = '';
                      if($status == 1) {
                          $status = $this->lang->line('productsale_pending');
                          $setButton = 'text-red';
                      } elseif($status == 3) {
                          $status = $this->lang->line('productsale_partial_paid');
                          $setButton = 'text-yellow';
                      } elseif($status == 3) {
                          $status = $this->lang->line('productsale_fully_paid');
                          $setButton = 'text-green';
                      }
                      echo $this->lang->line('productsale_payment_status'). " : ".$status;
                  ?>

                  <?php if($productsale->productsalerefund == 1) { ?>
                      <p class="refund"><?=$this->lang->line('productsale_refund')?></p>
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
            <th><?=$this->lang->line('productsale_description')?></th>
            <th><?=$this->lang->line('productsale_unit_price')?></th>
            <th><?=$this->lang->line('productsale_quantity')?></th>
            <th><?=$this->lang->line('productsale_subtotal')?></th>
        </tr>
      </thead>
      <tbody>
          <?php $subtotal = 0; $totalsubtotal = 0; if(count($productsaleitems)) { $i=1; foreach ($productsaleitems as $productsaleitem) { $subtotal = ($productsaleitem->productsaleunitprice * $productsaleitem->productsalequantity); $totalsubtotal += $subtotal; ?>
            <tr>
                <td data-title="<?=$this->lang->line('slno')?>">
                    <?php echo $i; ?>
                </td>
                
                <td data-title="<?=$this->lang->line('productsale_description')?>">
                    <?=isset($products[$productsaleitem->productID]) ? $products[$productsaleitem->productID] : ''?>
                </td>
                
                <td data-title="<?=$this->lang->line('productsale_unit_price')?>">
                    <?=number_format($productsaleitem->productsaleunitprice, 2)?>
                </td>

                <td data-title="<?=$this->lang->line('productsale_quantity')?>">
                    <?=number_format($productsaleitem->productsalequantity, 2)?>
                </td>
                <td data-title="<?=$this->lang->line('productsale_subtotal')?>">
                    <?=number_format($subtotal, 2)?>
                </td>
            </tr>
          <?php $i++; } } ?>
      </tbody>
      <tfoot>
          <tr>
              <td class="pull-right" colspan="4"><b><?=$this->lang->line('productsale_total_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($totalsubtotal, 2)?></b></td>
          </tr>
          <tr>
              <td class="pull-right" colspan="4"><b><?=$this->lang->line('productsale_paid')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format($productsalepaid->productsalepaidamount, 2)?></b></td>
          </tr> 
          <tr>
              <td class="pull-right" colspan="4"><b><?=$this->lang->line('productsale_balance');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></td>
              <td><b><?=number_format(($totalsubtotal - $productsalepaid->productsalepaidamount), 2)?></b></td>
          </tr>
      </tfoot>
    </table>
   
    <table width="100%">
        <tr>
            <td width="65%" >
                <p><?=$productsale->productsaledescription?></p>
            </td>
            <td width="35%">
                <table>
                    <tr>
                        <td><?=$this->lang->line('productsale_create_by')?> : <?=$createuser?></td>
                    </tr>
                    <tr>
                        <td><?=$this->lang->line('productsale_date')?> : <?=date('d M Y', strtotime($productsale->create_date))?></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
  </div>
</body>
</html>