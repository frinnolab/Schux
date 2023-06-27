<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-clock-o"></i> <?=$this->lang->line('panel_title')?></h3>


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
                   if(permissionChecker('hourly_template_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('hourly_template/add') ?>">
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
                                <th class="col-sm-2"><?=$this->lang->line('hourly_template_hourly_grades')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('hourly_template_hourly_rate')?></th>
                                <?php if(permissionChecker('hourly_template_edit') || permissionChecker('hourly_template_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($hourly_templates)) {$i = 1; foreach($hourly_templates as $hourly_template) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hourly_template_hourly_grades')?>">
                                        <?=$hourly_template->hourly_grades?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('hourly_template_hourly_rate')?>">
                                        <?=$hourly_template->hourly_rate?>
                                    </td>
                                    <?php if(permissionChecker('hourly_template_edit') || permissionChecker('hourly_template_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('hourly_template/edit/'.$hourly_template->hourly_templateID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('hourly_template/delete/'.$hourly_template->hourly_templateID, $this->lang->line('delete')) ?>
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