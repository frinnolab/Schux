

<?php if(count($user)) { ?>
    <div class="well">
        <div class="row">

            <div class="col-sm-7">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('make_payment/print_preview/'.$make_payment->make_paymentID, $this->lang->line('pdf_preview')) 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>

            <div class="col-sm-5">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("make_payment/add/$userID/$usertypeID")?>"><?=$this->lang->line('menu_make_payment')?></a></li>
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
                                <b><?=$this->lang->line('make_payment_gender')?></b> <a class="pull-right"><?=$user->sex?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('make_payment_dob')?></b> <a class="pull-right"><?=($user->dob) ? date('d M Y', strtotime($user->dob)) : '';?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('make_payment_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#salary" data-toggle="tab"><?=$this->lang->line('make_payment_salary')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="salary">
                            <br>
                            <div class="row">
                                <div class="col-sm-6" style="margin-bottom: 10px;">
                                    <div class="info-box">
                                        <?php if(isset($make_payment->total_hours)) { ?>
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("make_payment_salary_grades")?></span>
                                                <?=$persent_salary_template->hourly_grades?>
                                            </p>
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("make_payment_hourly_rate")?></span>
                                                <?=number_format($persent_salary_template->hourly_rate, 2)?>
                                            </p>
                                        <?php } else { ?>
                                            <?php if($make_payment->salaryID == 1) { ?>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_salary_grades")?></span>
                                                    <?=$persent_salary_template->salary_grades?>
                                                </p>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_basic_salary")?></span>
                                                    <?=number_format($persent_salary_template->basic_salary, 2)?>
                                                </p>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_overtime_rate")?></span>
                                                    <?=number_format($persent_salary_template->overtime_rate, 2)?>
                                                </p>
                                            <?php } ?>
                                        <?php } ?>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_month")?></span>
                                            <?=date('M Y', strtotime('1-'.$make_payment->month))?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_date")?></span>
                                            <?=date('d M Y', strtotime($make_payment->create_date))?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_payment_method")?></span>
                                            <?=isset($paymentMethod[$make_payment->payment_method]) ? $paymentMethod[$make_payment->payment_method] : ''?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_comments")?></span>
                                            <?=$make_payment->comments?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php if($make_payment->salaryID == 1) { ?>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="box" style="border: 1px solid #eee">
                                            <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_allowances')?></h3>
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
                                                <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_deductions')?></h3>
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
                            <?php } ?>

                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <div class="box" style="border: 1px solid #eee;">
                                        <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                            <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_total_salary_details')?></h3>
                                        </div>
                                        <div class="box-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_gross_salary')?></td>
    
                                                    <?php 
                                                        $total_gross_salary = 0;
                                                        if($make_payment->salaryID == 1) {
                                                            $total_gross_salary = ($make_payment->gross_salary+$persent_salary_template->basic_salary);
                                                        } else {
                                                            $total_gross_salary = $make_payment->gross_salary;
                                                        }
                                                    ?>
                                                    <td class="col-sm-4" style="line-height: 36px"><?=number_format($total_gross_salary, 2)?></td>
                                                </tr>

                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_total_deduction')?></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><?=number_format($make_payment->total_deduction, 2)?></td>
                                                </tr>

                                                <?php 
                                                    if(isset($make_payment->total_hours)) {
                                                       $net_hourly_rate = ($make_payment->total_hours * $make_payment->net_salary);
                                                       $net_hourly_rate_grp = '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .')';
                                                ?>
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_total_hours')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px">
                                                        <?=number_format($make_payment->total_hours, 2)?></td>
                                                    </tr>
                                                <?php } ?>

                                                <?php if(isset($make_payment->total_hours)) { ?>
                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_net_hourly_rate')?> <span class="text-red"><?=$net_hourly_rate_grp?></span></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><b>
                                                    <?=number_format($net_hourly_rate, 2)?></b></td>
                                                </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_net_salary')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($make_payment->net_salary, 2)?></b></td>
                                                    </tr>
                                                <?php  } ?>

                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><b><?=$this->lang->line('make_payment_payment_amount')?></b></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($make_payment->payment_amount, 2)?></b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        var paymentID = "<?=$make_payment->make_paymentID;?>";
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
                url: "<?=base_url('make_payment/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject + "&message=" + message + "&paymentID=" + paymentID,
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

