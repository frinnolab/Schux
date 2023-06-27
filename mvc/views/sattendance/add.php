<?php if ($siteinfos->note==1) { ?>
    <div class="callout callout-danger">
        <p><b>Note:</b> There are two types of attendance, day wise and class wise. you can select your institute attendance system in <a href="<?=base_url('setting')?>" class="text-blue">settings.</a></p>
    </div>
<?php } ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-sattendance"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("sattendance/index")?>"><?=$this->lang->line('menu_sattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_sattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if($setting->attendance=="subject"){ ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_classes')?> <span class="text-red">*</span></label>
                                            <?php
                                                $classArray = array("0" => $this->lang->line("attendance_select_classes"));
                                                if(count($classes)) {
                                                    foreach ($classes as $classa) {
                                                        $classArray[$classa->classesID] = $classa->classes;
                                                    }
                                                }
                                                echo form_dropdown("classesID", $classArray, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_section')?> <span class="text-red">*</span></label>
                                            <?php
                                                $sectionArray = array('0' => $this->lang->line("attendance_select_section"));
                                                if(count($sections)) {
                                                    foreach ($sections as $section) {
                                                        $sectionArray[$section->sectionID] = $section->section;
                                                    }
                                                }
                                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $sectionID), "id='sectionID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_subject')?> <span class="text-red">*</span></label>
                                            <?php
                                                $subjectArray = array('0' => $this->lang->line("attendance_select_subject"));
                                                if(count($subjects)) {
                                                    foreach ($subjects as $subject) {
                                                        $subjectArray[$subject->subjectID] = $subject->subject;
                                                    }
                                                }
                                                echo form_dropdown("subjectID", $subjectArray, set_value("subjectID", $subjectID), "id='subjectID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_date')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12" style="margin-top: 20px;"><?=$this->lang->line('add_attendance')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_classes')?> <span class="text-red">*</span></label>

                                            <?php
                                                $classesArray = array("0" => $this->lang->line("attendance_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $classesArray[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $classesArray, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_section')?> <span class="text-red">*</span></label>
                                            <?php
                                              $sectionArray = array('0' => $this->lang->line("attendance_select_section"));
                                                if(count($sections)) {
                                                    foreach ($sections as $section) {
                                                        $sectionArray[$section->sectionID] = $section->section;
                                                    }
                                                }
                                                echo form_dropdown("sectionID", $sectionArray, set_value("sectionID", $sectionID), "id='sectionID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_date')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12" style="margin-top: 20px;"><?=$this->lang->line('add_attendance')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>

                <?php if(count($sattendanceinfo)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                            echo '<h5><center>'.$this->lang->line('attendance_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('attendance_classes').' : '. $sattendanceinfo['class'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('attendance_section').' : '. $sattendanceinfo['section'].'</center></h5>';
                            if($setting->attendance == "subject") {
                                echo '<h5><center>'.$this->lang->line('attendance_subject').' : '. $sattendanceinfo['subject'].'</center></h5>';
                            }
                            echo '<h5><center>'.$this->lang->line('attendance_day').' : '. $sattendanceinfo['day'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('attendance_date').' : '. $sattendanceinfo['date'].'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">
                <?php if(count($students)) { ?>

                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('attendance_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('attendance_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('attendance_email')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('attendance_roll')?></th>
                                <th class="col-sm-5">
                                    <?=$this->lang->line('attendance_attendance')?>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if(count($students)) {$i = 1; foreach($students as $student) { if(isset($attendances[$student->studentID])) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_photo')?>">
                                        <?=profileproimage($student->photo)?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_name')?>">
                                        <?php echo $student->srname; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('attendance_email')?>">
                                        <?php echo $student->email; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_roll')?>">
                                        <?php echo $student->srroll; ?>
                                    </td>
                                    <td class="studentID" data-studentid="<?=$student->studentID?>" data-title="<?=$this->lang->line('attendance_attendance')?>">
                                        <?php
                                            $aday = "a".abs($day);
                                            if(isset($attendances[$student->studentID])) {
                                                if($setting->attendance=="subject") {
                                                    if($monthyear == $attendances[$student->studentID]->monthyear && $attendances[$student->studentID]->studentID == $student->srstudentID && $attendances[$student->studentID]->classesID == $student->srclassesID && $attendances[$student->studentID]->subjectID == $subjectID) {
                                                        $pmethod = '';
                                                        $lemethod = '';
                                                        $lmethod = '';
                                                        $amethod = '';

                                                        if($attendances[$student->studentID]->$aday == "P") {
                                                            $pmethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "LE") {
                                                            $lemethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "L") {
                                                            $lmethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "A") {
                                                            $amethod = "checked";
                                                        }

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-1', $pmethod, "attendance btn btn-warning present", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_present'),'P', 'checked');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-2', $lemethod, "attendance btn btn-warning lateexcuse", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_late_excuse'),'LE');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-3', $lmethod, "attendance btn btn-warning late", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_late_present'),'L');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-4', $amethod, "attendance btn btn-warning absent", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_absent'),'A');
                                                    }
                                                } else {
                                                    if ($monthyear == $attendances[$student->studentID]->monthyear && $attendances[$student->studentID]->studentID == $student->srstudentID && $attendances[$student->studentID]->classesID == $student->srclassesID) {
                                                        $pmethod = '';
                                                        $lemethod = '';
                                                        $lmethod = '';
                                                        $amethod = '';

                                                        if($attendances[$student->studentID]->$aday == "P") {
                                                            $pmethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "LE") {
                                                            $lemethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "L") {
                                                            $lmethod = "checked";
                                                        } elseif($attendances[$student->studentID]->$aday == "A") {
                                                            $amethod = "checked";
                                                        }

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-1', $pmethod, "attendance btn btn-warning present", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_present'),'P', 'checked');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-2', $lemethod, "attendance btn btn-warning lateexcuse", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_late_excuse'),'LE');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-3', $lmethod, "attendance btn btn-warning late", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_late_present'),'L');

                                                        echo  btn_attendance_radio($attendances[$student->studentID]->attendanceID.'-4', $amethod, "attendance btn btn-warning absent", "attendance".$attendances[$student->studentID]->attendanceID, $this->lang->line('sattendance_absent'),'A');
                                                    }
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }}} ?>
                        </tbody>
                    </table>
                </div>
                <span style="margin-top: 20px;" class="btn btn-success pull-right save_attendance"><?=$this->lang->line('sattendance_submit')?>
                <?php } ?></span>

                <script type="text/javascript">
                    window.addEventListener('load', function() {
                        setTimeout(lazyLoad, 1000);
                    });

                    function lazyLoad() {
                        var card_images = document.querySelectorAll('.card-image');
                        card_images.forEach(function(card_image) {
                            var image_url = card_image.getAttribute('data-image-full');
                            var content_image = card_image.querySelector('img');
                            content_image.src = image_url;
                            content_image.addEventListener('load', function() {
                                card_image.style.backgroundImage = 'url(' + image_url + ')';
                                card_image.className = card_image.className + ' is-loaded';
                            });
                        });
                    }

                    $('.save_attendance').click(function(){
                        var attendance = {};
                        $('.attendance').each(function(i){
                            var name = $(this).attr('name');
                            if($("input:radio[name="+name+"]").is(":checked")) {
                                var val = $('input:radio[name='+name+']:checked').val();
                            } else {
                                var val = 'A';
                            }
                            attendance[name] = val;
                        });

                        var day = "<?=$day?>";
                        var classes = "<?=$set?>";
                        var section = "<?=$sectionID?>";
                        var monthyear = "<?=$monthyear?>";
                        <?php if($setting->attendance=="subject"){ ?>
                        var subjectID = "<?=$subjectID?>";
                        <?php } else { ?>
                        var subjectID = 0;
                        <?php } ?>

                        if(parseInt(classes) && parseInt(day)) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('sattendance/save_attendace')?>",
                                data: {"day" : day, "classes" : classes, "section" : section, "subject" : subjectID , "monthyear" : monthyear , "attendance" : attendance },
                                dataType: "html",
                                success: function(data) {
                                    var response = JSON.parse(data);
                                    if(response.status == true) {
                                        toastr["success"](response.message)
                                        toastr.options = {
                                          "closeButton": true,
                                          "debug": false,
                                          "newestOnTop": false,
                                          "progressBar": false,
                                          "positionClass": "toast-top-right",
                                          "preventDuplicates": false,
                                          "onclick": null,
                                          "showDuration": "500",
                                          "hideDuration": "500",
                                          "timeOut": "5000",
                                          "extendedTimeOut": "1000",
                                          "showEasing": "swing",
                                          "hideEasing": "linear",
                                          "showMethod": "fadeIn",
                                          "hideMethod": "fadeOut"
                                        }
                                    } else {
                                        $.each(response, function(index, value) {
                                            if(index != 'status') {
                                                toastr["error"](value)
                                                toastr.options = {
                                                  "closeButton": true,
                                                  "debug": false,
                                                  "newestOnTop": false,
                                                  "progressBar": false,
                                                  "positionClass": "toast-top-right",
                                                  "preventDuplicates": false,
                                                  "onclick": null,
                                                  "showDuration": "500",
                                                  "hideDuration": "500",
                                                  "timeOut": "5000",
                                                  "extendedTimeOut": "1000",
                                                  "showEasing": "swing",
                                                  "hideEasing": "linear",
                                                  "showMethod": "fadeIn",
                                                  "hideMethod": "fadeOut"
                                                }
                                            }
                                        })
                                    }
                                }
                            });
                        }
                    });
                </script>
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->


<script type="text/javascript">

    $('.select2').select2();


    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
        daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
        datesDisabled: ["<?=$get_all_holidays;?>"],       
    });

    $("#classesID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {

        <?php if($setting->attendance=="subject"){ ?>
        if(id === '0') {
            $('#subjectID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/subjectall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
        <?php } ?>

        if(id === '0') {
            $('#sectionID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/sectionall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    }
    });
</script>
