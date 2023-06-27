<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productsupplier"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('productsupplier_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('productsupplier/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('productsupplier_companyname')?></th>
                                <th><?=$this->lang->line('productsupplier_suppliername')?></th>
                                <th><?=$this->lang->line('productsupplier_tin')?></th>
                                <th><?=$this->lang->line('productsupplier_vrn')?></th>
                                <th><?=$this->lang->line('productsupplier_email')?></th>
                                <th><?=$this->lang->line('productsupplier_phone')?></th>
                                <th><?=$this->lang->line('productsupplier_alternate_phone')?></th>
                                <th><?=$this->lang->line('productsupplier_address')?></th>
                                <?php if(permissionChecker('productsupplier_edit') || permissionChecker('productsupplier_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($suppliers)) {$i = 1; foreach($suppliers as $supplier) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_companyname')?>">
                                        <?=$supplier->productsuppliercompanyname;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_suppliername')?>">
                                        <?=$supplier->productsuppliername;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_tin')?>">
                                        <?=$supplier->productsuppliertin;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_vrn')?>">
                                        <?=$supplier->productsuppliervrn;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_email')?>">
                                        <?=$supplier->productsupplieremail;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_phone')?>">
                                        <?=$supplier->productsupplierphone;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_alternate_phone')?>">
                                        <?=$supplier->productsupplieralternatephone;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsupplier_address')?>">
                                        <?=$supplier->productsupplieraddress;?>
                                    </td>
                                    <?php if(permissionChecker('productsupplier_edit') || permissionChecker('productsupplier_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('productsupplier/edit/'.$supplier->productsupplierID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('productsupplier/delete/'.$supplier->productsupplierID, $this->lang->line('delete')) ?>
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