<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-recycle"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_studentsessionreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="studentDiv">
                    <label><?=$this->lang->line("studentsessionreport_student")?> <span class="text-red"> * </span></label>
                    <?php
                        $studentArray[0] = $this->lang->line("studentsessionreport_please_select");
                        if(count($students)) {
                            foreach ($students as $student) {
                                $labelname = " ( ".(($student->email) ? $student->email : (($student->username) ? $student->username : ''))." ) ";
                                $studentArray[$student->studentID] = $student->name. $labelname;
                            }
                        }
                        echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                     ?>
                </div>
                <div class="col-sm-4">
                    <button id="get_studentsessionreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("studentsessionreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_studentsessionreport"></div>

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

    $(document).on('click','#get_studentsessionreport', function() {
        $('#load_studentsessionreport').html("");
     
        var error = 0;
        var field = {
            'studentID'   : $('#studentID').val(), 
        };

        if (field['studentID'] == 0) {
            $('#studentDiv').addClass('has-error');
            error++;
        } else {
            $('#studentDiv').removeClass('has-error');
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
            url: "<?=base_url('studentsessionreport/getstudentsessionreport')?>",
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
            $('#load_studentsessionreport').html(response.render);
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


