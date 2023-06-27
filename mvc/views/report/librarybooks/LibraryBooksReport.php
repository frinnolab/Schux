<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            $pdf_preview_uri = base_url('librarybooksreport/pdf/'.$bookname.'/'.$subjectcode.'/'.$rackNo.'/'.$status);
            $xml_preview_uri = base_url('librarybooksreport/xlsx/'.$bookname.'/'.$subjectcode.'/'.$rackNo.'/'.$status);
            echo btn_printReport('librarybooksreport', $this->lang->line('librarybooksreport_print'), 'printablediv');
            echo btn_pdfPreviewReport('librarybooksreport',$pdf_preview_uri, $this->lang->line('librarybooksreport_pdf_preview'));
            echo btn_xmlReport('librarybooksreport',$xml_preview_uri, $this->lang->line('librarybooksreport_xlsx'));
            echo btn_sentToMailReport('librarybooksreport', $this->lang->line('librarybooksreport_mail'));
        ?>
    </div>
</div>

<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i>
            <?=$this->lang->line('librarybooksreport_report_for')?> - <?=$this->lang->line('librarybooksreport_librarybooks');?>
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
                    <h5 class="pull-left">
                        <?php if($status == 1) {
                            echo $this->lang->line('librarybooksreport_status')." : ";
                            echo $this->lang->line('librarybooksreport_available');
                        } elseif($status == 2) {
                            echo $this->lang->line('librarybooksreport_status')." : ";
                            echo $this->lang->line('librarybooksreport_unavailable');
                        } elseif($bookname != '0') {
                            echo $this->lang->line('librarybooksreport_bookname')." : ".$bookfullname;
                        } elseif ($subjectcode != '0') {
                            echo $this->lang->line('librarybooksreport_subjectcode')." : ".$subjectcode;
                        } elseif($rackNo != '0') {
                            echo $this->lang->line('librarybooksreport_rackNo')." : ".$rackNo;
                        } ?>
                    </h5>
                </div>
                <div class="col-sm-12">
                    <?php if(count($books)) { ?>
                    <div id="hide-table">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th><?=$this->lang->line('slno')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_bookname')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_author')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_subjectcode')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_rackNo')?></th>
                                    <th><?=$this->lang->line('librarybooksreport_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $i=0; 
                                    foreach($books as $book) { $i++?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_bookname')?>"><?=$book['bookname']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_author')?>"><?=$book['author']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_subjectcode')?>"><?=$book['subjectcode']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_rackNo')?>"><?=$book['rackNo']?></td>
                                            <td data-title="<?=$this->lang->line('librarybooksreport_status')?>"><?=$book['status']?></td>
                                        </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <br/>
                        <div class="callout callout-danger">
                            <p><b class="text-info"><?=$this->lang->line('librarybooksreport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('librarybooksreport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('librarybooksreport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('librarybooksreport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("librarybooksreport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("librarybooksreport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("librarybooksreport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("librarybooksreport_send")?>" />
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
        var copyright = "<center><?=$siteinfos->footer?> | <?=$this->lang->line('librarybooksreport_hotline')?> : <?=$siteinfos->phone?></center>";
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
            $("#to_error").html("<?=$this->lang->line('librarybooksreport_mail_valid')?>").css("text-align", "left").css("color", 'red');
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
            'bookname'   : '<?=$bookname?>',
            'subjectcode': '<?=$subjectcode?>',
            'rackNo'     : '<?=$rackNo?>',
            'status'     : '<?=$status?>',
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('librarybooksreport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('librarybooksreport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('librarybooksreport/send_pdf_to_mail')?>",
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
