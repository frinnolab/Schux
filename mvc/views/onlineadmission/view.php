<?php if(count($admissioninfo)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-8">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?=btn_add_pdf('onlineadmission/print_preview/'.$admissioninfo->onlineadmissionID, $this->lang->line('pdf_preview'))?>
                <a class="btn-cs btn-sm-cs" href="<?=base_url('onlineadmission/approve/'.$admissioninfo->onlineadmissionID.'/'.$admissioninfo->classesID)?>"><span class="fa fa-plus"></span> <?=$this->lang->line('onlineadmission_approve')?></a>

                <a class="btn-cs btn-sm-cs" onclick="return confirm('This cannot be undone. are you sure?')" href="<?=base_url('onlineadmission/waiting/'.$admissioninfo->onlineadmissionID.'/'.$admissioninfo->classesID)?>"><span class="fa fa-circle-o"></span> <?=$this->lang->line('onlineadmission_waiting')?></a>
                
                <a class="btn-cs btn-sm-cs" onclick="return confirm('you are about to decline the record. This cannot be undone. are you sure?')" href="<?=base_url('onlineadmission/decline/'.$admissioninfo->onlineadmissionID.'/'.$admissioninfo->classesID)?>"><span class="fa fa-close"></span> <?=$this->lang->line('onlineadmission_decline')?></a>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-4">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("onlineadmission/index/".$admissioninfo->classesID)?>"><?=$this->lang->line('menu_onlineadmission')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($admissioninfo->photo)?>
                        <h3 class="profile-username text-center"><?=$admissioninfo->name?></h3>
                        <p class="text-muted text-center"><?=$usertype->usertype?></p>

                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('onlineadmission_admissionID')?></b> 
                                <a class="pull-right">
                                    <?php
                                        $admissionIDlen = strlen($admissioninfo->onlineadmissionID);
                                        $boxLimit = 8;

                                        if($admissionIDlen >= $boxLimit) {
                                            $boxLimit += 2;
                                        }

                                        $zerolength = ($boxLimit - $admissionIDlen);
                                        if($zerolength > 0) {
                                            for($i=1; $i <= $zerolength; $i++) {
                                                echo '0';
                                            }
                                        }
                                        $admissionIDArray = str_split($admissioninfo->onlineadmissionID);
                                        if(count($admissionIDArray)) {
                                            foreach ($admissionIDArray as $value) {
                                                echo $value;
                                            }
                                        }
                                    ?>
                                </a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('onlineadmission_apply_classes')?></b> <a class="pull-right"><?=count($classes) ? $classes->classes : ''?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('onlineadmission_status')?></b> 
                                <a class="pull-right">
                                    <?php
                                        if ($admissioninfo->status == 2) {
                                            echo '<span class="text-yellow">'.$this->lang->line('onlineadmission_waiting').'</span>';
                                        } else {
                                            echo '<span class="text-blue">'.$this->lang->line('onlineadmission_new').'</span>';
                                        }
                                    ?>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('onlineadmission_profile')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="panel-body profile-view-dis">
                                <div class="row">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_dob")?> </span>: 
                                        <?php if($admissioninfo->dob) { echo date("d M Y", strtotime($admissioninfo->dob)); } ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_gender")?> </span>: 
                                        <?=$admissioninfo->sex?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_religion")?> </span>: <?=$admissioninfo->religion?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_email")?> </span>: <?=$admissioninfo->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_phone")?> </span>: <?=$admissioninfo->phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_country")?> </span>: 
                                        <?php if(isset($allcountry[$admissioninfo->country])) { echo $allcountry[$admissioninfo->country]; } ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("onlineadmission_address")?> </span>: <?=$admissioninfo->address?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form class="form-horizontal" role="form" action="<?=base_url('onlineadmission/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="to" class="col-sm-2 control-label">
                                <?=$this->lang->line("to")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="to_error">
                            </span>
                        </div>

                        <div class="form-group">
                            <label for="subject" class="col-sm-2 control-label">
                                <?=$this->lang->line("subject")?> <span class="text-red">*</span>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                            </div>
                            <span class="col-sm-4 control-label" id="subject_error">
                            </span>
                        </div>

                        <div class="form-group">
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
                        <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>"/>
                    </div>
                </div>
            </div>
        </div>
    </form>

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

        $('#send_pdf').click(function() {
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var id = "<?=$admissioninfo->onlineadmissionID;?>";
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
                    url: "<?=base_url('onlineadmission/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&onlineadmissionID=" + id+ "&message=" + message,
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
<?php } ?>
