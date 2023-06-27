<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            if($fromdate !='' && $todate !='') {
                $pdf_preview_uri = base_url('feesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID.'/'.strtotime($fromdate).'/'.strtotime($todate));
            } else {
                $pdf_preview_uri = base_url('feesreport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID);
            }

            if($fromdate !='' && $todate !='') {
                $xml_preview_uri = base_url('feesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID.'/'.strtotime($fromdate).'/'.strtotime($todate));
            } else {
                $xml_preview_uri = base_url('feesreport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$feetypeID);
            }

            echo btn_printReport('feesreport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('feesreport',$pdf_preview_uri, $this->lang->line('report_pdf_preview'));
            // echo btn_xmlReport('feesreport',$xml_preview_uri, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('feesreport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('feesreport_report_for')?> - 
            <?=$this->lang->line('feesreport_fees');?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>
                <?php if($classesID >= 0 && $sectionID >= 0) { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left">
                                    <?php 
                                        echo $this->lang->line('feesreport_class')." : ";
                                        echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('feesreport_all_class');
                                    ?>
                                </h5>                         
                                <h5 class="pull-right">
                                    <?php
                                       echo $this->lang->line('feesreport_section')." : ";
                                       echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('feesreport_all_section');
                                    ?>
                                </h5>
                            </div>
                        </div>          
                    </div>
                <?php } ?>
                <div class="col-sm-12">
                    <?php if(count($getFeesReports)) { ?>
                    <div id="hide-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th rowspan="2"><?=$this->lang->line('slno')?></th>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_payment_date')?></th>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_name')?></th>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_registerNO')?></th>
                                    <?php if(!($classesID > 0)) { ?>
                                        <th rowspan="2"><?=$this->lang->line('feesreport_class')?></th>
                                    <?php } ?>

                                    <?php if(!($sectionID > 0)) { ?> 
                                        <th rowspan="2"><?=$this->lang->line('feesreport_section')?></th>
                                    <?php } ?>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_roll')?></th>
                                    <th rowspan="2"><?=$this->lang->line('feesreport_feetype')?></th>
                                    <th colspan="3"><?=$this->lang->line('feesreport_paid')?></th>
                                    <!-- <th><?=$this->lang->line('feesreport_weaver')?></th> -->
                                    <th colspan="3"><?=$this->lang->line('feesreport_fine')?></th>
                                </tr>
                                <tr>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                    <td>USD</td>
                                    <td>GBP</td>
                                    <td>TZS</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $totalPaidTZS = 0;
                                    $totalPaidUSD = 0;
                                    $totalPaidGBP = 0;

                                    $totalWeaver = 0;
                                    
                                    $totalFineTZS = 0;
                                    $totalFineUSD = 0;
                                    $totalFineGBP = 0;
                                    $i= 0;
                                    foreach($getFeesReports as $getFeesReport) { 
                                        if(isset($weaverandfine[$getFeesReport->paymentID]) && (($weaverandfine[$getFeesReport->paymentID]->fine !='') || ($weaverandfine[$getFeesReport->paymentID]->weaver !='')) || $getFeesReport->paymentamount != '') { $i++; ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_payment_date')?>"><?=date('d M Y',strtotime($getFeesReport->paymentdate))?></td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_name')?>"><?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srname : '' ?></td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_registerNO')?>">
                                                <?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srregisterNO : '' ?>
                                            </td>
                                            
                                            <?php if(!($classesID > 0)) { 
                                                echo "<td data-title='".$this->lang->line('feesreport_class')."''>";
                                                if(isset($students[$getFeesReport->studentID])) {
                                                    $stclassID = $students[$getFeesReport->studentID]->srclassesID;
                                                    echo isset($classes[$stclassID]) ? $classes[$stclassID] : '';
                                                } 
                                                echo "</td>";
                                            } ?>

                                            <?php if(!($sectionID > 0)) { 
                                                echo "<td data-title='".$this->lang->line('feesreport_section')."''>";
                                                if(isset($students[$getFeesReport->studentID])) {
                                                    $stsectionID = $students[$getFeesReport->studentID]->srsectionID;
                                                    echo isset($sections[$stsectionID]) ? $sections[$stsectionID] : '';
                                                } 
                                                echo "</td>";
                                            } ?>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_roll')?>"><?=isset($students[$getFeesReport->studentID]) ? $students[$getFeesReport->studentID]->srroll : '' ?></td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_feetype')?>">
                                                <?php 
                                                    if(isset($invoices[$getFeesReport->invoiceID])) {
                                                        $feetypeIDD = $invoices[$getFeesReport->invoiceID];
                                                        if(isset($feetypes[$feetypeIDD])) {
                                                            echo $feetypes[$feetypeIDD];
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_paid')?>">
                                                <?php
                                                    if ($getFeesReport->currency == "USD") {
                                                        echo number_format($getFeesReport->paymentamount,2);
                                                        $totalPaidUSD += $getFeesReport->paymentamount;
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_paid')?>">
                                                <?php
                                                    if ($getFeesReport->currency == "GBP") {
                                                        echo number_format($getFeesReport->paymentamount,2);
                                                        $totalPaidGBP += $getFeesReport->paymentamount;
                                                    }
                                                ?>
                                            </td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_paid')?>">
                                                <?php 
                                                    if ($getFeesReport->currency == "TZS") {
                                                        echo number_format($getFeesReport->paymentamount,2);
                                                        $totalPaidTZS += $getFeesReport->paymentamount;
                                                    }
                                                ?>
                                            </td>
                                            
                                            <!-- <td data-title="<?=$this->lang->line('feesreport_weaver')?>">
                                                <?php 
                                                    if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                        echo number_format($weaverandfine[$getFeesReport->paymentID]->weaver,2);
                                                        $totalWeaver += $weaverandfine[$getFeesReport->paymentID]->weaver; 
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                ?>
                                            </td> -->
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_fine')?>">
                                                <?php 
                                                    if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                        if ($weaverandfine[$getFeesReport->paymentID]->currency == "USD") {
                                                            echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                            $totalFineUSD += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        }else {
                                                            echo number_format(0,2);
                                                        }
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                
                                                ?>
                                            </td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_fine')?>">
                                                <?php 
                                                    if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                        if ($weaverandfine[$getFeesReport->paymentID]->currency == "GBP") {
                                                            echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                            $totalFineGBP += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        }else {
                                                            echo number_format(0,2);
                                                        }
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                
                                                ?>
                                            </td>
                                            
                                            <td data-title="<?=$this->lang->line('feesreport_fine')?>">
                                                <?php 
                                                    if(isset($weaverandfine[$getFeesReport->paymentID])) {
                                                        if ($weaverandfine[$getFeesReport->paymentID]->currency == "TZS") {
                                                            echo  number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                            $totalFineTZS += number_format($weaverandfine[$getFeesReport->paymentID]->fine,2);
                                                        }else {
                                                            echo number_format(0,2);
                                                        }
                                                    } else {
                                                        echo number_format(0,2);
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php } }
                                        $colspan = 6;
                                        if($classesID == 0) {
                                            $colspan = 7;
                                        }

                                        if($sectionID == 0) {
                                            $colspan = 7;
                                        }

                                        if($classesID == 0 && $sectionID == 0) {
                                            $colspan = 8;
                                        }
                                    ?>
                                    <tr style="font-weight: bold">
                                        <td data-title="<?=$this->lang->line('feesreport_grand_total')?>" align="right" colspan="<?=$colspan?>">
                                            <?=$this->lang->line('feesreport_grand_total')?> <!-- <?=isset($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?> --></td>
                                        <td data-title="<?=$this->lang->line('feesreport_total_paid')?>"><?=number_format($totalPaidUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('feesreport_total_paid')?>"><?=number_format($totalPaidGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('feesreport_total_paid')?>"><?=number_format($totalPaidTZS,2)?></td>
                                        <!-- <td data-title="<?=$this->lang->line('feesreport_total_weaver')?>"><?=number_format($totalWeaver,2)?></td> -->
                                        <td data-title="<?=$this->lang->line('feesreport_total_fine')?>"><?=number_format($totalFineUSD,2)?></td>
                                        <td data-title="<?=$this->lang->line('feesreport_total_fine')?>"><?=number_format($totalFineGBP,2)?></td>
                                        <td data-title="<?=$this->lang->line('feesreport_total_fine')?>"><?=number_format($totalFineTZS,2)?></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <br/>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('feesreport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('feesreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('feesreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('feesreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("feesreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("feesreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("feesreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("feesreport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>

<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('feesreport_hotline')?> : <?=$siteinfos->phone?></center>";
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:50px;' /></center>"
          + divElements + footer + copyright + "</body>";

        window.print();
        document.body.innerHTML = oldPage;
        window.location.reload();

    }

    function check_email(email) {
        var status = false;
        var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
        if (email.search(emailRegEx) == -1) {
            $("#to_error").html('');
            $("#to_error").html("<?=$this->lang->line('feesreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            $("#to_error").html("<?=$this->lang->line('feesreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('feesreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('feesreport/send_pdf_to_mail')?>",
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
