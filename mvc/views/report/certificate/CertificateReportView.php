
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-diamond"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_certificatereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="classesDiv">
                    <label><?=$this->lang->line("certificatereport_class")?><span class="text-red"> * </span></label>
                    <?php
                        $array = array("0" => $this->lang->line("certificatereport_please_select"));
                        if(count($classes)) {
                            foreach ($classes as $classa) {
                                 $array[$classa->classesID] = $classa->classes;
                            }
                        }
                        echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="sectionDiv">
                    <label><?=$this->lang->line("certificatereport_section")?></label>
                    <select id="sectionID" name="sectionID" class="form-control select2">
                        <option value=""><?php echo $this->lang->line("certificatereport_please_select"); ?></option>
                    </select>
                </div>

                <div class="form-group col-sm-4" id="templateDiv">
                    <label><?=$this->lang->line("certificatereport_template")?><span class="text-red"> * </span></label>
                    <?php
                        $templateArray = array("0" => $this->lang->line("certificatereport_please_select"));
                        if(count($templates)) {
                            foreach ($templates as $template) {
                                 $templateArray[$template->certificate_templateID] = $template->name;
                            }
                        }
                        echo form_dropdown("templateID", $templateArray, set_value("templateID"), "id='templateID' class='form-control select2'");
                     ?>
                </div>


                <div class="col-sm-4">
                    <button id="get_student_list" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("certificatereport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_certificatereport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
            "<html><head><title></title></head><body>" +
            divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }

    $('.select2').select2();

    function divHide(){ 
        $('#sectionDiv').hide('slow');  
        $('#templateDiv').hide('slow');  
    }

    function divShow(){
        $('#sectionDiv').show('slow');  
        $('#templateDiv').show('slow');  
    }

    $(document).ready(function() {
        divHide();
    });

    $("#classesID").change(function() {
        $('#templateID').val('0');
        $('#sectionID').html('<option value="">'+"<?=$this->lang->line("certificatereport_please_select")?>"+'</option>');
        $('#sectionID').val(''); 

        var id = $(this).val();

        if(id == '0') {
            divHide();
        } else {
            divShow()
        }

        if(id == '0') {
            $('#sectionID').html('<option value="">'+"<?=$this->lang->line("certificatereport_please_select")?>"+'</option>');
            $('#sectionID').val('');    
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('certificatereport/getSection')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    });

    $(document).on('click','#get_student_list', function() {
        var error = 0 ;
        var field ={
            'classesID' : $('#classesID').val(), 
            'sectionID' : $('#sectionID').val(), 
            'templateID' : $('#templateID').val(), 
        }

        if(!parseInt(field['classesID'])) {
            field['classesID'] = parseInt(field['classesID']);
        }

        if (field['classesID'] === 0) {
            $('#classesDiv').addClass('has-error');
            error++;
        } else {
            $('#classesDiv').removeClass('has-error');
        }

        if (field['sectionID'] == "") {
            $('#sectionDiv').addClass('has-error');
            error++;
        } else {
            $('#sectionDiv').removeClass('has-error');
        }



        if(!parseInt(field['templateID'])) {
            field['templateID'] = parseInt(field['templateID']);
        }

        if (field['templateID'] === 0) {
            $('#templateDiv').addClass('has-error');
            error++;
        } else {
            $('#templateDiv').removeClass('has-error');
        }

        if(error === 0) {
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
            url: "<?=base_url('certificatereport/getStudentList')?>",
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
            $('#load_certificatereport').html(response.render);
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
