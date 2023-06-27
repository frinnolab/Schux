<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            if($lID == '') {
                $pdf_preview_uri = base_url('librarybookissuereport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$typeID.'/'.$fromdate.'/'.$todate);
                $xml_preview_uri = base_url('librarybookissuereport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$typeID.'/'.$fromdate.'/'.$todate);
            } else {
                $pdf_preview_uri = base_url('librarybookissuereport/pdf/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$typeID.'/'.$fromdate.'/'.$todate.'/'.$lID);
                $xml_preview_uri = base_url('librarybookissuereport/xlsx/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$typeID.'/'.$fromdate.'/'.$todate.'/'.$lID);
            }

            echo btn_printReport('librarybookissuereport', $this->lang->line('librarybookissuereport_print'), 'printablediv');
            echo btn_pdfPreviewReport('librarybookissuereport',$pdf_preview_uri, $this->lang->line('librarybookissuereport_pdf_preview'));
            echo btn_xmlReport('librarybookissuereport',$xml_preview_uri, $this->lang->line('librarybookissuereport_xlsx'));
            echo btn_sentToMailReport('librarybookissuereport', $this->lang->line('librarybookissuereport_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('librarybookissuereport_report_for')?> - <?=$this->lang->line('librarybookissuereport_librarybooksissue');?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
    <!-- form start -->
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>

                <?php if($fromdate >= 0 || $todate >= 0 ) { ?>
                    <div class="col-sm-12">
                        <div class="row">
                            <div class="col-sm-12">
                                <h5 class="pull-left" style="margin-top:0px">
                                    <?php
                                        $f = FALSE;
                                        if($fromdate != '0' && $todate != '0') {
                                            echo $this->lang->line('librarybookissuereport_fromdate')." : ";
                                            echo date('d M Y',$fromdate);
                                        } elseif($lID != '') {
                                            echo $this->lang->line('librarybookissuereport_libraryID')." : ";
                                            echo $lID;
                                        } elseif($typeID !=0) {
                                            echo $this->lang->line('librarybookissuereport_type')." : ";
                                            if($typeID == 1) {
                                                echo $this->lang->line('librarybookissuereport_issuedate');
                                            } elseif($typeID == 2) {
                                                echo $this->lang->line('librarybookissuereport_returndate');
                                            } elseif($typeID == 3) {
                                                echo $this->lang->line('librarybookissuereport_duedate');
                                            }
                                        } else {
                                            echo $this->lang->line('librarybookissuereport_class')." : ";
                                            echo isset($classes[$classesID]) ? $classes[$classesID] : $this->lang->line('librarybookissuereport_all_class');
                                            $f = TRUE;
                                        }
                                    ?>
                                </h5>                         
                                <h5 class="pull-right" style="margin-top:0px">
                                    <?php
                                        if($fromdate != '0' && $todate != '0') {
                                            echo $this->lang->line('librarybookissuereport_todate')." : ";
                                            echo date('d M Y',$todate);
                                        } else {
                                            if($f) {
                                                echo $this->lang->line('librarybookissuereport_section')." : ";
                                                echo isset($sections[$sectionID]) ? $sections[$sectionID] : $this->lang->line('librarybookissuereport_all_section');
                                            }
                                        }
                                    ?>
                                </h5>                        
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="col-sm-12">
                    <?php if(count($getLibrarybookissueReports)) { ?>
                    <div id="hide-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_libraryID')?></th>

                                    <?php 
                                        if($typeID == 0) {
                                            echo '<th>'.$this->lang->line('librarybookissuereport_issuedate').'</th>';
                                            echo '<th>'.$this->lang->line('librarybookissuereport_duedate').'</th>';
                                            echo '<th>'.$this->lang->line('librarybookissuereport_returndate').'</th>';
                                        } elseif($typeID == 1) {
                                            echo '<th>'.$this->lang->line('librarybookissuereport_issuedate').'</th>';
                                        } elseif($typeID == 2) {
                                            echo '<th>'.$this->lang->line('librarybookissuereport_returndate').'</th>';
                                        } elseif($typeID == 3) {
                                            echo '<th>'.$this->lang->line('librarybookissuereport_duedate').'</th>';
                                        }
                                    ?>

                                    <th><?=$this->lang->line('librarybookissuereport_name')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_registerNO')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_subject_code')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_book')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_serial')?></th>
                                    <th><?=$this->lang->line('librarybookissuereport_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i= 0;
                                    foreach($getLibrarybookissueReports as $getLibrarybookissueReport) { $i++; ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('librarybookissuereport_libraryID')?>"><?=$getLibrarybookissueReport->lID?></td>

                                            <?php 
                                                if($typeID == 0) {
                                                    if(isset($getLibrarybookissueReport->issue_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_issuedate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->issue_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_issuedate').'">&nbsp;</td>';
                                                    }

                                                    if(isset($getLibrarybookissueReport->due_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_duedate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->due_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_duedate').'">&nbsp;</td>';
                                                    }

                                                    if(isset($getLibrarybookissueReport->return_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_returndate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->return_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_returndate').'">&nbsp;</td>';
                                                    }
                                                } elseif($typeID == 1) {
                                                    if(isset($getLibrarybookissueReport->issue_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_issuedate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->issue_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_issuedate').'">&nbsp;</td>';
                                                    }
                                                } elseif($typeID == 2) {
                                                    if(isset($getLibrarybookissueReport->return_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_returndate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->return_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_returndate').'">&nbsp;</td>';
                                                    }
                                                } elseif ($typeID == 3) {
                                                    if(isset($getLibrarybookissueReport->due_date)) {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_duedate').'">'.date('d M Y', strtotime($getLibrarybookissueReport->due_date)).'</td>';
                                                    } else {
                                                        echo '<td data-title="'.$this->lang->line('librarybookissuereport_duedate').'">&nbsp;</td>';
                                                    }
                                                }
                                            ?>

                                            <td data-title="<?=$this->lang->line('librarybookissuereport_name')?>"><?=$getLibrarybookissueReport->srname?></td>
                                            <td data-title="<?=$this->lang->line('librarybookissuereport_registerNO')?>">
                                                <?=$getLibrarybookissueReport->srregisterNO?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('librarybookissuereport_subject_code')?>">
                                                <?=isset($books[$getLibrarybookissueReport->bookID]) ? $books[$getLibrarybookissueReport->bookID]->subject_code : '' ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('librarybookissuereport_book')?>">
                                                <?=isset($books[$getLibrarybookissueReport->bookID]) ? $books[$getLibrarybookissueReport->bookID]->book : '' ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('librarybookissuereport_serial')?>">
                                                <?=$getLibrarybookissueReport->serial_no?>
                                            </td>

                                            <?php
                                                echo "<td data-title='".$this->lang->line('librarybookissuereport_status')."''>";
                                                    if($getLibrarybookissueReport->return_date != '') {
                                                        echo $this->lang->line('librarybookissuereport_return') ;
                                                    } else {
                                                        echo $this->lang->line('librarybookissuereport_non_return') ;
                                                    }
                                                echo "</td>";
                                            ?>
                                        </tr>
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <br/>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('librarybookissuereport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('librarybookissuereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('librarybookissuereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('librarybookissuereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("librarybookissuereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("librarybookissuereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("librarybookissuereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("librarybookissuereport_send")?>" />
            </div>
        </div>
      </div>
    </div>
</form>

<?php 
    if($fromdate != 0) {
        $fromdate = date('d-m-Y',$fromdate);
    } else {
        $fromdate = '';
    }

    if($todate != 0) {
        $todate = date('d-m-Y',$todate);
    } else {
        $todate = '';
    }
?>

<script type="text/javascript">
    function printDiv(divID) {
        var oldPage = document.body.innerHTML;
        $('#headerImage').remove();
        $('.footerAll').remove();
        var divElements = document.getElementById(divID).innerHTML;
        var footer = "<center><img src='<?=base_url('uploads/images/'.$siteinfos->photo)?>' style='width:30px;' /></center>";
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('librarybookissuereport_hotline')?> : <?=$siteinfos->phone?></center>";
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
            $("#to_error").html("<?=$this->lang->line('librarybookissuereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'lID'        : '<?=$lID?>',
            'typeID'     : '<?=$typeID?>',
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
            $("#to_error").html("<?=$this->lang->line('librarybookissuereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('librarybookissuereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('librarybookissuereport/send_pdf_to_mail')?>",
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
