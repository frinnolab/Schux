<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productpurchasereport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_productpurchasereport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="supplierDiv">
                    <label><?=$this->lang->line("productpurchasereport_supplier")?></label>
                    <?php
                    $supplierArray = array(
                        "0" => $this->lang->line("productpurchasereport_please_select"),
                    );
                    if(count($productsuppliers)) {
                        foreach($productsuppliers as $productsupplier) {
                            $supplierArray[$productsupplier->productsupplierID] = $productsupplier->productsuppliercompanyname;
                        }
                    }
                    echo form_dropdown("productsupplierID", $supplierArray, set_value("productsupplierID"), "id='productsupplierID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="WarehouseDiv">
                    <label><?=$this->lang->line("productpurchasereport_warehouse")?></label>
                    <?php
                    $wareHouseArray = array(
                        "0" => $this->lang->line("productpurchasereport_please_select"),
                    );
                    if(count($productwarehouses)) {
                        foreach($productwarehouses as $productwarehouse) {
                            $wareHouseArray[$productwarehouse->productwarehouseID] = $productwarehouse->productwarehousename;
                        }
                    }
                    echo form_dropdown("productwarehouseID", $wareHouseArray, set_value("productwarehouseID"), "id='productwarehouseID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="referenceNoDiv">
                    <label><?=$this->lang->line("productpurchasereport_referenceNo")?></label>
                    <input class="form-control" type="text" name="reference_no" id="reference_no">
                </div>

                <div class="form-group col-sm-4" id="statusDiv">
                    <label><?=$this->lang->line("productpurchasereport_status")?></label>
                    <?php
                    $array = array(
                        "0" => $this->lang->line("productpurchasereport_please_select"),
                        "1" => $this->lang->line("productpurchasereport_pending"),
                        "2" =>$this->lang->line("productpurchasereport_partial"),
                        "3" => $this->lang->line("productpurchasereport_fully_paid"),
                        "4" => $this->lang->line("productpurchasereport_refund")
                    );
                    echo form_dropdown("statusID", $array, set_value("statusID"), "id='statusID' class='form-control select2'");
                    ?>
                </div>

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label><?=$this->lang->line("productpurchasereport_fromdate")?></label>
                   <input class="form-control" type="text" name="fromdate" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("productpurchasereport_todate")?></label>
                    <input class="form-control" type="text" name="todate" id="todate">
                </div>

                <div class="col-sm-4">
                    <button id="get_productpurchasereport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("productpurchasereport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_productpurchasereport"></div>


<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('productpurchasereport_hotline')?> : <?=$siteinfos->phone?></center>";
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


    $('#get_productpurchasereport').click(function() {

        var productsupplierID = $('#productsupplierID').val();
        var productwarehouseID = $('#productwarehouseID').val();
        var reference_no = $('#reference_no').val();
        var statusID = $('#statusID').val();
        var fromdate = $('#fromdate').val();
        var todate   = $('#todate').val();

        var field = {
            'productsupplierID': productsupplierID,
            'productwarehouseID': productwarehouseID,
            'reference_no': reference_no,
            'statusID': statusID,
            'fromdate': fromdate,
            'todate': todate
        };

        makingPostDataPreviousofAjaxCall(field);
    });

    function makingPostDataPreviousofAjaxCall(field) {
        passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('productpurchasereport/getProductpurchaseReport')?>",
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
            $('#load_productpurchasereport').html(response.render);
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
