<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-cart-plus"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('purchase_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('purchase/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
              <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('purchase_assetID')?></th>
                                <th class=""><?=$this->lang->line('purchase_vendorID')?></th>
                                <th class=""><?=$this->lang->line('purchase_quantity')?></th>
                                <th class=""><?=$this->lang->line('purchase_unit')?></th>
                                <th class=""><?=$this->lang->line('purchase_price')?></th>
                                <th class=""><?=$this->lang->line('purchase_date')?></th>
                                <th class=""><?=$this->lang->line('purchase_service_date')?></th>
                                <th class=""><?=$this->lang->line('purchase_expire_date')?></th>
                                <th class=""><?=$this->lang->line('purchased_by')?></th>
                              <?php if(permissionChecker('purchase_edit') || permissionChecker('purchase_delete')) { ?>
                                    <th class=""><?=$this->lang->line('action')?></th>
                              <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($purchases)) {$i = 1; foreach($purchases as $purchase) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_assetID')?>">
                                        <?php echo $purchase->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_vendorID')?>">
                                        <?php echo $purchase->productsuppliercompanyname; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_quantity')?>">
                                        <?php echo $purchase->quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_unit')?>">
                                        <?php echo isset($unit[$purchase->unit]) ? $unit[$purchase->unit] : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_price')?>">
                                        <?php echo $purchase->purchase_price; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_date')?>">
                                        <?php echo isset($purchase->purchase_date) ? date("d M Y", strtotime($purchase->purchase_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_service_date')?>">
                                        <?php echo isset($purchase->service_date) ? date("d M Y", strtotime($purchase->service_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchase_expire_date')?>">
                                        <?php echo isset($purchase->expire_date) ? date("d M Y", strtotime($purchase->expire_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('purchased_by')?>">
                                        <?php echo $purchase->purchaser_name; ?>
                                    </td>
                                  <?php if(permissionChecker('purchase_edit') || permissionChecker('purchase_delete')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('purchase/edit/'.$purchase->purchaseID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('purchase/delete/'.$purchase->purchaseID, $this->lang->line('delete')) ?>
                                            <?php
                                                if(permissionChecker('purchase_edit')) {
                                                    if($purchase->status == 1) {
                                                        echo btn_status_show('purchase/status/'.$purchase->purchaseID, $this->lang->line('purchase_status_approved'));
                                                    } else {
                                                        echo btn_not_status_show('purchase/status/'.$purchase->purchaseID, $this->lang->line('purchase_status_not_approved'));
                                                    }
                                                }
                                            ?>
                                        </td>
                                  <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>