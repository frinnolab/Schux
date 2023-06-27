
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-fax"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("asset/index")?>"><?=$this->lang->line('menu_asset')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_asset')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <?php
                    if(form_error('serial'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="serial" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_serial")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="serial" name="serial" value="<?=set_value('serial')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('serial'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('description'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="description" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_description")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="description" name="description" value="<?=set_value('description')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('status'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="status" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_status")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                echo form_dropdown("status", array(0 => $this->lang->line('asset_select_status'), 1 => $this->lang->line('asset_status_checked_out'), 2 => $this->lang->line('asset_status_checked_in')), set_value("status"), "id='status' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('status'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('asset_condition'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="asset_condition" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_condition")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                echo form_dropdown("asset_condition", array(0 => $this->lang->line('asset_select_condition'), 1 => $this->lang->line('asset_condition_new'), 2 => $this->lang->line('asset_condition_used')), set_value("asset_condition"), "id='asset_condition' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('asset_condition'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('asset_categoryID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="asset_categoryID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_categoryID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array[0] = $this->lang->line('asset_select_category');
                                if(count($categories)) {
                                    foreach ($categories as $category) {
                                        $array[$category->asset_categoryID] = $category->category;
                                    }
                                }
                                echo form_dropdown("asset_categoryID", $array, set_value("asset_categoryID"), "id='asset_categoryID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('asset_categoryID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('asset_locationID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="asset_locationID" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_locationID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $local[0] = $this->lang->line('asset_select_location');
                                if(count($locations)) {
                                    foreach ($locations as $location) {
                                        $local[$location->locationID] = $location->location;
                                    }
                                }
                                echo form_dropdown("asset_locationID", $local, set_value("asset_locationID"), "id='asset_locationID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('asset_locationID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('attachment'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="attachment" class="col-sm-2 control-label">
                            <?=$this->lang->line("asset_attachment")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('asset_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('asset_file_browse')?></span>
                                        <input type="file" name="attachment"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('attachment'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_asset")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();

    $(document).on('click', '#close-preview', function(){
        $('.image-preview').popover('hide');
        // Hover befor close the preview
        $('.image-preview').hover(
            function () {
                $('.image-preview').popover('show');
                $('.content').css('padding-bottom', '100px');
            },
            function () {
                $('.image-preview').popover('hide');
                $('.content').css('padding-bottom', '20px');
            }
        );
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('asset_file_browse')?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var img = $('<img/>', {
                id: 'dynamic',
                width:250,
                height:200,
                overflow:'hidden'
            });
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('asset_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '200px');
            }
            reader.readAsDataURL(file);
        });
    });
</script>
