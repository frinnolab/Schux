
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pagelines"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_activitiescategory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('activitiescategory_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('activitiescategory/add') ?>">
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
                                <th><?=$this->lang->line('activitiescategory_title')?></th>
                                <?php if(permissionChecker('activitiescategory_edit') || permissionChecker('activitiescategory_delete')) { ?>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($activitiescategorys)) {$i = 1; foreach($activitiescategorys as $activitiescategory) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('activitiescategory_title')?>">
                                        <?php echo $activitiescategory->title; ?>
                                    </td>
                                    <?php if(permissionChecker('activitiescategory_edit') || permissionChecker('activitiescategory_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('activitiescategory/edit/'.$activitiescategory->activitiescategoryID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('activitiescategory/delete/'.$activitiescategory->activitiescategoryID, $this->lang->line('delete')) ?>
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