<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>


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
//                    if(permissionChecker('exam_type_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('exam_type/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
<!--                --><?php //} ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('exam_type_title')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('exam_type_typeNumber')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('exam_type_status')?></th>
<!--                                --><?php //if(permissionChecker('exam_type_edit') || permissionChecker('exam_type_delete') || permissionChecker('exam_type_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
<!--                                --><?php //} ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($exam_types)) {$i = 1; foreach($exam_types as $exam_type) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_type_title')?>">
                                        <?php
                                            if(strlen($exam_type->title) > 25)
                                                echo strip_tags(substr($exam_type->title, 0, 25)."...");
                                            else
                                                echo strip_tags(substr($exam_type->title, 0, 25));
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_type_typeNumber')?>">
                                        <?php echo $exam_type->examTypeNumber; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('exam_type_status')?>">
                                        <?php echo $exam_type->status; ?>
                                    </td>
<!--                                    --><?php //if(permissionChecker('exam_type_edit') || permissionChecker('exam_type_delete') || permissionChecker('exam_type_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit_show('exam_type/edit/'.$exam_type->onlineExamTypeID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete_show('exam_type/delete/'.$exam_type->onlineExamTypeID, $this->lang->line('delete')) ?>
                                        </td>
<!--                                    --><?php //} ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>