<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
        if($fromdate != '' || $todate != '' ) {
            $generatepdfurl = base_url("productsalereport/pdf/".$productsalecustomertypeID."/".$productsaleclassesID."/".$productsalecustomerID."/".$reference_no."/".$statusID."/".strtotime($fromdate)."/".strtotime($todate));
            $generatexmlurl = base_url("productsalereport/xlsx/".$productsalecustomertypeID."/".$productsaleclassesID."/".$productsalecustomerID."/".$reference_no."/".$statusID."/".strtotime($fromdate)."/".strtotime($todate));
        } else {
            $generatepdfurl = base_url("productsalereport/pdf/".$productsalecustomertypeID."/".$productsaleclassesID."/".$productsalecustomerID."/".$reference_no."/".$statusID);
            $generatexmlurl = base_url("productsalereport/xlsx/".$productsalecustomertypeID."/".$productsaleclassesID."/".$productsalecustomerID."/".$reference_no."/".$statusID);
        }
        echo btn_printReport('productsalereport', $this->lang->line('report_print'), 'printablediv');
        echo btn_pdfPreviewReport('productsalereport',$generatepdfurl, $this->lang->line('report_pdf_preview'));
        echo btn_xmlReport('productsalereport',$generatexmlurl, $this->lang->line('report_xlsx'));
        echo btn_sentToMailReport('productsalereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('productsalereport_report_for')?> - <?=$this->lang->line('productsalereport_productsale')?>  </h3>
    </div><!-- /.box-header -->

    <div id="printablediv">
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>

                <?php if($fromdate != '' && $todate != '' ) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?=$this->lang->line('productsalereport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
                        </h5>
                        <h5 class="pull-right">
                            <?=$this->lang->line('productsalereport_todate')?> : <?=date('d M Y',strtotime($todate))?></p>
                        </h5>
                    </div>
                <?php } elseif($statusID != 0 ) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productsalereport_status')." : ";
                                if($statusID == 1) {
                                    echo $this->lang->line("productsalereport_pending");
                                } elseif($statusID == 2) {
                                    echo $this->lang->line("productsalereport_partial");
                                } elseif($statusID == 3) {
                                    echo $this->lang->line("productsalereport_fully_paid");
                                } elseif($statusID == 4) {
                                    echo $this->lang->line("productsalereport_refund");
                                }
                            ?>
                        </h5>
                    </div>
                <?php } elseif($reference_no != '0') { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productsalereport_referenceNo')." : ";
                                echo $reference_no;
                            ?>
                        </h5>
                    </div>
                <?php } elseif($productsalecustomertypeID != 0 && $productsalecustomerID != 0 ) { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productsalereport_role')." : ";
                                echo isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : '';
                            ?>
                        </h5>
                        <h5 class="pull-right">
                            <?php
                                echo $this->lang->line('productsalereport_user')." : ";
                                if(isset($users[3][$productsalecustomerID])) {
                                    $userName = isset($users[3][$productsalecustomerID]->name) ? $users[3][$productsalecustomerID]->name : $users[3][$productsalecustomerID]->srname;
                                    echo $userName;
                                }
                            ?>
                        </h5>
                    </div>
                <?php } else { ?>
                    <div class="col-sm-12">
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productsalereport_role')." : ";
                                 echo isset($usertypes[$productsalecustomertypeID]) ? $usertypes[$productsalecustomertypeID] : $this->lang->line('productsalereport_all');
                            ?>
                        </h5>
                    </div>
                <?php } ?>

                <div class="col-sm-12" style="margin-top:5px">
                    <?php if (count($productsales)) { ?>
                        <div id="fees_collection_details" class="tab-pane active">
                            <div id="hide-table">
                                <table id="example1" class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th><?=$this->lang->line('slno')?></th>
                                            <th><?=$this->lang->line('productsalereport_referenceNo')?></th>
                                            <th><?=$this->lang->line('productsalereport_role')?></th>
                                            <th><?=$this->lang->line('productsalereport_user')?></th>
                                            <th><?=$this->lang->line('productsalereport_product_item')?></th>
                                            <th><?=$this->lang->line('productsalereport_product_item_quantity')?></th>
                                            <th><?=$this->lang->line('productsalereport_date')?></th>
                                            <th><?=$this->lang->line('productsalereport_total')?></th>
                                            <th><?=$this->lang->line('productsalereport_paid')?></th>
                                            <th><?=$this->lang->line('productsalereport_balance')?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $i=1;
                                            foreach($productsales as $productsale) { ?>
                                                <tr>
                                                    <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                                    <td data-title="<?=$this->lang->line('productsalereport_referenceNo')?>">
                                                        <?=$productsale['productsalereferenceno'];?>
                                                        <?=($productsale['productsalerefund']) ? '<span class="text-red">('. $this->lang->line('productsalereport_refund') .')</span>' : ''?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('productsalereport_role')?>">
                                                        <?=$productsale['productsalecustomertype'];?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_user')?>">
                                                       <?=$productsale['productsalecustomerName'];?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_product_item')?>">
                                                       <?=$productsale['productname'];?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_product_item_quantity')?>">
                                                       <?=$productsale['productsalequantity'];?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_date')?>">
                                                        <?=$productsale['productsaledate'];?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_total')?>">
                                                        <?=number_format($productsale['productsaleprice'],2);?>
                                                    </td>

                                                    <td data-title="<?=$this->lang->line('productsalereport_paid')?>">
                                                        <?=number_format($productsale['productsalepaidamount'],2);?>
                                                    </td>
                                                    <td data-title="<?=$this->lang->line('productsalereport_balance')?>">
                                                        <?=number_format($productsale['productsalebalanceamount'],2);?>
                                                    </td>

                                                </tr>
                                            <?php $i++; }  ?>
                                            <tr>
                                                <td data-title="<?=$this->lang->line('productsalereport_grandtotal')?>" colspan="7" class="text-right text-bold"><?=$this->lang->line('productsalereport_grandtotal')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                                <td data-title="<?=$this->lang->line('productsalereport_totalamount')?>" class="text-bold"><?=number_format($totalproductsaleprice,2)?></td>
                                                <td data-title="<?=$this->lang->line('productsalereport_totalpaid')?>" class="text-bold"><?=number_format($totalproductsalepaidamount,2)?></td>
                                                <td data-title="<?=$this->lang->line('productsalereport_totalbalance')?>" class="text-bold"><?=number_format($totalproductsalebalanceamount,2)?></td>
                                            </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('productsalereport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('productsalereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('productsalereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('productsalereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("productsalereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("productsalereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("productsalereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("productsalereport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('productsalereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var field = {
            'to'      : $('#to').val(),
            'subject' : $('#subject').val(),
            'message' : $('#message').val(),
            'productsalecustomertypeID' : "<?=$productsalecustomertypeID?>",
            'productsaleclassesID'  : "<?=$productsaleclassesID?>",
            'productsalecustomerID' : "<?=$productsalecustomerID?>",
            'reference_no' : "<?=$reference_no?>",
            'statusID'     : "<?=$statusID?>",
            'fromdate'     : "<?=$fromdate?>",
            'todate'       : "<?=$todate?>"
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('productsalereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('productsalereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productsalereport/send_pdf_to_mail')?>",
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
