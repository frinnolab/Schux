
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-map-signs"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("instruction/index")?>"><?=$this->lang->line('panel_title')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('panel_title')?></li>
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
                            <?=$this->lang->line("instruction_title")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title', $instruction->title)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('content'))
                        echo "<div class='form-group has-error'>";
                    else
                        echo "<div class='form-group'>";
                    ?>
                        <label for="content" class="col-sm-2 control-label">
                            <?=$this->lang->line("instruction_content")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="content" name="content" ><?=set_value('content', $instruction->content)?></textarea>
                        </div>
                        <span class="col-sm-2 control-label">
                            <?php echo form_error('content'); ?>
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
<script type="text/javascript">
    $('#content').jqte();
</script>
