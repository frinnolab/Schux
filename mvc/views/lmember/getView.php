<?php if(count($student)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-7">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?=btn_add_pdf('lmember/print_preview/'.$student->studentID."/".$set, $this->lang->line('pdf_preview'))?>
                <?php
                    if(count($lmember)) {
                        if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1)) {
                            if(permissionChecker('lmember_edit')) {
                                echo btn_sm_edit('lmember/edit/'.$student->studentID."/".$set, $this->lang->line('edit'));
                            }
                        }
                    } 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("lmember/index/$set")?>"><?=$this->lang->line('panel_title')?></a></li>
                    <li class="active"><?=$this->lang->line('view')?></li>
                </ol>
            </div>

        </div>
    </div>
<?php } ?>

<?php if(count($student)) { ?>
    <div id="printablediv">
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <?=profileviewimage($student->photo)?>
                        <h3 class="student-username text-center"><?=$student->srname ?: $student->name ?></h3>
                        <p class="text-muted text-center"><?=isset($usertypes[$student->usertypeID]) ? $usertypes[$student->usertypeID] : ''?></p>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('lmember_registerNO')?></b> <a class="pull-right"><?=$student->srregisterNO?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('lmember_roll')?></b> <a class="pull-right"><?=$student->srroll?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('lmember_classes')?></b> <a class="pull-right"><?=count($classes) ? $classes->classes : ''?></a>
                            </li>
                            <li class="list-group-item" style="background-color: #FFF">
                                <b><?=$this->lang->line('menu_section')?></b> <a class="pull-right"><?=count($section) ? $section->section : ''?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-sm-9">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab"><?=$this->lang->line('lmember_profile')?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="active tab-pane" id="profile">
                            <div class="panel-body profile-view-dis">
                                <div class="row">
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_dob")?> </span>: 
                                        <?php if($student->dob) { echo date("d M Y", strtotime($student->dob)); } else { echo ''; }?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_sex")?> </span>: <?=$student->sex?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_email")?> </span>: <?=$student->email?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_phone")?> </span>: <?=$student->phone?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_lID")?> </span>: <?=count($lmember) ? $lmember->lID : 'N/A'?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_lfee")?> </span>: <?=count($lmember) ? $lmember->lbalance : 'N/A'?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_joindate")?> </span>: <?=count($lmember) ? date("d M Y", strtotime($lmember->ljoindate)) : 'N/A'?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_religion")?> </span>: <?=$student->religion?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_bloodgroup")?> </span>: <?php if(isset($allbloodgroup[$student->bloodgroup])) { echo $student->bloodgroup; } ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_state")?> </span>: <?=$student->state?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_country")?> </span>: <?php if(isset($allcountry[$student->country])) { echo $allcountry[$student->country]; } ?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_remarks")?> </span>: <?=$student->remarks?></p>
                                    </div>

                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_extracurricularactivities")?> </span>: <?=$student->extracurricularactivities?></p>
                                    </div>
                                    <div class="profile-view-tab">
                                        <p><span><?=$this->lang->line("lmember_address")?> </span>: <?=$student->address?></p>
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

<?php if(count($student)) { ?>
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
            var id = "<?=$student->studentID;?>";
            var set = "<?=$set;?>";
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
                    url: "<?=base_url('lmember/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message+ "&set=" + set,
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