<?php if(count($complain)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?=btn_add_pdf('complain/print_preview/'.$complain->complainID, $this->lang->line('pdf_preview'))?>

                <?php if($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) { if(permissionChecker('complain_edit')) { echo btn_sm_edit('complain/edit/'.$complain->complainID, $this->lang->line('edit')); }} 
                ?>

                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
          </div>
          <div class="col-sm-6">
              <ol class="breadcrumb">
                  <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                  <li><a href="<?=base_url("complain/index")?>"><?=$this->lang->line('menu_complain')?></a></li>
                  <li class="active"><?=$this->lang->line('view')?></li>
              </ol>
          </div>
      </div>   
    </div>

    <div id="printablediv">
        <div class="row">
            <?php if(count($user)) { ?>
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <img class="profile-user-img img-responsive img-circle" src="<?=base_url('uploads/images/'.$user->photo)?>">
                        <h3 class="profile-username text-center">
                            <?php 
                                if($user->usertypeID == 3) {
                                    echo $user->srname;
                                } else {
                                    echo $user->name;
                                }
                            ?>
                        </h3>
                        <?php if($user->usertypeID == 2) { ?>
                            <p class="text-muted text-center"><?=$user->designation?></p>
                        <?php } else { ?>
                            <p class="text-muted text-center"><?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?></p>
                        <?php } ?>
                        <ul class="list-group list-group-unbordered">
                            <?php if($user->usertypeID == 4) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                </li>
                            <?php } elseif($user->usertypeID == 3) { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_registerNO')?></b> <a class="pull-right"><?=$user->srregisterNO?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_roll')?></b> <a class="pull-right"><?=$user->srroll?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_class')?></b> <a class="pull-right"><?=count($classes) ? $classes->classes : ''?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_section')?></b> <a class="pull-right"><?=count($section) ? $section->section : ''?></a>
                                </li>
                            <?php } else { ?>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_gender')?></b> <a class="pull-right"><?=$user->sex?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_dob')?></b> <a class="pull-right"><?=date('d M Y',strtotime($user->dob))?></a>
                                </li>
                                <li class="list-group-item" style="background-color: #FFF">
                                    <b><?=$this->lang->line('complain_phone')?></b> <a class="pull-right"><?=$user->phone?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="<?php if(count($user)) { echo 'col-sm-6 col-mergin'; } else { echo 'col-sm-9'; } ?>">
                <div class="box box-primary box-height">
                    <div class="box-body box-profile">
                        <h3><?=$complain->title?></h3>
                        <p><?=$complain->description?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 <?php if(!count($user)) { echo 'col-mergin'; } ?>">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <div class="single_box">
                            <strong><?=$this->lang->line('complain_create_date');?></strong>
                            <p class="text-muted"><?=date("d M Y - h:i:s A",strtotime($complain->create_date))?></p>
                        </div>
                        <div class="single_box">
                            <strong><?=$this->lang->line('complain_modify_date');?></strong>
                            <p class="text-muted"><?=date("d M Y - h:i:s A",strtotime($complain->modify_date))?></p>
                        </div>
                        <div class="single_box">
                            <strong><?=$this->lang->line('complain_complainer_name');?></strong>
                            <p class="text-muted"><?=count($createinfo) ? $createinfo->name : '';?></p>
                        </div>
                        <div class="single_box">
                            <strong><?=$this->lang->line('complain_complainer_role');?></strong>
                            <p class="text-muted"><?=count($createinfo) ? $createinfo->usertype : '' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" role="form" action="<?=base_url('complain/send_mail');?>" method="post">
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

    <script type="text/javascript">
        function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML ="<html><head><title></title></head><body>" +divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
            window.location.reload();
        }

        function check_email(email) {
            var status = false;
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if(email.search(emailRegEx) == -1) {
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
            var complainID = "<?=$complain->complainID;?>";
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
                    url: "<?=base_url('complain/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&complainID=" + complainID+ "&message=" + message,
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

<?php  } ?>
