<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
        if($fromdate && $todate) {
            $pdf_preview_uri = base_url('leaveapplicationreport/pdf/'.$usertypeID.'/'.$classesID.'/'.$sectionID.'/'.$userID.'/'.$categoryID.'/'.$statusID.'/'.$fromdate.'/'.$todate);
        } else {
            $pdf_preview_uri = base_url('leaveapplicationreport/pdf/'.$usertypeID.'/'.$classesID.'/'.$sectionID.'/'.$userID.'/'.$categoryID.'/'.$statusID);
        }
            echo btn_printReport('leaveapplicationreport', $this->lang->line('leaveapplicationreport_print'), 'printablediv');
            echo btn_pdfPreviewReport('leaveapplicationreport',$pdf_preview_uri, $this->lang->line('leaveapplicationreport_pdf_preview'));
            echo btn_sentToMailReport('leaveapplicationreport', $this->lang->line('leaveapplicationreport_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('leaveapplicationreport_report_for')?> -
            <?=isset($usertypes[$usertypeID]) ? $usertypes[$usertypeID]: $this->lang->line('leaveapplicationreport_all_users');?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <h5 class="pull-left">
                            <?php if($fromdate && $todate) { ?>
                                <?=$this->lang->line('leaveapplicationreport_fromdate')?> : <?=date('d M Y',$fromdate)?>
                            <?php } elseif($categoryID) { ?>
                                <?=$this->lang->line('leaveapplicationreport_category')?> : <?=$categoryName?>
                            <?php } elseif($usertypeID) { ?>
                                <?=$this->lang->line('leaveapplicationreport_role')?> : <?=isset($usertypes[$usertypeID]) ? $usertypes[$usertypeID] : ''?>
                            <?php } ?>
                            </h5>  
                            <h5 class="pull-right">
                            <?php if($fromdate && $todate) { ?>
                                <?=$this->lang->line('leaveapplicationreport_todate')?> : <?=date('d M Y',$todate)?>
                            <?php } elseif($statusID) { ?>
                                <?=$this->lang->line('leaveapplicationreport_status')?> : <?=$statusName?>
                            <?php } elseif((int)$usertypeID && (int)$userID) { ?>
                                <?=$this->lang->line('leaveapplicationreport_user')?> : <?=isset($userObejct[$usertypeID][$userID]) ? $userObejct[$usertypeID][$userID]->name : '' ?>
                            <?php } ?>
                            </h5>  
                        </div>
                    </div>
                </div>

                <div class="col-sm-12">
                    <?php if (count($leaveapplications)) { ?>
                    <div id="hide-table">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('leaveapplicationreport_slno')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_applicant')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_role')?></th>
                                    <?php if($usertypeID == 3) { ?>
                                        <th><?=$this->lang->line('leaveapplicationreport_class')?></th>
                                        <th><?=$this->lang->line('leaveapplicationreport_section')?></th>
                                    <?php } ?>
                                    <th><?=$this->lang->line('leaveapplicationreport_category')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_date')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_schdule')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_days')?></th>
                                    <th><?=$this->lang->line('leaveapplicationreport_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php $i = 0; foreach($leaveapplications as $leaveapplication) { $i++; ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_slno')?>"><?=$i?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_applicant')?>"><?=isset($userObejct[$leaveapplication->create_usertypeID][$leaveapplication->create_userID]) ? $userObejct[$leaveapplication->create_usertypeID][$leaveapplication->create_userID]->name : '' ?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_role')?>"><?=isset($usertypes[$leaveapplication->create_usertypeID]) ? $usertypes[$leaveapplication->create_usertypeID] : ''?></td>
                                    <?php if($usertypeID == 3) { ?>
                                        <td data-title="<?=$this->lang->line('leaveapplicationreport_class')?>"><?=isset($classes[$leaveapplication->srclassesID]) ? $classes[$leaveapplication->srclassesID] : ''?></td>
                                        <td data-title="<?=$this->lang->line('leaveapplicationreport_section')?>"><?=isset($sections[$leaveapplication->srsectionID]) ? $sections[$leaveapplication->srsectionID] : ''?></td>
                                    <?php } ?>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_category')?>"><?=$leaveapplication->leavecategory?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_date')?>"><?=date('d M Y',strtotime($leaveapplication->apply_date))?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_schdule')?>"><?=date('d M Y',strtotime($leaveapplication->from_date))?> - <?=date('d M Y',strtotime($leaveapplication->to_date))?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_days')?>"><?=$leaveapplication->leave_days?></td>
                                    <td data-title="<?=$this->lang->line('leaveapplicationreport_status')?>">
                                        <?php 
                                            if($leaveapplication->status == 1) {
                                                echo "Approved";
                                            } elseif($leaveapplication->status == '0') {
                                                echo "Delined";
                                            } else {
                                                echo "Pending";
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>   
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('leaveapplicationreport_data_not_found')?></b></p>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('leaveapplicationreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('leaveapplicationreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('leaveapplicationreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("leaveapplicationreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("leaveapplicationreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("leaveapplicationreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("leaveapplicationreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>
<!-- email end here -->

<script type="text/javascript">
    
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('leaveapplicationreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'usertypeID' : '<?=$usertypeID?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'userID'     : '<?=$userID?>',
            'categoryID' : '<?=$categoryID?>',
            'statusID'   : '<?=$statusID?>',
            'fromdate'   : '<?=$fromdate?>',
            'todate'     : '<?=$todate?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");
        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('leaveapplicationreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('leaveapplicationreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('leaveapplicationreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('leaveapplicationreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 
                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('leaveapplicationreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
                        }
                        if(response.message) {
                            toastr["error"](response.message)
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
                    } else {
                        location.reload();
                    }
                }
            });
        }
    });
</script>