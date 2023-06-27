<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-admitcardreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_admitcardreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("admitcardreport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $classesArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                        );
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }   
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="examDiv">
                    <label><?=$this->lang->line("admitcardreport_exam")?><span class="text-red"> * </span></label>
                    <?php
                        $examArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                        );
                        echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("admitcardreport_section")?></label>
                    <?php
                        $sectionArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                        );
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="studentDiv">
                    <label><?=$this->lang->line("admitcardreport_student")?></label>
                    <?php
                        $studentArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                        );
                        echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="typeDiv">
                    <label><?=$this->lang->line("admitcardreport_type")?><span class="text-red"> * </span></label>
                    <?php
                        $typeArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                            "1" => $this->lang->line("admitcardreport_frontpart"),
                            "2" => $this->lang->line("admitcardreport_backpart"),
                        );
                        echo form_dropdown("typeID", $typeArray, set_value("typeID"), "id='typeID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="backgroundDiv">
                    <label><?=$this->lang->line("admitcardreport_background")?><span class="text-red"> * </span></label>
                    <?php
                        $backgroundArray = array(
                            "0" => $this->lang->line("admitcardreport_please_select"),
                            "1" => $this->lang->line("admitcardreport_yes"),
                            "2" => $this->lang->line("admitcardreport_no"),
                        );
                        echo form_dropdown("backgroundID", $backgroundArray, set_value("backgroundID"), "id='backgroundID' class='form-control select2'");
                     ?>
                </div>



                <div class="col-sm-4">
                    <button id="get_admitcardreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("admitcardreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_admitcardreport"></div>


<script type="text/javascript">
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();

    $(function(){
        $("#examID").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#studentID").val(0);
        $('#classesDiv').show('slow');
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');
    });

    $(document).on('change', "#examID", function() {
        $('#load_admitcardreport').html("");
        var examID = $(this).val();
        if(examID == '0'){
            $("#sectionDiv").hide('slow');
            $('#studentDiv').hide('slow');
        } else {
            $("#sectionDiv").show('slow');
            $('#studentDiv').show('slow');
        }
    });

    $(document).on('change', "#classesID", function() {
        $('#load_admitcardreport').html("");
        var classesID = $(this).val();
        if(classesID == '0'){
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("admitcardreport_please_select")?>"+'</option>');
            $('#sectionID').val('0'); 
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("admitcardreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('admitcardreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
            $.ajax({
                type: 'POST',
                url: "<?=base_url('admitcardreport/getExam')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });
        }
    });



    $(document).on('change',"#sectionID", function() {
        $('#load_admitcardreport').html("");
        var sectionID = $(this).val();
        var classesID = $("#classesID").val();
        if(sectionID == '0') {
            $('#studentID').html('<option value="0">'+"<?=$this->lang->line("admitcardreport_please_select")?>"+'</option>');
            $('#studentID').val('0');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('admitcardreport/getStudent')?>",
                data: {"classesID":classesID,"sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });


    $(document).on('change',"#studentID", function() {
        $('#load_admitcardreport').html('');
    });

    $(document).on('click','#get_admitcardreport', function() {
        $('#load_admitcardreport').html('');
        var passData;
        var error = 0;
        var field = {
            'examID'      : $("#examID").val(),
            'classesID'   : $('#classesID').val(), 
            'sectionID'   : $('#sectionID').val(), 
            'studentID'   : $('#studentID').val(), 
            'typeID'      : $('#typeID').val(), 
            'backgroundID': $('#backgroundID').val(), 
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

        if (field['typeID'] == 0) {
            $('#typeDiv').addClass('has-error');
            error++;
        } else {
            $('#typeDiv').removeClass('has-error');
        }

        if (field['backgroundID'] == 0) {
            $('#backgroundDiv').addClass('has-error');
            error++;
        } else {
            $('#backgroundDiv').removeClass('has-error');
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
            url: "<?=base_url('admitcardreport/getAdmitcardReport')?>",
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
            $('#load_admitcardreport').html(response.render);
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


