<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-tattendance"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("tattendance/index")?>"><?=$this->lang->line('menu_tattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_tattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="date" class="control-label">
                                            <?=$this->lang->line('tattendance_date')?> <span class="text-red">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <input type="submit" class="btn btn-success" style="margin-top:20px" value="<?=$this->lang->line("add_attendance")?>" >
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(count($dateinfo)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php 
                            echo '<h5><center>'.$this->lang->line('tattendance_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('tattendance_day').' : '. $dateinfo['day'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('tattendance_date').' : '. $dateinfo['date'].'</center></h5>';
                        ?>
                    </div>
                <?php } ?>

                <?php if(count($teachers)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('tattendance_email')?></th>
                                    <th class="col-sm-5"><?=$this->lang->line('tattendance_attendance')?></th>
                                </tr>
                            </thead>
                            <tbody id="list">
                                <?php if(count($teachers)) {$i = 1; foreach($teachers as $teacher) { if(isset($tattendances[$teacher->teacherID])) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_photo')?>">
                                            <?=profileproimage($teacher->photo)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_name')?>">
                                            <?php echo $teacher->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_email')?>">
                                            <?php echo $teacher->email; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('tattendance_attendance')?>">
                                            <?php
                                                $aday = "a".abs($day);
                                                if(isset($tattendances[$teacher->teacherID])) {
                                                    if($monthyear == $tattendances[$teacher->teacherID]->monthyear && $tattendances[$teacher->teacherID]->teacherID == $teacher->teacherID) {
                                                        
                                                        $pmethod = '';
                                                        $lemethod = '';
                                                        $lmethod = '';
                                                        $amethod = '';

                                                        if($tattendances[$teacher->teacherID]->$aday == "P") {
                                                            $pmethod = "checked";
                                                        } elseif($tattendances[$teacher->teacherID]->$aday == "LE") {
                                                            $lemethod = "checked";
                                                        } elseif($tattendances[$teacher->teacherID]->$aday == "L") {
                                                            $lmethod = "checked";
                                                        } elseif($tattendances[$teacher->teacherID]->$aday == "A") {
                                                            $amethod = "checked";
                                                        }

                                                        echo  btn_attendance_radio($tattendances[$teacher->teacherID]->tattendanceID.'-1', $pmethod, "attendance btn btn-warning present", "attendance".$tattendances[$teacher->teacherID]->tattendanceID, $this->lang->line('tattendance_present'),'P', 'checked');

                                                        echo  btn_attendance_radio($tattendances[$teacher->teacherID]->tattendanceID.'-2', $lemethod, "attendance btn btn-warning lateexcuse", "attendance".$tattendances[$teacher->teacherID]->tattendanceID, $this->lang->line('tattendance_late_excuse'),'LE');

                                                        echo  btn_attendance_radio($tattendances[$teacher->teacherID]->tattendanceID.'-3', $lmethod, "attendance btn btn-warning late", "attendance".$tattendances[$teacher->teacherID]->tattendanceID, $this->lang->line('tattendance_late_present'),'L');

                                                        echo  btn_attendance_radio($tattendances[$teacher->teacherID]->tattendanceID.'-4', $amethod, "attendance btn btn-warning absent", "attendance".$tattendances[$teacher->teacherID]->tattendanceID, $this->lang->line('tattendance_absent'),'A');
                                                    }
                                                }
                                            ?>
                                        </td>
                                    </tr>
                                <?php $i++; }}} ?>
                            </tbody>
                        </table>
                    </div>

                    <span style="margin-top: 20px;" class="btn btn-success pull-right save_attendance"><?=$this->lang->line('tattendance_submit')?></span>

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
                            var monthyear = "<?=$monthyear?>";
                            
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('tattendance/save_attendace')?>",
                                data: {"day" : day, "monthyear" : monthyear , "attendance" : attendance },
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

                        });
                    </script>
                <?php } ?>
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
        daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
        datesDisabled: ["<?=$get_all_holidays;?>"],       
    });
</script>
