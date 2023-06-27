<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('admitcardreport/pdf/'.$examID.'/'.$classesID.'/'.$sectionID.'/'.$studentID.'/'.$typeID.'/'.$backgroundID);
            echo btn_printReport('admitcardreport', $this->lang->line('admitcardreport_print'), 'printablediv');
            echo btn_pdfPreviewReport('admitcardreport',$pdf_preview_uri, $this->lang->line('admitcardreport_pdf_preview'));
            echo btn_sentToMailReport('admitcardreport', $this->lang->line('admitcardreport_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> 
        <?=$this->lang->line('admitcardreport_report_for')?> - <?=$this->lang->line('admitcardreport_admitcard')?>
        </h3>
    </div><!-- /.box-header -->
    <div id="printablediv">
        <style type="text/css">
            .mainadmincardreport {
                max-width:794px;
                margin-left: auto;
                margin-right: auto;
                -webkit-print-color-adjust: exact;
                overflow: hidden;
            }

            .admincardreport {
                border: 1px solid #ddd;
                overflow: hidden;
                padding: 20px 50px;
                margin-bottom: 10px;
                min-height: 443px;
                <?php if($backgroundID == 1) { ?>
                background:url("<?=base_url('uploads/default/admitcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }

            .studentinfo { 
                width: 100%;
            }

            .studentinfo p {
                width: 50%;
                float: left;
                margin-bottom: 1px;
                padding: 0 0px;
                font-size: 12px;
            }

            .studentinfo p span {
                font-weight: bold;
            }

            .admitcardbody {
                float: left;
                width: 100%;
                color: #000;
                padding: 0px 0px;
            }

            .admitcardbody h3{
                text-align: center;
                border-bottom: 1px solid #ddd;
                padding-bottom: 6px;
                color: #000;
                font-weight: 500;
                margin: 0px;
                font-size: 14px;
            }

            .subjectlist {
                width:100%;
                float: left;
                font-family: monospace;
                margin-top: -20px;
            }

            .subjectlist table { 
                text-align: center;
                font-size: 10px;
                width: 100%;
            }

            .subjectlist table td{
                padding: 2px;
                border:1px solid #ddd;
            }

            .admitcardfooter {
                float: left;
                width: 100%;
                font-weight: normal;
                margin-top: 15px;
                color: #000;
            }
            
            .account_signature {
                float: left;
            }

            .headmaster_signature {
                float: right;
            }


            .mainadmincardreport .admincardreport h2 {
                color: #700CE8;
                margin-bottom: 0px;
            }

            .mainadmincardreport .admincardreport h5 {
                color: #DBA912;
            }

            .mainadmincardreport img {
                margin-top: 11px;
            }

            .admitcardreportbackend {
                border: 1px solid #ddd;
                overflow: hidden;
                padding:30px 50px;
                margin-bottom: 10px;
                height: 443px;
                color: #000;
                <?php if($backgroundID == 1) { ?>
                background:url("<?=base_url('uploads/default/admitcard-border.png')?>")!important;
                background-size: 100% 100% !important;
                <?php } ?>
            }

            .admitcardreportbackend ol{
                padding-left: 10px;
            }
            
            .admitcardreportbackend ol li{
                line-height: 20px;
            }
            
            .admitcardreportbackend ol li span{
                font-weight: 600
            }
        </style>
        <div class="box-body" style="margin-bottom: 50px;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="mainadmincardreport">
                        <?php if(count($students)) { foreach($students as $student) { ?>
                        <?php if($typeID == 1) { ?>
                            <div class="admincardreport" style="height:480px">
                                <table width="100%" style="text-align:center">
                                    <tr>
                                        <td id="logo" style="width:8%">
                                            <?php
                                                if($siteinfos->photo) {
                                                    $array = array(
                                                        "src" => base_url('uploads/images/'.$siteinfos->photo),
                                                        'width' => '50px',
                                                        'height' => '50px',
                                                        "style" => "margin-right:0px;"
                                                    );
                                                    echo img($array)."<br>";
                                                }
                                            ?>
                                        </td>
                                        <td style="width:84%"> 
                                            <h2><?=$siteinfos->sname?></h2>
                                            <h5><?=$siteinfos->address?></h5> 
                                        </td>
                                        <td style="width:8%">
                                            <img src="<?=imagelink($student->photo)?>" alt="">
                                        </td>
                                    </tr>
                                    
                                </table>
                                <div class="admitcardbody">
                                    <h3><?=$examTitle?> <?=$this->lang->line('admitcardreport_exam_admit_card')?> - ( <?=$examYear?> )</h3>
                                    <div class="admitcardstudentinfo">
                                        <div class="studentinfo">
                                            <p><span><?=$this->lang->line('admitcardreport_name')?></span> : <?=$student->srname?> </p>
                                            <p><span><?=$this->lang->line('admitcardreport_registerNO')?></span> : <?=$student->srregisterNO?> </p>
                                            <p><span><?=$this->lang->line('admitcardreport_class')?></span> : <?=isset($classes[$student->srclassesID]) ? $classes[$student->srclassesID] : ''?> </p>
                                            <p><span><?=$this->lang->line('admitcardreport_section')?></span> : <?=isset($sections[$student->srsectionID]) ? $sections[$student->srsectionID] : ''?> </p>
                                            <p><span><?=$this->lang->line('admitcardreport_roll')?></span> : <?=$student->srroll?></p>
                                        </div>
                                    </div>
                                    
                                    <div class="subjectlist">
                                        <h3><?=$this->lang->line('admitcardreport_subject_appear')?></h3>
                                        <table>
                                            <tr>
                                                <td><?=$this->lang->line('admitcardreport_slno')?></td>
                                                <td><?=$this->lang->line('admitcardreport_subject_code')?></td>
                                                <td><?=$this->lang->line('admitcardreport_subject_name')?></td>
                                                <td><?=$this->lang->line('admitcardreport_subject_mark')?></td>
                                            </tr>
                                            <?php $i= 0; if(count($subjects)) { foreach($subjects as $subject) { if($subject->type == 1) { $i++; ?>
                                                <tr>
                                                    <td><?=$i?></td>
                                                    <td><?=$subject->subject_code?></td>
                                                    <td><?=$subject->subject?></td>
                                                    <td><?=$subject->finalmark?></td>
                                                </tr>
                                            <?php } } }
                                            if(isset($subjects[$student->sroptionalsubjectID])) { $opsubject = $subjects[$student->sroptionalsubjectID]; ?>
                                                <tr>
                                                    <td><?=$i+1?></td>
                                                    <td><?=$opsubject->subject_code?></td>
                                                    <td><?=$opsubject->subject?></td>
                                                    <td><?=$opsubject->finalmark?></td>
                                                </tr>
                                            <?php } ?>
                                        </table>
                                    </div>
                                </div>
                                <div class="admitcardfooter">
                                    <span class="account_signature"></span>
                                    <span class="headmaster_signature"><?=$this->lang->line('admitcardreport_signature')?></span>
                                </div>
                            </div>
                        <?php } elseif($typeID == 2) { ?>
                            <div class="admitcardreportbackend" style="height:480px">
                                <ol>
                                    <li><span>Do not Carry these Electronic Gadgets : </span> electronic gadgets (Bluetooth devices, head phones, pen/buttonhole cameras, scanner, calculator, storage devices etc) in the examination lab. These items are strictly prohibited from the examination lab.</li>
                                    <li><span>Do not Carry these Ornaments : </span> Candidates should also not wear charms, veil, items containing metals such as rings, bracelet, earrings, nose-pin, chains, necklace, pendants, badge, broach, hair pin, hair band.</li>
                                    <li><span>What Candidates should Wear to the Exam hall : </span> Candidates should not wear clothes with full sleeves or big buttons, etc. Candidates are advised to wear open footwear like slippers, sandals instead of shoes as the candidates could be asked to remove shoes by the frisking staff.</li>
                                    <li><span>Do not Carry Stationary : </span> Pen/pencil and paper for rough work would be provided in the examination lab. Electronic watch (timer) will be available on the computer screen allotted to the candidates.</li>
                                    <li><span>Do not carry Bags : </span> Do not carry back pack, College bag or any other bag like hand bag. If candidates bring any bag, they must make arrangement for safe custody of these items. The Commission shall not make any arrangement nor be responsible for the safe custody of such items.</li>
                                    <li><span>What will Happen if you carry Prohibited items to Exam Hall : </span> If any such prohibited item is found in the possession of a candidate in the examination lab, his/her candidature is liable to be canceled and legal/criminal proceedings could be initiated against him/her. He/she would also liable to be debarred from appearing in future examinations of the Commission for a period of 3 years.</li>
                                    <li><span>Candidate should not create Disturbance in the Exam Hall : </span> If any candidate is found obstructing the conduct of the examination or creating disturbances at the examination venue, his/her candidature shall be summarily canceled.</li>
                                </ol>
                            </div>
                        <?php } } } else { ?>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('admitcardreport_data_not_found')?></b></p>
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div><!-- row -->
        </div><!-- Body -->
    </div>
</div>


<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('admitcardreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('admitcardreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('admitcardreport_send_pdf_to_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("admitcardreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("admitcardreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("admitcardreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("admitcardreport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('admitcardreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }


    $('#send_pdf').click(function() {
        var field = {
            'to'          : $('#to').val(), 
            'subject'     : $('#subject').val(), 
            'message'     : $('#message').val(),
            'examID'      : '<?=$examID?>',
            'classesID'   : '<?=$classesID?>',
            'sectionID'   : '<?=$sectionID?>',
            'studentID'   : '<?=$studentID?>',
            'typeID'      : '<?=$typeID?>',
            'backgroundID': '<?=$backgroundID?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('admitcardreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('admitcardreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('admitcardreport/send_pdf_to_mail')?>",
                data: field,
                dataType: "html",
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.status == false) {
                        $('#send_pdf').removeAttr('disabled');
                        if( response.to) {
                            $("#to_error").html("<?=$this->lang->line('admitcardreport_mail_to')?>").css("text-align", "left").css("color", 'red');
                        } 
                        if( response.subject) {
                            $("#subject_error").html("<?=$this->lang->line('admitcardreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
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