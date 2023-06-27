<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leaveapply"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if(permissionChecker('leaveapply_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('leaveapply/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('leaveapply_applicationto')?></th>
                                <th><?=$this->lang->line('leaveapply_category')?></th>
                                <th><?=$this->lang->line('leaveapply_date')?></th>
                                <th><?=$this->lang->line('leaveapply_schedule')?></th>
                                <th><?=$this->lang->line('leaveapply_days')?></th>
                                <th><?=$this->lang->line('leaveapply_attachment')?></th>
                                <th><?=$this->lang->line('leaveapply_status')?></th>
                                <?php if(permissionChecker('leaveapply_view') || permissionChecker('leaveapply_edit') || permissionChecker('leaveapply_delete')) { ?>
                                    <th class="col-md-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($myleaveapplications)) {$i = 1; foreach($myleaveapplications as $leaveapply) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_applicationto')?>">
                                        <?=isset($userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]) ? $userName[$leaveapply->applicationto_usertypeID][$leaveapply->applicationto_userID]->name : ''?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_category')?>">
                                        <?=isset($leavecategorys[$leaveapply->leavecategoryID]) ? $leavecategorys[$leaveapply->leavecategoryID] : ''?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_date')?>">
                                        <?=date('d M Y',strtotime($leaveapply->apply_date))?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_schedule')?>">
                                        <?=date('d M Y',strtotime($leaveapply->from_date))?> - <?=date('d M Y',strtotime($leaveapply->to_date))?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_days')?>">
                                        <?php echo $leaveapply->leave_days; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('leaveapply_attachment')?>">
                                        <?php 
                                            if($leaveapply->attachmentorginalname) { echo btn_download_file('leaveapply/download/'.$leaveapply->leaveapplicationID, namesorting($leaveapply->attachmentorginalname, 12), $this->lang->line('download')); 
                                            }
                                        ?>    
                                    </td>
                                    <td data-title="<?=$this->lang->line('leaveapply_status')?>">
                                        <?php if($leaveapply->status == null) { ?>
                                            <button type="button" class="btn btn-warning btn-xs"><?=$this->lang->line('leaveapply_pending')?></button>
                                        <?php } elseif($leaveapply->status == 1) { ?>
                                            <button type="button" class="btn btn-success btn-xs"><?=$this->lang->line('leaveapply_approved')?></button>
                                        <?php } else { ?>
                                            <button type="button" class="btn btn-danger btn-xs"><?=$this->lang->line('leaveapply_declined')?></button>
                                        <?php } ?>
                                    </td>
                                    <?php if(permissionChecker('leaveapply_view') || permissionChecker('leaveapply_edit') || permissionChecker('leaveapply_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_view('leaveapply/view/'.$leaveapply->leaveapplicationID, $this->lang->line('view')) ?>
                                            <?php if($leaveapply->status == NULL) {?>
                                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                                    <?php echo btn_edit('leaveapply/edit/'.$leaveapply->leaveapplicationID, $this->lang->line('edit')) ?>
                                                    <?php echo btn_delete('leaveapply/delete/'.$leaveapply->leaveapplicationID, $this->lang->line('delete')) ?>
                                                <?php } ?>
                                            <?php } ?>
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