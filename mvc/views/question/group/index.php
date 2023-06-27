
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-question-circle"></i> <?=$this->lang->line('panel_title')?></h3>

       
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
                    if(permissionChecker('question_group_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('question_group/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('question_group_title')?></th>
                                <?php if(permissionChecker('question_group_edit') || permissionChecker('question_group_delete') || permissionChecker('question_group_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($groups)) {$i = 1; foreach($groups as $group) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('question_group_title')?>">
                                        <?php 
                                            if(strlen($group->title) > 25)
                                                echo strip_tags(substr($group->title, 0, 25)."...");
                                            else 
                                                echo strip_tags(substr($group->title, 0, 25));
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('question_group_edit') || permissionChecker('question_group_delete') || permissionChecker('question_group_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('question_group/edit/'.$group->questionGroupID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('question_group/delete/'.$group->questionGroupID, $this->lang->line('delete')) ?>
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