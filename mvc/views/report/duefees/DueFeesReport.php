<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            if($fromdate !='' && $todate !='') {
                $pdf_preview_uri = base_url('duefeesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID.'/'.strtotime($fromdate).'/'.strtotime($todate));
            } else {
                $pdf_preview_uri = base_url('duefeesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID);
            }

            if($fromdate !='' && $todate !='') {
                $xml_preview_uri = base_url('duefeesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID.'/'.strtotime($fromdate).'/'.strtotime($todate));
            } else {
                $xml_preview_uri = base_url('duefeesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID);
            }

            echo btn_printReport('duefeesreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('duefeesreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            // echo btn_xmlReport('duefeesreport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('duefeesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('duefeesreport_report_for')?> - 
            <?=$this->lang->line('duefeesreport_duefees');?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($classesID >= 0 || $sectionID >= 0 ) { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left">
                                    <?php 
                                        echo $this->lang->line('duefeesreport_class')." : ";
                                        echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('duefeesreport_all_class');
                                    ?>
                                </h5>                         
                                <h5 class="pull-right">
                                    <?php
                                       echo $this->lang->line('duefeesreport_section')." : ";
                                       echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('duefeesreport_all_section');
                                    ?>
                                </h5>
                            </div>
                        </div>
                    </div>
                <?php } 
                if(count($getDueFeesReports)) { ?>
                    <div class="col-sm-12">
                        <div id="hide-table">
                            <table class="table table-bordered table-responsive">
                                <thead>
                                    <tr>
                                        <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_invoice_date')?></th>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_name')?></th>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_registerNO')?></th>
                                        <?php if($classesID == 0) { ?>
                                          <th rowspan="2"><?=$this->lang->line('duefeesreport_class')?></th>
                                        <?php } ?>
                                        <?php if($sectionID == 0) { ?>
                                          <th rowspan="2"><?=$this->lang->line('duefeesreport_section')?></th>
                                        <?php } ?>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_roll')?></th>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_feetype')?></th>
                                        <th rowspan="2"><?=$this->lang->line('duefeesreport_discount')?> (%) </th>
                                        <th colspan="3"><?=$this->lang->line('duefeesreport_due') ?></th>
                                    </tr>
                                    <tr>
                                        <td>USD</td>
                                        <td>GBP</td>
                                        <td>TZS</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                        $totalDueUSD = 0;
                                        $totalDueGBP = 0;
                                        $totalDueTZS = 0; 
                                        $i = 0; 
                                        foreach($getDueFeesReports as $getDueFeesReport) { 
                                        if($sectionID > 0) { if(isset($students[$getDueFeesReport->studentID]) && $students[$getDueFeesReport->studentID]->srsectionID == $sectionID) { $i++; ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_invoice_date')?>"><?=date('d M Y',strtotime($getDueFeesReport->create_date))?></td>

                                                <td data-title="<?=$this->lang->line('duefeesreport_name')?>"><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '' ?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_registerNO')?>">
                                                    <?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '' ?>
                                                </td>
                                                <?php 
                                                    if($classesID == 0) { 
                                                        echo "<td data-title='".$this->lang->line('duefeesreport_class')."'>";
                                                        if(isset($students[$getDueFeesReport->studentID])) {
                                                            $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
                                                            echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                        } 
                                                        echo "</td>";
                                                    } 
                                                ?>

                                                <?php 
                                                    if($sectionID == 0) { 
                                                        echo "<td data-title='".$this->lang->line('duefeesreport_section')."'>";
                                                            if(isset($students[$getDueFeesReport->studentID])) {
                                                            $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
                                                            echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                        } 
                                                        echo "</td>";
                                                    }
                                                ?>

                                                <td data-title="<?=$this->lang->line('duefeesreport_roll')?>"><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '' ?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_feetype')?>">
                                                    <?php 
                                                        if(isset($feetypes[$getDueFeesReport->feetypeID])) {
                                                            echo $feetypes[$getDueFeesReport->feetypeID];
                                                        }
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_discount')?>"><?=number_format($getDueFeesReport->discount, 2);?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "USD") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueUSD += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueUSD += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "GBP") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueGBP += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueGBP += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "TZS") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueTZS += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueTZS += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                            </tr>
                                        <?php } } else { $i++;?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_invoice_date')?>"><?=date('d M Y',strtotime($getDueFeesReport->create_date))?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_name')?>"><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srname : '' ?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_registerNO')?>">
                                                    <?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srregisterNO : '' ?>
                                                </td>

                                                <?php 
                                                    if($classesID == 0) { 
                                                        echo "<td data-title='".$this->lang->line('duefeesreport_class')."'>";
                                                        if(isset($students[$getDueFeesReport->studentID])) {
                                                            $stclassID = $students[$getDueFeesReport->studentID]->srclassesID;
                                                            echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                        } 
                                                        echo "</td>";
                                                    } 
                                                ?>

                                                <?php 
                                                    if($sectionID == 0) { 
                                                        echo "<td data-title='".$this->lang->line('duefeesreport_section')."'>";
                                                            if(isset($students[$getDueFeesReport->studentID])) {
                                                            $stsectionID = $students[$getDueFeesReport->studentID]->srsectionID;
                                                            echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                        } 
                                                        echo "</td>";
                                                    }
                                                ?>

                                                
                                                <td data-title="<?=$this->lang->line('duefeesreport_roll')?>"><?=isset($students[$getDueFeesReport->studentID]) ? $students[$getDueFeesReport->studentID]->srroll : '' ?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_feetype')?>">
                                                    <?php 
                                                        if(isset($feetypes[$getDueFeesReport->feetypeID])) {
                                                            echo $feetypes[$getDueFeesReport->feetypeID];
                                                        }
                                                    ?>
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_discount')?>"><?=number_format($getDueFeesReport->discount, 2);?></td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "USD") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueUSD += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueUSD += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "GBP") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueGBP += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueGBP += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                                <td data-title="<?=$this->lang->line('duefeesreport_due')?>">
                                                    <?php
                                                        if ($getDueFeesReport->currency == "TZS") {
                                                            $discount = (($getDueFeesReport->amount/100)*$getDueFeesReport->discount);
                                                            if(isset($getFeesReports[$getDueFeesReport->invoiceID])) {
                                                                $due = (($getDueFeesReport->amount - $getFeesReports[$getDueFeesReport->invoiceID]) - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueTZS += $due;
                                                            } else {
                                                                $due = ($getDueFeesReport->amount - $discount);
                                                                echo number_format($due,2);
                                                                $totalDueTZS += $due;
                                                            }
                                                        }
                                                    ?>    
                                                </td>
                                            </tr>
                                    <?php  } } ?>
                                    <tr>
                                        <?php 
                                            $colspan = 7;
                                            if($classesID == 0) {
                                                $colspan = 8;
                                            }

                                            if($sectionID == 0) {
                                                $colspan = 8;
                                            }

                                            if($classesID == 0 && $sectionID == 0) {
                                                $colspan = 9;
                                            }
                                        ?>
                                        <td data-title="<?=$this->lang->line('duefeesreport_grand_total')?>" class="text-right text-bold" colspan="<?=$colspan?>">
                                            <?=$this->lang->line('duefeesreport_grand_total')?> <!--<?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : '' ?> -->
                                        </td>
                                        <td data-title="<?=$this->lang->line('duefeesreport_total_due')?>" class="text-bold"><?=number_format($totalDueUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('duefeesreport_total_due')?>" class="text-bold"><?=number_format($totalDueGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('duefeesreport_total_due')?>" class="text-bold"><?=number_format($totalDueTZS,2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12">
                        <br/>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('duefeesreport_data_not_found')?></b></p>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-sm-12 text-center footerAll">
                    <?=reportfooter($siteinfos, $schoolyearsessionobj)?>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('feesreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('duefeesreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('duefeesreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("duefeesreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("duefeesreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("duefeesreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("duefeesreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>



<script type="text/javascript">
    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('duefeesreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $("#send_pdf").click(function() {
        var field = {
            'to'         : $('#to').val(), 
            'subject'    : $('#subject').val(), 
            'message'    : $('#message').val(),
            'schoolyearID': '<?=$schoolyearID?>',
            'classesID'  : '<?=$classesID?>',
            'sectionID'  : '<?=$sectionID?>',
            'studentID'  : '<?=$studentID?>',
            'feetypeID'  : '<?=$feetypeID?>',
            'fromdate'   : '<?=$fromdate?>',
            'todate'     : '<?=$todate?>'
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('duefeesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('duefeesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('duefeesreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if(response.status == false) {
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
