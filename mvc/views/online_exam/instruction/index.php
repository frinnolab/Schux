<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-map-signs"></i> <?=$this->lang->line('panel_title')?></h3>


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
                    if(permissionChecker('instruction_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('instruction/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('instruction_title')?></th>
                                <th class="col-sm-7"><?=$this->lang->line('instruction_content')?></th>
                                <?php if(permissionChecker('instruction_edit') || permissionChecker('instruction_delete') || permissionChecker('instruction_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($instructions)) {$i = 1; foreach($instructions as $instruction) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('instruction_title')?>">
                                        <?php
                                            if(strlen($instruction->title) > 25)
                                                echo strip_tags(substr($instruction->title, 0, 25)."...");
                                            else
                                                echo strip_tags(substr($instruction->title, 0, 25));
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('instruction_content')?>">
                                        <?php
                                            if(strlen($instruction->content) > 150)
                                                echo substr($instruction->content, 0, 150)."...";
                                            else
                                                echo $instruction->content; 
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('instruction_edit') || permissionChecker('instruction_delete') || permissionChecker('instruction_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('instruction/view/'.$instruction->instructionID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('instruction/edit/'.$instruction->instructionID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('instruction/delete/'.$instruction->instructionID, $this->lang->line('delete')) ?>
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
