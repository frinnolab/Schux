<?php if(count($productpurchase)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('productpurchase/print_preview/'.$productpurchase->productpurchaseID, $this->lang->line('pdf_preview'))
                ?>

                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php 
                        if(permissionChecker('productpurchase_edit')) {
                            if((float)$productpurchasepaid->productpurchasepaidamount == 0 && $productpurchase->productpurchaserefund == 0) {
                                echo btn_sm_edit('productpurchase/edit/'.$productpurchase->productpurchaseID, $this->lang->line('edit')); 
                            }
                        }
                    ?>
                <?php } ?>
                
                <?php if($productpurchase->productpurchaserefund == 0) { ?>
                    <?php if($productpurchase->productpurchasestatus != 2) { ?>
                        <a href="#addpayment" id="<?=$productpurchase->productpurchaseID?>" class="btn-cs btn-sm-cs getpurchaseinfobtn" style="text-decoration: none;" role="button" data-toggle="modal"><i class="fa fa-credit-card"></i> <?=$this->lang->line('productpurchase_payment')?></a>
                    <?php } ?>
                <?php } ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>


            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("productpurchase/index")?>"><?=$this->lang->line('panel_title')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_view')?></li>
                </ol>
            </div>
        </div>
    </div>

    <div id="printablediv">
        <section class="content invoice" >
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <?php
                            if($siteinfos->photo) {
                                $array = array(
                                    "src" => base_url('uploads/images/'.$siteinfos->photo),
                                    'width' => '25px',
                                    'height' => '25px',
                                    'class' => 'img-circle',
                                    'style' => 'margin-top:-10px' 
                                );
                                echo img($array);
                            } 
                        ?>
                        <?php  echo $siteinfos->sname; ?>
                        <small class="pull-right"><?=$this->lang->line('productpurchase_create_date').' : '.date('d M Y')?></small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <?php  echo $this->lang->line("productpurchase_from"); ?>
                    <?php if(count($productsupplier)) { ?>
                        <address>
                            <strong><?=$productsupplier->productsuppliercompanyname?></strong><br>
                            <?=$productsupplier->productsupplieraddress?><br>
                            <?=$this->lang->line("productpurchase_phone"). " : ". $productsupplier->productsupplierphone?><br>
                            <?=$this->lang->line("productpurchase_email"). " : ". $productsupplier->productsupplieremail?><br>
                        </address>
                    <?php } ?>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <?php  echo $this->lang->line("productpurchase_to"); ?>
                    <address>
                        <strong><?=$siteinfos->sname?></strong><br>
                        <?php if(count($productwarehouse)) { ?>
                            <?=$this->lang->line("productpurchase_warehouse_name"). " : ". $productwarehouse->productwarehousename?><br>
                            <?=$productwarehouse->productwarehouseaddress?><br>
                            <?=$this->lang->line("productpurchase_phone"). " : ". $productwarehouse->productwarehousephone?><br>
                            <?=$this->lang->line("productpurchase_email"). " : ". $productwarehouse->productwarehouseemail?><br>
                        <?php } ?>
                    </address>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <b><?=$this->lang->line("productpurchase_referenceno")." : ".$productpurchase->productpurchasereferenceno?></b>
                    <br>
                    <?php
                        $status = $productpurchase->productpurchasestatus;
                        $setButton = '';
                        if($status == 0) {
                            $status = $this->lang->line('productpurchase_pending');
                            $setButton = 'text-red';
                        } elseif($status == 1) {
                            $status = $this->lang->line('productpurchase_partial_paid');
                            $setButton = 'text-yellow';
                        } elseif($status == 2) {
                            $status = $this->lang->line('productpurchase_fully_paid');
                            $setButton = 'text-green';
                        }

                        echo $this->lang->line('productpurchase_payment_status'). " : ". "<span class='".$setButton."'>".$status."</span>";
                    ?>

                    <?php if($productpurchase->productpurchaserefund == 1) { ?>
                        <p class="refund"><?=$this->lang->line('productpurchase_refund')?></p>
                    <?php } ?>
                </div>
            </div>

            <br />
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered product-style">
                            <thead>
                                <tr>
                                    <th class="col-lg-2" ><?=$this->lang->line('slno')?></th>
                                    <th class="col-lg-4"><?=$this->lang->line('productpurchase_description')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productpurchase_unit_price')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productpurchase_quantity')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productpurchase_subtotal')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $subtotal = 0; $totalsubtotal = 0; if(count($productpurchaseitems)) { $i=1; foreach ($productpurchaseitems as $productpurchaseitem) { $subtotal = ($productpurchaseitem->productpurchaseunitprice * $productpurchaseitem->productpurchasequantity); $totalsubtotal += $subtotal; ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('productpurchase_description')?>">
                                            <?=isset($products[$productpurchaseitem->productID]) ? $products[$productpurchaseitem->productID] : ''?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('productpurchase_unit_price')?>">
                                            <?=number_format($productpurchaseitem->productpurchaseunitprice, 2)?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('productpurchase_quantity')?>">
                                            <?=number_format($productpurchaseitem->productpurchasequantity, 2)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('productpurchase_subtotal')?> ">
                                            <?=number_format($subtotal, 2)?>
                                        </td>
                                    </tr>
                                <?php $i++; } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_subtotal_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($totalsubtotal, 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_vat')?>(<?= $productpurchase->purchase_product_vat_percentage ?>%)</span></td>
                                    <td><b><?=number_format($productpurchase->purchase_product_vat_amount,2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_discount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($productpurchase->purchase_product_discount, 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_total_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($totalsubtotal + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount, 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_paid')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($productpurchasepaid->productpurchasepaidamount, 2)?></b></td>
                                </tr> 
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productpurchase_balance');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format(($totalsubtotal + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount - $productpurchasepaid->productpurchasepaidamount), 2)?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-sm-9 col-xs-12 pull-left">
                    <p><?=$productpurchase->productpurchasedescription?></p>
                </div>
                <div class="col-sm-3 col-xs-12 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?=$this->lang->line('productpurchase_create_by')?> : <?=$createuser?>
                            <br>
                            <?=$this->lang->line('productpurchase_date')?> : <?=date('d M Y', strtotime($productpurchase->create_date))?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <form class="form-horizontal" role="form" action="<?=base_url('productpurchase/send_mail');?>" method="post">
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

    <?php if($productpurchase->productpurchaserefund == 0) { ?>
        <?php if($productpurchase->productpurchasestatus != 2) { ?>
            <form class="form-horizontal" role="form" method="post" id="productPurchasePaymentAddDataForm" enctype="multipart/form-data">
                <div class="modal fade" id="addpayment">
                  <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"><?=$this->lang->line('productpurchase_add_payment')?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productpurchasepaiddateerrorDiv">
                                            <label for="productpurchasepaiddate"><?=$this->lang->line('productpurchase_date')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productpurchasepaiddate" name="productpurchasepaiddate">
                                            <span id="productpurchasepaiddateerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productpurchasepaidreferencenoerrorDiv">
                                            <label for="productpurchasepaidreferenceno"><?=$this->lang->line('productpurchase_referenceno')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productpurchasepaidreferenceno" name="productpurchasepaidreferenceno">
                                            <span id="productpurchasepaidreferencenoerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productpurchasepaidamounterrorDiv">
                                            <label for="productpurchasepaidamount"><?=$this->lang->line('productpurchase_amount')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productpurchasepaidamount" name="productpurchasepaidamount">
                                            <span id="productpurchasepaidamounterror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productpurchasepaidpaymentmethoderrorDiv">
                                            <label for="productpurchasepaidpaymentmethod"><?=$this->lang->line('productpurchase_paymentmethod')?> <span class="text-red">*</span></label>
                                            <?php
                                                $paymentmethodArray = array(
                                                    0 => $this->lang->line('productpurchase_select_paymentmethod'),
                                                    1 => $this->lang->line('productpurchase_cash'),
                                                    2 => $this->lang->line('productpurchase_cheque'),
                                                    3 => $this->lang->line('productpurchase_credit_card'),
                                                    4 => $this->lang->line('productpurchase_other'),
                                                );
                                                echo form_dropdown("productpurchasepaidpaymentmethod", $paymentmethodArray, set_value("productpurchasepaidpaymentmethod"), "id='productpurchasepaidpaymentmethod' class='form-control select2'");
                                            ?>

                                            <span id="productpurchasepaidpaymentmethoderror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productpurchasepaidfileerrorDiv">
                                            <label for="productpurchasepaidfile"><?=$this->lang->line('productpurchase_file')?></label>
                                            <div class="input-group image-preview">
                                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                        <span class="fa fa-remove"></span>
                                                        <?=$this->lang->line('productpurchase_clear')?>
                                                    </button>
                                                    <div class="btn btn-success image-preview-input">
                                                        <span class="fa fa-repeat"></span>
                                                        <span class="image-preview-input-title">
                                                        <?=$this->lang->line('productpurchase_browse')?></span>
                                                        <input type="file" name="productpurchasepaidfile"/>
                                                    </div>
                                                </span>
                                            </div>
                                            <span id="productpurchasepaidfileerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <?php if ($siteinfos->note==1) { ?>
                                    <div class="col-sm-12">
                                        <div class="callout callout-danger">
                                            <p><b>Note:</b> This payment add in current academic year.</p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                            <input type="button" id="add_payment_button" class="btn btn-success" value="<?=$this->lang->line("productpurchase_add_payment")?>" />
                        </div>
                    </div>
                  </div>
                </div>
            </form>

            <script type="text/javascript">
                $(function() {
                    var closebtn = $('<button/>', {
                        type:"button",
                        text: 'x',
                        id: 'close-preview',
                        style: 'font-size: initial;',
                    });
                    closebtn.attr("class","close pull-right");
                    
                    $('.image-preview').popover({
                        trigger:'manual',
                        html:true,
                        title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
                        content: "There's no image",
                        placement:'bottom'
                    });

                    $('.image-preview-clear').click(function(){
                        $('.image-preview').attr("data-content","").popover('hide');
                        $('.image-preview-filename').val("");
                        $('.image-preview-clear').hide();
                        $('.image-preview-input input:file').val("");
                        $(".image-preview-input-title").text("<?=$this->lang->line('productpurchase_browse')?>");
                    });

                    $(".image-preview-input input:file").change(function (){
                        var file = this.files[0];
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(".image-preview-input-title").text("<?=$this->lang->line('productpurchase_browse')?>");
                            $(".image-preview-clear").show();
                            $(".image-preview-filename").val(file.name);
                        }
                        reader.readAsDataURL(file);
                    });
                });
                
                function lenCheckerWithoutParseFloat(data, len) {
                    var retdata = 0;
                    var lencount = 0;
                    if(data.length > len) {
                        lencount = (data.length - len);
                        data = data.toString();
                        data = data.slice(0, -lencount);
                        retdata = data;
                    } else {
                        retdata = data;
                    }

                    return retdata;
                }

                function lenChecker(data, len) {
                    var retdata = 0;
                    var lencount = 0;
                    data = toFixedVal(data);
                    if(data.length > len) {
                        lencount = (data.length - len);
                        data = data.toString();
                        data = data.slice(0, -lencount);
                        retdata = parseFloat(data);
                    } else {
                        retdata = parseFloat(data);
                    }

                    return toFixedVal(retdata);
                }

                function dotAndNumber(data) {
                    var retArray = [];
                    var fltFlag = true;
                    if(data.length > 0) {
                        for(var i = 0; i <= (data.length-1); i++) {
                            if(i == 0 && data.charAt(i) == '.') {
                                fltFlag = false;
                                retArray.push(true);
                            } else {
                                if(data.charAt(i) == '.' && fltFlag == true) {
                                    retArray.push(true);
                                    fltFlag = false;
                                } else {
                                    if(isNumeric(data.charAt(i))) {
                                        retArray.push(true);
                                    } else {
                                        retArray.push(false);
                                    }
                                }

                            }
                        }
                    }

                    if(jQuery.inArray(false, retArray) ==  -1) {
                        return true;
                    }
                    return false;
                }

                function isNumeric(n) {
                    return !isNaN(parseFloat(n)) && isFinite(n);
                }

                function parseSentenceForNumber(sentence) {
                    var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
                    return matches && matches[0] || null;
                }

                function floatChecker(value) {
                    var val = value;
                    if(isNumeric(val)) {
                        return true;
                    } else {
                        return false;
                    }
                }

                function toFixedVal(x) {
                  if (Math.abs(x) < 1.0) {
                    var e = parseFloat(x.toString().split('e-')[1]);
                    if (e) {
                        x *= Math.pow(10,e-1);
                        x = '0.' + (new Array(e)).join('0') + x.toString().substring(2);
                    }
                  } else {
                    var e = parseFloat(x.toString().split('+')[1]);
                    if (e > 20) {
                        e -= 20;
                        x /= Math.pow(10,e);
                        x += (new Array(e+1)).join('0');
                    }
                  }
                  return x;
                }

                $('#productpurchasepaiddate').datepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy',
                    startDate:'<?=$schoolyearobj->startingdate?>',
                    endDate:'<?=$schoolyearobj->endingdate?>',
                });

                $(document).on('keyup', '#productpurchasepaidreferenceno', function() {
                    var productpurchasepaidreferenceno =  $(this).val();
                    if(productpurchasepaidreferenceno.length > 99) {
                        productpurchasepaidreferenceno = lenCheckerWithoutParseFloat(productpurchasepaidreferenceno, 99);
                        $(this).val(productpurchasepaidreferenceno);                    
                    }
                });

                var globalproductpurchasepaidamount = 0;
                var globalproductpurchaseID = 0;
                $(document).on('keyup', '#productpurchasepaidamount', function() {
                    var productpurchasepaidamount =  $(this).val();
                    if(dotAndNumber(productpurchasepaidamount)) {
                        if(productpurchasepaidamount != '' && productpurchasepaidamount != null) {
                            if(floatChecker(productpurchasepaidamount)) {
                                if(productpurchasepaidamount.length > 15) {
                                    productpurchasepaidamount = lenChecker(productpurchasepaidamount);
                                    $(this).val(productpurchasepaidamount);

                                    if(productpurchasepaidamount > globalproductpurchasepaidamount) {
                                        $(this).val(globalproductpurchasepaidamount);
                                    }                 
                                } else {
                                    if(productpurchasepaidamount > globalproductpurchasepaidamount) {
                                        $(this).val(globalproductpurchasepaidamount);
                                    }
                                }
                            }
                        }
                    } else {
                        var productpurchasepaidamount = parseSentenceForNumber($(this).val());
                        $(this).val(productpurchasepaidamount);
                    }
                });

                $('.getpurchaseinfobtn').click(function() {
                    var productpurchaseID =  $(this).attr('id');
                    globalproductpurchaseID = productpurchaseID;
                    if(productpurchaseID > 0) {
                        $.ajax({
                            type: 'POST',
                            url: "<?=base_url('productpurchase/getpurchaseinfo')?>",
                            data: {'productpurchaseID' : productpurchaseID},
                            dataType: "html",
                            success: function(data) {
                                $('#productpurchasepaidamount').val('');
                                var response = JSON.parse(data);
                                if(response.status == true) {
                                    $('#productpurchasepaidamount').val(response.dueamount);
                                    globalproductpurchasepaidamount = parseFloat(response.dueamount);
                                }
                            }
                        });
                    }   
                });

                $(document).on('click', '#add_payment_button', function() {
                    var error=0;;
                    var field = {
                        'productpurchasepaiddate'           : $('#productpurchasepaiddate').val(), 
                        'productpurchasepaidreferenceno'    : $('#productpurchasepaidreferenceno').val(), 
                        'productpurchasepaidamount'         : $('#productpurchasepaidamount').val(), 
                        'productpurchasepaidpaymentmethod'  : $('#productpurchasepaidpaymentmethod').val(), 
                    };

                    if (field['productpurchasepaiddate'] == '') {
                        $('#productpurchasepaiddateerrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productpurchasepaiddateerrorDiv').removeClass('has-error');
                    }

                    if (field['productpurchasepaidreferenceno'] == '') {
                        $('#productpurchasepaidreferencenoerrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productpurchasepaidreferencenoerrorDiv').removeClass('has-error');
                    }

                    if (field['productpurchasepaidamount'] == '') {
                        $('#productpurchasepaidamounterrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productpurchasepaidamounterrorDiv').removeClass('has-error');
                    }

                    if (field['productpurchasepaidpaymentmethod'] === '0') {
                        $('#productpurchasepaidpaymentmethoderrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productpurchasepaidpaymentmethoderrorDiv').removeClass('has-error');
                    }

                    if(error === 0) {
                        $(this).attr('disabled', 'disabled');
                        var formData = new FormData($('#productPurchasePaymentAddDataForm')[0]);
                        formData.append("productpurchaseID", globalproductpurchaseID);
                        makingPostDataPreviousofAjaxCall(formData);
                    }
                });

                function makingPostDataPreviousofAjaxCall(field) {
                    passData = field;
                    ajaxCall(passData);
                }

                function ajaxCall(passData) {
                    $.ajax({
                        type: 'POST',
                        url: "<?=base_url('productpurchase/saveproductpurchasepayment')?>",
                        data: passData,
                        async: true,
                        dataType: "html",
                        success: function(data) {
                            var response = JSON.parse(data);
                            errrorLoader(response);
                        },
                        cache: false,
                        contentType: false,
                        processData: false
                    });
                }

                function errrorLoader(response) {
                    if(response.status) {
                        window.location = "<?=base_url("productpurchase/view/$productpurchase->productpurchaseID")?>";
                    } else {
                        $('#add_payment_button').removeAttr('disabled');
                        $.each(response.error, function(index, val) {
                            toastr["error"](val)
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
                        });
                    }
                }
            </script>
        <?php } ?>
    <?php } ?>

    <script language="javascript" type="text/javascript">
        function printDiv(divID) {
            var divElements = document.getElementById(divID).innerHTML;
            var oldPage = document.body.innerHTML;
            document.body.innerHTML =
              "<html><head><title></title></head><body>" +
              divElements + "</body>";
            window.print();
            document.body.innerHTML = oldPage;
            window.location.reload();
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

        $('#send_pdf').click(function() {
            var field = {
                'to'                : $('#to').val(), 
                'subject'           : $('#subject').val(), 
                'message'           : $('#message').val(),
                'productpurchaseID' : "<?=$productpurchase->productpurchaseID;?>",
            };

            var to = $('#to').val();
            var subject = $('#subject').val();
            var error = 0;

            $("#to_error").html("");
            $("#subject_error").html("");

            if(to == "" || to == null) {
                error++;
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            }

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $('#send_pdf').attr('disabled','disabled');
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('productpurchase/send_mail')?>",
                    data: field,
                    dataType: "html",
                    success: function(data) {
                        var response = JSON.parse(data);
                        if (response.status == false) {
                            $('#send_pdf').removeAttr('disabled');
                            if(response.to) {
                                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
                            } 
                            if(response.subject) {
                                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
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
<?php } ?>