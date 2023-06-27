<?php if(count($productsale)) { ?>
    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                    echo btn_add_pdf('productsale/print_preview/'.$productsale->productsaleID, $this->lang->line('pdf_preview'));
                ?>

                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php 
                        if(permissionChecker('productsale_edit')) {
                            if((float)$productsalepaid->productsalepaidamount == 0 && $productsale->productsalerefund == 0) {
                                echo btn_sm_edit('productsale/edit/'.$productsale->productsaleID, $this->lang->line('edit')); 
                            }
                        }
                    ?>
                <?php } ?>
                <?php if($productsale->productsalerefund == 0) { ?>
                    <?php if($productsale->productsalestatus != 3) { ?>
                        <a href="#addpayment" id="<?=$productsale->productsaleID?>" class="btn-cs btn-sm-cs getsaleinfobtn" style="text-decoration: none;" role="button" data-toggle="modal"><i class="fa fa-credit-card"></i> <?=$this->lang->line('productsale_payment')?></a>
                    <?php } ?>
                <?php } ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>


            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("productsale/index")?>"><?=$this->lang->line('panel_title')?></a></li>
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
                        <small class="pull-right">
                            <?=$this->lang->line('productsale_create_date').' : '.date('d M Y')?>       
                        </small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <?php  echo $this->lang->line("productsale_from"); ?>
                    <?php if(count($siteinfos)) { ?>
                        <address>
                            <strong><?=$siteinfos->sname?></strong><br>
                            <?=$siteinfos->address?><br>
                            <?=$this->lang->line("productsale_phone"). " : ". $siteinfos->phone?><br>
                            <?=$this->lang->line("productsale_email"). " : ". $siteinfos->email?><br>
                        </address>
                    <?php } ?>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <?php  echo $this->lang->line("productsale_to"); ?>
                    <address>
                        <?php if(count($user)) { ?>
                            <?php if(isset($user->name)) { ?>
                                <strong><?=$user->name?></strong><br>
                                <?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : ''?><br>
                                <?=($user->address == NULL) ? '' : $user->address.'<br>' ?>
                                <?=$this->lang->line("productsale_phone"). " : ". $user->phone?><br>
                                <?=$this->lang->line("productsale_email"). " : ". $user->email?><br>
                            <?php } else { ?>
                                <strong><?=$user->srname?></strong><br>
                                <?=$this->lang->line("productsale_role")?> : <?=isset($usertypes[3]) ? $usertypes[3] : ''?><br>
                                <?=($user->address == NULL) ? '' : $user->address.'<br>' ?>
                                <?=$this->lang->line("productsale_phone"). " : "?><br>
                                <?=$this->lang->line("productsale_email"). " : "?><br>
                            <?php } ?>
                        <?php } ?>
                    </address>
                </div>
                <div class="col-sm-4 invoice-col" style="font-size: 16px;">
                    <b><?=$this->lang->line("productsale_referenceno")." : ".$productsale->productsalereferenceno?></b>
                    <br>
                    <?php 
                        $status = $productsale->productsalestatus;
                        $setButton = '';
                        if($status == 1) {
                            $status = $this->lang->line('productsale_pending');
                            $setButton = 'text-red';
                        } elseif($status == 2) {
                            $status = $this->lang->line('productsale_partial_paid');
                            $setButton = 'text-yellow';
                        } elseif($status == 3) {
                            $status = $this->lang->line('productsale_fully_paid');
                            $setButton = 'text-green';
                        }

                        echo $this->lang->line('productsale_payment_status'). " : ". "<span class='".$setButton."'>".$status."</span>";
                    ?>

                    <?php if($productsale->productsalerefund == 1) { ?>
                        <p class="refund"><?=$this->lang->line('productsale_refund')?></p>
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
                                    <th class="col-lg-4"><?=$this->lang->line('productsale_description')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productsale_unit_price')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productsale_quantity')?></th>
                                    <th class="col-lg-2"><?=$this->lang->line('productsale_subtotal')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $subtotal = 0; $totalsubtotal = 0; if(count($productsaleitems)) { $i=1; foreach ($productsaleitems as $productsaleitem) { $subtotal = ($productsaleitem->productsaleunitprice * $productsaleitem->productsalequantity); $totalsubtotal += $subtotal; ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('productsale_description')?>">
                                            <?=isset($products[$productsaleitem->productID]) ? $products[$productsaleitem->productID] : ''?>
                                        </td>
                                        
                                        <td data-title="<?=$this->lang->line('productsale_unit_price')?>">
                                            <?=number_format($productsaleitem->productsaleunitprice, 2)?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('productsale_quantity')?>">
                                            <?=number_format($productsaleitem->productsalequantity, 2)?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('productsale_subtotal')?> ">
                                            <?=number_format($subtotal, 2)?>
                                        </td>
                                    </tr>
                                <?php $i++; } } ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productsale_total_amount')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($totalsubtotal, 2)?></b></td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productsale_paid')?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format($productsalepaid->productsalepaidamount, 2)?></b></td>
                                </tr> 
                                <tr>
                                    <td colspan="4"><span class="pull-right"><b><?=$this->lang->line('productsale_balance');?> <?=!empty($siteinfos->currency_code) ? '('.$siteinfos->currency_code.')' : ''?></b></span></td>
                                    <td><b><?=number_format(($totalsubtotal - $productsalepaid->productsalepaidamount), 2)?></b></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="col-sm-9 col-xs-12 pull-left">
                    <p><?=$productsale->productsaledescription?></p>
                </div>
                <div class="col-sm-3 col-xs-12 pull-right">
                    <div class="well well-sm">
                        <p>
                            <?=$this->lang->line('productsale_create_by')?> : <?=$createuser?>
                            <br>
                            <?=$this->lang->line('productsale_date')?> : <?=date('d M Y', strtotime($productsale->create_date))?>
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>


    <form class="form-horizontal" role="form" action="<?=base_url('productsale/send_mail');?>" method="post">
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

    <?php if($productsale->productsalerefund == 0) { ?>
        <?php if($productsale->productsalestatus != 3) { ?>
            <form class="form-horizontal" role="form" method="post" id="productSalePaymentAddDataForm" enctype="multipart/form-data">
                <div class="modal fade" id="addpayment">
                  <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title"><?=$this->lang->line('productsale_add_payment')?></h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productsalepaiddateerrorDiv">
                                            <label for="productsalepaiddate"><?=$this->lang->line('productsale_date')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productsalepaiddate" name="productsalepaiddate">
                                            <span id="productsalepaiddateerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productsalepaidreferencenoerrorDiv">
                                            <label for="productsalepaidreferenceno"><?=$this->lang->line('productsale_referenceno')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productsalepaidreferenceno" name="productsalepaidreferenceno">
                                            <span id="productsalepaidreferencenoerror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productsalepaidamounterrorDiv">
                                            <label for="productsalepaidamount"><?=$this->lang->line('productsale_amount')?> <span class="text-red">*</span></label>
                                            <input type="text" class="form-control" id="productsalepaidamount" name="productsalepaidamount">
                                            <span id="productsalepaidamounterror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productsalepaidpaymentmethoderrorDiv">
                                            <label for="productsalepaidpaymentmethod"><?=$this->lang->line('productsale_paymentmethod')?> <span class="text-red">*</span></label>
                                            <?php
                                                $paymentmethodArray = array(
                                                    0 => $this->lang->line('productsale_select_paymentmethod'),
                                                    1 => $this->lang->line('productsale_cash'),
                                                    2 => $this->lang->line('productsale_cheque'),
                                                    3 => $this->lang->line('productsale_credit_card'),
                                                    4 => $this->lang->line('productsale_other'),
                                                );
                                                echo form_dropdown("productsalepaidpaymentmethod", $paymentmethodArray, set_value("productsalepaidpaymentmethod"), "id='productsalepaidpaymentmethod' class='form-control select2'");
                                            ?>

                                            <span id="productsalepaidpaymentmethoderror"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-sm-12">
                                        <div class="form-group" id="productsalepaidfileerrorDiv">
                                            <label for="productsalepaidfile"><?=$this->lang->line('productsale_file')?></label>
                                            <div class="input-group image-preview">
                                                <input type="text" class="form-control image-preview-filename" disabled="disabled">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-default image-preview-clear" style="display:none;">
                                                        <span class="fa fa-remove"></span>
                                                        <?=$this->lang->line('productsale_clear')?>
                                                    </button>
                                                    <div class="btn btn-success image-preview-input">
                                                        <span class="fa fa-repeat"></span>
                                                        <span class="image-preview-input-title">
                                                        <?=$this->lang->line('productsale_browse')?></span>
                                                        <input type="file" name="productsalepaidfile" id="productsalepaidfile"/>
                                                    </div>
                                                </span>
                                            </div>
                                            <span id="productsalepaidfileerror"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                            <input type="button" id="add_payment_button" class="btn btn-success" value="<?=$this->lang->line("productsale_add_payment")?>" />
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
                        $(".image-preview-input-title").text("<?=$this->lang->line('productsale_browse')?>");
                    });

                    $(".image-preview-input input:file").change(function (){
                        var file = this.files[0];
                        var reader = new FileReader();

                        reader.onload = function (e) {
                            $(".image-preview-input-title").text("<?=$this->lang->line('productsale_browse')?>");
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

                var globalproductsalepaidamount = 0;
                var globalproductsaleID = 0;
                $(document).on('keyup', '#productsalepaidamount', function() {
                    var productsalepaidamount =  $(this).val();
                    if(dotAndNumber(productsalepaidamount)) {
                        if(productsalepaidamount != '' && productsalepaidamount != null) {
                            if(floatChecker(productsalepaidamount)) {
                                if(productsalepaidamount.length > 15) {
                                    productsalepaidamount = lenChecker(productsalepaidamount, 15);
                                    $(this).val(productsalepaidamount);  

                                    if(productsalepaidamount > globalproductsalepaidamount) {
                                        $(this).val(globalproductsalepaidamount);
                                    }              
                                } else {
                                    if(productsalepaidamount > globalproductsalepaidamount) {
                                        $(this).val(globalproductsalepaidamount);
                                    }
                                }
                            }
                        }
                    } else {
                        var productsalepaidamount = parseSentenceForNumber($(this).val());
                        $(this).val(productsalepaidamount);
                    }
                });

                $('#productsalepaiddate').datepicker({
                    autoclose: true,
                    format: 'dd-mm-yyyy',
                    startDate:'<?=$schoolyearobj->startingdate?>',
                    endDate:'<?=$schoolyearobj->endingdate?>',
                });

                $('.getsaleinfobtn').click(function() {
                    var productsaleID =  $(this).attr('id');
                    globalproductsaleID = productsaleID;
                    if(productsaleID > 0) {
                        $.ajax({
                            type: 'POST',
                            url: "<?=base_url('productsale/getsaleinfo')?>",
                            data: {'productsaleID' : productsaleID},
                            dataType: "html",
                            success: function(data) {
                                $('#productsalepaidamount').val('');
                                var response = JSON.parse(data);
                                if(response.status == true) {
                                    $('#productsalepaidamount').val(response.dueamount);
                                    globalproductsalepaidamount = parseFloat(response.dueamount);
                                }
                            }
                        });
                    }   
                });

                $(document).on('keyup', '#productsalepaidreferenceno', function() {
                    var productsalepaidamount =  $(this).val();
                    if(productsalepaidamount.length > 99) {
                        productsalepaidamount = lenCheckerWithoutParseFloat(productsalepaidamount, 99);
                        $(this).val(productsalepaidamount);                    
                    }
                });

                $(document).on('click', '#add_payment_button', function() {
                    var error=0;;
                    var field = {
                        'productsalepaiddate'           : $('#productsalepaiddate').val(), 
                        'productsalepaidreferenceno'    : $('#productsalepaidreferenceno').val(), 
                        'productsalepaidamount'         : $('#productsalepaidamount').val(), 
                        'productsalepaidpaymentmethod'  : $('#productsalepaidpaymentmethod').val(), 
                    };

                    if (field['productsalepaiddate'] == '') {
                        $('#productsalepaiddateerrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productsalepaiddateerrorDiv').removeClass('has-error');
                    }

                    if (field['productsalepaidreferenceno'] == '') {
                        $('#productsalepaidreferencenoerrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productsalepaidreferencenoerrorDiv').removeClass('has-error');
                    }

                    if (field['productsalepaidamount'] == '') {
                        $('#productsalepaidamounterrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productsalepaidamounterrorDiv').removeClass('has-error');
                    }

                    if (field['productsalepaidpaymentmethod'] === '0') {
                        $('#productsalepaidpaymentmethoderrorDiv').addClass('has-error');
                        error++;
                    } else {
                        $('#productsalepaidpaymentmethoderrorDiv').removeClass('has-error');
                    }

                    if(error === 0) {
                        $(this).attr('disabled', 'disabled');
                        var formData = new FormData($('#productSalePaymentAddDataForm')[0]);
                        formData.append("productsaleID", globalproductsaleID);
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
                        url: "<?=base_url('productsale/saveproductsalepayment')?>",
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
                        window.location = "<?=base_url("productsale/view/".$productsale->productsaleID)?>";
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
                'productsaleID'     : "<?=$productsale->productsaleID;?>",
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
                    url: "<?=base_url('productsale/send_mail')?>",
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