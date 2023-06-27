<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productcategory"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('productcategory_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('productcategory/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('productcategory_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('productcategory_desc')?></th>
                                <?php if(permissionChecker('productcategory_edit') || permissionChecker('productcategory_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($productcategorys)) {$i = 1; foreach($productcategorys as $productcategory) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productcategory_name')?>">
                                        <?=$productcategory->productcategoryname?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productcategory_desc')?>">
                                        <?=$productcategory->productcategorydesc?>
                                    </td>
                                    <?php if(permissionChecker('productcategory_edit') || permissionChecker('productcategory_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('productcategory/edit/'.$productcategory->productcategoryID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('productcategory/delete/'.$productcategory->productcategoryID, $this->lang->line('delete')) ?>
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