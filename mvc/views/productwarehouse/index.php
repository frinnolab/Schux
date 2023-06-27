<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productwarehouse"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('productwarehouse_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('productwarehouse/add') ?>">
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
                                <th><?=$this->lang->line('productwarehouse_name')?></th>
                                <th><?=$this->lang->line('productwarehouse_code')?></th>
                                <th><?=$this->lang->line('productwarehouse_email')?></th>
                                <th><?=$this->lang->line('productwarehouse_phone')?></th>
                                <th><?=$this->lang->line('productwarehouse_address')?></th>
                                <?php if(permissionChecker('productwarehouse_edit') || permissionChecker('productwarehouse_delete')) { ?>
                                    <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($productwarehouses)) {$i = 1; foreach($productwarehouses as $productwarehouse) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productwarehouse_name')?>">
                                        <?=$productwarehouse->productwarehousename;?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('productwarehouse_code')?>">
                                        <?=$productwarehouse->productwarehousecode;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productwarehouse_email')?>">
                                        <?=$productwarehouse->productwarehouseemail;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productwarehouse_phone')?>">
                                        <?=$productwarehouse->productwarehousephone;?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productwarehouse_address')?>">
                                        <?=$productwarehouse->productwarehouseaddress;?>
                                    </td>
                                    <?php if(permissionChecker('productwarehouse_edit') || permissionChecker('productwarehouse_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('productwarehouse/edit/'.$productwarehouse->productwarehouseID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('productwarehouse/delete/'.$productwarehouse->productwarehouseID, $this->lang->line('delete')) ?>
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