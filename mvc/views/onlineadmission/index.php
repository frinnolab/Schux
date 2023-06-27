<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineadmission"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_onlineadmission')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $classArray = array("0" => $this->lang->line("onlineadmission_select_class"));
                            if(count($classes)) {
                                foreach ($classes as $classa) {
                                    if($siteinfos->ex_class != $classa->classesID) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                            }
                            echo form_dropdown("classesID", $classArray, set_value("classesID", $classesID), "id='classesID' class='form-control select2'");
                        ?>
                    </div>
                </h5>
                <br/>
            </div>
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('onlineadmission_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_gender')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('onlineadmission_phone')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('onlineadmission_status')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($onlineadmissions)) {$i = 1; foreach($onlineadmissions as $onlineadmission) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?=$i; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_photo')?>">
                                        <?=profileimage($onlineadmission->photo)?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_name')?>">
                                        <?=$onlineadmission->name?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_gender')?>">
                                        <?=$onlineadmission->sex?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_phone')?>">
                                        <?=$onlineadmission->phone; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('onlineadmission_status')?>">
                                        <?php
                                            if($onlineadmission->status == 1) {
                                                echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('onlineadmission_approved') . "</button>";
                                            } elseif ($onlineadmission->status == 2) {
                                                echo "<button class='btn btn-warning btn-xs'>" . $this->lang->line('onlineadmission_waiting') . "</button>";
                                            } elseif($onlineadmission->status == 3) {
                                                echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('onlineadmission_decline') . "</button>";
                                            } else {
                                                echo "<button class='btn btn-info btn-xs'>" . $this->lang->line('onlineadmission_new') . "</button>";
                                            }
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('onlineadmission')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            echo btn_view_show(base_url('onlineadmission/view/'.$onlineadmission->onlineadmissionID), $this->lang->line('onlineadmission_view'));
                                            echo btn_add_show(base_url('onlineadmission/approve/'.$onlineadmission->onlineadmissionID.'/'.$onlineadmission->classesID), $this->lang->line('onlineadmission_approve'));
                                            echo '<a onclick="return confirm(\'This cannot be undone. are you sure?\')" href="'.base_url('onlineadmission/waiting/'.$onlineadmission->onlineadmissionID.'/'.$onlineadmission->classesID).'" class="btn btn-warning btn-xs mrg" data-placement="top" data-toggle="tooltip" data-original-title="'.$this->lang->line('onlineadmission_waiting').'"><i class="fa fa-circle-o"></i></a>';
                                            echo btn_cancel('onlineadmission/decline/'.$onlineadmission->onlineadmissionID.'/'.$onlineadmission->classesID, $this->lang->line('onlineadmission_decline'));
                                        ?>
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

<script>
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = parseInt($('#classesID').val());
        if(classesID) {
            var url = "<?=base_url('onlineadmission/index/')?>"+classesID;
            window.location.href = url; 
        }   
    });
</script>
