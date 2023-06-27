<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-wheelchair"></i> <?=$this->lang->line('panel_title')?></h3>       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("childcare/index")?>"> <?=$this->lang->line('menu_childcare')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_childcare')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("classesID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(0 => $this->lang->line("select_class"));
                                if(count($classes)) {
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID", $careinfo->classesID), "id='classesID' class='form-control select2'");
                            ?>

                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('userID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="userID" class="col-sm-2 control-label">
                            <?=$this->lang->line("userID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $studentArray = array(0 => $this->lang->line("select_student"));
                                if(count($students)) {
                                    foreach ($students as $student) {
                                        $studentArray[$student->studentID] = $student->name;
                                    }
                                }
                                echo form_dropdown("userID", $studentArray, set_value("userID", $careinfo->userID), "id='userID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('userID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('receiver_name'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="receiver_name" class="col-sm-2 control-label">
                            <?=$this->lang->line("receiver_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="receiver_name" name="receiver_name" value="<?=set_value('receiver_name', $careinfo->receiver_name)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('receiver_name'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('phone'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="phone" class="col-sm-2 control-label">
                            <?=$this->lang->line("phone")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="phone" name="phone" value="<?=set_value('phone', $careinfo->phone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('phone'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('drop_date') || form_error('drop_time'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="add_drop_time" class="col-sm-2 control-label">
                            <?=$this->lang->line("add_drop_time")?> <span class="text-red">*</span>
                        </label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="drop_date" name="drop_date" value="<?=set_value('drop_date', isset($careinfo->dropped_at) ? date('d-m-Y', strtotime($careinfo->dropped_at)) : '')?>" >
                        </div>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="drop_time" name="drop_time" value="<?=set_value('drop_time', isset($careinfo->dropped_at) ? date('h:i A', strtotime($careinfo->dropped_at)) : '')?>" >
                        </div>

                        <span class="col-sm-4 control-label">
                            <?php echo form_error('drop_date').'<br>'; ?>
                            <?php echo form_error('drop_time'); ?>
                        </span>
                    </div>



                    <?php
                        if(form_error('receive_date') || form_error('receive_time'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="add_receive_time" class="col-sm-2 control-label">
                            <?=$this->lang->line("add_receive_time")?>
                        </label>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="receive_date" name="receive_date" value="<?=set_value('receive_date', isset($careinfo->received_at) ? date('d-m-Y', strtotime($careinfo->received_at)) : '')?>" >
                        </div>

                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="receive_time" name="receive_time" value="<?=set_value('receive_time', isset($careinfo->received_at) ? date('h:i A', strtotime($careinfo->received_at)) : '')?>" >
                        </div>

                        <span class="col-sm-4 control-label">
                            <?php echo form_error('receive_date').'<br>'; ?>
                            <?php echo form_error('receive_time'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('document'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="document" class="col-sm-2 control-label">
                            <?=$this->lang->line("document")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('file_browse')?></span>
                                        <input type="file" accept="application/pdf" name="document"/>
                                    </div>
                                </span>
                            </div>
                        </div>
                    </div>


                    <?php
                        if(form_error('comment'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="comment" class="col-sm-2 control-label">
                            <?=$this->lang->line("comment")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="comment" name="comment"><?=set_value('comment', $careinfo->comment)?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('comment'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-1">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_childcare")?>" >
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
            $(".image-preview-input-title").text("<?=$this->lang->line('file_browse')?>");
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
                $(".image-preview-input-title").text("<?=$this->lang->line('file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });

    $( document ).ready(function() {
        $("#drop_date").datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

        $("#receive_date").datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

        $('#drop_time, #receive_time').timepicker({
            minuteStep: 5,
            defaultTime: false,
        });

        $('#classesID').change(function() {
            var classes = $(this).val();
            if(classes != 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('childcare/all_student')?>",
                    data: "&classes=" + classes,
                    dataType: "html",
                    success: function(data) {
                        $('#userID').html(data);
                    }
                });
            }
        });
    });
</script>

