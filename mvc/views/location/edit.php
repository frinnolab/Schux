
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-newspaper-o"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("location/index")?>"><?=$this->lang->line('menu_location')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_location')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                    if(form_error('location'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="location" class="col-sm-2 control-label">
                            <?=$this->lang->line("location")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="location" name="location" value="<?=set_value('location', $location->location)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('location'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('description'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="description" class="col-sm-2 control-label">
                            <?=$this->lang->line("location_description")?>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="description" name="description" ><?=set_value('description', $location->description)?></textarea>
                        </div>
                        <span class="col-sm-3 control-label">
                                <?php echo form_error('description'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_location")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#description').jqte();
    });
</script>
