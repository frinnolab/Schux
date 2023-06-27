<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-wheelchair"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_childcare')?></a></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                    <?php if(permissionChecker('childcare_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('childcare/add') ?>">
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
                                <th><?=$this->lang->line('classesID')?></th>
                                <th><?=$this->lang->line('userID')?></th>
                                <th><?=$this->lang->line('phone')?></th>
                                <th><?=$this->lang->line('drop_date')?></th>
                                <th><?=$this->lang->line('receive_date')?></th>
                                <th><?=$this->lang->line('receiver_name')?></th>
                                <th><?=$this->lang->line('comment')?></th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                    <?php
                                        if(permissionChecker('childcare_edit') || permissionChecker('childcare_delete')) {
                                            echo "<th>".$this->lang->line('action')."</th>";
                                        }
                                    ?>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if(count($drop_receive)) {$i = 1; foreach($drop_receive as $drop) { ?>
                            <tr>
                                <td data-title="<?=$this->lang->line('slno')?>">
                                    <?php echo $i; ?>
                                </td>
                                <td data-title="<?=$this->lang->line('classesID')?>">
                                    <?php echo $drop->classes; ?>
                                </td>
                                <td data-title="<?=$this->lang->line('userID')?>">
                                    <?php echo $drop->name; ?>
                                </td>
                                <td data-title="<?=$this->lang->line('phone')?>">
                                    <?php echo $drop->phone; ?>
                                </td>
                                <td data-title="<?=$this->lang->line('drop_date')?>">
                                    <?=date('d M Y h:i:s A',strtotime($drop->dropped_at)) ?>
                                </td>
                                <td data-title="<?=$this->lang->line('receive_date')?>">
                                    <?php if($drop->received_at) { echo date('d M Y h:i:s A',strtotime($drop->received_at)); } ?>
                                </td>
                                <td data-title="<?=$this->lang->line('receiver_name')?>">
                                    <?php echo $drop->receiver_name; ?>
                                </td>
                                <td data-title="<?=$this->lang->line('comment')?>">
                                    <?php echo namesorting($drop->comment, 100); ?>
                                </td>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) { ?>
                                    <?php if(permissionChecker('childcare_edit') || permissionChecker('childcare_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php if ($drop->document) {
                                                echo btn_download('uploads/documents/'.$drop->document, $this->lang->line('download'));
                                            }?>
                                            <?php echo btn_edit('childcare/edit/'.$drop->childcareID, $this->lang->line('edit')); ?>
                                            <?php echo btn_delete('childcare/delete/'.$drop->childcareID, $this->lang->line('delete')); ?>
                                        </td>
                                    <?php } ?>
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