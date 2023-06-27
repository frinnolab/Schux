
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-rss"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("vendor/index")?>"><?=$this->lang->line('menu_vendor')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_vendor')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="name" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $vendor->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('email'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="email" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="email" name="email" value="<?=set_value('email', $vendor->email)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('email'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('phone'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $vendor->phone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('phone'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('contact_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="contact_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("vendor_contact_name")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="contact_name" name="contact_name" value="<?=set_value('contact_name', $vendor->contact_name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('contact_name'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_vendor")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
