<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-onlineadmissionreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_onlineadmissionreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="schoolyearIDDiv">
                    <label><?=$this->lang->line("onlineadmissionreport_academicyear")?> <span class="text-red">*</span></label>
                    <?php
                        $academicyearArray = [];
                        $academicyearArray['0'] = $this->lang->line("onlineadmissionreport_please_select");
                        if(count($schoolyears)) {
                            foreach ($schoolyears as $schoolyear) {
                                $academicyearArray[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                            }
                        }
                        echo form_dropdown("schoolyearID", $academicyearArray , set_value("schoolyearID"), "id='schoolyearID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="classesIDDiv">
                    <label><?=$this->lang->line("onlineadmissionreport_class")?> </label>
                    <?php
                        $classArray = [];
                        $classArray['0'] = $this->lang->line("onlineadmissionreport_please_select");
                        if(count($classes)) {
                            foreach ($classes as $class) {
                                if($siteinfos->ex_class != $class->classesID) {
                                    $classArray[$class->classesID] = $class->classes;
                                }
                            }
                        }
                        echo form_dropdown("classesID", $classArray , set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="statusDiv">
                    <label><?=$this->lang->line("onlineadmissionreport_status")?> </label>
                    <?php
                        $statusArray['10'] = $this->lang->line("onlineadmissionreport_please_select");
                        $statusArray['0'] = $this->lang->line("onlineadmissionreport_new");
                        $statusArray['1'] = $this->lang->line("onlineadmissionreport_approved");
                        $statusArray['2'] = $this->lang->line("onlineadmissionreport_waiting");
                        $statusArray['3'] = $this->lang->line("onlineadmissionreport_decline");
                        echo form_dropdown("status", $statusArray , set_value("status"), "id='status' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="phoneDiv">
                    <label><?=$this->lang->line("onlineadmissionreport_phone")?><span class="text-red">*</span> </label>
                    <?php
                        $phoneArray['0'] = $this->lang->line("onlineadmissionreport_please_select");
                        $phoneArray['1'] = $this->lang->line("onlineadmissionreport_enable");
                        $phoneArray['2'] = $this->lang->line("onlineadmissionreport_disable");
                        echo form_dropdown("phone", $phoneArray , set_value("phone"), "id='phone' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="admissionIDDiv">
                    <label><?=$this->lang->line("onlineadmissionreport_admissionID")?></label>
                    <input type="text" name="admissionID" class="form-control" id="admissionID">
                </div>
                <div class="col-sm-4">
                    <button id="get_onlineadmissionreport" class="btn btn-success" style="margin-top:23px;"><?=$this->lang->line("onlineadmissionreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_onlineadmissionreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('onlineadmissionreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();
    
    $(document).on('change',"#schoolyearID", function(event) {
        event.preventDefault();
        $('#load_onlineadmissionreport').html('');
    });

    $(document).on('change',"#classesID", function() {
        $('#load_onlineadmissionreport').html('');
    });
    $(document).on('change',"#status", function() {
        $('#load_onlineadmissionreport').html('');
    });
    $(document).on('change',"#phone", function() {
        $('#load_onlineadmissionreport').html('');
    });
    $(document).on('change',"#admissionID", function() {
        $('#load_onlineadmissionreport').html('');
    });


    $(document).on('click','#get_onlineadmissionreport', function() {
        $('#load_onlineadmissionreport').html('');

        var error = 0;        
        var schoolyearID = $("#schoolyearID").val();
        var classesID    = $("#classesID").val();
        var status       = $('#status').val();
        var phone        = $('#phone').val();
        var admissionID  = $('#admissionID').val();


        if(schoolyearID == '0') {
            error++;
            $('#schoolyearIDDiv').addClass('has-error');
        } else {
            $('#schoolyearIDDiv').removeClass('has-error');
        }

        if(admissionID != '') {
            if((Math.floor(admissionID) == admissionID) && $.isNumeric(admissionID)) {
                $('#admissionIDDiv').removeClass('has-error');
            } else {
                error++;
                $('#admissionIDDiv').addClass('has-error');
            }
        }

        if(phone == '0') {
            error++;
            $('#phoneDiv').addClass('has-error');
        } else {
            $('#phoneDiv').removeClass('has-error');
        }

        var field = {
            'schoolyearID': schoolyearID,
            'classesID'   : classesID,
            'status'      : status,
            'phone'      : phone,
            'admissionID' : admissionID
        };

        if(error == 0) {
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
            url: "<?=base_url('onlineadmissionreport/getonlineadmissionreport')?>",
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
            $('#load_onlineadmissionreport').html(response.render);
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


