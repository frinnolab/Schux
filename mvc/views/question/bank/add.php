
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-qrcode"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("question_bank/index")?>"><?=$this->lang->line('menu_question_bank')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_question_bank')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post" id="question_bank" enctype="multipart/form-data">
                    <?php
                    if(form_error('group'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_group")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($groups as $group) {
                                $array[$group->questionGroupID] = $group->title;
                            }
                            echo form_dropdown("group", $array, set_value("group"), "id='group' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('group'); ?>
                        </span>
                    </div>
                    
                    <?php
                    if(form_error('class'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="class" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_level")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(null => $this->lang->line("question_bank_select"));
                            foreach ($classes as $class) {
                                $array[$class->classesID] = $class->classes;
                            }
                            echo form_dropdown("class", $array, set_value("class"), "id='class' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('class'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('subject'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_subject")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(null => $this->lang->line("question_bank_select"));
                            echo form_dropdown("subject", $array, set_value("subject"), "id='subject' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subject'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('year'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="year" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_year")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="year" name="year" value="<?=set_value('year')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('year'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('question'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="question" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_question")?> <span class='text-red'>*</span>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="question" name="question" ><?=set_value('question')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('question'); ?>
                        </span>
                    </div>
                    
                    <?php
                    if(form_error('explanation'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="explanation" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_explanation")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="explanation" name="explanation" ><?=set_value('explanation')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('explanation'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('photo'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="photo" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_image")?>
                        </label>
                        <div class="col-sm-6">
                            <div class="input-group image-preview">
                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                        <span class="fa fa-remove"></span>
                                        <?=$this->lang->line('question_bank_clear')?>
                                    </button>
                                    <div class="btn btn-success image-preview-input">
                                        <span class="fa fa-repeat"></span>
                                        <span class="image-preview-input-title">
                                        <?=$this->lang->line('question_bank_file_browse')?></span>
                                        <input type="file" accept="image/png, image/jpeg, image/gif" name="photo"/>
                                    </div>
                                </span>
                            </div>
                        </div>

                        <span class="col-sm-4">
                            <?php echo form_error('photo'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('hints'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="hints" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_hints")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="hints" name="hints" value="<?=set_value('hints')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('hints'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('mark'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="mark" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_mark")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="mark" name="mark" value="<?=set_value('mark')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('mark'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('type'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="type" class="col-sm-2 control-label">
                            <?=$this->lang->line("question_bank_type")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach ($types as $type) {
                                $array[$type->typeNumber] = $type->name;
                            }
                            echo form_dropdown("type", $array, set_value("type"), "id='type' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('type'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('totalOption'))
                        echo "<div class='form-group has-error' id='totalOptionDiv'>";
                    else
                        echo "<div class='form-group' id='totalOptionDiv'>";
                    ?>
                        <label for="totalOption" class="col-sm-2 control-label" >
                            <?=$this->lang->line("question_bank_totalOption")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array(0 => $this->lang->line("question_bank_select"));
                            foreach (range(0,10) as $i) {
                                $array[$i] = $i;
                            }
                            echo form_dropdown("totalOption", $array, set_value("totalOption"), "id='totalOption' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('totalOption'); ?>
                        </span>
                    </div>

                    <div id="in"></div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
                        </div>
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();

    $('#class').on('change', function(){
        let classID = $('#class').val();
        let subjects = <?= json_encode($subjects) ?>;
        let i;
        subjects = subjects.filter(subject => {
            return subject.classesID === classID
        })
        $('#subject').html('');
        subjects_options = subjects.map(subject=> {
                                            $('#subject').append(`<option value=${subject.subjectID}>${subject.subject}</option>`)
                                        });
    })

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
            $(".image-preview-input-title").text("<?=$this->lang->line('question_bank_file_browse')?>");
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
                $(".image-preview-input-title").text("<?=$this->lang->line('question_bank_file_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
                img.attr('src', e.target.result);
                $(".image-preview").attr("data-content",$(img)[0].outerHTML).popover("show");
                $('.content').css('padding-bottom', '100px');
            }
            reader.readAsDataURL(file);
        });
    });

    $('#question').jqte();
    $('#explanation').jqte();
    $(function () {
        $('#totalOptionDiv').hide();
    });

    $(document).ready(function() {
        // var totalOptionID = '<?=$totalOptionID?>';
        // if(totalOptionID > 0) {
            // $('#totalOptionDiv').show();
        // }

        var typeID = '<?=$typeID?>';
        if(typeID > 0) {
            $('#totalOptionDiv').show();
        }
    });

    // console.log($('#type').val());
    // if($('#type').val() != '0') {
    //     $('#totalOptionDiv').show();
    // }

    $('#type').change(function() {
        $('#in').children().remove();
        var type = $(this).val();
        if(type == 0) {
            $('#totalOptionDiv').hide();
        } else {
            $('#totalOption').val(0);
            $('#totalOptionDiv').show();
        }
    });

    $('#totalOption').change(function() {
        var valTotalOption = $(this).val();
        var type = $('#type').val();

        if(parseInt(valTotalOption) !=0) {
            var opt = [];
            var ans = [];
            var count = $('.coption').size();

            for(j=1; j<=count; j++) {
                if(type == 3) {
                    opt[j] = $('#answer'+j).val();
                } else {
                    opt[j] = $('#option'+j).val();
                    if($('#ans'+j).prop('checked')) {
                        ans[j] = 'checked="checked"';
                    }
                }
            }
                        
            $('#in').children().remove();
            for(i=1; i<=valTotalOption; i++) {
                if($('#in').size())
                    $('#in').append(formHtmlData(i, type, opt[i], ans[i]));
                else
                    $('#in').append(formHtmlData(i, type));
            }   

        } else {
             $('#in').children().remove();
        }

    });

    function formHtmlData(id, type, value='', checked='') {
        var required = 'required';
        if(type == 1) {
            type = 'radio';
        } else if(type == 2) {
            type = 'checkbox';
            required = '';
        } else if(type == 3) {
            var html = '<div class="form-group coption"><label for="answer'+id+'" class="col-sm-2 control-label"><?=$this->lang->line("question_bank_answer")?> '+ id +'</label><div class="col-sm-4"><input type="text" class="form-control" id="answer'+id+'" name="answer[]" value="'+value+'"></div><div class="col-sm-1"></div><span class="col-sm-4 control-label text-red" id="anserror'+id+'"><?php if(isset($form_validation['answer1'])) { echo $form_validation['answer1']; } ?></span></div>';
            return html;
        }
        var html = '<div class="form-group coption"><label for="option'+id+'" class="col-sm-2 control-label"><?=$this->lang->line("question_bank_option")?> '+ id +'</label><div class="col-sm-4" style="display:inline-table"><input type="text" class="form-control" id="option'+id+'" name="option[]" value="'+value+'"><span class="input-group-addon"><input class="answer" id="ans'+id+'" '+checked+' type="'+type+'" name="answer[]" value="'+id+'" data-toggle="tooltip" data-placement="top" title="Correct Answer" '+ required +' /></span></div><div class="col-sm-3" style="display:inline-table"><input style="padding-top:7px" type="file" name="image'+id+'" id="image'+id+'"></div><span class="col-sm-3 control-label text-red" id="anserror'+id+'"><?php if(isset($form_validation['answer1'])) { echo $form_validation['answer1']; } ?></span></div>';
        return html;
    }   
</script>


<?php
    if(count($options) || count($answers)) {
        if($typeID == 3) {
            $options =  $answers;
        }
        else {
            $options =  $options;
        }
        foreach ($options as $optionKey => $optionValue) { 
?>
            <script type="text/javascript">
                var optID = '<?=$optionKey+1?>';
                var optTypeID = '<?=$typeID?>';
                var optVal = '<?=$optionValue?>';
                var optAns = '';
                <?php if($answers) { ?> var optAns = '<?=(in_array($optionKey+1, $answers)) ? 'checked="checked"' : '' ?>'; <?php } ?>
                $('#in').append(formHtmlData(optID, optTypeID, optVal, optAns));
            </script>
<?php     }
    }
?>
