<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leavecategory"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_leavecategory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('leavecategory_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('leavecategory/add') ?>">
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
                                <th><?=$this->lang->line('leavecategory_category')?></th>
                                <?php if(permissionChecker('leavecategory_edit') || permissionChecker('leavecategory_delete')) { ?>
                                    <th class="col-md-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($leave_categories)) {$i = 1; foreach($leave_categories as $leavecategory) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leavecategory_category')?>">
                                        <?php
                                            if(strlen($leavecategory->leavecategory) > 25)
                                                echo strip_tags(substr($leavecategory->leavecategory, 0, 25)."...");
                                            else
                                                echo strip_tags(substr($leavecategory->leavecategory, 0, 25));
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('leavecategory_edit') || permissionChecker('leavecategory_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('leavecategory/edit/'.$leavecategory->leavecategoryID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('leavecategory/delete/'.$leavecategory->leavecategoryID, $this->lang->line('delete')) ?>
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