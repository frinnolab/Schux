<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-terminalreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_terminalreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("terminalreport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $classesArray['0'] = $this->lang->line("terminalreport_please_select");
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="examDiv">
                    <label><?=$this->lang->line("terminalreport_exam")?><span class="text-red"> * </span></label>
                    <?php
                        $examsArray['0'] = $this->lang->line("terminalreport_please_select");
                        if(count($exams)) {
                            foreach ($exams as $exam) {
                                $examsArray[$exam->examID] = $exam->exam;
                            }
                        }
                        echo form_dropdown("examID", $examsArray, set_value("examID"), "id='examID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("terminalreport_section")?></label>
                    <?php
                        $sectionArray[0] = $this->lang->line("terminalreport_please_select");
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="studentDiv">
                    <label><?=$this->lang->line("terminalreport_student")?></label>
                    <?php
                        $studentArray[0] = $this->lang->line("terminalreport_please_select");
                        echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>
                <div class="col-sm-4">
                    <button id="get_terminalreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("terminalreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_terminalreport"></div>


<script type="text/javascript">
    $('.select2').select2();
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }


    $(function(){
        $("#examID").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#studentID").val(0);

        $('#classesDiv').show('slow');
        $('#examDiv').hide('slow');
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');
    });

    $(document).on('change',"#classesID", function() {
        $('#load_terminalreport').html("");
        $('#examDiv').show('slow');
        $('#sectionDiv').show('slow');
        var classesID = $(this).val();
        if(classesID == '0') {
            $('#examDiv').hide('slow');
            $('#sectionDiv').hide('slow');
            $('#studentDiv').hide('slow');
            $('#examID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#examID').val('0');
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#sectionID').val('0');
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport/getExam')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });


    $(document).on('change',"#sectionID", function() {
        $('#load_terminalreport').html("");
        $('#studentDiv').show('slow');
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();

        if(sectionID == '0') {
            $('#studentDiv').hide('slow');
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("terminalreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('terminalreport/getStudent')?>",
                data: {"classesID" : classesID,"sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_terminalreport', function() {
        $('#load_terminalreport').html("");
        var error = 0;
        var field = {
            'examID'      : $('#examID').val(), 
            'classesID'   : $('#classesID').val(), 
            'sectionID'   : $('#sectionID').val(), 
            'studentID'   : $('#studentID').val(), 
        };

        if (field['examID'] == 0) {
            $('#examDiv').addClass('has-error');
            error++;
        } else {
            $('#examDiv').removeClass('has-error');
        }

        if (field['classesID'] == 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
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
            url: "<?=base_url('terminalreport/getTerminalreport')?>",
            data: passData,
            dataType: "html",
            success: function(data) {
                var response = JSON.parse(data);
                console.log('response', data)
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_terminalreport').html(response.render);
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


