<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-attendanceoverviewreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_attendanceoverviewreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="usertypeDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_reportfor")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array(
                            "0" => $this->lang->line("attendanceoverviewreport_please_select"),
                            "1" => $this->lang->line("attendanceoverviewreport_student"),
                            "2" => $this->lang->line("attendanceoverviewreport_teacher"),
                            "3" => $this->lang->line("attendanceoverviewreport_user"),
                        );
                        echo form_dropdown("usertype", $array, set_value("usertype"), "id='usertype' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => $this->lang->line("attendanceoverviewreport_please_select"));
                        if(count($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                    </select>
                </div>

               <div class="form-group col-sm-4" id="subjectDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_subject")?><span class="text-red"> * </span></label>
                    <select id="subjectID" name="subjectID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-4" id="userDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_user")?></label>
                    <select id="userID" name="userID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("attendanceoverviewreport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-4" id="monthDiv">
                    <label><?=$this->lang->line("attendanceoverviewreport_month")?> <span class="text-red"> * </span></label>
                    <input type="text" id="monthID" name="monthID" class="form-control"/>
                </div>

                <div class="col-sm-4">
                    <button id="get_attendanceoverviewreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("attendanceoverviewreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_attendanceoverview_report"></div>

<?php
    $startDate = $schoolyearsessionobj->startingmonth.'-'.$schoolyearsessionobj->startingyear;
    $endDate   = $schoolyearsessionobj->endingmonth.'-'.$schoolyearsessionobj->endingyear;
?>

<script type="text/javascript">

    $('#monthID').datepicker( {
        format: "mm-yyyy",
        viewMode: "months", 
        minViewMode: "months",
        todayBtn: false,
        startDate:"<?=$startDate?>",
        endDate:"<?=$endDate?>",
    });

    $('.select2').select2();
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('attendanceoverviewreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $(document).ready(function() {
        $("#usertype").val('0');
        $("#classesID").val('0');
        $("#sectionID").val('0');
        $("#subjectID").val('0');
        $("#userID").val('0');

        $("#classesDiv").hide("slow");
        $("#sectionDiv").hide("slow");
        $("#monthDiv").hide("slow");
        $("#userDiv").hide("slow");
        $("#subjectDiv").hide("slow");
    });

    $(document).on('change','#usertype', function() {
        $('#load_attendanceoverview_report').html('');
        var usertype = $(this).val();

        $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
        $('#userID').val('0');
        if(usertype == 0) {
            $("#classesDiv").hide("slow");
            $("#sectionDiv").hide("slow");
            $("#monthDiv").hide("slow");
            $("#userDiv").hide("slow");
            $("#subjectDiv").hide("slow");
        } else if(usertype == 1) {
            $("#classesDiv").show("slow");
            $("#sectionDiv").show("slow");
            $("#monthDiv").show("slow");
            $("#userDiv").show("slow");
            <?php if($siteinfos->attendance == 'subject') { ?>
                $('#subjectDiv').show()
            <?php } else { ?>
                $('#subjectDiv').hide()
            <?php } ?>
        } else if(usertype == 2) {
            $("#classesDiv").hide("slow");
            $("#sectionDiv").hide("slow");
            $('#subjectDiv').hide('slow');
            $("#monthDiv").show("slow");
            $("#userDiv").show("slow");
        } else if(usertype == 3) {
            $("#classesDiv").hide("slow");
            $("#sectionDiv").hide("slow");
            $('#subjectDiv').hide('slow');
            $("#monthDiv").show("slow");
            $("#userDiv").show("slow");
        }

        if(usertype == 2 || usertype == 3) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getUser')?>",
                data: {"usertype" : usertype},
                dataType: "html",
                success: function(data) {
                   $('#userID').html(data);
                }
            });
        }

    });

    $(document).on('change', '#classesID', function() {
        $('#load_attendanceoverview_report').html('');
        var classesID = $(this).val();

        $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
        $('#userID').val('0');
        if(classesID == 0) {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#sectionID').val('0');
            $('#subjectID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#subjectID').val('0');
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#userID').val('0');
            $('#monthID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getSubject')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
    });

    $(document).on('change', '#sectionID', function() {
        $('#load_attendanceoverview_report').html('');
        var usertype = $('#usertype').val();
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        if(sectionID == 0 ) {
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("attendanceoverviewreport_please_select")?>"+'</option>');
            $('#userID').val('0');
        } else if(sectionID > 0 ) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('attendanceoverviewreport/getStudent')?>",
                data: {"usertype" : usertype,'classesID':classesID,'sectionID':sectionID},
                dataType: "html",
                success: function(data) {
                   $('#userID').html(data);
                }
            });
        }
    });

    $(document).on('change', '#subjectID', function() {
        $('#load_attendanceoverview_report').html('');
    });

    $(document).on('change', '#userID', function() {
        $('#load_attendanceoverview_report').html('');
    });
    
    $(document).on('change', '#monthID', function() {
        $('#load_attendanceoverview_report').html('');
    });

    $(document).on('click', '#get_attendanceoverviewreport', function() {
        $('#load_attendanceoverview_report').html('');
        var error = 0;
        var field = {
            'usertype'  : $('#usertype').val(),
            'classesID' : $('#classesID').val(),
            'sectionID' : $('#sectionID').val(),
            'monthID'   : $('#monthID').val(),
            'userID'    : $('#userID').val(),
            'subjectID' : $('#subjectID').val(),
        };

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }

    });

    function validation_checker(field, error){
        if (field['usertype'] == '0') {
            $('#usertypeDiv').addClass('has-error');
            error++;
        } else {
            $('#usertypeDiv').removeClass('has-error');
        }

        if (field['monthID'] == '') {
            $('#monthDiv').addClass('has-error');
            error++;
        } else {
            $('#monthDiv').removeClass('has-error');
        }

        if(field['usertype'] == '1') {
            if (field['classesID'] == '0') {
                $('#classesDiv').addClass('has-error');
                error++;
            } else {
                $('#classesDiv').removeClass('has-error');
            }

            <?php if($siteinfos->attendance == 'subject') { ?>
                if (field['subjectID'] == '0') {
                    $('#subjectDiv').addClass('has-error');
                    error++;
                } else {
                    $('#subjectDiv').removeClass('has-error');
                }

            <?php } ?>
            }

        return error;
    }

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('attendanceoverviewreport/getAttendacneOverviewReport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_attendanceoverview_report').html(response.render);
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
