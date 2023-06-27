<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
            <?php
                if(permissionChecker('question_bank_view')) {
                    echo btn_add_pdf('question_bank/print_preview/'.$question->questionBankID, $this->lang->line('pdf_preview'));
                }
                if(permissionChecker('question_bank_edit')) {
                    echo btn_sm_edit('question_bank/edit/'.$question->questionBankID, $this->lang->line('edit'));
                }
            ?>
            <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
        </div>


        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("question_bank/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                <li class="active"><?=$this->lang->line('menu_view')?></li>
            </ol>
        </div>

    </div>

</div>


<section class="panel">
    <div class="panel-body bio-graph-info">
        <div id="printablediv" class="box-body">
            <div class="row">
                <div class="col-sm-12">

                    <?php
                            $questionOptions = isset($options[$question->questionBankID]) ? $options[$question->questionBankID] : [];
                            $questionAnswers = isset($answers[$question->questionBankID]) ? $answers[$question->questionBankID] : [];
                            if($question->typeNumber == 1 || $question->typeNumber == 2) {
                                $questionAnswers = pluck($questionAnswers, 'optionID');
                            }

                            if($question != '') {
                                ?>
                                <div class="clearfix">
                                    <div class="question-body">
                                        <!--    <label class="lb-title">sbi clear : questin 1 of 10</label>-->
                                        <label class="lb-content question-color"><a href="<?=base_url('question_bank/edit/'.$question->questionBankID)?>" target="_blank"><?=$question->question?></a></label>
                                        <label class="lb-mark"> <?= $question->mark != "" ? $question->mark.' '.$this->lang->line('question_bank_mark') : ''?> </label>
                                    </div>
                                    <?php if($question->upload != '') { ?>
                                    <div>
                                        <img style="width:220px;height:120px;" src="<?=base_url('uploads/images/'.$question->upload)?>" alt="">
                                    </div>
                                    <?php } ?>

                                    <div class="question-answer">
                                        <table class="table">
                                            <tr>
                                                <?php
                                                $tdCount = 0;
                                                foreach ($questionOptions as $option) {
                                                    $checked = '';
                                                    if(in_array($option->optionID, $questionAnswers)) {
                                                        $checked = 'checked';
                                                    } ?>
                                                    <td width="50%">
                                                        <input id="option<?=$option->optionID?>" value="1" name="option" type="<?=$question->typeNumber == 1 ? 'radio' : 'checkbox'?>" <?=$checked?> disabled>
                                                        <label for="option<?=$option->optionID?>">
                                                            <span class="fa-stack <?=$question->typeNumber == 1 ? 'radio-button' : 'checkbox-button'?>">
                                                                <i class="active fa fa-check">
                                                                </i>
                                                            </span>
                                                            <span ><?=$option->name?></span>
                                                            <?php
                                                                if(!is_null($option->img) && $option->img != "") {
                                                                    ?>
                                                                    <img style="display: block;" src="<?=base_url('uploads/images/'.$option->img)?>" width="100px" height="80px"/>
                                                                    <?php
                                                                }
                                                            ?>
                                                        </label>
                                                    </td>
                                                    <?php
                                                        $tdCount++;
                                                        if($tdCount == 2) {
                                                            $tdCount = 0;
                                                            echo "</tr><tr>";
                                                        }
                                                    }

                                                    if($question->typeNumber == 3) {
                                                        foreach ($questionAnswers as $answerKey => $answer) {
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="button" value="<?=$answerKey+1?>"> <input class="fillInTheBlank" id="answer<?=$answer->answerID?>" name="option" value="<?=$answer->text?>" type="text" disabled>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <br/>
                                <?php
                            }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- email modal starts here -->
<form class="form-horizontal" role="form" action="<?=base_url('student/send_mail');?>" method="post">
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
        var questionBankID = "<?=$question->questionBankID?>";
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
                url: "<?=base_url('question_bank/send_mail')?>",
                data: 'to='+ to + '&subject=' + subject+ "&message=" + message + "&questionBankID=" + questionBankID,
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

