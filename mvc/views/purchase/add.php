
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-cart-plus"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("purchase/index")?>"><?=$this->lang->line('menu_purchase')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_purchase')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('assetID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="assetID" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_assetID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array[0] = $this->lang->line('purchase_select_asset');
                                if(count($assets)) {
                                    foreach ($assets as $asset) {
                                        $array[$asset->assetID] = $asset->description;
                                    }
                                }
                                echo form_dropdown("assetID", $array, set_value("assetID"), "id='assetID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('assetID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('vendorID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="vendorID" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_vendorID")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $vendor_array[0] = $this->lang->line('purchase_select_vendor');
                                if(count($vendors)) {
                                    foreach ($vendors as $vendor) {
                                        $vendor_array[$vendor->productsupplierID] = $vendor->productsuppliercompanyname;
                                    }
                                }
                                echo form_dropdown("vendorID", $vendor_array, set_value("vendorID"), "id='vendorID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('vendorID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('purchased_by'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="purchased_by" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchased_by")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $user_array[0] = $this->lang->line('purchase_select_purchased_by');
                                if(count($users)) {
                                    foreach ($users as $user) {
                                        $user_array[$user->userID] = $user->name;
                                    }
                                }
                                echo form_dropdown("purchased_by", $user_array, set_value("purchased_by"), "id='purchased_by' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('purchased_by'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('quantity'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="quantity" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_quantity")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="quantity" name="quantity" value="<?=set_value('quantity')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('quantity'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('unit'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="unit" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_unit")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                echo form_dropdown("unit", array( 0 => $this->lang->line('purchase_select_unit'), 1 => $this->lang->line('purchase_unit_kg'), 2 => $this->lang->line('purchase_unit_piece'), 3 => $this->lang->line('purchase_unit_box')), set_value("unit"), "id='unit' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('unit'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('purchase_price'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="purchase_price" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_price")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="purchase_price" name="purchase_price" value="<?=set_value('purchase_price')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('purchase_price'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('purchase_date'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="purchase_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="purchase_date" name="purchase_date" value="<?=set_value('purchase_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('purchase_date'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('service_date'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="service_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_service_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="service_date" name="service_date" value="<?=set_value('service_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('service_date'); ?>
                        </span>
                    </div>

                    <?php
                    if(form_error('expire_date'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="expire_date" class="col-sm-2 control-label">
                            <?=$this->lang->line("purchase_expire_date")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="expire_date" name="expire_date" value="<?=set_value('expire_date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('expire_date'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_purchase")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.select2').select2();
    $('#purchase_date, #service_date').datepicker();
    $('#expire_date').datepicker({startView: 2});
</script>
