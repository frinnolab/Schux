<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leaveapplicationreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_leaveapplicationreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="usertypeIDDIV">
                    <label for="usertypeID"><?=$this->lang->line("leaveapplicationreport_role")?></label>
                    <?php
                        $usertypesArray['0'] = $this->lang->line("leaveapplicationreport_please_select");
                        if(count($usertypes)) {
                            foreach($usertypes as $usertype) {
                                if($usertype->usertypeID == 4) {
                                    continue;
                                }
                                $usertypesArray[$usertype->usertypeID] = $usertype->usertype;
                            }
                        }
                        echo form_dropdown("usertypeID", $usertypesArray, set_value("usertypeID"), "id='usertypeID' class='form-control select2'");
                     ?>
                </div>


                <div class="form-group col-sm-4" id="classesDiv">
                    <label for="classesID"><?=$this->lang->line("leaveapplicationreport_class")?></label>
                    <?php
                        $classesArray['0'] = $this->lang->line("leaveapplicationreport_please_select");
                        if(count($classes)) {
                            foreach ($classes as $classaKey => $classa) {
                                $classesArray[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label for="sectionID" ><?=$this->lang->line("leaveapplicationreport_section")?></label>
                    <?php
                        $sectionArray = array(
                            "0" => $this->lang->line("leaveapplicationreport_please_select"),
                        );
                        echo form_dropdown("sectionID", $sectionArray, set_value("sectionID"), "id='sectionID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="userDiv">
                    <label for="userID"><?=$this->lang->line("leaveapplicationreport_user")?></label>
                    <?php
                        $userArray['0'] = $this->lang->line("leaveapplicationreport_please_select");
                        echo form_dropdown("userID", $userArray, set_value("userID"), "id='userID' class='form-control select2'");
                     ?>
                </div> 

                <div class="form-group col-sm-4" id="categoryDiv">
                    <label for="categoryID"><?=$this->lang->line("leaveapplicationreport_category")?></label>
                    <?php
                        $categoryArray['0'] = $this->lang->line("leaveapplicationreport_please_select");
                        if(count($leavecategories)) {
                            foreach ($leavecategories as $category) {
                                $categoryArray[$category->leavecategoryID] = $category->leavecategory;
                            }
                        }
                        echo form_dropdown("categoryID", $categoryArray, set_value("categoryID"), "id='categoryID' class='form-control select2'");
                     ?>
                </div> 

                <div class="form-group col-sm-4" id="statusDiv">
                    <label for="statusID"><?=$this->lang->line("leaveapplicationreport_status")?></label>
                    <?php
                        $statusArray['0'] = $this->lang->line("leaveapplicationreport_please_select");
                        $statusArray['1'] = $this->lang->line("leaveapplicationreport_status_pending");
                        $statusArray['2'] = $this->lang->line("leaveapplicationreport_status_declined");
                        $statusArray['3'] = $this->lang->line("leaveapplicationreport_status_approved");
                        echo form_dropdown("statusID", $statusArray, set_value("statusID"), "id='statusID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label for="type"><?=$this->lang->line("leaveapplicationreport_fromdate")?></label>
                    <input type="text" name="fromdate" class="form-control" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label for="type"><?=$this->lang->line("leaveapplicationreport_todate")?></label>
                    <input type="text" name="todate" class="form-control" id="todate">
                </div>

                <div class="col-sm-4">
                    <button id="get_leaveapplicationreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("leaveapplicationreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_leaveapplicationreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        var divElements = document.getElementById(divID).innerHTML;
        document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }


    $(function(){
        $("#routinefor").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#userID").val(0);
        $("#categoryID").val(0);
        $("#statusID").val(0);
        $('#classesDiv').hide('slow');
        $('#sectionDiv').hide('slow');
        $('#userDiv').hide('slow');
        $(".select2").select2();
        
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
    });

    $(document).on('change', "#usertypeID", function() {
        $('#load_leaveapplicationreport').html("");
        var usertypeID = $(this).val();
        var classesID = $("#classesID").val();
        var sectionID = $("#sectionID").val();
        var userText = $('#usertypeID option:selected').text();
        var error = 0;

        $('#userDivlabel').text(userText);
        if(usertypeID == '0'){
            $('#classesDiv').hide('slow');
            $('#sectionDiv').hide('slow');
            $('#userDiv').hide('slow');
        } else if(usertypeID == '3') {
            $("#classesID").val(0);
            $("#sectionID").val(0);
            $("#userID").val(0);
            $('#classesDiv').show('slow');
            $('#sectionDiv').show('slow');
            $('#userDiv').show('slow');
        } else if(usertypeID != '0' && usertypeID !='3') {
            $("#classesID").val(0);
            $("#sectionID").val(0);
            $("#userID").val(0);
            $('#classesDiv').hide('slow');
            $('#sectionDiv').hide('slow');
            $('#userDiv').show('slow');
        }

        var passData = {
            'usertypeID':usertypeID,
        }

        if(usertypeID > 0)  {
            $.ajax({
                type : 'POST',
                url  : '<?=base_url('leaveapplicationreport/getUser')?>',
                data : passData,
                success : function(data) {
                    $('#userID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#classesID", function() {
        $('#load_leaveapplicationreport').html('');
        var usertypeID = $('#usertypeID').val();
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#sectionID').html('<option value="0">'+"<?=$this->lang->line("leaveapplicationreport_please_select")?>"+'</option>');
            $('#userID').html('<option value="0">'+"<?=$this->lang->line("leaveapplicationreport_please_select")?>"+'</option>');
        } else {
            $.ajax({
                type:'POST',
                url:'<?=base_url('leaveapplicationreport/getSection')?>',
                data:{'id':classesID},
                success:function(data) {
                    $('#sectionID').html(data);
                }
            });
        }

        if(classesID > 0 && usertypeID == 3) {
            $.ajax({
                type:'POST',
                url:'<?=base_url('leaveapplicationreport/getStudentByClass')?>',
                data:{'usertypeID':usertypeID,'classesID':classesID},
                success:function(data) {
                    $('#userID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#sectionID", function() {
        $('#load_leaveapplicationreport').html('');
        var usertypeID = $('#usertypeID').val();
        var classesID = $('#classesID').val();
        var sectionID = $('#sectionID').val();

        if(classesID > 0 && usertypeID == 3) {
            $.ajax({
                type:'POST',
                url:'<?=base_url('leaveapplicationreport/getStudentBySection')?>',
                data:{'usertypeID':usertypeID,'classesID':classesID,'sectionID':sectionID},
                success:function(data) {
                    $('#userID').html('0');
                    $('#userID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#userID", function() {
        $('#load_leaveapplicationreport').html("");
    });


    $(document).on('change', "#fromdate", function() {
        $('#load_leaveapplicationreport').html("");
    });

    $(document).on('change', "#todate", function() {
        $('#load_leaveapplicationreport').html("");
    });

    $(document).on('click','#get_leaveapplicationreport', function() {
        var usertypeID = $('#usertypeID').val();
        var classesID  = $('#classesID').val();
        var sectionID  = $('#sectionID').val();
        var userID     = $('#userID').val();
        var categoryID = $('#categoryID').val();
        var statusID   = $('#statusID').val();
        var fromdate   = $('#fromdate').val();
        var todate     = $('#todate').val();
        var error = 0;
        var field = {
            'usertypeID': usertypeID,
            'classesID' : classesID,
            'sectionID' : sectionID,
            'userID'    : userID,
            'categoryID': categoryID,
            'statusID'  : statusID,
            'fromdate'  : fromdate,
            'todate': todate,
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
            url:'<?=base_url('leaveapplicationreport/getleaveapplicationreport')?>',
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
            $('#load_leaveapplicationreport').html(response.render);
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


