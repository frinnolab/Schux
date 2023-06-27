
<div class="row">
    <div class="col-sm-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data" id="invoiceDataForm"> 

                    <div class="classesDiv form-group <?=form_error('classesID') ? 'has-error' : '' ?>" >
                        <label for="classesID">
                            <?=$this->lang->line("invoice_classesID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $classesArray = array('0' => $this->lang->line("invoice_select_classes"));
                                if(count($classes)) {
                                    foreach ($classes as $classa) {
                                        $classesArray[$classa->classesID] = $classa->classes;
                                    }
                                }
                                echo form_dropdown("classesID", $classesArray, set_value("classesID"), "id='classesID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <div class="sectionsDiv form-group <?=form_error('sectionsID') ? 'has-error' : '' ?>" >
                        <label for="sectionsID">
                            <?=$this->lang->line("invoice_sectionsID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $sectionsArray = array('0' => $this->lang->line("invoice_select_sections"), '-1' => $this->lang->line("not_admitted_students"));
                                
                                echo form_dropdown("sectionsID", $sectionsArray, set_value("sectionsID"), "id='sectionsID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('sectionsID'); ?>
                        </span>
                    </div>

                    <div class="studentDiv form-group <?=form_error('studentID') ? 'has-error' : '' ?>" >
                        <label for="studentID">
                            <?=$this->lang->line("invoice_studentID")?> <span class="text-red">*</span>
                        </label>
                            <?php
                                $studentArray = array('0' => $this->lang->line("invoice_all_student"));
                                if(count($students)) {
                                    foreach ($students as $student) {
                                        $studentArray[$student->studentID] = $student->name;
                                    }
                                }
                                echo form_dropdown("studentID", $studentArray, set_value("studentID"), "id='studentID' class='form-control select2'");
                            ?>
                        <span class="text-red">
                            <?php echo form_error('studentID'); ?>
                        </span>
                    </div>

                    <div class="dateDiv form-group <?=form_error('date') ? 'has-error' : '' ?>" >
                        <label for="date">
                            <?=$this->lang->line("invoice_date")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        <span class="text-red">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <div class="statusDiv form-group <?=form_error('statusID') ? 'has-error' : '' ?>" >
                        <label for="statusID">
                            <?=$this->lang->line("invoice_status")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $statusArray = array(
                                5 => $this->lang->line("invoice_select_paymentstatus"),
                                0 => $this->lang->line("invoice_notpaid"),
                                1 => $this->lang->line("invoice_partially_paid"),
                                2 => $this->lang->line("invoice_fully_paid")
                            );

                            echo form_dropdown("statusID", $statusArray, set_value("statusID"), "id='statusID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('statusID'); ?>
                        </span>
                    </div>

                    <div class="paymentmethodDiv hide form-group <?=form_error('paymentmethodID') ? 'has-error' : '' ?>" >
                        <label for="paymentmethodID">
                            <?=$this->lang->line("invoice_paymentmethod")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $paymentmethodArray = array(
                                '0' => $this->lang->line("invoice_select_paymentmethod"),
                                'Cash' => $this->lang->line('Cash'),
                                'Cheque' => $this->lang->line('Cheque'),
                                'DTB TZS' => $this->lang->line('DTBTZS'),
                                'DTB USD' => $this->lang->line('DTBUSD'),
                                'Bank of Baroda TZS' => $this->lang->line('BarodaTZS'),
                                'Bank of Baroda USD' => $this->lang->line('BarodaUSD'),
                                'Exim Bank USD' => $this->lang->line('EximUSD'),
                                'Exim Bank TZS' => $this->lang->line('EximTZS'),
                            );
                            echo form_dropdown("paymentmethodID", $paymentmethodArray, set_value("paymentmethodID"), "id='paymentmethodID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('paymentmethodID'); ?>
                        </span>
                    </div>

                    <input id="addInvoiceButton" type="button" class="btn btn-success" value="<?=$this->lang->line("add_invoice")?>" >
                </form>
            </div>
        </div>
    </div>


    <div class="col-sm-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-feetypes"></i> <?=$this->lang->line('invoice_feetype_list')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_invoice')?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="" role="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group <?=form_error('feetypeID') ? 'has-error' : '' ?>" >
                                <label for="feetypeID" class="control-label">
                                    <?=$this->lang->line("invoice_feetype")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $feetypeArray = array('0' => $this->lang->line("invoice_select_feetype"));
                                    foreach ($feetypes as $feetype) {
                                        $feetypeArray[$feetype->feetypesID] = $feetype->feetypes;
                                    }
                                    echo form_dropdown("feetypeID", $feetypeArray, set_value("feetypeID"), "id='feetypeID' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('feetypeID'); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered feetype-style" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-3"><?=$this->lang->line('invoice_feetype')?></th>
                                <th class="col-sm-2" ><?=$this->lang->line('invoice_amount')?></th>
                                <th class="col-sm-1" ><?=$this->lang->line('invoice_discount')?>(%)</th>
                                <th class="col-sm-2" ><?=$this->lang->line('invoice_subtotal')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('invoice_paid_amount')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('due_date')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="feetypeList">
                        </tbody>

                        <tfoot id="feetypeListFooter">
                            <tr>
                                <td colspan="2" style="font-weight: bold"><?=$this->lang->line('invoice_total')?></td>
                                <td id="totalAmount" style="font-weight: bold">0.00 USD <br /> 0.00 GBP <br /> 0.00 TZS</td>
                                <td id="totalDiscount" style="font-weight: bold">0.00</td>
                                <td id="totalSubtotal" style="font-weight: bold">0.00 USD <br /> 0.00 GBP <br /> 0.00 TZS</td>
                                <td id="totalPaidAmount" style="font-weight: bold">0.00 USD <br /> 0.00 GBP <br /> 0.00 TZS</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function dd(data) {
        console.log(data);
    }

    $('.select2').select2();
    $('#date').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });
    

    $('#classesID').change(function(event) {
        var classesID = $(this).val();

        if(classesID === '0') {
            $('#sectionsID').html('<option value="0"><?=$this->lang->line('invoice_all_section')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/getsection')?>",
                data: {'classesID' : classesID},
                dataType: "html",
                success: function(data) {
                    $('#sectionsID').html(data);
                }
            });
        }
    });

    $('#sectionsID').change(function(event) {
        var sectionsID = $(this).val();
        let classID = $('#classesID').val()
        console.log(classID)

        if(sectionsID === '0') {
            $('#studentID').html('<option value="0"><?=$this->lang->line('invoice_all_student')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/getstudent')?>",
                data: {'sectionsID' : sectionsID, 'classesID' : classID},
                dataType: "html",
                success: function(data) {
                    $('#studentID').html(data);
                }
            });
        }
    });

    // $('#classesID').change(function(event) {
    //     var classesID = $(this).val();

    //     if(classesID === '0') {
    //         $('#studentID').html('<option value="0"><?=$this->lang->line('invoice_all_student')?></option>');
    //     } else {
    //         $.ajax({
    //             type: 'POST',
    //             url: "<?=base_url('invoice/getstudent')?>",
    //             data: {'classesID' : classesID},
    //             dataType: "html",
    //             success: function(data) {
    //                 $('#studentID').html(data);
    //             }
    //         });
    //     }
    // });

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }

    function productItemDesign(feetypeID, productText) {
        var randID = getRandomInt();
        
        $(`#td_due_date_${randID}`).datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
        });

        if($('#feetypeList tr:last').text() == '') {
            var lastTdNumber = 0;
        } else {
            var lastTdNumber = $("#feetypeList tr:last td:eq(0)").text();
        }

        lastTdNumber = parseInt(lastTdNumber);
        lastTdNumber++;

        var text = '<tr id="tr_'+randID+'" invoicefeetypeID="'+feetypeID+'">';
            text += '<td>';
                text += lastTdNumber;
            text += '</td>';

            text += '<td>';
                text += productText;
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-amount" id="td_amount_id_'+randID+'" data-amount-id="'+randID+'">');
                text += '<select class="form-control change-currency" id="td_currency_id_'+randID+'"> <option value="TZS">TZS</option> <option value="USD">USD</option> <option value="GBP">GBP</option> </select>';
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-discount" id="td_discount_id_'+randID+'" data-discount-id="'+randID+'">');
            text += '</td>';

            text += '<td>';
                text += '0.00';
            text += '</td>';

            text += '<td>';
                if($('#statusID').val() != 0 && $('#statusID').val() != 5) {
                    text += ('<input type="text" class="form-control change-paidamount" id="td_paidamount_id_'+randID+'" data-paidamount-id="'+randID+'">');
                } else {
                    text += ('<input type="text" class="form-control change-paidamount" id="td_paidamount_id_'+randID+'" data-paidamount-id="'+randID+'" readonly="readonly">');
                }
            text += '</td>';

            text += '<td>';
                text += ('<div class="dateDiv form-group <?=form_error('date') ? 'has-error' : '' ?>" >');
                text += '<input type="date" class="form-control due_date" id="td_due_date_'+randID+'" name="due_date" value="<?=set_value('date')?>" >';
                text += '    <span class="text-red">';
                text += '        <?php echo form_error('date'); ?>';
                text += '    </span>';
                text += '</div>';
            text += '</td>';

            text += '<td>';
                text += ('<a style="margin-top:3px" href="#" class="btn btn-danger btn-sm deleteBtn" id="feetype_'+randID+'" data-feetype-id="'+randID+'"><i class="fa fa-trash-o"></i></a>');
            text += '</td>';
        text += '</tr>';

        return text; 
    }

    $('#feetypeID').change(function(e) {
        var feetypeID   = $(this).val();
        if(feetypeID != 0) {
            var feetypeText = $(this).find(":selected").text();
            var appendData  = productItemDesign(feetypeID, feetypeText);
            $('#feetypeList').append(appendData);
        }
    });

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

    function isNumeric(n) {
        return !isNaN(parseFloat(n)) && isFinite(n);
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

    function floatChecker(value) {
        var val = value;
        if(isNumeric(val)) {
            return true;
        } else {
            return false;
        }
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
    
    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    }

    function currencyConvert(data) {
        return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
    }

    var globaltotalamount = 0;
    var globaltotaldiscount = 0;
    var globaltotalsubtotal = 0;
    var globaltotalpaidamount = 0;

    var globaltotalUSDamount = 0;
    var globaltotalGBPamount = 0;
    var globaltotalTZSamount = 0;

    var globaltotalUSDsubtotal = 0;
    var globaltotalGBPsubtotal = 0;
    var globaltotalTZSsubtotal = 0;

    var globaltotalpaidUSDamount = 0;
    var globaltotalpaidGBPamount = 0;
    var globaltotalpaidTZSamount = 0;

    function totalInfo() {
        var i = 1;
        var j = 1;

        var totalAmount = 0;
        var totalDiscount = 0;
        var totalSubtotal = 0;
        var totalPaidAmount = 0;

        var totalUSDAmount = 0;
        var totalGBPAmount = 0;
        var totalTZSAmount = 0;

        var totalUSDSubtotal = 0;
        var totalGBPSubtotal = 0;
        var totalTZSSubtotal = 0;

        var totalPaidUSDAmount = 0;
        var totalPaidGBPAmount = 0;
        var totalPaidTZSAmount = 0;

        var discount = 0; 

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(2).children().val() != '' && $(this).children().eq(2).children().val() != null && $(this).children().eq(2).children().val() != '.') {
                var currency = $(this).children().eq(2).children().eq(1).val();
                var amount = parseFloat($(this).children().eq(2).children().val());
                totalAmount += amount;
                if(currency == "USD") {
                    totalUSDAmount += amount;
                }
                if(currency == "GBP") {
                    totalGBPAmount += amount;
                }
                if(currency == "TZS") {
                    totalTZSAmount += amount;
                }
            } 
        });
        globaltotalamount = totalAmount;
        globaltotalUSDamount = totalUSDAmount;
        globaltotalGBPamount = totalGBPAmount;
        globaltotalTZSamount = totalTZSAmount;
        // $('#totalAmount').text(currencyConvert(totalAmount));
        $('#totalAmount').html(currencyConvert(totalUSDAmount)+" USD <br/>"+currencyConvert(totalGBPAmount)+" GBP <br/>"+currencyConvert(totalTZSAmount)+" TZS");

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(3).children().val() != '' && $(this).children().eq(3).children().val() != null && $(this).children().eq(3).children().val() != '.') {
                var discount = parseFloat($(this).children().eq(3).children().val());
                totalDiscount += discount;
            } 
        });
        globaltotaldiscount = totalDiscount;
        $('#totalDiscount').text(currencyConvert(totalDiscount));


        $('#feetypeList tr').each(function(index, value) {
            var currency = $(this).children().eq(2).children().eq(1).val();
            var amount = parseFloat($(this).children().eq(2).children().val());
            var discount = parseFloat($(this).children().eq(3).children().val());
            var subtotal = 0;
            var subtotalUSD = 0;
            var subtotalGBP = 0;
            var subtotalTZS = 0;

            if (currency == "USD") {
                if(amount > 0) {
                    if(discount > 0) {
                        if(discount == 100) {
                            subtotalUSD = 0;
                        } else {
                            subtotalUSD = (amount - ((amount/100) * discount));
                        }
                    } else {
                        subtotalUSD = amount;
                    }
                }
            }

            if (currency == "GBP") {
                if(amount > 0) {
                    if(discount > 0) {
                        if(discount == 100) {
                            subtotalGBP = 0;
                        } else {
                            subtotalGBP = (amount - ((amount/100) * discount));
                        }
                    } else {
                        subtotalGBP = amount;
                    }
                }
            }

            if (currency == "TZS") {
                if(amount > 0) {
                    if(discount > 0) {
                        if(discount == 100) {
                            subtotalTZS = 0;
                        } else {
                            subtotalTZS = (amount - ((amount/100) * discount));
                        }
                    } else {
                        subtotalTZS = amount;
                    }
                }
                
            }
            // if(amount > 0) {
            //     if(discount > 0) {
            //         if(discount == 100) {
            //             subtotal = 0;
            //         } else {
            //             subtotal = (amount - ((amount/100) * discount));
            //         }
            //     } else {
            //         subtotal = amount;
            //     }
            // }

            if (subtotalTZS > 0) {
                subtotal = subtotalTZS;
            }

            if (subtotalUSD > 0) {
                subtotal = subtotalUSD;
            }

            if (subtotalGBP > 0) {
                subtotal = subtotalGBP;
            }

            $(this).children().eq(4).html(subtotal);
            totalUSDSubtotal += subtotalUSD
            totalGBPSubtotal += subtotalGBP
            totalTZSSubtotal += subtotalTZS

            // $(this).children().eq(4).text(subtotal);
            // totalSubtotal += subtotal;
        });
        globaltotalsubtotal = totalSubtotal;
        globaltotalUSDsubtotal = totalUSDSubtotal;
        globaltotalTZSsubtotal = totalTZSSubtotal;
        $('#totalSubtotal').html(currencyConvert(totalUSDSubtotal)+"USD <br/>"+currencyConvert(totalGBPSubtotal)+"GBP <br/>"+currencyConvert(totalTZSSubtotal)+" TZS");
        // $('#totalSubtotal').text(currencyConvert(totalSubtotal));

        $('#feetypeList tr').each(function(index, value) {
            if($(this).children().eq(5).children().val() != '' && $(this).children().eq(5).children().val() != null && $(this).children().eq(5).children().val() != '.') {
                var paidamount = parseFloat($(this).children().eq(5).children().val());
                var currency = $(this).children().eq(2).children().eq(1).val();
                if (currency == "USD") {
                    totalPaidUSDAmount += paidamount;
                }
                if (currency == "GBP") {
                    totalPaidGBPAmount += paidamount;
                }
                if (currency == "TZS") {
                    totalPaidTZSAmount += paidamount;
                }
                // totalPaidAmount += paidamount;
            } 
        });
        globaltotalpaidamount = totalPaidAmount;
        totalPaidUSDAmount = totalPaidUSDAmount;
        totalPaidGBPAmount = totalPaidGBPAmount;
        totalPaidTZSAmount = totalPaidTZSAmount;
        $('#totalPaidAmount').html(currencyConvert(totalPaidUSDAmount)+"USD <br/>"+currencyConvert(totalPaidGBPAmount)+"GBP <br/>"+currencyConvert(totalPaidTZSAmount)+"TZS");
        // $('#totalPaidAmount').text(currencyConvert(totalPaidAmount));

    }

    $(document).on('keyup', '.change-amount', function() {
        var amount =  toFixedVal($(this).val());
        var amountID = $(this).attr('data-amount-id');

        if(dotAndNumber(amount)) {
            if(amount.length > 15) {
                amount = lenChecker(amount, 15);
                $(this).val(amount);
            }
            
            if(amount != '' && amount != null) {
                $(this).val(amount);
                totalInfo();
            } else {
                totalInfo();
            }
        } else {
            var amount = parseSentenceForNumber(toFixedVal($(this).val()));
            $(this).val(amount);
        }

        removePaidAmount(amountID);
    });

    $(document).on('change', '.change-currency', function() {
        var currency =  toFixedVal($(this).val());
        var amount =  toFixedVal($(this).siblings().val());
        var amountID = $(this).siblings().attr('data-amount-id');
        
        if(dotAndNumber(amount)) {
            if(amount.length > 15) {
                amount = lenChecker(amount, 15);
                $(this).siblings().val(amount);
            }
            
            if(amount != '' && amount != null) {
                $(this).siblings().val(amount);
                totalInfo();
            } else {
                totalInfo();
            }
        } else {
            var amount = parseSentenceForNumber(toFixedVal($(this).val()));
            $(this).siblings().val(amount);
        }

        removePaidAmount(amountID);
    });

    $(document).on('keyup', '.change-paidamount', function() {
        var trID = $(this).parent().parent().attr('id').replace('tr_','');
        var amount = $('#'+'td_amount_id_'+trID).val();
        var discount = $('#'+'td_discount_id_'+trID).val();

        if(discount != '' && discount != null) {
            amount = (amount - ((amount/100) * discount));
        }

        if(amount != '' && amount != null) {
            var paidamount =  toFixedVal($(this).val());
            var paidamountID = $(this).attr('data-paidamount-id'); 
            
            if(dotAndNumber(paidamount)) {
                if(paidamount.length > 15) {
                    paidamount = lenChecker(paidamount, 15);
                    if(parseFloat(paidamount) > parseFloat(amount)) {
                        $(this).val(amount);
                    } else {
                        $(this).val(paidamount);
                    }
                }
                
                if(paidamount != '' && paidamount != null) {
                    if(parseFloat(paidamount) > parseFloat(amount)) {
                        $(this).val(amount);
                    } else {
                        $(this).val(paidamount);
                    }
                    totalInfo();
                } else {
                    totalInfo();
                }
            } else {
                var paidamount = parseSentenceForNumber(toFixedVal($(this).val()));
                if(parseFloat(paidamount) > parseFloat(amount)) {
                    $(this).val(amount);
                } else {
                    $(this).val(paidamount);
                }
            }
        } else {
            $(this).val('');
        }
    });

    $(document).on('keyup', '.change-discount', function() {
        var trID = $(this).parent().parent().attr('id').replace('tr_','');
        var randID = $(this).attr('data-discount-id'); 
        var amount = $('#'+'td_amount_id_'+trID).val();

        if(amount != '' && amount != null) {
            var discount =  toFixedVal($(this).val());
            var discountID = $(this).attr('data-discount-id'); 
            
            if(dotAndNumber(discount)) {
                if(discount > 100) {
                    discount = 100;
                }
                $(this).val(discount);
                totalInfo();
            } else {
                var discount = parseSentenceForNumber(toFixedVal($(this).val()));
                $(this).val(discount);
            }
        } else {
            $(this).val('');
        }

        removePaidAmount(randID);
    });

    $(document).on('click', '.deleteBtn', function(er) {
        er.preventDefault();
        var feetypeID = $(this).attr('data-feetype-id');
        $('#tr_'+feetypeID).remove();
        
        var i = 1;
        $('#feetypeList tr').each(function(index, value) {
            $(this).children().eq(0).text(i);
            i++;
        });
        totalInfo();
    });

    function removePaidAmount(randID) {
        var ramount = $('#td_amount_id_'+randID).val();
        var rdiscount = $('#td_discount_id_'+randID).val();
        var rpaidamount = ($('#td_paidamount_id_'+randID).val());
        
        if(ramount == '' && ramount == null) {
            ramount = 0;
        }

        if(rdiscount == '' && rdiscount == null) {
            rdiscount = 0;
        }

        if(rpaidamount != '' && rpaidamount != null) {
            ramount = parseFloat((ramount - (ramount/100) * rdiscount)); 
            rpaidamount = parseFloat(rpaidamount);  
            if(rpaidamount > ramount) {
                $('#td_paidamount_id_'+randID).val('');
            }
        }
    }

</script>

<script type="text/javascript">
    $('#statusID').change(function() {
        if(($(this).val() != 0) && ($(this).val() != 5)) {
            $('.paymentmethodDiv').removeClass('hide');

            $('#feetypeList tr').each(function(index, value) {
                $(this).children().eq(5).children().removeAttr('readonly');
            });
        } else {
            $('.paymentmethodDiv').addClass('hide');

            $('#feetypeList tr').each(function(index, value) {
                $(this).children().eq(5).children().attr('readonly', 'readonly');
            });
        }
    });

    $(document).on('click', '#addInvoiceButton', function() {
        console.log('submited');
        var error=0;;
        var field = {
            'classesID'           : $('#classesID').val(),
            'sectionsID'           : $('#sectionsID').val(),
            'studentID'           : $('#studentID').val(), 
            'date'                : $('#date').val(),
            'statusID'            : $('#statusID').val(), 
            'paymentmethodID'     : $('#paymentmethodID').val(), 
        };
        
        if(field['classesID'] === '0') {
            $('.classesDiv').addClass('has-error');
            error++;
        } else {
            $('.classesDiv').removeClass('has-error');
        }
        
        if(field['sectionsID'] === '0') {
            $('.sectionsDiv').addClass('has-error');
            error++;
        } else {
            $('.sectionsDiv').removeClass('has-error');
        }

        if(field['date'] === '') {
            $('.dateDiv').addClass('has-error');
            error++;
        } else {
            $('.dateDiv').removeClass('has-error');
        }

        if(field['statusID'] === '5') {
            $('.statusDiv').addClass('has-error');
            error++;
        } else {
            $('.statusDiv').removeClass('has-error');
        }

        if(field['statusID'] != 0 && field['statusID'] != 5) {
            if(field['paymentmethodID'] === '0') {
                $('.paymentmethodDiv').addClass('has-error');
                error++;
            } else {
                $('.paymentmethodDiv').removeClass('has-error');
            }
        }

        var totalsubtotal = 0;
        var totalpaidamount = 0;
        var feetypeitems = $('tr[id^=tr_]').map(function(){
            if($(this).children().eq(4).text() != '' && $(this).children().eq(4).text() != null) {
                totalsubtotal += parseFloat($(this).children().eq(4).text());
            }

            if($(this).children().eq(5).children().val() != '' && $(this).children().eq(5).children().val() != null) {
                totalpaidamount += parseFloat($(this).children().eq(5).children().val());
            }

            return { feetypeID : $(this).attr('invoicefeetypeid'), amount: $(this).children().eq(2).children().val(), currency: $(this).children().eq(2).children().eq(1).val(), discount : $(this).children().eq(3).children().val(), subtotal: $(this).children().eq(4).text() , paidamount: $(this).children().eq(5).children().val() , duedate: $(this).children().eq(6).children().eq(0).children().val() };
        }).get();

        if (typeof feetypeitems == 'undefined' || feetypeitems.length <= 0) {
            error++;
            toastr["error"]('The fee type item is required.')
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

        feetypeitems = JSON.stringify(feetypeitems);
        
        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#invoiceDataForm')[0]);
            formData.append("feetypeitems", feetypeitems);
            formData.append("totalsubtotal", totalsubtotal);
            formData.append("totalpaidamount", totalpaidamount);
            formData.append("editID", 0);
            
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
            url: "<?=base_url('invoice/saveinvoice')?>",
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
            window.location = "<?=base_url("invoice/index")?>";
        } else {
            $('#addInvoiceButton').removeAttr('disabled');
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

