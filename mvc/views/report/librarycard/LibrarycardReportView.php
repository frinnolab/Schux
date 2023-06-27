<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-librarycardreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_librarycardreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label for="classesID"><?=$this->lang->line("librarycardreport_class")?> <span class="text-red">*</span></label>
                    <?php
                        $classesArray['0'] = $this->lang->line("librarycardreport_please_select");
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label for="sectionID" ><?=$this->lang->line("librarycardreport_section")?></label>
                    <?php
                        $sectionArray = array(
                            "0" => $this->lang->line("librarycardreport_please_select"),
                        );
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="studentDiv">
                    <label id="studentDivlabel" for="studentID"><?=$this->lang->line("librarycardreport_student")?></label>
                    <?php
                        $userArray['0'] = $this->lang->line("librarycardreport_please_select");
                        echo form_dropdown("studentID", $userArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="typeDiv">
                    <label for="type"><?=$this->lang->line("librarycardreport_type")?> <span class="text-red">*</span></label>
                    <?php
                        $typeArray = array(
                            '0' => $this->lang->line("librarycardreport_please_select"),
                            '1' => $this->lang->line("librarycardreport_frontpart"),
                            '2' => $this->lang->line("librarycardreport_backpart"),
                        );
                        echo form_dropdown("type", $typeArray, set_value("type"), "id='type' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="backgroundDiv">
                    <label for="background"><?=$this->lang->line("librarycardreport_background")?> <span class="text-red">*</span></label>
                    <?php
                        $backgroundArray = array(
                            '0' => $this->lang->line("librarycardreport_please_select"),
                            '1' => $this->lang->line("librarycardreport_yes"),
                            '2' => $this->lang->line("librarycardreport_no"),
                        );
                        echo form_dropdown("background", $backgroundArray, set_value("background"), "id='background' class='form-control select2'");
                     ?>
                </div>

                <div class="col-sm-4">
                    <button id="get_librarycardreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("librarycardreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_librarycardreport"></div>


<script type="text/javascript">
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $(function() {
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#studentID").val(0);
        $("#type").val(0);
        $("#background").val(0);
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');
        $(".select2").select2();
    });

    $(document).on('change', "#classesID", function() {
        $('#load_librarycardreport').html('');
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("librarycardreport_please_select")?>"+'</option>');
        } else {
            $('#sectionDiv').show('slow');
            $.ajax({
                type:'POST',
                url:'<?=base_url('librarycardreport/getSection')?>',
                data:{'classesID':classesID},
                success:function(data) {
                    $('#sectionID').html(data);
                },
            });
        }
    });

    $(document).on('change', "#sectionID", function() {
        $('#load_librarycardreport').html('');
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        $('#studentDiv').show('slow');

        $.ajax({
            type:'POST',
            url:'<?=base_url('librarycardreport/getStudent')?>',
            data:{'classesID':classesID,'sectionID':sectionID},
            success:function(data) {
                $('#studentID').html(data);
            }
        });
    });

    $(document).on('change', "#studentID", function() {
        $('#load_librarycardreport').html("");
    });


    $(document).on('change', "#type", function() {
        $('#load_librarycardreport').html("");
    });

    $(document).on('change', "#background", function() {
        $('#load_librarycardreport').html("");
    });

    $(document).on('click','#get_librarycardreport', function() {
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        var studentID = $('#studentID').val();
        var type      = $('#type').val();
        var background= $('#background').val();

        var error = 0;
        var field = {
            'classesID' : classesID,
            'sectionID' : sectionID,
            'studentID' : studentID,
            'type'      : type,
            'background': background,
        }

        if(classesID == 0 ) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }

        if(type == 0 ) {
            $('#typeDiv').addClass('has-error');
            error++;
        } else {
            $('#typeDiv').removeClass('has-error');
        }

        if(background == 0 ) {
            $('#backgroundDiv').addClass('has-error');
            error++;
        } else {
            $('#backgroundDiv').removeClass('has-error');
        } 

        if(error == 0 ) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function makingPostDataPreviousofAjaxCall(field) {
        var passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type:'POST',
            url:'<?=base_url('librarycardreport/getLibrarycardReport')?>',
            data:passData,
            dataType:'html',
            success:function(data) {
                var response = JSON.parse(data);
                renderLoder(response, passData);
            }
        });
    }

    function renderLoder(response, passData) {
        if(response.status) {
            $('#load_librarycardreport').html(response.render);
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


