
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-clock-o"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("hourly_template/index")?>"><?=$this->lang->line('menu_hourly_template')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_hourly_template')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('hourly_grades'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="hourly_grades" class="col-sm-2 control-label">
                            <?=$this->lang->line("hourly_template_hourly_grades")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="hourly_grades" name="hourly_grades" value="<?=set_value('hourly_grades')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hourly_grades'); ?>
                        </span>
                    </div>


                    <?php
                        if(form_error('hourly_rate'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="hourly_rate" class="col-sm-2 control-label">
                            <?=$this->lang->line("hourly_template_hourly_rate")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="hourly_rate" name="hourly_rate" value="<?=set_value('hourly_rate')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hourly_rate'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_hourly_grade")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>