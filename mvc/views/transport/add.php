
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-sbus"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("transport/index")?>"><?=$this->lang->line('menu_transport')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_transport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('transport_owner')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_owner" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_owner")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_owner" name="transport_owner" value="<?=set_value('transport_owner')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_owner'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('transport_owner')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_owner_phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_owner_phone")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_owner_phone" name="transport_owner_phone" value="<?=set_value('transport_owner_phone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_owner_phone'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('transport_owner_alternate_phone')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_owner_alternate_phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_owner_alternate_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_owner_alternate_phone" name="transport_owner_alternate_phone" value="<?=set_value('transport_owner_alternate_phone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_owner_alternate_phone'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('transport_driver')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_driver" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_driver")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_driver" name="transport_driver" value="<?=set_value('transport_driver')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_driver'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('transport_driver_phone')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_driver_phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_driver_phone")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_driver_phone" name="transport_driver_phone" value="<?=set_value('transport_driver_phone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_driver_phone'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('transport_driver_alternate_phone')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="transport_driver_alternate_phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_driver_alternate_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="transport_driver_alternate_phone" name="transport_driver_alternate_phone" value="<?=set_value('transport_driver_alternate_phone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('transport_driver_alternate_phone'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('vehicle')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="vehicle" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_vehicle")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="vehicle" name="vehicle" value="<?=set_value('vehicle')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vehicle'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('capacity')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="capacity" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_capacity")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="number" class="form-control" id="capacity" name="capacity" value="<?=set_value('capacity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('capacity'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('note')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("transport_note")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="note" name="note"><?=set_value('note')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('note'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_transport")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>