<div class="row">
    <div class="col-sm-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-productsale"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data" id="productPurchaseDataForm"> 
                    <div class="productsalecustomertypeDiv form-group <?=form_error('productsalecustomertypeID') ? 'has-error' : '' ?>" >
                        <label for="productsalecustomertypeID">
                            <?=$this->lang->line("productsale_role")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $usertypeArray = array(0 => $this->lang->line("productsale_select_role"));
                            if(count($usertypes)) {
                                foreach ($usertypes as $usertype) {
                                    $usertypeArray[$usertype->usertypeID] = $usertype->usertype;
                                }
                            }
                            echo form_dropdown("productsalecustomertypeID", $usertypeArray, set_value("productsalecustomertypeID", $productsale->productsalecustomertypeID), "id='productsalecustomertypeID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productsalecustomertypeID'); ?>
                        </span>
                    </div> 


                    <div class="productsaleclassesDiv form-group <?=($classesID > 0) ? '' : 'hide'?>  <?=form_error('productsaleclassesID') ? 'has-error' : '' ?>" >
                        <label for="productsaleclassesID">
                            <?=$this->lang->line("productsale_classes")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $classesArray = array(0 => $this->lang->line("productsale_select_classes"));
                            if(count($classes)) {
                                foreach ($classes as $classa) {
                                    $classesArray[$classa->classesID] = $classa->classes;
                                }
                            }
                            echo form_dropdown("productsaleclassesID", $classesArray, set_value("productsaleclassesID", $classesID), "id='productsaleclassesID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productsaleclassesID'); ?>
                        </span>
                    </div>  

                    <div class="productsalecustomerDiv form-group <?=form_error('productsalecustomerID') ? 'has-error' : '' ?>" >
                        <label for="productsalecustomerID">
                            <?=$this->lang->line("productsale_user")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            echo form_dropdown("productsalecustomerID", $productsalecustomers, set_value("productsalecustomerID", $productsale->productsalecustomerID), "id='productsalecustomerID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productsalecustomerID'); ?>
                        </span>
                    </div>

                    <div class="productsalereferencenoDiv form-group <?=form_error('productsalereferenceno') ? 'has-error' : '' ?>" >
                        <label for="productsalereferenceno">
                            <?=$this->lang->line("productsale_referenceno")?>
                        </label>
                            <input type="text" class="form-control" id="productsalereferenceno" name="productsalereferenceno" value="<?=set_value('productsalereferenceno', $productsale->productsalereferenceno)?>" >
                        <span class="text-red">
                            <?php echo form_error('productsalereferenceno'); ?>
                        </span>
                    </div>


                    <div class="productsaledateDiv form-group <?=form_error('productsaledate') ? 'has-error' : '' ?>" >
                        <label for="productsaledate">
                            <?=$this->lang->line("productsale_date")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="productsaledate" name="productsaledate" value="<?=set_value('productsaledate', date('d-m-Y', strtotime($productsale->productsaledate)))?>" >
                        <span class="text-red">
                            <?php echo form_error('productsaledate'); ?>
                        </span>
                    </div>  

                    <div class="productsalepaymentstatusDiv form-group <?=form_error('productsalepaymentstatusID') ? 'has-error' : '' ?>" >
                        <label for="productsalepaymentstatusID">
                            <?=$this->lang->line("productsale_payment_status")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $productwarehouseArray = array(
                                0 => $this->lang->line("productsale_select_payment_status"),
                                1 => $this->lang->line("productsale_due"),
                            );

                            if($siteinfos->school_year == $this->session->userdata('defaultschoolyearID')) {
                                $productwarehouseArray[2] = $this->lang->line("productsale_partial");
                                $productwarehouseArray[3] = $this->lang->line("productsale_paid");
                            }

                            echo form_dropdown("productsalepaymentstatusID", $productwarehouseArray, set_value("productsalepaymentstatusID", $productsale->productsalestatus), "id='productsalepaymentstatusID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productsalepaymentstatusID'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsalefile') ? 'has-error' : '' ?>" >
                        <label for="productsalefile">
                            <?=$this->lang->line("productsale_file")?>
                        </label>
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
                                    <input type="file" name="productsalefile"/>
                                </div>
                            </span>
                        </div>
                        <span class="text-red">
                            <?php echo form_error('productsale_file'); ?>
                        </span>
                    </div>

                    <div class="productsaledescriptionDiv form-group <?=form_error('productsaledescription') ? 'has-error' : '' ?>" >
                        <label for="productsaledescription">
                            <?=$this->lang->line("productsale_description")?>
                        </label>
                        <textarea class="form-control" style="resize:none;" id="productsaledescription" name="productsaledescription"><?=set_value('productsaledescription', $productsale->productsaledescription)?></textarea>
                        <span class="text-red">
                            <?php echo form_error('productsaledescription'); ?>
                        </span>
                    </div>

                    <input id="updatePurchaseButton" type="button" class="btn btn-success" value="<?=$this->lang->line("update_productsale")?>" >
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-productpurchaseitem"></i> <?=$this->lang->line('productsale_saleitem')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("productsale/index")?>"><?=$this->lang->line('menu_productsale')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_productsale')?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="" role="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('productcategoryID') ? 'has-error' : '' ?>" >
                                <label for="productcategoryID" class="control-label">
                                    <?=$this->lang->line("productsale_category")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productcategoryArray = array(0 => $this->lang->line("productsale_select_category"));
                                    if(count($productcategorys)) {
                                        foreach ($productcategorys as $productcategory) {
                                            $productcategoryArray[$productcategory->productcategoryID] = $productcategory->productcategoryname;
                                        }
                                    }
                                    echo form_dropdown("productcategoryID", $productcategoryArray, set_value("productcategoryID"), "id='productcategoryID' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('productcategoryID'); ?>
                                </span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('productID') ? 'has-error' : '' ?>" >
                                <label for="productID" class="control-label">
                                    <?=$this->lang->line("productsale_product")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productArray = array(0 => $this->lang->line("productsale_select_product"));
                                    echo form_dropdown("productID", $productArray, set_value("productID"), "id='productID' class='form-control select2'");
                                ?>
                                <span class="control-label">
                                    <?php echo form_error('productID'); ?>
                                </span>
                            </div>  
                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table table-bordered product-style" style="font-size: 16px;">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-4"><?=$this->lang->line('productsale_product')?></th>
                                <th class="col-sm-2" ><?=$this->lang->line('productsale_unit_price')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('productsale_quantity')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('productsale_subtotal')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                            <?php 
                                $productpurchasequintityobj =  json_decode($productpurchasequintity, TRUE);
                                $productsalequintityobj = json_decode($productsalequintity, TRUE);

                                $totalQuantity = 0;
                                $totalSubtotal = 0;
                                $totalProductStockQuantity = [];
                                $totalEditQuantity = [];
                                if(count($products)) {
                                    foreach ($products as $productID => $product) { 
                                        if(isset($productpurchasequintityobj[$productID])) {
                                            $totalProductQuantity = $productpurchasequintityobj[$productID]['quantity'];
                                        } else {
                                            $totalProductQuantity = 0;
                                        }

                                        if(isset($productsalequintityobj[$productID])) {
                                            $totalSaleQuantity = $productsalequintityobj[$productID]['quantity'];
                                        } else {
                                            $totalSaleQuantity = 0;
                                        }

                                        $totalProductStockQuantity[$productID] = ($totalProductQuantity - $totalSaleQuantity);
                                    }
                                }

                                if(count($productsaleitems)) { 
                                    foreach ($productsaleitems as $productsaleitem) {
                                        if(isset($totalEditQuantity[$productsaleitem->productID])) {
                                            $totalEditQuantity[$productsaleitem->productID] += $productsaleitem->productsalequantity;
                                        } else {
                                            $totalEditQuantity[$productsaleitem->productID] = $productsaleitem->productsalequantity;
                                        }
                                    }
                                }

                                if(count($productsaleitems)) {
                                    $i=1; 
                                    foreach ($productsaleitems as $productsaleitem) {
                                        $randID = rand(1, 9999999999);
                                        $totalQuantity += $productsaleitem->productsalequantity;
                                        $subtotal = ($productsaleitem->productsaleunitprice * $productsaleitem->productsalequantity);
                                        $totalSubtotal += $subtotal; 

                                        echo '<tr id="tr_'.$randID.'" saleproductid="'.$productsaleitem->productID.'">';
                                            echo '<td>';
                                                echo $i;
                                            echo '</td>';

                                            echo '<td>';
                                                echo isset($products[$productsaleitem->productID]) ? $products[$productsaleitem->productID] : '';
                                            echo '</td>';

                                            echo '<td>';
                                                echo '<input type="text" class="form-control change-productprice" id="productunitprice_'.$randID.'" data-productprice-id="'.$randID.'" value="'.$productsaleitem->productsaleunitprice.'">';
                                            echo '</td>';

                                            echo '<td>';
                                                echo '<input type="text" class="form-control change-productquantity" id="productquantity_'.$randID.'" data-productquantity-id="'.$randID.'" value="'.$productsaleitem->productsalequantity.'" max="'.($totalProductStockQuantity[$productsaleitem->productID] + $totalEditQuantity[$productsaleitem->productID]).'">';
                                            echo '</td>';

                                            echo '<td id="producttotal_'.$randID.'">';
                                                echo number_format($subtotal, 2);
                                            echo '</td>';

                                            echo '<td>';
                                                echo '<a style="margin-top:3px" href="#" class="btn btn-danger btn-sm deleteBtn" id="productaction_'.$randID.'" data-productaction-id="'.$randID.'"><i class="fa fa-trash-o"></i></a>';
                                            echo '</td>';
                                        echo '</tr>';
                                        $i++; 
                                    }
                                } 
                            ?>
                        </tbody>

                        <tfoot id="productListFooter">
                            <tr>
                                <td colspan="3" style="font-weight: bold"><?=$this->lang->line('productsale_total')?></td>
                                <td id="totalQuantity" style="font-weight: bold"><?=number_format($totalQuantity, 2)?></td>
                                <td id="totalSubtotal" style="font-weight: bold"><?=number_format($totalSubtotal, 2)?></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="box hide" id="payment-box" >
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group productsalepaidreferencenoDiv <?=form_error('productsalepaidreferenceno') ? 'has-error' : '' ?>" >
                            <label for="productsalepaidreferenceno" class="control-label">
                                <?=$this->lang->line("productsale_referenceno")?> 
                            </label>
                            <input type="text" class="form-control" id="productsalepaidreferenceno" name="productsalepaidreferenceno" value="<?=set_value('productsalepaidreferenceno')?>" >
                            <span class="control-label">
                                <?php echo form_error('productsalepaidreferenceno'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group productsalepaidamountDiv <?=form_error('productsalepaidamount') ? 'has-error' : '' ?>" >
                            <label for="productsalepaidamount" class="control-label">
                                <?=$this->lang->line("productsale_amount")?> <span class="text-red">*</span>
                            </label>
                            <input type="text" class="form-control" id="productsalepaidamount" name="productsalepaidamount" value="<?=set_value('productsalepaidamount')?>" >
                            <span class="control-label">
                                <?php echo form_error('productsalepaidamount'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group productsalepaidpaymentmethodDiv <?=form_error('productsalepaidpaymentmethod') ? 'has-error' : '' ?>" >
                            <label for="productsalepaidpaymentmethod" class="control-label">
                                <?=$this->lang->line("productsale_paymentmethod")?> <span class="text-red">*</span>
                            </label>
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
                            <span class="control-label">
                                <?php echo form_error('productsalepaidpaymentmethod'); ?>
                            </span>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('#productsaledate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $(function() {
        // Create the close button
        var closebtn = $('<button/>', {
            type:"button",
            text: 'x',
            id: 'close-preview',
            style: 'font-size: initial;',
        });
        closebtn.attr("class","close pull-right");
        // Set the popover default content
        $('.image-preview').popover({
            trigger:'manual',
            html:true,
            title: "<strong>Preview</strong>"+$(closebtn)[0].outerHTML,
            content: "There's no image",
            placement:'bottom'
        });
        // Clear event
        $('.image-preview-clear').click(function(){
            $('.image-preview').attr("data-content","").popover('hide');
            $('.image-preview-filename').val("");
            $('.image-preview-clear').hide();
            $('.image-preview-input input:file').val("");
            $(".image-preview-input-title").text("<?=$this->lang->line('productsale_browse')?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('productsale_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
    });

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }

    function productItemDesign(productID, productText) {
        var productpurchasequintity = <?=$productpurchasequintity?>;
        var productsalequintity = <?=$productsalequintity?>;
        var productsalequintityforedit = <?=$productsalequintityforedit?>;
        var productobj = <?=$productobj?>;
        var randID = getRandomInt();
        if($('#productList tr:last').text() == '') {
            var lastTdNumber = 0;
        } else {
            var lastTdNumber = $("#productList tr:last td:eq(0)").text();
        }

        if(typeof(productpurchasequintity) == 'object') {
            if(typeof(productpurchasequintity[productID]) == 'object') {
                var productpurchasequintityinfo = productpurchasequintity[productID];
            } else {
                productpurchasequintityinfo = {'quantity' : '0', 'productID' : productID};            
            }
        }

        if(typeof(productsalequintity) == 'object') {
            if(typeof(productsalequintity[productID]) == 'object') {
                var productsalequintityinfo = productsalequintity[productID];
            } else {
                productsalequintityinfo = {'quantity' : '0', 'productID' : productID};            
            }
        }

        if(typeof(productsalequintityforedit) == 'object') {
            if(typeof(productsalequintityforedit[productID]) == 'object') {
                var productsalequintityforeditinfo = productsalequintityforedit[productID];
            } else {
                productsalequintityforeditinfo = {'quantity' : '0', 'productID' : productID};            
            }
        }

        if(typeof(productobj) == 'object') {
            if(typeof(productobj[productID]) == 'object') {
                var productobjinfo = productobj[productID];
            } else {
                productobjinfo = {'productID' : productID, 'productbuyingprice' : '0', 'productsellingprice' : '0'};            
            }
        }

        lastTdNumber = parseInt(lastTdNumber);
        lastTdNumber++;

        var text = '<tr id="tr_'+randID+'" saleproductid="'+productID+'">';
            text += '<td>';
                text += lastTdNumber;
            text += '</td>';

            text += '<td>';
                text += productText;
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-productprice" id="productunitprice_'+randID+'" value="'+productobjinfo.productsellingprice+'" data-productprice-id="'+randID+'">');
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-productquantity" id="productquantity_'+randID+'" max="'+ (parseFloat(productpurchasequintityinfo.quantity) - parseFloat(productsalequintityinfo.quantity) + parseFloat(productsalequintityforeditinfo.quantity)) +'"  data-productquantity-id="'+randID+'">' );
            text += '</td>';

            text += '<td id="producttotal_'+randID+'">';
                text += '0.00';
            text += '</td>';

            text += '<td>';
                text += ('<a style="margin-top:3px" href="#" class="btn btn-danger btn-sm deleteBtn" id="productaction_'+randID+'" data-productaction-id="'+randID+'"><i class="fa fa-trash-o"></i></a>');
            text += '</td>';
        text += '</tr>';

        return text; 
    }

    $('#productsalecustomertypeID, #productsaleclassesID').change(function(event) {
        var productsalecustomertypeID = $('#productsalecustomertypeID').val();
        var productsaleclassesID = $('#productsaleclassesID').val();

        if(productsalecustomertypeID === '3') {
            $('.productsaleclassesDiv').removeClass('hide');
        } else {
            $('.productsaleclassesDiv').addClass('hide');
        }

        if(productsalecustomertypeID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('productsale_select_user')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productsale/getuser')?>",
                data: {'productsalecustomertypeID' : productsalecustomertypeID, 'productsaleclassesID' : productsaleclassesID, 'productsaleusercalltype' : 'edit'},
                dataType: "html",
                success: function(data) {
                    $('#productsalecustomerID').html(data);
                }
            });
        }
    });

    $('#productcategoryID').change(function(event) {
        var productcategoryID = $(this).val();
        if(productcategoryID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('productsale_select_product')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productsale/getproductsale')?>",
                data: "productcategoryID=" + productcategoryID,
                dataType: "html",
                success: function(data) {
                    $('#productID').html(data);
                }
            });
        }
    });

    $('#productID').change(function(e) {
        var productID   = $(this).val();
        if(productID != 0) {
            var productText = $(this).find(":selected").text();
            var appendData  = productItemDesign(productID, productText);
            $('#productList').append(appendData);
        }
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

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }


    function currencyConvert(data) {
        return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
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

    function sentanceLengthRemove(sentence) {
        sentence = sentence.toString();
        sentence = sentence.slice(0, -1);
        sentence = parseFloat(sentence);
        return sentence;
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

    $(document).on('keyup', '#productsalereferenceno', function() {
        var productsalereferenceno =  $(this).val();
        if(productsalereferenceno.length > 99) {
            productsalereferenceno = lenCheckerWithoutParseFloat(productsalereferenceno, 99);
            $(this).val(productsalereferenceno);                    
        }
    });

    $(document).on('keyup', '#productsalepaidreferenceno', function() {
        var productsalepaidreferenceno =  $(this).val();
        if(productsalepaidreferenceno.length > 99) {
            productsalepaidreferenceno = lenCheckerWithoutParseFloat(productsalepaidreferenceno, 99);
            $(this).val(productsalepaidreferenceno);                    
        }
    });

    var globalsubtotal = 0;
    function totalInfo() {
        var i = 1;
        var totalQuantity = 0;
        var totalSubtotal = 0;
        $('#productList tr').each(function(index, value) {
            if($(this).children().eq(3).children().val() != '' && $(this).children().eq(3).children().val() != null) {
                var quantity = parseFloat($(this).children().eq(3).children().val());
                var subtotal = parseFloat($(this).children().eq(4).text().replace(/,/gi, ''));
                totalQuantity += quantity;
                totalSubtotal += subtotal;
            } 
        });
        globalsubtotal = totalSubtotal;
        $('#totalQuantity').text(currencyConvert(totalQuantity));
        $('#totalSubtotal').text(currencyConvert(totalSubtotal));
    }

    function totalUnitQuantity(gettrID, getproductID, getamount) {
        var totalQuantity = 0;
        var maxValue = 0;
        var quantity = 0;
        $('#productList tr').each(function(index, value) {
            var trID = $(value).attr('id');
            var productID = $(value).attr('saleproductid');
            if($(this).children().eq(3).children().val() != '' && $(this).children().eq(3).children().val() != null) {
                if((trID != gettrID) && (parseInt(productID) == parseInt(getproductID))) {
                    totalQuantity += parseFloat($(value).children().eq(3).children().val());
                }
            }
        });

        maxValue = parseFloat($('#'+gettrID).children().eq(3).children().attr('max'));
        
        quantity = (maxValue - totalQuantity);
        if(getamount > quantity) {
            // $('#'+gettrID).css('background-color', '#f5e1e1');
            $('#'+gettrID).children().eq(3).children().val(quantity);
        } else {
            // $('#'+gettrID).css('background-color', '#ffffff');
        }
    }

    function totalUnitQuantityAmount(gettrID, getproductID, getamount) {
        var totalQuantity = 0;
        var maxValue = 0;
        var quantity = 0;
        $('#productList tr').each(function(index, value) {
            var trID = $(value).attr('id');
            var productID = $(value).attr('saleproductid');
            if($(this).children().eq(3).children().val() != '' && $(this).children().eq(3).children().val() != null) {
                if((trID != gettrID) && (parseInt(productID) == parseInt(getproductID))) {
                    totalQuantity += parseFloat($(value).children().eq(3).children().val());
                }
            }
        });

        maxValue = parseFloat($('#'+gettrID).children().eq(3).children().attr('max'));
        quantity = (maxValue - totalQuantity);
        if(getamount > quantity) {
            return quantity;
        } else {
            return getamount;
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

    $(document).on('keyup', '.change-productprice', function() {
        var productPrice =  toFixedVal($(this).val());
        var productPriceID = $(this).attr('data-productprice-id'); 
        var productQuantity = toFixedVal($('#productquantity_'+productPriceID).val());

        if(dotAndNumber(productPrice)) {
            if(productPrice.length > 15) {
                productPrice = lenChecker(productPrice, 15);
                $(this).val(productPrice);
            }
            
            if((productPrice != '' && productPrice != null) && (productQuantity != '' && productQuantity != null)) {
                if(floatChecker(productPrice)) {
                    if(productPrice.length > 15) {
                        productPrice = lenChecker(productPrice, 15);
                        $(this).val(productPrice);
                        $('#producttotal_'+productPriceID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    } else {
                        $('#producttotal_'+productPriceID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    }
                }
            } else {
                $('#producttotal_'+productPriceID).text('0.00');
                totalInfo();
            }
        } else {
            var productPrice = parseSentenceForNumber(toFixedVal($(this).val()));
            $(this).val(productPrice);
        }
    });

    $(document).on('keyup', '.change-productquantity', function() {
        var gettrID = $(this).parent().parent().attr('id');
        var getproductID = $(this).parent().parent().attr('saleproductid');
        var productQuantity =  toFixedVal($(this).val());
        var productQuantityID = $(this).attr('data-productquantity-id'); 
        var productPrice = toFixedVal($('#productunitprice_'+productQuantityID).val());

        if(dotAndNumber(productQuantity)) {
            if((productQuantity != '' && productQuantity != null) && (productPrice != '' && productPrice != null)) {
                if(floatChecker(productQuantity)) {
                    totalUnitQuantity(gettrID, getproductID, productQuantity);
                    productQuantity = totalUnitQuantityAmount(gettrID, getproductID, productQuantity);
                    $('#producttotal_'+productQuantityID).text(currencyConvert(productQuantity*productPrice));
                    totalInfo();
                }
            } else {
                $('#producttotal_'+productQuantityID).text('0.00');
                totalUnitQuantity(gettrID, getproductID, productQuantity);
                totalInfo();
            }
        } else {
            var productQuantity = parseSentenceForNumber(toFixedVal($(this).val()));
            $(this).val(productQuantity);
            totalUnitQuantity(gettrID, getproductID, productQuantity);
        }
    });

    $(document).on('click', '.deleteBtn', function(e) {
        e.preventDefault();
        var productItemID = $(this).attr('data-productaction-id');
        $('#tr_'+productItemID).remove();
        
        var i = 1;
        $('#productList tr').each(function(index, value) {
            $(this).children().eq(0).text(i);
            i++;
        });
        totalInfo();
        $('#productsalepaidamount').val('');
    });

    $('#productsalepaymentstatusID').change(function() {
        var productsalepaymentstatusID = $(this).val();
        if(productsalepaymentstatusID == 0 || productsalepaymentstatusID == 1) {
            $('#payment-box').addClass('hide');
        } else {
            $('#payment-box').removeClass('hide');
        }
    });

    $(document).on('keyup', '#productsalepaidamount', function() {
        var productsalepaidamount =  toFixedVal($(this).val());
        if(dotAndNumber(productsalepaidamount)) {
            if(productsalepaidamount != '' && productsalepaidamount != null) {
                if(floatChecker(productsalepaidamount)) {
                    if(productsalepaidamount.length > 15) {
                        $(this).val(globalsubtotal);                    
                    } else {
                        if(productsalepaidamount > globalsubtotal) {
                            $(this).val(globalsubtotal);
                        }
                    }
                }
            }
        } else {
            var productsalepaidamount = parseSentenceForNumber($(this).val());
            $(this).val(productsalepaidamount);
        }
    });

    $(document).on('click', '#updatePurchaseButton', function() {
        var error=0;;
        var field = {
            'productsalecustomertypeID'             : $('#productsalecustomertypeID').val(), 
            'productsalecustomerID'                 : $('#productsalecustomerID').val(), 
            'productsalereferenceno'                : $('#productsalereferenceno').val(), 
            'productsaledate'                       : $('#productsaledate').val(), 
            'productpurchasedescription'            : $('#productpurchasedescription').val(), 
            'productsalepaymentstatusID'            : $('#productsalepaymentstatusID').val(), 
        };

        if (field['productsalecustomertypeID'] === '0') {
            $('.productsalecustomertypeDiv').addClass('has-error');
            error++;
        } else {
            $('.productsalecustomertypeDiv').removeClass('has-error');
        }

        if (field['productsalecustomerID'] === '0') {
            $('.productsalecustomerDiv').addClass('has-error');
            error++;
        } else {
            $('.productsalecustomerDiv').removeClass('has-error');
        }

        // if (field['productsalereferenceno'] == '') {
        //     $('.productsalereferencenoDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('.productsalereferencenoDiv').removeClass('has-error');
        // }

        if (field['productsaledate'] == '') {
            $('.productsaledateDiv').addClass('has-error');
            error++;
        } else {
            $('.productsaledateDiv').removeClass('has-error');
        }

        if (field['productsalepaymentstatusID'] === '0') {
            $('.productsalepaymentstatusDiv').addClass('has-error');
            error++;
        } else {
            $('.productsalepaymentstatusDiv').removeClass('has-error');
        }

        if(parseInt($('#productsalepaymentstatusID').val()) > 1) {
            // if($('#productsalepaidreferenceno').val() == '') {
            //     $('.productsalepaidreferencenoDiv').addClass('has-error');
            //     error++;
            // } else {
            //     $('.productsalepaidreferencenoDiv').removeClass('has-error');
            // }

            if($('#productsalepaidamount').val() == '') {
                $('.productsalepaidamountDiv').addClass('has-error');
                error++;
            } else {
                $('.productsalepaidamountDiv').removeClass('has-error');
            }

            if($('#productsalepaidpaymentmethod').val() === '0') {
                $('.productsalepaidpaymentmethodDiv').addClass('has-error');
                error++;
            } else {
                $('.productsalepaidpaymentmethodDiv').removeClass('has-error');
            }
        }

        var productitem = $('tr[id^=tr_]').map(function(){
            return { productID : $(this).attr('saleproductid'), unitprice: $(this).children().eq(2).children().val(), quantity : $(this).children().eq(3).children().val() };
        }).get();

        if (typeof productitem == 'undefined' || productitem.length <= 0) {
            error++;
            toastr["error"]('The product item is required.')
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

        productitem = JSON.stringify(productitem);

        if(error === 0) {
            $(this).attr('disabled', 'disabled');
            var formData = new FormData($('#productPurchaseDataForm')[0]);
            formData.append("productitem", productitem);
            formData.append("productsalepaidreferenceno", $('#productsalepaidreferenceno').val());
            formData.append("productsalepaidamount", $('#productsalepaidamount').val());
            formData.append("productsalepaidpaymentmethod", $('#productsalepaidpaymentmethod').val());
            formData.append("editID", <?=$productsaleID?>);
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
            url: "<?=base_url('productsale/saveproductsale')?>",
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
            $('#addPurchaseButton').removeAttr('disabled');
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

    $(document).ready(function() {
        totalInfo();
    });
</script>

