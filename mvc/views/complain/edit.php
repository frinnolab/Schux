
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-commenting "></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("complain/index")?>"><?=$this->lang->line('menu_complain')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_complain')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                    <?php
                        if(form_error('title'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                        ?>
                        <label for="title" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_title")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title', $complain->title)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('usertypeID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                        ?>
                        <label for="usertypeID" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_usertypeID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $types[0] = $this->lang->line('complain_select_usertype');
                                if(count($usertypes)) {
                                    foreach ($usertypes as $key => $usertype) {
                                        $types[$usertype->usertypeID] = $usertype->usertype;
                                    }
                                }
                                echo form_dropdown("usertypeID", $types, set_value("usertypeID", $complain->usertypeID), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('usertypeID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('classID'))
                            echo "<div id='divClassID' class='form-group has-error' >";
                        else
                            echo "<div id='divClassID' class='form-group' >";
                    ?>
                        <label for="classID" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_classID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray = array(
                                    'select' => $this->lang->line('complain_select_class')
                                );
                                if(count($classes)) {
                                    foreach ($classes as $key => $classa) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classID", $classArray, set_value("classID", $classesID), "id='classID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('userID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="userID" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_userID")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array(
                                    '0' => $this->lang->line('complain_select_users')
                                );

                                if(count($users)) {
                                    foreach ($users as $key => $user) {
                                        $array[$key] = $user;
                                    }
                                }
                    
                                echo form_dropdown("userID", $array, set_value("userID", $complain->userID), "id='userID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('userID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('description'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="description" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_description")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="description" name="description" ><?=set_value('description', $complain->description)?></textarea>
                        </div>
                        <span class="col-sm-2 control-label">
                                <?php echo form_error('description'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('attachment'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="attachment" class="col-sm-2 control-label">
                            <?=$this->lang->line("complain_attachment")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('complain_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('complain_file_browse')?></span>
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
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_complain")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php
    $useroption = '<option value="0">'.$this->lang->line('complain_select_users').'</option>';
    $classoption = '<option value="0">'.$this->lang->line('complain_select_class').'</option>';
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.select2').select2();
        $('#divClassID').hide();
        $('#description').jqte();

        var usertypeID = "<?=$usertypeID?>";
        if (usertypeID==3) {
            $('#divClassID').show();       
        }

        $("#usertypeID").change(function () {
            var usertypeID = $(this).val();
            if (usertypeID != 'select') {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('complain/allusers')?>",
                    data: "usertypeID=" + usertypeID,
                    dataType: "html",
                    success: function (data) {
                        if (usertypeID == 3) {
                            $('#divClassID').show();
                            $('#classID').html(data);

                            $('#userID').html('<?=$useroption?>');
                        } else {
                            $('#divClassID').hide();
                            $('#userID').html(data);
                        }
                    }
                });
            } else {
                $('#userID').html('<?=$useroption?>');
            }
        });

        $('#classID').change(function() {
            var classes = $(this).val();
            if(classes != 'select') {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('complain/allStudent')?>",
                    data: "&classes=" + classes,
                    dataType: "html",
                    success: function(data) {
                        $('#userID').html(data);
                    }
                });
            } else {
                $('#userID').html('<?=$useroption?>');
            }
        });
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
            $(".image-preview-input-title").text("<?=$this->lang->line('complain_file_browse')?>"); 
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
                $(".image-preview-input-title").text("<?=$this->lang->line('complain_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }        
            reader.readAsDataURL(file);
        });  
    });
</script>
