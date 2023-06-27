<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productpurchase"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                    <?php if(permissionChecker('productpurchase_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('productpurchase/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        </h5>
                    <?php } ?>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('productpurchase_referenceno')?></th>
                                <th><?=$this->lang->line('productpurchase_supplier')?></th>
                                <th><?=$this->lang->line('productpurchase_date')?></th>
                                <th><?=$this->lang->line('productpurchase_file')?></th>
                                <th><?=$this->lang->line('productpurchase_grandtotal')?></th>
                                <th><?=$this->lang->line('productpurchase_paid')?></th>
                                <th><?=$this->lang->line('productpurchase_balance')?></th>
                                <?php if(permissionChecker('productpurchase_edit') || permissionChecker('productpurchase_delete') || permissionChecker('productpurchase_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($productpurchases)) {$i = 1; foreach($productpurchases as $productpurchase) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productpurchase_referenceno')?>">
                                        <?=$productpurchase->productpurchasereferenceno;?>
                                        <?=($productpurchase->productpurchaserefund) ? '<span class="text-red">('. $this->lang->line('productpurchase_refund') .')</span>' : ''?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('productpurchase_supplier')?>">
                                        <?=isset($productsuppliers[$productpurchase->productsupplierID]) ? $productsuppliers[$productpurchase->productsupplierID] : ''?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productpurchase_date')?>">
                                        <?=date('d M Y', strtotime($productpurchase->productpurchasedate));?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productpurchase_file')?>">
                                        <?php 
                                            if($productpurchase->productpurchasefileorginalname) { echo btn_download_file('productpurchase/download/'.$productpurchase->productpurchaseID, namesorting($productpurchase->productpurchasefileorginalname, 12), $this->lang->line('download')); 
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productpurchase_grandtotal')?>">
                                        <?=isset($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID]) ? number_format($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID] + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount, 2) : '0.00'?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productpurchase_paid')?>">
                                        <?=isset($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID]) ? number_format($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID], 2) : '0.00'?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productpurchase_balance')?>">
                                        <?php

                                            $grandtotal = $grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID] + $productpurchase->purchase_product_vat_amount - $productpurchase->purchase_product_discount;

                                            if(isset($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID]) && isset($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID])) {
                                                echo number_format(($grandtotal - $grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID]), 2);
                                            } elseif(isset($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID])) {
                                                echo number_format($grandtotal, 2);
                                            } elseif(isset($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID])) {
                                                echo number_format((0-$grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID]), 2);
                                            }
                                        ?>
                                    </td>


                                    <?php if(permissionChecker('productpurchase_edit') || permissionChecker('productpurchase_delete') || permissionChecker('productpurchase_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php
                                                echo btn_view('productpurchase/view/'.$productpurchase->productpurchaseID, $this->lang->line('view'));


                                                if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) {
                                                    if(isset($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID]) && $grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID] > 0) {
                                                        if($productpurchase->productpurchaserefund == 0) {
                                                            if(permissionChecker('productpurchase_edit') && permissionChecker('productpurchase_delete')) {
                                                                echo btn_cancel('productpurchase/cancel/'.$productpurchase->productpurchaseID, $this->lang->line('cancel'));
                                                            }
                                                        }
                                                    } else {
                                                        echo btn_edit('productpurchase/edit/'.$productpurchase->productpurchaseID, $this->lang->line('edit'));
                                                        echo btn_delete('productpurchase/delete/'.$productpurchase->productpurchaseID, $this->lang->line('delete'));
                                                    }
                                                }

                                                if($productpurchase->productpurchaserefund == 0) {
                                                    if(permissionChecker('productpurchase_add')) {
                                                        if(isset($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID]) && isset($grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID])) {
                                                            if((float)$grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID] > (float)$grandtotalandpaid['totalpaid'][$productpurchase->productpurchaseID]) {
                                                                echo '<a href="#addpayment" id="'.$productpurchase->productpurchaseID.'" class="btn btn-primary btn-xs mrg getpurchaseinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productpurchase_add_payment').'"></i></a>';
                                                            }
                                                        } else {
                                                            if(isset($grandtotalandpaid['grandtotal'][$productpurchase->productpurchaseID])) {
                                                                echo '<a href="#addpayment" id="'.$productpurchase->productpurchaseID.'" class="btn btn-primary btn-xs mrg getpurchaseinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productpurchase_add_payment').'"></i></a>';
                                                            }
                                                        }
                                                    }
                                                }

                                                if(permissionChecker('productpurchase_view')) {
                                                    echo '<a href="#paymentlist" id="'.$productpurchase->productpurchaseID.'" class="btn btn-info btn-xs mrg getpaymentinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-list-ul" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productpurchase_view_payments').'"></i></a>';
                                                }
                                            ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


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
                                <label for="productpurchasepaidreferenceno"><?=$this->lang->line('productpurchase_referenceno')?></label>
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

<form class="form-horizontal" role="form" method="post">
    <div class="modal fade" id="paymentlist">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="modal-title"><?=$this->lang->line('productpurchase_view_payments')?></h4>
            </div>
            <div class="modal-body">
                <div id="hide-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('productpurchase_date')?></th>
                                <th><?=$this->lang->line('productpurchase_referenceno')?></th>
                                <th><?=$this->lang->line('productpurchase_amount')?></th>
                                <th><?=$this->lang->line('productpurchase_paid_by')?></th>
                                <?php if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) { ?>
                                    <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody id="payment-list-body">
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
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

    $('#productpurchasepaiddate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearobj->startingdate?>',
        endDate:'<?=$schoolyearobj->endingdate?>',
    });

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
    }

    function floatChecker(value) {
        var val = value;
        if(isNumeric(val)) {
            return true;
        } else {
            return false;
        }
    }

    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    } 

    function sentanceLengthRemove(sentence) {
        sentence = sentence.toString();
        sentence = sentence.slice(0, -1);
        sentence = parseFloat(sentence);
        return sentence;
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

    $('.getpaymentinfobtn').click(function() {
        var productpurchaseID =  $(this).attr('id');
        if(productpurchaseID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productpurchase/paymentlist')?>",
                data: {'productpurchaseID' : productpurchaseID},
                dataType: "html",
                success: function(data) {
                    $('#payment-list-body').children().remove();
                    $('#payment-list-body').append(data);
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

        // if (field['productpurchasepaidreferenceno'] == '') {
        //     $('#productpurchasepaidreferencenoerrorDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('#productpurchasepaidreferencenoerrorDiv').removeClass('has-error');
        // }

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
            window.location = "<?=base_url("productpurchase/index")?>";
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

