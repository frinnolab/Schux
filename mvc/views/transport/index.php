
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-sbus"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_transport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('transport_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('transport/add') ?>">
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
                                <th class="col-sm-1"><?=$this->lang->line('transport_owner')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('transport_owner_phone')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('transport_owner_alternate_phone')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('transport_driver')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transport_driver_phone')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transport_driver_alternate_phone')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('transport_vehicle')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('transport_capacity')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('transport_note')?></th>
                                <?php if(permissionChecker('transport_edit') || permissionChecker('transport_delete')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($transports)) {$i = 1; foreach($transports as $transport) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_owner')?>">
                                        <?php echo $transport->transport_owner ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_owner_phone')?>">
                                        <?php echo $transport->transport_owner_phone ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_owner_alternate_phone')?>">
                                        <?php echo $transport->transport_owner_alternate_phone ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_driver')?>">
                                        <?php echo $transport->transport_driver ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_driver_phone')?>">
                                        <?php echo $transport->transport_driver_phone ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_driver_alternate_phone')?>">
                                        <?php echo $transport->transport_driver_alternate_phone ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_vehicle')?>">
                                        <?php echo $transport->vehicle ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_capacity')?>">
                                        <?php echo $transport->capacity ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('transport_note')?>">
                                        <?php echo $transport->transport_note ?>
                                    </td>

                                    <?php if(permissionChecker('transport_edit') || permissionChecker('transport_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('transport/edit/'.$transport->transportID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('transport/delete/'.$transport->transportID, $this->lang->line('delete')) ?>
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
