<div class="row">
    <div class="col-sm-3">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-productpurchase"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form role="form" method="post" enctype="multipart/form-data" id="productPurchaseDataForm"> 
                    <div class="productsupplierDiv form-group <?=form_error('productsupplierID') ? 'has-error' : '' ?>" >
                        <label for="productsupplierID">
                            <?=$this->lang->line("productpurchase_supplier")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $productsupplierArray = array(0 => $this->lang->line("productpurchase_select_supplier"));
                            if(count($productsuppliers)) {
                                foreach ($productsuppliers as $productsupplier) {
                                    $productsupplierArray[$productsupplier->productsupplierID] = $productsupplier->productsuppliercompanyname;
                                }
                            }
                            echo form_dropdown("productsupplierID", $productsupplierArray, set_value("productsupplierID", $productpurchase->productsupplierID), "id='productsupplierID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productsupplierID');?>
                        </span>
                    </div>  

                    <div class="productwarehouseDiv form-group <?=form_error('productwarehouseID') ? 'has-error' : '' ?>" >
                        <label for="productwarehouseID">
                            <?=$this->lang->line("productpurchase_warehouse")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $productwarehouseArray = array(0 => $this->lang->line("productpurchase_select_warehouse"));
                            if(count($productwarehouses)) {
                                foreach ($productwarehouses as $productwarehouse) {
                                    $productwarehouseArray[$productwarehouse->productwarehouseID] = $productwarehouse->productwarehousename;
                                }
                            }
                            echo form_dropdown("productwarehouseID", $productwarehouseArray, set_value("productwarehouseID", $productpurchase->productwarehouseID), "id='productwarehouseID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productwarehouseID'); ?>
                        </span>
                    </div>

                    <div class="productpurchasereferencenoDiv form-group <?=form_error('productpurchasereferenceno') ? 'has-error' : '' ?>" >
                        <label for="productpurchasereferenceno">
                            <?=$this->lang->line("productpurchase_referenceno")?>
                        </label>
                            <input type="text" class="form-control" id="productpurchasereferenceno" name="productpurchasereferenceno" value="<?=set_value('productpurchasereferenceno', $productpurchase->productpurchasereferenceno)?>" >
                        <span class="text-red">
                            <?php echo form_error('productpurchasereferenceno'); ?>
                        </span>
                    </div>


                    <div class="productpurchasedateDiv form-group <?=form_error('productpurchasedate') ? 'has-error' : '' ?>" >
                        <label for="productpurchasedate">
                            <?=$this->lang->line("productpurchase_date")?> <span class="text-red">*</span>
                        </label>
                            <input type="text" class="form-control" id="productpurchasedate" name="productpurchasedate" value="<?=set_value('productpurchasedate', date('d-m-Y', strtotime($productpurchase->productpurchasedate)))?>" >
                        <span class="text-red">
                            <?php echo form_error('productpurchasedate'); ?>
                        </span>
                    </div>  

                    <div class="productvatselectDiv form-group <?=form_error('productvatselectID') ? 'has-error' : '' ?>" >
                        <label for="productvatselectID">
                            <?=$this->lang->line("productpurchase_vat_select")?> <span class="text-red">*</span>
                        </label>
                        <?php
                            $productvatChoicesArray = array(-1 => $this->lang->line("productpurchase_vat_select"));
                            $productvatChoicesArray[0] = '0% VAT';
                            $productvatChoicesArray[18] = '18% VAT';
                            
                            echo form_dropdown("productvatselectID", $productvatChoicesArray, set_value("productvatselectID", $productpurchase->purchase_product_vat_percentage), "id='productvatselectID' class='form-control select2'");
                        ?>
                        <span class="text-red">
                            <?php echo form_error('productvatselectID'); ?>
                        </span>
                    </div>

                    <div class="productpurchasevatDiv form-group <?=form_error('productpurchasevat') ? 'has-error' : '' ?>" >
                        <label for="productpurchasevat">
                            <?=$this->lang->line("productpurchase_vat")?>
                        </label>
                            <input type="text" class="form-control" id="productpurchasevat" name="productpurchasevat" value="<?=set_value('productpurchasevat', $productpurchase->purchase_product_vat_amount)?>" readonly>
                        <span class="text-red">
                            <?php echo form_error('productpurchasevat'); ?>
                        </span>
                    </div>

                    <div class="productpurchasediscountDiv form-group <?=form_error('productpurchasediscount') ? 'has-error' : '' ?>" >
                        <label for="productpurchasediscount">
                            <?=$this->lang->line("productpurchase_discount")?>
                        </label>
                            <input type="number" min="0" class="form-control" id="productpurchasediscount" name="productpurchasediscount" value="<?=set_value('productpurchasediscount', $productpurchase->purchase_product_discount)?>" placeholder="0" >
                        <span class="text-red">
                            <?php echo form_error('productpurchasediscount'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productpurchasefile') ? 'has-error' : '' ?>" >
                        <label for="productpurchasefile">
                            <?=$this->lang->line("productpurchase_file")?>
                        </label>
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
                                    <input type="file" name="productpurchasefile"/>
                                </div>
                            </span>
                        </div>
                        <span class="text-red">
                            <?php echo form_error('productpurchase_file'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productpurchasedescription') ? 'has-error' : '' ?>" >
                        <label for="productpurchasedescription">
                            <?=$this->lang->line("productpurchase_description")?>
                        </label>
                        <textarea class="form-control" style="resize:none;" id="productpurchasedescription" name="productpurchasedescription"><?=set_value('productpurchasedescription', $productpurchase->productpurchasedescription)?></textarea>
                        <span class="text-red">
                            <?php echo form_error('productpurchasedescription'); ?>
                        </span>
                    </div>

                    <input id="addPurchaseButton" type="button" class="btn btn-success" value="<?=$this->lang->line("update_productpurchase")?>" >
                </form>
            </div>
        </div>
    </div>

    <div class="col-sm-9">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa iniicon-productpurchaseitem"></i> <?=$this->lang->line('productpurchase_purchaseitem')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("productpurchase/index")?>"><?=$this->lang->line('menu_productpurchase')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_productpurchase')?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form class="" role="form" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group <?=form_error('productcategoryID') ? 'has-error' : '' ?>" >
                                <label for="productcategoryID" class="control-label">
                                    <?=$this->lang->line("productpurchase_category")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productcategoryArray = array(0 => $this->lang->line("productpurchase_select_category"));
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
                                    <?=$this->lang->line("productpurchase_product")?> <span class="text-red">*</span>
                                </label>
                                <?php
                                    $productArray = array(0 => $this->lang->line("productpurchase_select_product"));
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
                                <th class="col-sm-4"><?=$this->lang->line('productpurchase_product')?></th>
                                <th class="col-sm-2" ><?=$this->lang->line('productpurchase_unit_price')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('productpurchase_quantity')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('productpurchase_subtotal')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="productList">
                            <?php 
                                $totalQuantity = 0;
                                $totalSubtotal = 0;
                                if(count($productpurchaseitems)) {
                                    $i=1; 
                                    foreach ($productpurchaseitems as $productpurchaseitem) {
                                        $randID = rand(1, 9999999999);
                                        $totalQuantity += $productpurchaseitem->productpurchasequantity;
                                        $subtotal = ($productpurchaseitem->productpurchaseunitprice * $productpurchaseitem->productpurchasequantity);
                                        $totalSubtotal += $subtotal; 
                                        echo '<tr id="tr_'.$randID.'" purchaseproductid="'.$productpurchaseitem->productID.'">';
                                            echo '<td>';
                                                echo $i;
                                            echo '</td>';

                                            echo '<td>';
                                                echo isset($products[$productpurchaseitem->productID]) ? $products[$productpurchaseitem->productID] : '';
                                            echo '</td>';

                                            echo '<td>';
                                                echo '<input type="text" class="form-control change-productprice" id="productunitprice_'.$randID.'" data-productprice-id="'.$randID.'" value="'.$productpurchaseitem->productpurchaseunitprice.'">';
                                            echo '</td>';

                                            echo '<td>';
                                                echo '<input type="text" class="form-control change-productquantity" id="productquantity_'.$randID.'" data-productquantity-id="'.$randID.'" value="'.$productpurchaseitem->productpurchasequantity.'">';
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
                                <td colspan="3" style="font-weight: bold"><?=$this->lang->line('productpurchase_total')?></td>
                                <td id="totalQuantity" style="font-weight: bold"><?=number_format($totalQuantity, 2)?></td>
                                <td id="totalSubtotal" style="font-weight: bold"><?=number_format($totalSubtotal, 2)?></td>
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
            $(".image-preview-input-title").text("<?=$this->lang->line('productpurchase_browse')?>");
        });
        // Create the preview image
        $(".image-preview-input input:file").change(function (){
            var file = this.files[0];
            var reader = new FileReader();
            // Set preview image into the popover data-content
            reader.onload = function (e) {
                $(".image-preview-input-title").text("<?=$this->lang->line('productpurchase_browse')?>");
                $(".image-preview-clear").show();
                $(".image-preview-filename").val(file.name);
            }
            reader.readAsDataURL(file);
        });
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

    function parseSentenceForNumber(sentence) {
        var matches = sentence.replace(/,/g, '').match(/(\+|-)?((\d+(\.\d+)?)|(\.\d+))/);
        return matches && matches[0] || null;
    }   

    function getRandomInt() {
      return Math.floor(Math.random() * Math.floor(9999999999999999));
    }

    function productItemDesign(productID, productText) {
        var randID = getRandomInt();
        if($('#productList tr:last').text() == '') {
            var lastTdNumber = 0;
        } else {
            var lastTdNumber = $("#productList tr:last td:eq(0)").text();
        }
        lastTdNumber = parseInt(lastTdNumber);
        lastTdNumber++;

        var text = '<tr id="tr_'+randID+'" purchaseproductid="'+productID+'">';
            text += '<td>';
                text += lastTdNumber;
            text += '</td>';

            text += '<td>';
                text += productText;
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-productprice" id="productunitprice_'+randID+'" data-productprice-id="'+randID+'">');
            text += '</td>';

            text += '<td>';
                text += ('<input type="text" class="form-control change-productquantity" id="productquantity_'+randID+'" data-productquantity-id="'+randID+'">' );
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
    
    function currencyConvert(data) {
        return data.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
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

    $(document).on('keyup', '#productpurchasereferenceno', function() {
        var productpurchasereferenceno =  $(this).val();
        if(productpurchasereferenceno.length > 99) {
            productpurchasereferenceno = lenCheckerWithoutParseFloat(productpurchasereferenceno, 99);
            $(this).val(productpurchasereferenceno);                    
        }
    });




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

        totalSubtotal -= $('#productpurchasediscount').val()

        $('#productpurchasediscount').change(function(){
            totalSubtotal = totalSubtotal - $(this).val()
        })

        $('#productpurchasediscount').on('keyup', function(){
            totalSubtotal = totalSubtotal - $(this).val()
        })

        if ($('#productvatselectID').val() !== -1) {
            let vat = ($('#productvatselectID').val()/100) * totalSubtotal
            $('#productpurchasevat').val(vat)
            totalSubtotal = vat + totalSubtotal
        }

        $('#productvatselectID').change(function(){
            let vat_percent = $(this).val()
            if (vat_percent != -1) {
                vat = (vat_percent/100) * totalSubtotal
                $('#productpurchasevat').val(vat)
                totalSubtotal = vat + totalSubtotal
            }
        })

        $('#totalQuantity').text(currencyConvert(totalQuantity));
        $('#totalSubtotal').text(currencyConvert(totalSubtotal));
    }

    $('#productvatselectID').change(function(){
        totalInfo()
    })

    $('#productpurchasediscount').change(function(){
        totalInfo()
    })

    $('#productpurchasediscount').on('keyup', function(){
        totalInfo()
    })

    $('.select2').select2();
    $('#productpurchasedate').datepicker({
        autoclose: true,
        format: 'dd-mm-yyyy',
        startDate:'<?=$schoolyearsessionobj->startingdate?>',
        endDate:'<?=$schoolyearsessionobj->endingdate?>',
    });

    $('#productcategoryID').change(function(event) {
        var productcategoryID = $(this).val();
        if(productcategoryID === '0') {
            $('#productID').html('<option value="0"><?=$this->lang->line('productpurchase_select_product')?></option>');
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('productpurchase/getproductpurchase')?>",
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
        if(productID !=0) {
            var productText = $(this).find(":selected").text();
            var appendData  = productItemDesign(productID, productText);
            $('#productList').append(appendData);
        }
    });

    $(document).on('keyup', '.change-productprice', function() {
        var productPrice =  $(this).val();
        var productPriceID = $(this).attr('data-productprice-id'); 
        var productQuantity = $('#productquantity_'+productPriceID).val();
        
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
            var productPrice = parseSentenceForNumber($(this).val());
            $(this).val(productPrice);
        }
    });

    $(document).on('keyup', '.change-productquantity', function() {
        var productQuantity =  $(this).val();
        var productQuantityID = $(this).attr('data-productquantity-id'); 
        var productPrice = $('#productunitprice_'+productQuantityID).val();
        
        if(dotAndNumber(productQuantity)) {
            if(productQuantity.length > 15) {
                productQuantity = lenChecker(productQuantity, 15);
                $(this).val(productQuantity);
            }

            if((productQuantity != '' && productQuantity != null) && (productPrice != '' && productPrice != null)) {
                if(floatChecker(productQuantity)) {
                    if(productQuantity.length > 15) {
                        productQuantity = lenChecker(productQuantity, 15);
                        $(this).val(productQuantity);
                        $('#producttotal_'+productQuantityID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    } else {
                        $('#producttotal_'+productQuantityID).text(currencyConvert(productPrice*productQuantity));
                        totalInfo();
                    }

                }
            } else {
                $('#producttotal_'+productQuantityID).text('0.00');
                totalInfo();
            }
        } else {
            var productQuantity = parseSentenceForNumber($(this).val());
            $(this).val(productQuantity);
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
    });

    $(document).on('click', '#addPurchaseButton', function() {
        var error=0;;
        var field = {
            'productsupplierID'             : $('#productsupplierID').val(), 
            'productwarehouseID'            : $('#productwarehouseID').val(), 
            'productpurchasereferenceno'    : $('#productpurchasereferenceno').val(), 
            'productpurchasedate'           : $('#productpurchasedate').val(), 
            'productpurchasedescription'    : $('#productpurchasedescription').val(), 
        };

        if (field['productsupplierID'] === '0') {
            $('.productsupplierDiv').addClass('has-error');
            error++;
        } else {
            $('.productsupplierDiv').removeClass('has-error');
        }

        if (field['productwarehouseID'] === '0') {
            $('.productwarehouseDiv').addClass('has-error');
            error++;
        } else {
            $('.productwarehouseDiv').removeClass('has-error');
        }

        // if (field['productpurchasereferenceno'] == '') {
        //     $('.productpurchasereferencenoDiv').addClass('has-error');
        //     error++;
        // } else {
        //     $('.productpurchasereferencenoDiv').removeClass('has-error');
        // }

        if (field['productpurchasedate'] == '') {
            $('.productpurchasedateDiv').addClass('has-error');
            error++;
        } else {
            $('.productpurchasedateDiv').removeClass('has-error');
        }

        var productitem = $('tr[id^=tr_]').map(function(){
            return { productID : $(this).attr('purchaseproductid'), unitprice: $(this).children().eq(2).children().val(), quantity : $(this).children().eq(3).children().val() };
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
            formData.append("editID", <?=$productpurchaseID?>);
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
            url: "<?=base_url('productpurchase/saveproductpurchase')?>",
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

</script>