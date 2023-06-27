<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-salaryreport"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"> <?=$this->lang->line('menu_salaryreport')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group col-sm-4" id="usertypeIDDiv">
                    <label for="usertypeID"><?=$this->lang->line("salaryreport_salary_for")?></label>
                    <?php
                        $usertypesArray['0'] = $this->lang->line("salaryreport_please_select");
                        if(count($usertypes)) {
                            foreach($usertypes as $usertype) {
                                $usertypesArray[$usertype->usertypeID] = $usertype->usertype;
                            }
                        }
                        echo form_dropdown("usertypeID", $usertypesArray, set_value("usertypeID"), "id='usertypeID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="userDiv">
                    <label id="userDivlabel" for="userID"><?=$this->lang->line("salaryreport_user")?></label>
                    <?php
                        $userArray['0'] = $this->lang->line("salaryreport_please_select");
                        echo form_dropdown("userID", $userArray, set_value("userID"), "id='userID' class='form-control select2'");
                     ?>
                </div>

                <div class="form-group col-sm-4" id="monthsdateeDiv">
                    <label for="monthsdate" ><?=$this->lang->line("salaryreport_month")?></label>
                    <input type="text" name="monthsdate" class="form-control" id="monthsdate">
                </div>

                <div class="form-group col-sm-4" id="fromdateDiv">
                    <label for="fromdate" ><?=$this->lang->line("salaryreport_fromdate")?></label>
                    <input type="text" name="fromdate" class="form-control" id="fromdate">
                </div>

                <div class="form-group col-sm-4" id="todateDiv">
                    <label><?=$this->lang->line("salaryreport_todate")?></label>
                    <input type="text" name="todate" class="form-control" id="todate">
                </div>

                <div class="col-sm-4">
                    <button id="get_salaryreport" class="btn btn-success" style="margin-top:23px;"> <?=$this->lang->line("salaryreport_submit")?></button>
                </div>

            </div>
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<div id="load_salaryreport"></div>

<script type="text/javascript">

    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('salaryreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();
    }


    $(function() {
        var str1 = '<?=$schoolyearsessionobj->startingyear?>';
        var str2 = '<?=$schoolyearsessionobj->startingmonth?>-';
        var startingmonth = str2.concat(str1);

        var end1 = '<?=$schoolyearsessionobj->endingyear?>';
        var end2 = '<?=$schoolyearsessionobj->endingmonth?>-';
        var endingmonth = end2.concat(end1);

        $("#monthsdate").datepicker( {
            autoclose: true,
            format: "mm-yyyy",
            minViewMode: 1,
            startDate:startingmonth,
            endDate:endingmonth
        });

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


    $(function(){
        $("#userID").val(0);
        $("#usertypeID").val(0);
        $('#userDiv').hide('slow');
        $(".select2").select2();
    });

    $(document).on('change', "#usertypeID", function() {
        $('#load_salaryreport').html("");
        var usertypeID = $(this).val();
        var userlabelText = $('#usertypeID option:selected').text();
        var error = 0;

        $('#userDivlabel').text(userlabelText);
        if(usertypeID == '0') {
            $('#userDiv').hide('slow');
        } else {
            $("#userID").val(0);
            $('#userDiv').show('slow');
        }

        var passData = {
            'usertypeID':usertypeID,
        }

        if(usertypeID > 0)  {
            $.ajax({
                type : 'POST',
                url  : '<?=base_url('salaryreport/getUser')?>',
                data : passData,
                success : function(data) {
                    $('#userID').html(data);
                }
            });
        }
    });

    $(document).on('change', "#userID", function() {
        $('#load_salaryreport').html("");
    });

    $(document).on('click','#get_salaryreport', function() {
        var usertypeID = $('#usertypeID').val();
        var userID     = $('#userID').val();
        var month      = $('#monthsdate').val();
        var fromdate   = $('#fromdate').val();
        var todate     = $('#todate').val();
        var error = 0;

        var field = {
            'usertypeID': usertypeID,
            'userID'    : userID,
            "month"     : month,
            "fromdate"  : fromdate,
            "todate"    : todate
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
        var passData = field;
        ajaxCall(passData);
    }

    function ajaxCall(passData) {
        $.ajax({
            type:'POST',
            url:'<?=base_url('salaryreport/getSalaryReport')?>',
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
            $('#load_salaryreport').html(response.render);
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


