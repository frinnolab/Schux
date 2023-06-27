
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productwarehouse"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("productwarehouse/index")?>"><?=$this->lang->line('menu_productwarehouse')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_productwarehouse')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('productwarehousename') ? 'has-error' : '' ?>" >
                        <label for="productwarehousename" class="col-sm-2 control-label">
                            <?=$this->lang->line("productwarehouse_name")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productwarehousename" name="productwarehousename" value="<?=set_value('productwarehousename', $productwarehouse->productwarehousename)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productwarehousename'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productwarehousecode') ? 'has-error' : '' ?>" >
                        <label for="productwarehousecode" class="col-sm-2 control-label">
                            <?=$this->lang->line("productwarehouse_code")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productwarehousecode" name="productwarehousecode" value="<?=set_value('productwarehousecode', $productwarehouse->productwarehousecode)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productwarehousecode'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productwarehouseemail') ? 'has-error' : '' ?>" >
                        <label for="productwarehouseemail" class="col-sm-2 control-label">
                            <?=$this->lang->line("productwarehouse_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productwarehouseemail" name="productwarehouseemail" value="<?=set_value('productwarehouseemail', $productwarehouse->productwarehouseemail)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productwarehouseemail'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productwarehousephone') ? 'has-error' : '' ?>" >
                        <label for="productwarehousephone" class="col-sm-2 control-label">
                            <?=$this->lang->line("productwarehouse_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productwarehousephone" name="productwarehousephone" value="<?=set_value('productwarehousephone', $productwarehouse->productwarehousephone)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productwarehousephone'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productwarehouseaddress') ? 'has-error' : '' ?>" >
                        <label for="productwarehouseaddress" class="col-sm-2 control-label">
                            <?=$this->lang->line("productwarehouse_address")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="productwarehouseaddress" name="productwarehouseaddress"><?=set_value('productwarehouseaddress', $productwarehouse->productwarehouseaddress)?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productwarehouseaddress'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_productwarehouse")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
