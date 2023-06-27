<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-accountledgerreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_accountledgerreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="schoolyearIDDiv">
                    <label><?=$this->lang->line("accountledgerreport_academicyear")?></label>
                    <?php
                        $array = [];
                        $array['0'] = $this->lang->line("accountledgerreport_all_accademic_year");
                        if(count($schoolyears)) {
                            foreach ($schoolyears as $schoolyear) {
                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                            }
                        }
                        echo form_dropdown("schoolyearID", $array , set_value("schoolyearID"), "id='schoolyearID' class='form-control select2'");
                     ?>
                </div>
                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label><?=$this->lang->line("accountledgerreport_fromdate")?></label>
                    <input type="text" name="fromdate" class="form-control fromdate" id="fromdate">
                </div>
                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("accountledgerreport_todate")?></label>
                    <input type="text" name="todate" class="form-control todate" id="todate">
                </div>
                <div class="col-sm-4">
                    <button id="get_accountledgerreport" class="btn btn-success"> <?=$this->lang->line("accountledgerreport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_accountledgerreport"></div>


<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('accountledgerreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }

    $('.select2').select2();

    $('#fromdate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
    });

    $('#todate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
    });
    
    $(document).on('change',"#schoolyearID", function(event) {
        event.preventDefault();
        $('#load_accountledgerreport').html('');
        $("#fromdate").val('');
        $('#todate').val('');
    });

    $(document).on('change',"#fromdate", function() {
        $('#load_accountledgerreport').html('');
    });

    $(document).on('change',"#todate", function() {
        $('#load_accountledgerreport').html('');
    });


    $(document).on('click','#get_accountledgerreport', function() {
        $('#load_accountledgerreport').html('');
        var passData;
        var error = 0;
        var field = {
            'schoolyearID': $("#schoolyearID").val(),
            'fromdate'    : $("#fromdate").val(),
            'todate'      : $('#todate').val(), 
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
            url: "<?=base_url('accountledgerreport/getaccountledgerreport')?>",
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
            $('#load_accountledgerreport').html(response.render);
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


