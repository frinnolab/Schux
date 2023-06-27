
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-fighter-jet"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?= base_url('activities') ?>"><?=$this->lang->line('menu_activities')?></a></li>
            <li class="active"><?=$this->lang->line('add_activities')?></li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">

                    <?php
                        if(form_error('description'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="description" class="col-sm-2 control-label">
                            <?=$this->lang->line("activities_description")?>
                            <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            
                            <textarea class="form-control" name="description" id="description" cols="30" rows="3"></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('description'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('time_from')||form_error('time_to'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="time_from" class="col-sm-2 control-label">
                            <?=$this->lang->line("activities_time_frame")?>
                        </label>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="time_from" name="time_from" value="<?=set_value('time_from')?>" >
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="time_to" name="time_to" value="<?=set_value('time_to')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('time_from'); ?>
                            <?php echo form_error('time_to'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('time_at'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="time_at" class="col-sm-2 control-label">
                            <?=$this->lang->line("activities_time_at")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="time_at" name="time_at" value="<?=set_value('time_at')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('time_at'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('location'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="location" class="col-sm-2 control-label">
                            <?=$this->lang->line("activities_location")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="location" name="location" value="<?=set_value('location')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('location'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-2 control-label">
                            <?=$this->lang->line("activities_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('attachment'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="attachment" class="col-sm-2 control-label">
                            <?=$this->lang->line("attachment")?>
                        </label>
                        <div class="col-sm-6">
                            <input id="fileupload" multiple="multiple" type="file" name="attachment[]"/>

                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('attachment'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_activities")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="box-footer clearfix">
        <div id="dvPreview"></div>
    </div>
</div>

<script>

    $(function () {
        $("#fileupload").change(function () {
            if (typeof (FileReader) != "undefined") {
                var dvPreview = $("#dvPreview");
                dvPreview.html("");
                var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
                $($(this)[0].files).each(function () {
                    var file = $(this);
                    if (regex.test(file[0].name.toLowerCase())) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var img = $("<img />");
                            var span = $("<span></span>");

                            img.attr("style", "");
                            img.attr("class", "act-attach");
                            img.attr("src", e.target.result);

                            span.attr("class", "thumbnail-attach");

                            span.append(img);
                            dvPreview.append(span);
                        }
                        reader.readAsDataURL(file[0]);
                    } else {
                        alert(file[0].name + " is not a valid image file.");
                        dvPreview.html("");
                        return false;
                    }
                });
            } else {
                alert("This browser does not support HTML5 FileReader.");
            }
        });
    });

    $('.act-image img').click(function(){
        var id = $(this).attr('id');
        if ($(this).hasClass('selected')) {
            $(this).removeClass('selected');
            $('input[type=hidden]').each(function() {
                if ($(this).val() === id) {
                    $(this).remove();
                }
            });
        } else {
            $(this).addClass('selected'); // adds the class to the clicked image
            $("#students").after(
                "<input id='students' type='hidden' name='students[]' value="+id+" />"
            );
        }
    });
    $('#time_from, #time_to, #time_at').timepicker({
        minuteStep: 5,
    });
    $('#date').datepicker();
</script>