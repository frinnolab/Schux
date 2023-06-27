<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-rss"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_vendor')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('vendor_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('vendor/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
              <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('vendor_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('vendor_email')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('vendor_phone')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('vendor_contact_name')?></th>
                                <?php if(permissionChecker('vendor_edit') || permissionChecker('vendor_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($vendors)) {$i = 1; foreach($vendors as $vendor) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('vendor_name')?>">
                                        <?=$vendor->name?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('vendor_email')?>">
                                        <?=$vendor->email?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('vendor_phone')?>">
                                        <?=$vendor->phone?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('vendor_contact_name')?>">
                                        <?=$vendor->contact_name?>
                                    </td>
                                    <?php if(permissionChecker('vendor_edit') || permissionChecker('vendor_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('vendor/edit/'.$vendor->vendorID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('vendor/delete/'.$vendor->vendorID, $this->lang->line('delete')) ?>
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