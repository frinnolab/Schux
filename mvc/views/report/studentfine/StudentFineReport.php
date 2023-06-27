<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('studentfinereport/pdf/'.strtotime($fromdate).'/'.strtotime($todate));
            $xml_preview_uri = base_url('studentfinereport/xlsx/'.strtotime($fromdate).'/'.strtotime($todate));
            echo btn_printReport('studentfinereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('studentfinereport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('studentfinereport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('studentfinereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
            <?=$this->lang->line('studentfinereport_report_for')?> - <?=$this->lang->line('studentfinereport_studentfine')?> 
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($fromdate !='' && $todate !='') { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left"><?=$this->lang->line('studentfinereport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></h5>                         
                                <h5 class="pull-right"><?=$this->lang->line('studentfinereport_todate')?> : <?=date('d M Y',strtotime($todate))?></h5>                        
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12">
                    <?php if(count($studentfines)) { ?>
                        <div id="hide-table">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('studentfinereport_slno');?></th>
                                        <th><?=$this->lang->line('studentfinereport_date');?></th>
                                        <th><?=$this->lang->line('studentfinereport_name');?></th>
                                        <th><?=$this->lang->line('studentfinereport_registerNO');?></th>
                                        <th><?=$this->lang->line('studentfinereport_class');?></th>
                                        <th><?=$this->lang->line('studentfinereport_section');?></th>
                                        <th><?=$this->lang->line('studentfinereport_roll');?></th>
                                        <th><?=$this->lang->line('studentfinereport_feetype');?></th>
                                        <th><?=$this->lang->line('studentfinereport_fine');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $total_fine = 0; $i=0; foreach($studentfines as $studentfine) { $i++;?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('studentfinereport_slno');?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_date');?>">
                                                <?=date('d M Y',strtotime($studentfine->paymentdate))?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_name');?>"><?=$studentfine->srname?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_registerNO');?>"><?=$studentfine->srregisterNO?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_class');?>">
                                                <?=isset($classes[$studentfine->srclassesID]) ? $classes[$studentfine->srclassesID] : ' '?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_section');?>">
                                                <?=isset($sections[$studentfine->srsectionID]) ? $sections[$studentfine->srsectionID] : ' '?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_roll');?>"><?=$studentfine->srroll?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_feetype');?>"><?=$studentfine->feetypes?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_fine');?>">
                                                <?php
                                                    echo number_format($studentfine->fine,2);
                                                    $total_fine += $studentfine->fine;
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('studentfinereport_grand_total');?>" colspan="8" class="text-right text-bold"><?=$this->lang->line('studentfinereport_grand_total')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                            <td data-title="<?=$this->lang->line('studentfinereport_total_fine');?>" class="text-bold"><?=number_format($total_fine,2)?></td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } else {  ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('studentfinereport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('routinereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('studentfinereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('studentfinereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("studentfinereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentfinereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("studentfinereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("studentfinereport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('studentfinereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            $("#to_error").html("<?=$this->lang->line('studentfinereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('studentfinereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('studentfinereport/send_pdf_to_mail')?>",
                data: field,
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