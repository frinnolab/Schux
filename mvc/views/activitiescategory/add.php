<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-pagelines"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("activitiescategory/index")?>"><?=$this->lang->line('menu_activitiescategory')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_activitiescategory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('title'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("activitiescategory_title")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('fa_icon'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="fa_icon" class="col-sm-2 control-label">
                            <?=$this->lang->line("activitiescategory_fa_icon")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="fa_icon" name="fa_icon" value="<?=set_value('fa_icon')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('fa_icon'); ?>
                        </span>
                    </div>



                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_activitiescategory")?>" >
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>