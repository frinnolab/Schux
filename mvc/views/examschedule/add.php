<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-puzzle-piece"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("examschedule/index")?>"><?=$this->lang->line('menu_examschedule')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_examschedule')?></li>
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
                            <?=$this->lang->line('examschedule_classes')?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $classArray[0] = $this->lang->line("examschedule_select_classes");
                                if(count($classes)) {
                                    foreach ($classes as $classa) {
                                        $classArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                     <?php
                        if(form_error('examID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="examID" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $examArray[0] = $this->lang->line("examschedule_select_exam");
                                if(count($exams)) {
                                    foreach ($exams as $exam) {
                                        $examArray[$exam->examID] = $exam->exam;
                                    }
                                }
                                echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('examID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('sectionID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sectionID" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_section")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $sectionArray[0] = $this->lang->line("examschedule_select_section");
                                if(count($sections)) {
                                    foreach ($sections as $section) {
                                        $sectionArray[$section->sectionID] = $section->section;
                                    }
                                }

                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sectionID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('subjectID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subjectID" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_subject")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $subjectArray[0] = $this->lang->line("examschedule_select_subject");
                                if(count($subjects)) {
                                    foreach ($subjects as $subject) {
                                        $subjectArray[$subject->subjectID] = $subject->subject;
                                    }
                                }
                                echo form_dropdown("subjectID", $subjectArray, set_value("subjectID"), "id='subjectID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subjectID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('date'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_date")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('examfrom'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="examfrom" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_examfrom")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="examfrom" name="examfrom" value="<?=set_value('examfrom')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('examfrom'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('examto'))
                            echo "<div class='form-group has-error'>";
                        else
                            echo "<div class='form-group'>";
                    ?>
                        <label for="examto" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_examto")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="examto" name="examto" value="<?=set_value('examto')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('examto'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('room'))
                            echo "<div class='form-group has-error'>";
                        else
                            echo "<div class='form-group'>";
                    ?>
                        <label for="room" class="col-sm-2 control-label">
                            <?=$this->lang->line("examschedule_room")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="room" name="room" value="<?=set_value('room')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('room'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_examschedule")?>" >
                        </div>
                    </div>
                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="callout callout-danger">
                        <p><b>Note:</b> Create exam, class, section & subject before you create a new exam schedule</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.select2').select2();
$('#classesID').change(function(event) {
    var classesID = $(this).val();
    if(classesID === '0') {
        $('#subjectID').val(0);
        $('#sectionID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('examschedule/examcall')?>",
            data: "classesID=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#examID').html(data);
            }
        });

        $.ajax({
            type: 'POST',
            url: "<?=base_url('examschedule/subjectcall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#subjectID').html(data);
            }
        });

        $.ajax({
            type: 'POST',
            url: "<?=base_url('examschedule/sectioncall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data);
            }
        });
    }
});

$("#date").datepicker({
    autoclose: true,
    format: 'dd-mm-yyyy',
    startDate:'<?=$schoolyearsessionobj->startingdate?>',
    endDate:'<?=$schoolyearsessionobj->endingdate?>',
    daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
    datesDisabled: ["<?=$get_all_holidays;?>"], 
});
$('#examfrom').timepicker();
$('#examto').timepicker();

</script>
