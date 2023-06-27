<?php if(count($user)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-7">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('manage_salary/print_preview/'.$userID."/".$usertypeID, $this->lang->line('pdf_preview')) 
                ?>
                <?php if(permissionChecker('manage_salary_edit')) { echo btn_sm_edit('manage_salary/edit/'.$userID."/".$usertypeID, $this->lang->line('edit')); } ?>

                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>

            <div class="col-sm-5">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("manage_salary/index/$usertypeID")?>"><?=$this->lang->line('menu_manage_salary')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>
<?php } ?>

<?php if(count($user)) { ?>
    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($user->photo)?>
                        <h3 class="student-username text-center"><?=$user->name?></h3>
                        <p class="text-muted text-center"><?=count($usertype) ? $usertype->usertype : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('manage_salary_gender')?></b> <a class="pull-right"><?=$user->sex?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('manage_salary_dob')?></b> <a class="pull-right"><?=($user->dob) ? date('d M Y', strtotime($user->dob)) : '';?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('manage_salary_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#salaryinfo" data-toggle="tab"><?=$this->lang->line('manage_salary_salary_info')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="salaryinfo">
                            <br>
                            <?php if($manage_salary->salary == 1) { ?>
                                <div class="row">
                                    <div class="col-sm-6" style="margin-bottom: 10px;">
                                        <div class="info-box">
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("manage_salary_salary_grades")?></span>
                                                <?=$salary_template->salary_grades?>
                                            </p>

                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("manage_salary_basic_salary")?></span>
                                                <?=number_format($salary_template->basic_salary, 2)?>
                                            </p>

                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("manage_salary_overtime_rate")?></span>
                                                <?=number_format($salary_template->overtime_rate, 2)?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="box" style="border: 1px solid #eee">
                                            <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('manage_salary_allowances')?></h3>
                                            </div>
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-12" id="allowances">
                                                        <div class="info-box">
                                                            <?php 
                                                                if(count($salaryoptions)) { 
                                                                    foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                        if($salaryoption->option_type == 1) {
                                                            ?>
                                                                <p>
                                                                    <span><?=$salaryoption->label_name?></span>
                                                                    <?=number_format($salaryoption->label_amount, 2)?>
                                                                </p>
                                                            <?php        
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="box" style="border: 1px solid #eee;">
                                            <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('manage_salary_deductions')?></h3>
                                            </div><!-- /.box-header -->
                                            <!-- form start -->
                                            <div class="box-body">
                                                <div class="row">
                                                    <div class="col-sm-12" id="deductions">
                                                        <div class="info-box">
                                                            <?php 
                                                                if(count($salaryoptions)) { 
                                                                    foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                        if($salaryoption->option_type == 2) {
                                                            ?>
                                                                <p>
                                                                    <span><?=$salaryoption->label_name?></span>
                                                                    <?=number_format($salaryoption->label_amount, 2)?>
                                                                </p>
                                                            <?php        
                                                                        }
                                                                    }
                                                                }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div class="box" style="border: 1px solid #eee;">
                                            <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('manage_salary_total_salary_details')?></h3>
                                            </div>
                                            <div class="box-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('manage_salary_gross_salary')?></td>
                                                        <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('manage_salary_total_deduction')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('manage_salary_net_salary')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($netsalary, 2)?></b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } elseif($manage_salary->salary == 2) { ?>
                                <div class="row">
                                    <div class="col-sm-6" style="margin-bottom: 10px;">
                                        <div class="info-box">
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("manage_salary_salary_grades")?></span>
                                                <?=$hourly_salary->hourly_grades?>
                                            </p>

                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("hourly_template_hourly_rate")?></span>
                                                <?=number_format($hourly_salary->hourly_rate, 2)?>
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div class="box" style="border: 1px solid #eee;">
                                            <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('manage_salary_total_salary_details')?></h3>
                                            </div>
                                            <div class="box-body">
                                                <table class="table table-bordered">
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('manage_salary_gross_salary')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><?=number_format($grosssalary, 2)?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('manage_salary_total_deduction')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><?=number_format($totaldeduction, 2)?></td>
                                                    </tr>

                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('hourly_template_net_hourly_rate')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($netsalary, 2)?></b></td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('lmember/send_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
            </div>
            <div class="modal-body">
            
                <?php 
                    if(form_error('to')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("to")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="to_error">
                    </span>
                </div>

                <?php 
                    if(form_error('subject')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="subject" class="col-sm-2 control-label">
                        <?=$this->lang->line("subject")?> <span class="text-red">*</span>
                    </label>
                    <div class="col-sm-6">
                        <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                    </div>
                    <span class="col-sm-4 control-label" id="subject_error">
                    </span>

                </div>

                <?php 
                    if(form_error('message')) 
                        echo "<div class='form-group has-error' >";
                    else     
                        echo "<div class='form-group' >";
                ?>
                    <label for="message" class="col-sm-2 control-label">
                        <?=$this->lang->line("message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>

            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->  
<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        var divElements = document.getElementById(divID).innerHTML;
        var oldPage = document.body.innerHTML;
        document.body.innerHTML = 
          "<html><head><title></title></head><body>" + 
          divElements + "</body>";
        window.print();

        document.body.innerHTML = oldPage;
        window.location.reload();
    }
    function closeWindow() {
        location.reload(); 
    }

    function check_email(email) {
        var status = false;     
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $("#send_pdf").click(function(){
        var to = $('#to').val();
        var subject = $('#subject').val();
        var message = $('#message').val();
        var userID = "<?=$userID;?>";
        var usertypeID = "<?=$usertypeID;?>";
        var error = 0;

        $("#to_error").html("");
        if(to == "" || to == null) {
            error++;
            $("#to_error").html("");
            $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        } 

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("");
            $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('manage_salary/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&message=" + message+ "&userID=" + userID + "&usertypeID=" + usertypeID,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        $.each(response, function(index, value) {
                            if(index != 'status') {
                                toastr["error"](value)
                                toastr.options = {
                                  "closeButton": true,
                                  "debug": false,
                                  "newestOnTop": false,
                                  "progressBar": false,
                                  "positionClass": "toast-top-right",
                                  "preventDuplicates": false,
                                  "onclick": null,
                                  "showDuration": "500",
                                  "hideDuration": "500",
                                  "timeOut": "5000",
                                  "extendedTimeOut": "1000",
                                  "showEasing": "swing",
                                  "hideEasing": "linear",
                                  "showMethod": "fadeIn",
                                  "hideMethod": "fadeOut"
                                }
                            }
                        });
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>

