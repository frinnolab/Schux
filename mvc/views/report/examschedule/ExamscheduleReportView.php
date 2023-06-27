<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-examschedulereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_examschedulereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("examschedulereport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $classesArray['0'] = $this->lang->line("examschedulereport_please_select");
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="examDiv">
                    <label><?=$this->lang->line("examschedulereport_exam")?><span class="text-red"> * </span></label>
                    <?php
                        $examArray['0'] = $this->lang->line("examschedulereport_please_select");
                        echo form_dropdown("examID", $examArray, set_value("examID"), "id='examID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("examschedulereport_section")?></label>
                    <?php
                        $sectionArray['0'] = $this->lang->line("examschedulereport_please_select");
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>
                <div class="col-sm-4">
                    <button id="get_routinereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("examschedulereport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_examschedulereport"></div>


<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('examschedulereport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();

    }
    
    $(document).ready(function(){
        hideDiv();
    });

    $('.select2').select2();

    function hideDiv(){
        $("#sectionDiv").hide('slow');
    }

    function showDiv(){
        $("#sectionDiv").show('slow');
    }

    $(document).on('change', '#classesID', function() {
    	$('#load_examschedulereport').html('');
        var classesID = $(this).val();
        if(classesID == '0') {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("examschedulereport_please_select")?>"+'</option>');
            $('#sectionID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('examschedulereport/getExam')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#examID').html(data);
                }
            });
            $.ajax({
                type: 'POST',
                url: "<?=base_url('examschedulereport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('change', '#examID', function() {
        $('#load_examschedulereport').html('');
        var examID = $(this).val();
        if(examID != '0') {
            showDiv();
        } else{
            hideDiv();
        }
    });


    $(document).on('click','#get_routinereport', function() {
        $('#load_examschedulereport').html();
        var passData;
        var error = 0;
        var field = {
            'examID'     : $('#examID').val(), 
            'classesID'     : $('#classesID').val(), 
            'sectionID'     : $('#sectionID').val(), 
        };

        if (field['examID'] == 0) {
            $('#examDiv').addClass('has-error');
            error++;
        } else {
            $('#examDiv').removeClass('has-error');
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
            url: "<?=base_url('examschedulereport/getExamscheduleReport')?>",
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
            $('#load_examschedulereport').html(response.render);
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


