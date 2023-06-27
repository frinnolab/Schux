
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-star"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("section/index")?>"></i><?=$this->lang->line('menu_section')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_section')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('section'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="section" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="section" name="section" value="<?=set_value('section')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('section'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('category'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="category" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_category")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="category" name="category" value="<?=set_value('category')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('category'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('capacity'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="capacity" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_capacity")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="capacity" name="capacity" value="<?=set_value('capacity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('capacity'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_classes")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray = [ 0 => $this->lang->line("section_select_class")];
                                foreach ($classes as $classa) {
                                    $classArray[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('teacherID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="teacherID" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_teacher_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $teacherArray = [ 0 => $this->lang->line("section_select_teacher")];
                                foreach ($teachers as $teacher) {
                                    $teacherArray[$teacher->teacherID] = $teacher->name;
                                }
                                echo form_dropdown("teacherID", $teacherArray, set_value("teacherID"), "id='teacherID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('teacherID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('note'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="note" class="col-sm-2 control-label">
                            <?=$this->lang->line("section_note")?>
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
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_section")?>" >
                        </div>
                    </div>

                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create a class and teacher before create a new section</p>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script>
$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>
