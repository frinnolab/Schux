<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-searchpaymentfeesreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_searchpaymentfeesreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">

            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="gspaymentIDDiv">
                    <label><?=$this->lang->line("searchpaymentfeesreport_invoice_number")?><span class="text-red"> * </span></label>
                   <input class="form-control" type="text" name="gspaymentID" id="gspaymentID">
                </div>

                <div class="col-sm-4">
                    <button id="get_searchpaymentfeesreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("searchpaymentfeesreport_submit")?></button>
                </div>

            </div>

        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_searchpaymentfeesreport"></div>


<script type="text/javascript">

    function printDiv(data) {
        res = data.split('=');
        printDiv = res[0];
        printData = res[1];
        if((printData == 'INV-S-') || (printData == 'inv-s-')) {
            var oldPage = document.body.innerHTML;
            var divElements = document.getElementById(printDiv).innerHTML;
            document.body.innerHTML = "<html><head><title></title></head><body>" + divElements + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
            window.location.reload();
        } else if((printData == 'INV-G-') || (printData == 'inv-g-')) {
            var oldPage = document.body.innerHTML;
            $('#headerImage').remove();
            $('.footerAll').remove();
            var divElements = document.getElementById(printDiv).innerHTML;
            var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
            var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('searchpaymentfeesreport_hotline')?> : <?=$siteinfos->phone?></center>";
            document.body.innerHTML = "<html><head><title></title></head><body>" + "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>" + divElements + footer + copyright + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
            window.location.reload();
        }
    }

    $('#get_searchpaymentfeesreport').click(function() {
        var gspaymentID = $('#gspaymentID').val();
        var count = gspaymentID.length;
        var gspayment = gspaymentID.substring(-count, 6);
        var ID =  gspaymentID.substring(6,count);
        var error = 0;

        if(gspaymentID == '') {
            error++;
            $('#gspaymentIDDiv').addClass('has-error');
        } else if(count <= 6) {
            error++;
            $('#gspaymentIDDiv').addClass('has-error');
        } else if((gspayment == "INV-G-") || gspayment == "inv-g-") {
            if(Math.floor(ID) == ID && $.isNumeric(ID)) {
                $('#gspaymentIDDiv').removeClass('has-error');
            } else {
                error++;
                $('#gspaymentIDDiv').addClass('has-error');
            }
        } else if((gspayment == "INV-S-") || (gspayment == "inv-s-")) {
            if(Math.floor(ID) == ID && $.isNumeric(ID)) {
                $('#gspaymentIDDiv').removeClass('has-error');
            } else {
                error++;
                $('#gspaymentIDDiv').addClass('has-error');
            }
        } else if((gspayment != 'INV-S-') || (gspayment != 'INV-G-') || (gspayment != 'inv-s-') || (gspayment != 'inv-g-')) {
            error++;
            $('#gspaymentIDDiv').addClass('has-error');
        } else {
            $('#gspaymentIDDiv').removeClass('has-error');
        }

        var field = {
            'gspaymentID': gspaymentID
        };

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
            url: "<?=base_url('searchpaymentfeesreport/getSearchPaymentFeesReport')?>",
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
            $('#load_searchpaymentfeesreport').html(response.render);
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
