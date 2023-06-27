<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-duefeesreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_duefeesreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("duefeesreport_class")?></label>
                    <?php
                        $classesArray = array(
                            "0" => $this->lang->line("duefeesreport_please_select"),
                        );
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("duefeesreport_section")?></label>
                    <?php
                        $sectionArray = array(
                            "0" => $this->lang->line("duefeesreport_please_select"),
                        );
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="studentDiv">
                    <label><?=$this->lang->line("duefeesreport_student")?></label>
                    <?php
                        $studentArray = array(
                            "0" => $this->lang->line("duefeesreport_please_select"),
                        );
                        echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="feetypeDiv">
                    <label><?=$this->lang->line("duefeesreport_feetype")?></label>
                    <?php
                        $feetypeArray = array(
                            "0" => $this->lang->line("duefeesreport_please_select"),
                        );
                        if(count($feetypes)) {
                            foreach($feetypes as $feetype) {
                                $feetypeArray[$feetype->feetypesID] = $feetype->feetypes;
                            }
                        }
                        echo form_dropdown("feetypeID", $feetypeArray, set_value("feetypeID"), "id='feetypeID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label for="fromdate" ><?=$this->lang->line("duefeesreport_fromdate")?></label>
                    <input type="text" name="fromdate" class="form-control" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("duefeesreport_todate")?></label>
                    <input type="text" name="todate" class="form-control" id="todate">
                </div>



                <div class="col-sm-4">
                    <button id="get_duefeesreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("duefeesreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_duefeesreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('duefeesreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();
    $(function(){
        $('#sectionDiv').hide('slow');
        $('#studentDiv').hide('slow');

        $('#fromdate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

        $('#todate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

    });

    $(document).bind('click', '#fromdate, #todate', function() {
        
        $('#fromdate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });

        $('#todate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate:'<?=$schoolyearsessionobj->startingdate?>',
            endDate:'<?=$schoolyearsessionobj->endingdate?>',
        });
    });

    $(document).on('change', "#classesID", function() {
        $('#load_duefeesreport').html("");
        var classesID = $(this).val();

        $('#sectionID').val(0);
        $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("duefeesreport_please_select")?>" +"</option>");
        $('#studentID').val(0);

        if(classesID == '0'){
            $("#sectionDiv").hide('slow');
            $("#studentDiv").hide('slow');
        } else {
            $("#sectionDiv").show('slow');
            $("#studentDiv").show('slow');
        }

        if(classesID !=0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('duefeesreport/getSection')?>",
                data: {"classesID" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#schoolyearDiv", function() {
        $('#load_duefeesreport').html("");
    });

    $(document).on('change', "#sectionID", function() {
        $('#load_duefeesreport').html("");
        var sectionID = $(this).val();
        var classesID = $('#classesID').val();

        $('#studentID').html("<option value='0'>" + "<?=$this->lang->line("duefeesreport_please_select")?>" +"</option>");
        $('#studentID').val(0);

        if(sectionID !=0 && classesID != 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('duefeesreport/getStudent')?>",
                data: {"classesID":classesID,"sectionID" : sectionID},
                dataType: "html",
                success: function(data) {
                   $('#studentID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_duefeesreport', function() {
        $('#load_duefeesreport').html("");
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();
        var studentID = $('#studentID').val();
        var feetypeID = $('#feetypeID').val();
        var fromdate  = $('#fromdate').val();
        var todate    = $('#todate').val();
        var error = 0;

        var field = {
            "classesID" : classesID,
            "sectionID" : sectionID,
            "studentID" : studentID,
            "feetypeID" : feetypeID,
            "fromdate"  : fromdate,
            "todate"    : todate,
        }

        if(fromdate != '' && todate == '') {
            error++;
            $('#todateDiv').addClass('has-error');
        } else{
            $('#todateDiv').removeClass('has-error');
        }

        if(fromdate == '' && todate != '') {
            error++;
            $('#fromdateDiv').addClass('has-error');
        } else {
            $('#fromdateDiv').removeClass('has-error');
        }

        if(fromdate != '' && todate != '') {
            var fromdate = fromdate.split('-');
            var todate = todate.split('-');
            var currentdate = new Date();
            var newfromdate = new Date(fromdate[2], fromdate[1]-1, fromdate[0]);
            var newtodate   = new Date(todate[2], todate[1]-1, todate[0]);

            if(newfromdate.getTime() > newtodate.getTime()) {
                error++;
                $('#todateDiv').addClass('has-error');
            } else {
                $('#todateDiv').removeClass('has-error');
            }

        }

        if(error == 0 ) {
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
            url: "<?=base_url('duefeesreport/getDueFeesReport')?>",
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
            $('#load_duefeesreport').html(response.render);
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


