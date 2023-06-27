<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productsale"></i> <?=$this->lang->line('panel_title')?></h3>


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
                    <?php if(permissionChecker('productsale_add')) { ?>
                        <h5 class="page-header">
                            <a href="<?php echo base_url('productsale/add') ?>">
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
                                <th><?=$this->lang->line('productsale_referenceno')?></th>
                                <th><?=$this->lang->line('productsale_role')?></th>
                                <th><?=$this->lang->line('productsale_user')?></th>
                                <th><?=$this->lang->line('productsale_date')?></th>
                                <th><?=$this->lang->line('productsale_file')?></th>
                                <th><?=$this->lang->line('productsale_grandtotal')?></th>
                                <th><?=$this->lang->line('productsale_paid')?></th>
                                <th><?=$this->lang->line('productsale_balance')?></th>
                                <?php if(permissionChecker('productsale_edit') || permissionChecker('productsale_delete') || permissionChecker('productsale_view')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($productsales)) {$i = 1; foreach($productsales as $productsale) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsale_referenceno')?>">
                                        <?=$productsale->productsalereferenceno;?>
                                        <?=($productsale->productsalerefund) ? '<span class="text-red">('. $this->lang->line('productsale_refund') .')</span>' : ''?>
                                    </td> 
                                    <td data-title="<?=$this->lang->line('productsale_role')?>">
                                        <?=isset($usertypes[$productsale->productsalecustomertypeID]) ? $usertypes[$productsale->productsalecustomertypeID] : ''?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productsale_user')?>">
                                        <?php 
                                            if(isset($users[$productsale->productsalecustomertypeID][$productsale->productsalecustomerID])) {
                                                if(isset($users[$productsale->productsalecustomertypeID][$productsale->productsalecustomerID]->name)) {
                                                    echo $users[$productsale->productsalecustomertypeID][$productsale->productsalecustomerID]->name;
                                                } else {
                                                    echo $users[$productsale->productsalecustomertypeID][$productsale->productsalecustomerID]->srname;
                                                }
                                            }
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productsale_date')?>">
                                        <?=date('d M Y', strtotime($productsale->productsaledate));?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productsale_file')?>">
                                        <?php 
                                            if($productsale->productsalefileorginalname) { echo btn_download_file('productsale/download/'.$productsale->productsaleID, namesorting($productsale->productsalefileorginalname, 12), $this->lang->line('download')); 
                                            }
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('productsale_grandtotal')?>">
                                        <?=isset($grandtotalandpaid['grandtotal'][$productsale->productsaleID]) ? number_format($grandtotalandpaid['grandtotal'][$productsale->productsaleID], 2) : ''?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productsale_paid')?>">
                                        <?=isset($grandtotalandpaid['totalpaid'][$productsale->productsaleID]) ? number_format($grandtotalandpaid['totalpaid'][$productsale->productsaleID], 2) : '0.00'?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('productsale_balance')?>">
                                        <?php
                                            if(isset($grandtotalandpaid['grandtotal'][$productsale->productsaleID]) && isset($grandtotalandpaid['totalpaid'][$productsale->productsaleID])) {
                                                echo number_format(($grandtotalandpaid['grandtotal'][$productsale->productsaleID] - $grandtotalandpaid['totalpaid'][$productsale->productsaleID]), 2);
                                            } elseif(isset($grandtotalandpaid['grandtotal'][$productsale->productsaleID])) {
                                                echo number_format($grandtotalandpaid['grandtotal'][$productsale->productsaleID], 2);
                                            } elseif(isset($grandtotalandpaid['totalpaid'][$productsale->productsaleID])) {
                                                echo number_format((0-$grandtotalandpaid['totalpaid'][$productsale->productsaleID]), 2);
                                            }
                                        ?>
                                    </td>


                                    <?php if(permissionChecker('productsale_edit') || permissionChecker('productsale_delete') || permissionChecker('productsale_view')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php
                                                echo btn_view('productsale/view/'.$productsale->productsaleID, $this->lang->line('view'));

                                                if(($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) || ($this->session->userdata('usertypeID') == 1) || ($this->session->userdata('usertypeID') == 5)) {
                                                    if(isset($grandtotalandpaid['totalpaid'][$productsale->productsaleID]) && $grandtotalandpaid['totalpaid'][$productsale->productsaleID] > 0) {
                                                        if($productsale->productsalerefund == 0) {
                                                            if(permissionChecker('productsale_edit') && permissionChecker('productsale_delete')) {
                                                                echo btn_cancel('productsale/cancel/'.$productsale->productsaleID, $this->lang->line('cancel'));
                                                            }
                                                        }
                                                    } else {
                                                        echo btn_edit('productsale/edit/'.$productsale->productsaleID, $this->lang->line('edit'));
                                                        echo btn_delete('productsale/delete/'.$productsale->productsaleID, $this->lang->line('delete'));
                                                    }
                                                }

                                                if($productsale->productsalerefund == 0) {
                                                    if(permissionChecker('productsale_add')) {
                                                        if(isset($grandtotalandpaid['grandtotal'][$productsale->productsaleID]) && isset($grandtotalandpaid['totalpaid'][$productsale->productsaleID])) {
                                                            if((float)$grandtotalandpaid['grandtotal'][$productsale->productsaleID] > (float)$grandtotalandpaid['totalpaid'][$productsale->productsaleID]) {
                                                                echo '<a href="#addpayment" id="'.$productsale->productsaleID.'" class="btn btn-primary btn-xs mrg getsaleinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productsale_add_payment').'"></i></a>';
                                                            }
                                                        } else {
                                                            if(isset($grandtotalandpaid['grandtotal'][$productsale->productsaleID])) {
                                                                echo '<a href="#addpayment" id="'.$productsale->productsaleID.'" class="btn btn-primary btn-xs mrg getsaleinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-credit-card" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productsale_add_payment').'"></i></a>';
                                                            }
                                                        }
                                                    }
                                                }

                                                if(permissionChecker('productsale_view')) {
                                                    echo '<a href="#paymentlist" id="'.$productsale->productsaleID.'" class="btn btn-info btn-xs mrg getpaymentinfobtn" rel="tooltip" data-toggle="modal"><i class="fa fa-list-ul" data-toggle="tooltip" data-placement="top" data-original-title="'.$this->lang->line('productsale_view_payments').'"></i></a>';
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
                                <label for="productsalepaidreferenceno"><?=$this->lang->line('productsale_referenceno')?></label>
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
                <input type="button" id="add_payment_button" class="btn btn-success" value="<?=$this->lang->line("productsale_add_payment")?>" />
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
                <h4 class="modal-title"><?=$this->lang->line('productsale_view_payments')?></h4>
            </div>
            <div class="modal-body">
                <div id="hide-table">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('productsale_date')?></th>
                                <th><?=$this->lang->line('productsale_referenceno')?></th>
                                <th><?=$this->lang->line('productsale_amount')?></th>
                                <th><?=$this->lang->line('productsale_paid_by')?></th>
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

    $('#productsalepaiddate').datepicker({
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

    $(document).on('keyup', '#productsalepaidreferenceno', function() {
        var productsalepaidamount =  $(this).val();
        if(productsalepaidamount.length > 99) {
            productsalepaidamount = lenCheckerWithoutParseFloat(productsalepaidamount, 99);
            $(this).val(productsalepaidamount);                    
        }
    });

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

    $('.getpaymentinfobtn').click(function() {
        var productsaleID =  $(this).attr('id');
        if(productsaleID > 0) {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productsale/paymentlist')?>",
                data: {'productsaleID' : productsaleID},
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

        // if (field['productsalepaidreferenceno'] == '') {
        //     $('#productsalepaidreferencenoerrorDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('#productsalepaidreferencenoerrorDiv').removeClass('has-error');
        // }

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
            window.location = "<?=base_url("productsale/index")?>";
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

