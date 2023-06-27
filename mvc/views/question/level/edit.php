
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-level-up"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("question_level/index")?>"><?=$this->lang->line('menu_question_level')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_question_level')?></li>
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
                            <?=$this->lang->line("question_level_title")?>
                            <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name', $question_level->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_class")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>