<?php $date = date('m/d/y', strtotime($leaveapplication->from_date)).' - '. date('m/d/y', strtotime($leaveapplication->to_date));?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leaveapply"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("leaveapply/index")?>"><?=$this->lang->line('menu_leaveapply')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_leaveapply')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <?php
                        if(form_error('applicationto_usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="applicationto_usertypeID" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_role")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $usertypesArray['0'] = lang('leaveapply_select_role');
                                if(count($usertypes)) {
                                    foreach($usertypes as $usertype) {
                                        if($usertype->usertypeID != 4 || $usertype->usertypeID != 3) {
                                            $usertypesArray[$usertype->usertypeID] = $usertype->usertype;
                                        }
                                    }
                                }
                                echo form_dropdown("applicationto_usertypeID", $usertypesArray, set_value("applicationto_usertypeID",$leaveapplication->applicationto_usertypeID), "id='applicationto_usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('applicationto_usertypeID'); ?>
                        </span>
                    </div>

                    <div class='form-group <?=form_error('applicationto_userID') ? "has-error" : "";?>' >
                        <label for="applicationto_userID" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_applicationto")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                if(count($users)) {
                                    $userArray = $users;
                                } else {
                                    $userArray = ['0' => $this->lang->line('leaveapply_select_user')];
                                }
                                echo form_dropdown("applicationto_userID", $userArray, set_value("applicationto_userID",$leaveapplication->applicationto_userID), "id='applicationto_userID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('applicationto_userID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('leavecategoryID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="leavecategoryID" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_category")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $categories[0] = $this->lang->line('leaveapply_select_category');
                                if(count($leavecategories)) {
                                    foreach ($leavecategories as $category) {
                                        $categories[$category->leavecategoryID] = $category->leavecategory." (". $category->leaveassignday .")";
                                    }
                                }
                                echo form_dropdown("leavecategoryID", $categories, set_value("leavecategoryID", $leaveapplication->leavecategoryID), "id='leavecategoryID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('leavecategoryID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('leave_schedule'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="leave_schedule" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_schedule")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="leave_schedule" name="leave_schedule" value="<?=set_value('leave_schedule', $date)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('leave_schedule'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('reason'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="reason" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_reason")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="reason" name="reason" ><?=set_value('reason', $leaveapplication->reason)?></textarea>
                        </div>
                        <span class="col-sm-3 control-label">
                            <?php echo form_error('reason'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('attachment'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="attachment" class="col-sm-2 control-label">
                            <?=$this->lang->line("leaveapply_attachment")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('leaveapply_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('leaveapply_file_browse')?></span>
                                        <input type="file" name="attachment"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('attachment'); ?>
                        </span>
                    </div>

                    <?php if($this->session->userdata('usertypeID') != 3 && $this->session->userdata('usertypeID') != 4) {?>
                        <?php
                            if(form_error('od_status'))
                                echo "<div class='form-group has-error' >";
                            else
                                echo "<div class='form-group' >";
                        ?>
                            <label for="od_status" class="col-sm-2 control-label">
                                <?=$this->lang->line("leaveapply_od_status")?>
                            </label>
                            <div class="col-sm-6">
                                <input type="checkbox" name="od_status" value="1" <?= $leaveapplication->od_status==1 ? "checked" : ""; ?> >
                            </div>
                            <span class="col-sm-3 control-label">
                                <?php echo form_error('od_status'); ?>
                            </span>
                        </div>
                    <?php } ?>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_leaveapply")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#reason').jqte();
    $('.select2').select2();

    $('#applicationto_usertypeID').change(function() {
        var applicationto_usertypeID = $(this).val();
        if(applicationto_usertypeID == '0') {
            $('#applicationto_userID').html('<option value="0"><?=lang("leaveapply_applicationto")?></option>');
            $('#applicationto_userID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('leaveapply/usercall')?>",
                data: "id=" + applicationto_usertypeID,
                dataType: "html",
                success: function(data) {
                    $('#applicationto_userID').html(data);
                }
            });
        }
    });

    var usertypeID = "<?=$usertypeID?>";
    var userID = "<?=$userID?>";
    if(usertypeID) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('leaveapply/usercall')?>",
            data: "id=" + usertypeID,
            dataType: "html",
            success: function(data) {
                $('#applicationto_userID').html(data);
                $('#applicationto_userID').val(userID);
            }
        });
    }


    $('#leave_schedule').daterangepicker({
        timePicker: true,
        timePickerIncrement: 5,
        maxDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->endingdate))?>',
        minDate: '<?=date('m/d/Y', strtotime($schoolyearsessionobj->startingdate))?>',
        locale: {
            format: 'MM/DD/YYYY'
        },
    });



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
            $(".image-preview-input-title").text("<?=$this->lang->line('leaveapply_file_browse')?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('leaveapply_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });
</script>