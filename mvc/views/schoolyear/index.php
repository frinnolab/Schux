
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fa fa-calendar-plus-o"></i> 
            <?=$this->lang->line('panel_title');?>
        </h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_schoolyear')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                
                    <h5 class="page-header">
                        <?php if(permissionChecker('schoolyear_add')) { ?>
                            <a href="<?=base_url('schoolyear/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        <?php } ?>
                    </h5>
                

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_schoolyear')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_schoolyeartitle')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_startingdate')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_endingdate')?></th>
                                <?php if(permissionChecker('schoolyear_edit') || permissionChecker('schoolyear_delete')) { ?>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(count($schoolyears)) {$i = 1; foreach($schoolyears as $schoolyear) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?=$i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('schoolyear_schoolyear')?>">
                                        <?=$schoolyear->schoolyear; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('schoolyear_schoolyeartitle')?>">
                                        <?=$schoolyear->schoolyeartitle; ?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('schoolyear_startingdate')?>">
                                        <?php 
                                            if($schoolyear->startingdate) {
                                                $startingdate = date("d-m-Y", strtotime($schoolyear->startingdate));
                                            }
                                            echo $startingdate;
                                        ?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('schoolyear_endingdate')?>">
                                        <?php 
                                            if($schoolyear->endingdate) {
                                                $endingdate = date("d-m-Y", strtotime($schoolyear->endingdate));
                                            }
                                            echo $endingdate;
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('schoolyear_edit') || permissionChecker('schoolyear_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?=btn_edit('schoolyear/edit/'.$schoolyear->schoolyearID, $this->lang->line('edit')) ?>
                                            <?=(($schoolyear->schoolyearID!=1) ? btn_delete('schoolyear/delete/'.$schoolyear->schoolyearID, $this->lang->line('delete')) : '')?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; } } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>