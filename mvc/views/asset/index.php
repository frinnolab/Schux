<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-fax"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('asset_add')) {
                ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('asset/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('asset_serial')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_description')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_categoryID')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_locationID')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('asset_quantity')?></th>
                                <?php if(permissionChecker('asset_edit') || permissionChecker('asset_delete') || permissionChecker('asset_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($assets)) {
                                $i = 1;
                                $n = 0;
                                foreach($assets as $asset) { 
                                    $asset_stock_quantity = $asset_purchased_quantity[$n]->quantity - $asset_assigned_quantity[$n]->quantity;
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_serial')?>">
                                        <?php echo $asset->serial; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_description')?>">
                                        <?php echo $asset->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_status')?>">
                                        <?php
                                            if($asset->status==1) {
                                                echo $this->lang->line('asset_status_checked_out');
                                            } else {
                                                echo $this->lang->line('asset_status_checked_in');
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_categoryID')?>">
                                        <?php echo $asset->category; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_locationID')?>">
                                        <?php echo namesorting($asset->location, 20); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_quantity')?>">
                                        <?= $asset_stock_quantity ?>
                                    </td>
                                    <?php if(permissionChecker('asset_edit') || permissionChecker('asset_delete') || permissionChecker('asset_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('asset/view/'.$asset->assetID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('asset/edit/'.$asset->assetID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset/delete/'.$asset->assetID, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; $n++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>