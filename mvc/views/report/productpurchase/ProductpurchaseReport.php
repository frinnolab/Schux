<div class="row">
    <div class="col-sm-12" style="margin:10px 0px">
        <?php
            if($fromdate != '' && $todate != '') {
                $generatepdfurl = base_url("productpurchasereport/pdf/".$productsupplierID."/".$productwarehouseID."/".$reference_no."/".$statusID."/".strtotime($fromdate)."/".strtotime($todate));
                $generatexmlurl = base_url("productpurchasereport/xlsx/".$productsupplierID."/".$productwarehouseID."/".$reference_no."/".$statusID."/".strtotime($fromdate)."/".strtotime($todate));
            } else {
                $generatepdfurl = base_url("productpurchasereport/pdf/".$productsupplierID."/".$productwarehouseID."/".$reference_no."/".$statusID);
                $generatexmlurl = base_url("productpurchasereport/xlsx/".$productsupplierID."/".$productwarehouseID."/".$reference_no."/".$statusID);
            }

            echo btn_printReport('productpurchasereport', $this->lang->line('report_print'), 'printablediv');
            echo btn_pdfPreviewReport('productpurchasereport',$generatepdfurl, $this->lang->line('report_pdf_preview'));
            echo btn_xmlReport('productpurchasereport',$generatexmlurl, $this->lang->line('report_xlsx'));
            echo btn_sentToMailReport('productpurchasereport', $this->lang->line('report_send_pdf_to_mail'));
        ?>
    </div>
</div>
<div class="box">
    <div class="box-header bg-gray">
        <h3 class="box-title text-navy"><i class="fa fa-clipboard"></i> <?=$this->lang->line('productpurchasereport_report_for')?> - <?=$this->lang->line('productpurchasereport_product_purchase')?>  </h3>
    </div><!-- /.box-header -->

    <div id="printablediv">
        <!-- form start -->
        <div class="box-body">
            <div class="row">
                <div class="col-sm-12">
                    <?=reportheader($siteinfos, $schoolyearsessionobj)?>
                </div>

                <div class="col-sm-12">
                    <?php if($fromdate != '' && $todate != '' ) { ?>
                        <h5 class="pull-left">
                            <?=$this->lang->line('productpurchasereport_fromdate')?> : <?=date('d M Y',strtotime($fromdate))?></p>
                        </h5>
                        <h5 class="pull-right">
                            <?=$this->lang->line('productpurchasereport_todate')?> : <?=date('d M Y',strtotime($todate))?></p>
                        </h5>
                    <?php } elseif($statusID != 0 ) { ?>
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productpurchasereport_status')." : ";
                                if($statusID == 1) {
                                    echo $this->lang->line("productpurchasereport_pending");
                                } elseif($statusID == 2) {
                                    echo $this->lang->line("productpurchasereport_partial");
                                } elseif($statusID == 3) {
                                    echo $this->lang->line("productpurchasereport_fully_paid");
                                } elseif($statusID == 4) {
                                    echo $this->lang->line("productpurchasereport_refund");
                                }
                            ?>
                        </h5>
                    <?php } elseif($reference_no != '0') { ?>
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productpurchasereport_referenceNo')." : ";
                                echo $reference_no;
                            ?>
                        </h5>
                    <?php } elseif($productsupplierID != 0 && $productwarehouseID != 0 ) { ?>
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productpurchasereport_supplier')." : ";
                                echo isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                            ?>
                        </h5>
                        <h5 class="pull-right">
                            <?php
                                echo $this->lang->line('productpurchasereport_warehouse')." : ";
                                echo isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';
                            ?>
                        </h5>
                    <?php } elseif($productsupplierID != 0) { ?>
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productpurchasereport_supplier')." : ";
                                echo isset($productsuppliers[$productsupplierID]) ? $productsuppliers[$productsupplierID] : '';
                            ?>
                        </h5>
                    <?php } elseif($productwarehouseID != 0) { ?>
                        <h5 class="pull-left">
                            <?php
                                echo $this->lang->line('productpurchasereport_warehouse')." : ";
                                echo isset($productwarehouses[$productwarehouseID]) ? $productwarehouses[$productwarehouseID] : '';
                            ?>
                        </h5>
                    <?php } ?>
                </div>

                <div class="col-sm-12" style="margin-top:5px">
                    <?php if (count($productpurchases)) { ?>
                        <div id="hide-table">
                            <table id="example1" class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th><?=$this->lang->line('slno')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_referenceNo')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_supplier')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_warehouse')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_date')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_total')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_paid')?></th>
                                        <th><?=$this->lang->line('productpurchasereport_balance')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                    $i=1;
                                    foreach($productpurchases as $productpurchase) { ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>"><?=$i?></td>
                                            <td data-title="<?=$this->lang->line('productpurchasereport_referenceNo')?>">
                                                <?=$productpurchase['reference_no'];?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('productpurchasereport_supplier')?>">
                                                <?=$productpurchase['supplier'];?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('productpurchasereport_supplier')?>">
                                                <?=$productpurchase['warehouse'];?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('productpurchasereport_date')?>">
                                                <?=$productpurchase['date'];?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('productpurchasereport_total')?>">
                                                <?=number_format($productpurchase['total'],2);?>
                                            </td>

                                            <td data-title="<?=$this->lang->line('productpurchasereport_paid')?>">
                                                <?=number_format($productpurchase['paid'],2);?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('productpurchasereport_balance')?>">
                                                <?=number_format($productpurchase['balance'],2);?>
                                            </td>
                                        </tr>
                                    <?php $i++; } ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('productpurchasereport_grandtotal')?>" colspan="5" class="text-right text-bold"><?=$this->lang->line('productpurchasereport_grandtotal')?> <?=!empty($siteinfos->currency_code) ? "(".$siteinfos->currency_code.")" : ''?></td>
                                        <td data-title="<?=$this->lang->line('productpurchasereport_totalamount')?>" class="text-bold"><?=number_format($totalproductpurchaseprice,2)?></td>
                                        <td data-title="<?=$this->lang->line('productpurchasereport_totalpaid')?>" class="text-bold"><?=number_format($totalproductpurchasepaidamount,2)?></td>
                                        <td data-title="<?=$this->lang->line('productpurchasereport_totalbalance')?>" class="text-bold"><?=number_format($totalproductpurchasebalanceamount,2)?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php } else { ?>
                    <div class="callout callout-danger">
                        <p><b class="text-info"><?=$this->lang->line('productpurchasereport_data_not_found')?></b></p>
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
<form class="form-horizontal" role="form" action="<?=base_url('productpurchasereport/send_pdf_to_mail');?>" method="post">
    <div class="modal fade" id="mail">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?=$this->lang->line('productpurchasereport_close')?></span></button>
                <h4 class="modal-title"><?=$this->lang->line('productpurchasereport_mail')?></h4>
            </div>
            <div class="modal-body">

                <?php
                    if(form_error('to'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                ?>
                    <label for="to" class="col-sm-2 control-label">
                        <?=$this->lang->line("productpurchasereport_to")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("productpurchasereport_subject")?> <span class="text-red">*</span>
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
                        <?=$this->lang->line("productpurchasereport_message")?>
                    </label>
                    <div class="col-sm-6">
                        <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("productpurchasereport_send")?>" />
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
            $("#to_error").html("<?=$this->lang->line('productpurchasereport_mail_valid')?>").css("text-align", "left").css("color", 'red');
        } else {
            status = true;
        }
        return status;
    }

    $('#send_pdf').click(function() {
        var field = {
            'to'                  : $('#to').val(),
            'subject'             : $('#subject').val(),
            'message'             : $('#message').val(),
            'productsupplierID'   : "<?=$productsupplierID?>",
            'productwarehouseID'  : "<?=$productwarehouseID?>",
            'reference_no'        : "<?=$reference_no?>",
            'statusID'            : "<?=$statusID?>",
            'fromdate'            : "<?=$fromdate?>",
            'todate'              : "<?=$todate?>"
        };

        var to = $('#to').val();
        var subject = $('#subject').val();
        var error = 0;

        $("#to_error").html("");
        $("#subject_error").html("");

        if(to == "" || to == null) {
            error++;
            $("#to_error").html("<?=$this->lang->line('productpurchasereport_mail_to')?>").css("text-align", "left").css("color", 'red');
        } else {
            if(check_email(to) == false) {
                error++
            }
        }

        if(subject == "" || subject == null) {
            error++;
            $("#subject_error").html("<?=$this->lang->line('productpurchasereport_mail_subject')?>").css("text-align", "left").css("color", 'red');
        } else {
            $("#subject_error").html("");
        }

        if(error == 0) {
            $('#send_pdf').attr('disabled','disabled');
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productpurchasereport/send_pdf_to_mail')?>",
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
