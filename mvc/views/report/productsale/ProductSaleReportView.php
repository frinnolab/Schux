<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productsalereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_productsalereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="productsalecustomertypeDiv">
                    <label><?=$this->lang->line("productsalereport_role")?></label>
                    <?php
                    $usertypeArray = array(0 => $this->lang->line("productsalereport_please_select"));
                    if(count($usertypes)) {
                        foreach ($usertypes as $usertype) {
                            $usertypeArray[$usertype->usertypeID] = $usertype->usertype;
                        }
                    }
                    echo form_dropdown("productsalecustomertypeID", $usertypeArray, set_value("productsalecustomertypeID"), "id='productsalecustomertypeID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4 hide" id="productsaleclassesDiv">
                    <label for="productsaleclassesID"><?=$this->lang->line("productsalereport_classes")?></label>
                    <?php
                    $classesArray = array(0 => $this->lang->line("productsalereport_please_select"));
                    if(count($classes)) {
                        foreach ($classes as $classa) {
                            $classesArray[$classa->classesID] = $classa->classes;
                        }
                    }
                    echo form_dropdown("productsaleclassesID", $classesArray, set_value("productsaleclassesID"), "id='productsaleclassesID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="productsalecustomerDiv">
                    <label for="productsalecustomerID"><?=$this->lang->line("productsalereport_user")?></label>
                    <?php
                    $productsalecustomerArray = array(0 => $this->lang->line("productsalereport_please_select"));
                    echo form_dropdown("productsalecustomerID", $productsalecustomerArray, set_value("productsalecustomerID"), "id='productsalecustomerID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="referenceNoDiv">
                    <label><?=$this->lang->line("productsalereport_referenceNo")?></label>
                    <input class="form-control" type="text" name="reference_no" id="reference_no">
                </div>

                <div class="form-group col-sm-4" id="statusDiv">
                    <label><?=$this->lang->line("productsalereport_status")?></label>
                    <?php
                    $array = array(
                        "0" => $this->lang->line("productsalereport_please_select"),
                        "1" => $this->lang->line("productsalereport_pending"),
                        "2" => $this->lang->line("productsalereport_partial"),
                        "3" => $this->lang->line("productsalereport_fully_paid"),
                        "4" => $this->lang->line("productsalereport_refund"),
                    );
                    echo form_dropdown("statusID", $array, set_value("statusID"), "id='statusID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label><?=$this->lang->line("productsalereport_fromdate")?></label>
                   <input class="form-control" type="text" name="fromdate" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("productsalereport_todate")?></label>
                    <input class="form-control" type="text" name="todate" id="todate">
                </div>

                <div class="col-sm-4">
                    <button id="get_productsalereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("productsalereport_submit")?></button>
                </div>
            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_productsalereport"></div>

<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('productsalereport_hotline')?> : <?=$siteinfos->phone?></center>";
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

    $('#productsalecustomertypeID, #productsaleclassesID').change(function(event) {
        var productsalecustomertypeID = $('#productsalecustomertypeID').val();
        var productsaleclassesID = $('#productsaleclassesID').val();

        if(productsalecustomertypeID === '3') {
            $('#productsaleclassesDiv').removeClass('hide');
        } else {
            $('#productsaleclassesDiv').addClass('hide');
        }

        if(productsalecustomertypeID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('productsalereport_please_select')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productsalereport/getuser')?>",
                data: {'productsalecustomertypeID' : productsalecustomertypeID, 'productsaleclassesID' : productsaleclassesID},
                dataType: "html",
                success: function(data) {
                    $('#productsalecustomerID').html(data);
                }
            });
        }
    });


    $('#get_productsalereport').click(function() {
        var productsalecustomertypeID = $('#productsalecustomertypeID').val();
        var productsaleclassesID = $('#productsaleclassesID').val();
        var productsalecustomerID = $('#productsalecustomerID').val();
        var reference_no = $('#reference_no').val();
        var statusID = $('#statusID').val();
        var fromdate = $('#fromdate').val();
        var todate   = $('#todate').val();
        var error = 0;

        var field = {
            'productsalecustomertypeID': productsalecustomertypeID,
            'productsaleclassesID': productsaleclassesID,
            'productsalecustomerID': productsalecustomerID,
            'reference_no': reference_no,
            'statusID': statusID,
            'fromdate': fromdate,
            'todate': todate
        };

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
            url: "<?=base_url('productsalereport/getProductSaleReport')?>",
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
            $('#load_productsalereport').html(response.render);
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
