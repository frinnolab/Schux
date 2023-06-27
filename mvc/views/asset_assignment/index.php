<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-plug"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_asset_assignment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php
                   if(permissionChecker('asset_assignment_add')) {
                ?>
                <h5 class="page-header">
                    <a href="<?php echo base_url('asset_assignment/add') ?>">
                        <i class="fa fa-plus"></i>
                        <?=$this->lang->line('add_title')?>
                    </a>
                </h5>
              <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class=""><?=$this->lang->line('slno')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assetID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_assigned_quantity')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_usertypeID')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_to')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_due_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_out_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_check_in_date')?></th>
                                <th class=""><?=$this->lang->line('asset_assignment_status')?></th>
                              <?php if(permissionChecker('asset_assignment_edit') || permissionChecker('asset_assignment_delete') || permissionChecker('asset_assignment_view')) { ?>
                                    <th class=""><?=$this->lang->line('action')?></th>
                              <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($asset_assignments)) {$i = 1; foreach($asset_assignments as $asset_assignment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assetID')?>">
                                        <?php echo $asset_assignment->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_assigned_quantity')?>">
                                        <?php echo $asset_assignment->assigned_quantity; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_usertypeID')?>">
                                        <?php echo $asset_assignment->usertype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_to')?>">
                                        <?php echo $asset_assignment->assigned_to; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_due_date')?>">
                                        <?php echo isset($asset_assignment->due_date) ? date('d M Y', strtotime($asset_assignment->due_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_out_date')?>">
                                        <?php echo isset($asset_assignment->check_out_date) ? date('d M Y', strtotime($asset_assignment->check_out_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_check_in_date')?>">
                                        <?php echo isset($asset_assignment->check_in_date) ? date('d M Y', strtotime($asset_assignment->check_in_date)) : ''; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('asset_assignment_status')?>">
                                        <?php
                                            if($asset_assignment->status == 1) {
                                                echo $this->lang->line('asset_assignment_checked_out');
                                            } elseif($asset_assignment->status == 2) {
                                                echo $this->lang->line('asset_assignment_in_storage');
                                            }
                                        ?>
                                    </td>
                                  <?php if(permissionChecker('asset_assignment_edit') || permissionChecker('asset_assignment_delete') || permissionChecker('asset_assignment_view')) { ?>

                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('asset_assignment/view/'.$asset_assignment->asset_assignmentID, $this->lang->line('view')) ?>
                                            <?php echo btn_edit('asset_assignment/edit/'.$asset_assignment->asset_assignmentID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('asset_assignment/delete/'.$asset_assignment->asset_assignmentID, $this->lang->line('delete')) ?>
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