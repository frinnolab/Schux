
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-studentreport"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_studentreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="reportDiv">
                    <label><?=$this->lang->line("studentreport_report_for")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                             "blood" => $this->lang->line("studentreport_blood_group"),
                             "country" => $this->lang->line("studentreport_country"),
                             "gender" => $this->lang->line("studentreport_gender"),
                             "transport" => $this->lang->line("studentreport_transport"),
                             "hostel" => $this->lang->line("studentreport_hostel"),
                             "birthday" => $this->lang->line("studentreport_birthday")
                         );
                         echo form_dropdown("reportfor", $array, set_value("reportfor"), "id='reportfor' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="bloodDiv">
                    <label><?=$this->lang->line("studentreport_blood_group")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                             "A+" => 'A+',
                             "A-" => 'A-',
                             "B+" => 'B+',
                             "B-" => 'B-',
                             "O+" => 'O+',
                             "O-" => 'O-',
                             "AB+" => 'AB+',
                             "AB-" => 'AB-',
                         );
                         echo form_dropdown("blood", $array, set_value("blood"), "id='blood' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="countryDiv">
                    <label><?=$this->lang->line("studentreport_country")?><span class="text-red"> * </span></label>
                    <?php
                          $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                          );
                          if(count($allcountry)) {
                            foreach ($allcountry as $key => $value) {
                              $array[$key] = $value;
                            }
                          }
                        echo form_dropdown("country", $array, set_value("country"), "id='country' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="transportDiv">
                    <label><?=$this->lang->line("studentreport_route")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                         );
                         foreach ($transports as $key => $value) {
                             $array[$value->transportID] = $value->route;
                         }
                         echo form_dropdown("transport", $array, set_value("transport"), "id='transport' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="hostelDiv">
                    <label><?=$this->lang->line("studentreport_hostel")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                         );
                          if(count($hostels)) {
                             foreach ($hostels as $key => $value) {
                               $array[$value->hostelID] = $value->name;
                            }
                          }
                         echo form_dropdown("hostel", $array, set_value("hostel"), "id='hostel' class='form-control select2'");
                     ?>
                </div>


                <div class="form-group col-sm-4" id="birthdaydateDiv">
                    <label for="birthdaydate"><?=$this->lang->line("studentreport_birthdaydate")?><span class="text-red"> * </span></label>
                    <input type="text" name="birthdaydate" class="form-control" id="birthdaydate">
                </div>

                <div class="form-group col-sm-4" id="genderDiv">
                    <label><?=$this->lang->line("studentreport_gender")?><span class="text-red"> * </span></label>
                    <?php
                         $array = array(
                             "0" => $this->lang->line("studentreport_please_select"),
                             "Male" => $this->lang->line("studentreport_male"),
                             "Female" => $this->lang->line("studentreport_female"),
                         );
                         echo form_dropdown("gender", $array, set_value("gender"), "id='gender' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("studentreport_class")?></label>
                    <?php
                          $array = array("0" => $this->lang->line("studentreport_please_select"));
                          if(count($classes)) {
                            foreach ($classes as $classa) {
                               $array[$classa->classesID] = $classa->classes;
                            }
                          }
                         echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("studentreport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value="0"><?php echo $this->lang->line("studentreport_please_select"); ?></option>
                    </select>
                </div>

                <div class="col-sm-4">
                    <button id="get_classreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("studentreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_studentreport"></div>
<script type="text/javascript">
    
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('studentreport_hotline')?> : <?=$siteinfos->phone?></center>";
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
        $('#birthdaydate').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
        });

        $("#reportfor").val(0);
        $("#classesID").val(0);
        $("#sectionID").val(0);
        $("#bloodDiv").hide();
        $("#countryDiv").hide();
        $("#transportDiv").hide();
        $("#hostelDiv").hide();
        $("#genderDiv").hide();
        $("#birthdaydateDiv").hide();
        $("#classesDiv").show();
        $("#sectionDiv").hide();
    });

    $(document).on('change','#reportfor', function() {
        var reportfor = $(this).val();
        if(reportfor == 0) {
            $("#bloodDiv").hide('slow');
            $("#countryDiv").hide('slow');
            $("#genderDiv").hide('slow');
            $("#transportDiv").hide('slow');
            $("#hostelDiv").hide('slow');
            $("#birthdaydateDiv").hide('slow');
            $("#sectionDiv").hide('slow');
            $("#sectionID").val('0');
            $("#classesID").val('0');
        } else if(reportfor == 'blood') {
            $("#blood").val('0');
            $("#bloodDiv").show("slow");
            $("#countryDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
            $("#birthdaydateDiv").hide();

        } else if(reportfor == 'country') {
            $("#country").val('0');
            $("#bloodDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
            $("#countryDiv").show("slow");
            $("#birthdaydateDiv").hide();

        } else if(reportfor == 'gender') {
            $("#gender").val('0');
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#transportDiv").hide();
            $("#hostelDiv").hide();
            $("#genderDiv").show("slow");
            $("#birthdaydateDiv").hide();

        } else if(reportfor == 'transport') {
            $("#transport").val('0');
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#hostelDiv").hide();
            $("#genderDiv").hide();
            $("#transportDiv").show("slow");
            $("#birthdaydateDiv").hide();

        } else if(reportfor == 'hostel') {
            $("#hostel").val('0');
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#transportDiv").hide();
            $("#genderDiv").hide();
            $("#birthdaydateDiv").hide();
            $("#hostelDiv").show("slow");
        }else if(reportfor == 'birthday') {
            $("#birthdaydateDiv").val('0');
            $("#bloodDiv").hide();
            $("#countryDiv").hide();
            $("#transportDiv").hide();
            $("#genderDiv").hide();
            $("#hostelDiv").hide();
            $("#birthdaydateDiv").show("slow");
        }
        $('#load_studentreport').html('');
    });


    $(document).on('change','#classesID', function() {
        var classesID = $(this).val();
        if(classesID == '0') {
            $("#sectionDiv").hide("slow");
        } else {
            $("#sectionDiv").show("slow");
        }
        $('#load_studentreport').html('');
        if(classesID == '0') {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("studentreport_please_select")?>"+'</option>');
            $('#sectionID').val('');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentreport/getSection')?>",
                data: {"id" : classesID},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_classreport',function(){
        var error = 0;
        var field = {
            'reportfor' : $('#reportfor').val(),
            'blood'     : $('#blood').val(),
            'country'   : $('#country').val(),
            'transport' : $('#transport').val(),
            'hostel'    : $('#hostel').val(),
            'birthdaydate' : $('#birthdaydate').val(),
            'gender'    : $('#gender').val(),
            'classesID' : $('#classesID').val(),
            'sectionID' : $('#sectionID').val(),
        };

        error = validation_checker(field, error);

        if(error === 0) {
            makingPostDataPreviousofAjaxCall(field);
        }
    });

    function validation_checker(field, error) {
        if (field['reportfor'] == 0) {
            $('#reportDiv').addClass('has-error');
            error++;
        } else {
            $('#reportDiv').removeClass('has-error');
        }

        if (field['reportfor'] == 'blood') {
            if (field['blood'] == 0) {
                $('#bloodDiv').addClass('has-error');
                error++;
            } else {
                $('#bloodDiv').removeClass('has-error');
            }
        }

        if (field['reportfor'] == 'country') {
            if (field['country'] == 0) {
                $('#countryDiv').addClass('has-error');
                error++;
            } else {
                $('#countryDiv').removeClass('has-error');
            }
        }

        if (field['reportfor'] == 'gender') {
            if (field['gender'] == 0) {
                $('#genderDiv').addClass('has-error');
                error++;
            } else {
                $('#genderDiv').removeClass('has-error');
            }
        }

        if (field['reportfor'] == 'transport') {
            if (field['transport'] == 0) {
                $('#transportDiv').addClass('has-error');
                error++;
            } else {
                $('#transportDiv').removeClass('has-error');
            }
        }

        if (field['reportfor'] == 'hostel') {
            if (field['hostel'] == 0) {
                $('#hostelDiv').addClass('has-error');
                error++;
            } else {
                $('#hostelDiv').removeClass('has-error');
            }
        }

        if (field['reportfor'] == 'birthday') {
            var birthdaydate = field['birthdaydate'].toString();
            if (isValidDate(birthdaydate) == false) {
                $('#birthdaydateDiv').addClass('has-error');
                error++;
            } else {
                $('#birthdaydateDiv').removeClass('has-error');
            }
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
            url: "<?=base_url('studentreport/getStudentReport')?>",
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
            $('#load_studentreport').html(response.render);
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


    function isValidDate(date) {
        var date = date.toString();
        var temp = date.split('-');
        var d = new Date(temp[2] + '/' + temp[1] + '/' + temp[0]);
        return (d && (d.getMonth() + 1) == temp[1] && d.getDate() == Number(temp[0]) && d.getFullYear() == Number(temp[2]));
    }
    
</script>
