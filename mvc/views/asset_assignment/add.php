
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-plug"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("asset_assignment/index")?>"><?=$this->lang->line('menu_asset_assignment')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_asset_assignment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('assetID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="assetID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_assetID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array[0] = $this->lang->line('asset_assignment_select_asset');
                                if(count($assets)) {
                                    foreach ($assets as $asset) {
                                        $array[$asset->assetID] = $asset->description;
                                    }
                                }
                                echo form_dropdown("assetID", $array, set_value("assetID"), "id='assetID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('assetID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('assigned_quantity'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="assigned_quantity" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_assigned_quantity")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="assigned_quantity" name="assigned_quantity" value="<?=set_value('assigned_quantity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('assigned_quantity'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="usertypeID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_usertypeID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $userArray[0] = $this->lang->line('asset_assignment_select_usertype');
                                if(count($usertypes)) {
                                    foreach ($usertypes as $key => $usertype) {
                                        $userArray[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("usertypeID", $userArray, set_value("usertypeID"), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classesID'))
                            echo "<div id='classesDiv' class='form-group has-error' >";
                        else
                            echo "<div id='classesDiv' class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_classesID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_class')
                                );

                                if(count($sendClasses)) {
                                    foreach ($sendClasses as $key => $class) {
                                        $classArray[$class->classesID] = $class->classes;
                                    }
                                }

                                echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('check_out_to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="check_out_to" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_check_out_to")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $userArray = array(
                                    '0' => $this->lang->line('asset_assignment_select_user')
                                );  

                                if(count($checkOutToUesrs)) {
                                    foreach ($checkOutToUesrs as $checkOutToUesrKey => $checkOutToUesr) {
                                        $userArray[$checkOutToUesrKey] = $checkOutToUesr;
                                    }
                                }

                                echo form_dropdown("check_out_to", $userArray, set_value("check_out_to"), "id='check_out_to' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('check_out_to'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('due_date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="due_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_due_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="due_date" name="due_date" value="<?=set_value('due_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('due_date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('check_out_date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="check_out_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_check_out_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="check_out_date" name="check_out_date" value="<?=set_value('check_out_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('check_out_date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('check_in_date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="check_in_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_check_in_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="check_in_date" name="check_in_date" value="<?=set_value('check_in_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('check_in_date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('asset_locationID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="asset_locationID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_location")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $local[0] = $this->lang->line('asset_assignment_select_location');
                                if(count($locations)) {
                                    foreach ($locations as $location) {
                                        $local[$location->locationID] = $location->location;
                                    }
                                }
                                echo form_dropdown("asset_locationID", $local, set_value("asset_locationID"), "id='asset_locationID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('asset_locationID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('status'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="status" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_status")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                echo form_dropdown("status", array(0 => $this->lang->line('asset_assignment_select_status'), 1 => $this->lang->line('asset_assignment_checked_out'), 2 => $this->lang->line('asset_assignment_in_storage')), set_value("status"), "id='status' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('status'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('note'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_assignment_note")?>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="note" name="note" ><?=set_value('note')?></textarea>
                        </div>
                        <span class="col-sm-2 control-label">
                                <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_asset_assignment")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if($showClass) { ?>
    <script type="text/javascript">
        $('#classesDiv').show();
    </script>
<?php } else { ?>
    <script type="text/javascript">
        $('#classesDiv').hide();
    </script>
<?php } ?>

<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $('#divClassID').hide();
        $('#note').jqte();
        $('#due_date, #check_in_date, #check_out_date').datepicker();

        $('#usertypeID').change(function() {
            var usertypeID = $(this).val();
            if(usertypeID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allusers')?>",
                    data: {'usertypeID' : usertypeID },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
                        if(usertypeID == 3) {
                            $('#classesDiv').show();
                            $('#classesID').html(data);
                        } else {
                            $('#classesDiv').hide();
                            $('#check_out_to').html(data);
                        }
                    }
                });
            } else {
                $('#classesDiv').hide();
            }
        });

        $('#classesID').change(function() {
            var classesID = $(this).val();
            $('#check_out_to').html("<option value='0'><?=$this->lang->line('asset_assignment_select_user')?></option>");
            if(classesID != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('asset_assignment/allstudent')?>",
                    data: {'classesID' : classesID },
                    dataType: "html",
                    success: function(data) {
                        $('#check_out_to').html(data);
                    }
                });
            }
        });



    });
</script>
