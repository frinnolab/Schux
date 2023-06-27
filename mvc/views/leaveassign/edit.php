
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leaveassign"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("leaveassign/index")?>"><?=$this->lang->line('menu_leaveassign')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_leaveassign')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="   usertypeID" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveassign_usertypeID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $usertypeArray[0] = $this->lang->line('leaveassign_select_usertype');
                                if(count($usertypes)) {
                                    foreach ($usertypes as $usertype) {
                                        if($usertype->usertypeID != 4) {
                                            $usertypeArray[$usertype->usertypeID] = $usertype->usertype;
                                        }
                                    }
                                }
                                echo form_dropdown("usertypeID", $usertypeArray, set_value("usertypeID", $leaveassign->usertypeID), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('leavecategoryID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="leavecategoryID" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveassign_categoryID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $leavecategoryArray[0] = $this->lang->line('leaveassign_select_category');
                                if(count($leavecategorys)) {
                                    foreach ($leavecategorys as $leavecategory) {
                                        $leavecategoryArray[$leavecategory->leavecategoryID] = $leavecategory->leavecategory;
                                    }
                                }
                                echo form_dropdown("leavecategoryID", $leavecategoryArray, set_value("leavecategoryID", $leaveassign->leavecategoryID), "id='leavecategoryID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('leavecategoryID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('leaveassignday'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="leaveassignday" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveassign_number_of_day")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input step="1" type="number" class="form-control" id="leaveassignday" name="leaveassignday" value="<?=set_value('leaveassignday', $leaveassign->leaveassignday)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('leaveassignday'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_leaveassign")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
</script>