
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-ebook"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("ebooks/index")?>"></i><?=$this->lang->line('menu_ebooks')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_ebooks')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <?php
                        if(form_error('name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="name" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name',$ebooks->name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('author'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="author" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_author")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="author" name="author" value="<?=set_value('author',$ebooks->author)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('author'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_classes")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array['0'] = $this->lang->line('ebooks_select_class');
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID",$ebooks->classesID), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('authority'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="authority" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_private")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="checkbox" id="authority" name="authority" value="<?=set_value('authority','1')?>" <?php if($ebooks->authority == '1') { echo "checked";}?>>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('authority'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('cover_photo'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="cover_photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_cover_photo")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" id="cover_photo" name="cover_photo" value="<?=set_value('cover_photo')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('cover_photo'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('file'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="file" class="col-sm-2 control-label">
                            <?=$this->lang->line("ebooks_file")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="file" class="form-control" id="file" name="file" value="<?=set_value('file')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('file'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_ebooks")?>" >
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<script>
$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>
