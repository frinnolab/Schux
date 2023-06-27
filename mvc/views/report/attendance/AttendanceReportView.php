
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-attendancereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_attendancereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="attendancetypeDiv">
                    <label><?=$this->lang->line("attendancereport_attendancetype")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("attendancereport_please_select"),
                             "P" => $this->lang->line("attendancereport_present"),
                             "LE" => $this->lang->line("attendancereport_late_present_with_excuse"),
                             "L" => $this->lang->line("attendancereport_late_present"),
                             "A" => $this->lang->line("attendancereport_absent"),
                             "LA" => $this->lang->line("attendancereport_leave"),

                         );
                         echo form_dropdown("attendancetype", $array, set_value("attendancetype"), "id='attendancetype' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("attendancereport_class")?></label>
                    <?php
                        $array = array("0" => $this->lang->line("attendancereport_please_select"));
                        if(count($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("attendancereport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendancereport_please_select"); ?></option>
                    </select>
                </div>

               <div class="form-group col-sm-4" id="subjectDiv">
                    <label><?=$this->lang->line("attendancereport_subject")?><span class="text-red"> * </span></label>
                    <select id="subjectID" name="subjectID" class="form-control select2">
                        <option value=""><?php echo $this->lang->line("attendancereport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-4" id="dateDiv">
                    <label><?=$this->lang->line("attendancereport_date")?><span class="text-red"> * </span></label>
                    <input class="form-control" name="date" id="date" value="" type="text">
                </div>

                <div class="col-sm-4">
                    <button id="get_attendancereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("attendancereport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_attendance_report"></div>

<?php if($siteinfos->attendance == 'subject') { ?>
    <script type="text/javascript">
        $('#subjectDiv').show()
    </script>
<?php } else { ?>
    <script type="text/javascript">
        $('#subjectDiv').hide()
    </script>
<?php } ?>

<script type="text/javascript">
    $('.select2').select2();
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('attendancereport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    
    function divHide() {
        $("#classesDiv").hide("slow");
        $("#sectionDiv").hide("slow");
        $("#dateDiv").hide("slow");
        $("#subjectDiv").hide("slow");
    }

    function divShow() {
        $("#classesDiv").show("slow");
        $("#sectionDiv").show("slow");
        $("#dateDiv").show("slow");
        <?php if($siteinfos->attendance == 'subject') { ?>
            $('#subjectDiv').show();
        <?php } else { ?>
            $("#subjectDiv").hide("slow");
        <?php } ?>
    }

    $(document).ready(function() {
        $("#attendancetype").val('0');
        $("#classesID").val(0);
        $("#sectionID").val('0');
        <?php if($siteinfos->attendance == 'subject') { ?>
            $("#subjectID").val('');
        <?php } ?>
        divHide();
    });

    $(document).bind('click', function() {
        $('#date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
            daysOfWeekDisabled: "<?=$siteinfos->weekends?>",
            datesDisabled: ["<?=$get_all_holidays;?>"], 
        }); 
    });

    $(document).on('change','#attendancetype', function() {
        $('#load_attendance_report').html('');
        var type = $(this).val();
        if(type != 0) {
            divShow();
        } else {
            $('#classesID').val(0);
            divHide();
        }
    });

    $(document).on('change', '#classesID', function() {
        $('#load_attendance_report').html('');
        var id = $(this).val();
        if(id == 0) {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("attendancereport_please_select")?>"+'</option>');
            $('#sectionID').val('0');

            $('#subjectID').html('<option value="">'+"<?=$this->lang->line("attendancereport_please_select")?>"+'</option>');
            $('#subjectID').val('');
            
            // $('#date').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancereport/getSection')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendancereport/getSubject')?>",
                data: {"classID" : id},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
    });

    $(document).on('change', '#sectionID', function() {
        $('#load_attendance_report').html('');
    });

    $(document).on('change', '#subjectID', function() {
        $('#load_attendance_report').html('');
    });
    
    $(document).on('change', '#date', function() {
        $('#load_attendance_report').html('');
    });

    $(document).on('click', '#get_attendancereport', function() {
        $('#load_attendance_report').html('');
        var error = 0;
        var field = {
            'attendancetype' : $('#attendancetype').val(),
            'classesID' : $('#classesID').val(),
            'sectionID' : $('#sectionID').val(),
            'date' : $('#date').val(),
            'subjectID' : $('#subjectID').val(),
        };

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function validation_checker(field, error){
        if (field['attendancetype'] == 0) {
            $('#attendancetypeDiv').addClass('has-error');
            error++;
        } else {
            $('#attendancetypeDiv').removeClass('has-error');
        }

        // if (field['classesID'] == 0) {
        //     $('#classesDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('#classesDiv').removeClass('has-error');
        // }

        if (field['date'] == '') {
            $('#dateDiv').addClass('has-error');
            error++;
        } else {
            $('#dateDiv').removeClass('has-error');
        }

        <?php if($siteinfos->attendance == 'subject') { ?>
            if (field['subjectID'] == '') {
                $('#subjectDiv').addClass('has-error');
                error++;
            } else {
                $('#subjectDiv').removeClass('has-error');
            }

        <?php } ?>

        return error;
    }

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendancereport/getAttendacneReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                console.log(response)
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_attendance_report').html(response.render);
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }
        } else {
            for (var key in passData) {
                if (passData.hasOwnProperty(key)) {
                    $('#'+key).parent().removeClass('has-error');
                }
            }

            for (var key in response) {
                if (response.hasOwnProperty(key)) {
                    $('#'+key).parent().addClass('has-error');
                }
            }
        }
    }
    
</script>
