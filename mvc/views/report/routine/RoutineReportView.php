<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-routinereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_routinereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="routineForDiv">
                    <label><?=$this->lang->line("routinereport_routine_for")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("routinereport_please_select"),
                             "student" => $this->lang->line("routinereport_student"),
                             "teacher" => $this->lang->line("routinereport_teacher"),
                         );
                         echo form_dropdown("routinefor", $array, set_value("routinefor"), "id='routinefor' class='form-control section2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="teacherDiv">
                    <label><?=$this->lang->line("routinereport_teacher")?><span class="text-red"> * </span></label>
                    <?php
                        $teacherArray = array(
                            "0" => $this->lang->line("routinereport_please_select"),
                        );
                        foreach ($teachers as $teacherKey => $teacher) {
                            $teacherArray[$teacher->teacherID] = $teacher->name;
                        }
                        echo form_dropdown("teacherID", $teacherArray, set_value("teacherID"), "id='teacherID' class='form-control section2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("routinereport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $classesArray = array(
                            "0" => $this->lang->line("routinereport_please_select"),
                        );
                        foreach ($classes as $classaKey => $classa) {
                            $classesArray[$classa->classesID] = $classa->classes;
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control section2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("routinereport_section")?><span class="text-red"> * </span></label>
                    <?php
                        $sectionArray = array(
                            "0" => $this->lang->line("routinereport_please_select"),
                        );
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control section2'");
                     ?>
                </div>



                <div class="col-sm-4">
                    <button id="get_routinereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("routinereport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_routinereport"></div>


<script type="text/javascript">
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('routinereport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.section2').select2();

    $(function(){
        $("#routinefor").val(0);
        $("#teacherID").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $('#teacherDiv').hide('slow');
        $('#classesDiv').hide('slow');
        $('#sectionDiv').hide('slow');
    });

    $(document).on('change', "#routinefor", function() {
        $('#load_routinereport').html("");
        var routinefor = $(this).val();
        if(routinefor == '0'){
            $('#teacherDiv').hide('slow');
            $("#classesDiv").hide('slow');
            $("#sectionDiv").hide('slow');
        } else if(routinefor == 'student') {
            $("#classesID").val(0);
            $("#sectionID").val(0);
            $('#teacherDiv').hide('slow');
            $("#classesDiv").show('slow');
            $("#sectionDiv").show('slow');
        } else if(routinefor == 'teacher') {
            $("#teacherID").val(0);
            $("#teacherDiv").show('slow');
            $("#classesDiv").hide('slow');
            $("#sectionDiv").hide('slow');
        }
    });



    $(document).on('change',"#classesID", function() {
        $('#load_routinereport').html("");
        var id = $(this).val();
        if(id == '0') {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("routinereport_please_select")?>"+'</option>');
            $('#sectionID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('routinereport/getSection')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('change',"#sectionID", function() {
        $('#load_routinereport').html('');
    });

    $(document).on('change',"#teacherID", function() {
        $('#load_routinereport').html('');
    });

    $(document).on('click','#get_routinereport', function() {
        $('#load_routinereport').html();
        var passData;
        var error = 0;
        var field = {
            'routinefor'    : $("#routinefor").val(),
            'teacherID'     : $('#teacherID').val(), 
            'classesID'     : $('#classesID').val(), 
            'sectionID'     : $('#sectionID').val(), 
        };

        if (field['routinefor'] == 0) {
            $('#routineForDiv').addClass('has-error');
            error++;
        } else {
            $('#routineForDiv').removeClass('has-error');
        }

        if (field['routinefor'] == 'student') {
            if (field['classesID'] == 0) {
                $('#classesDiv').addClass('has-error');
                error++;
            } else {
                $('#classesDiv').removeClass('has-error');
            }

            if (field['sectionID'] == 0) {
                $('#sectionDiv').addClass('has-error');
                error++;
            } else {
                $('#sectionDiv').removeClass('has-error');
            }
        } else if (field['routinefor'] == 'teacher') {
            if (field['teacherID'] == 0) {
                $('#teacherDiv').addClass('has-error');
                error++;
            } else {
                $('#teacherDiv').removeClass('has-error');
            }
        }

        if (error == 0) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('routinereport/getRoutineReport')?>",
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
            $('#load_routinereport').html(response.render);
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


