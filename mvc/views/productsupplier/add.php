
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-productsupplier"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("productsupplier/index")?>"><?=$this->lang->line('menu_productsupplier')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_productsupplier')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('productsuppliercompanyname') ? 'has-error' : '' ?>" >
                        <label for="productsuppliercompanyname" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_companyname")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliercompanyname" name="productsuppliercompanyname" value="<?=set_value('productsuppliercompanyname')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliercompanyname'); ?>
                        </span>
                    </div>
                    
                    <div class="form-group <?=form_error('productsuppliername') ? 'has-error' : '' ?>" >
                        <label for="productsuppliername" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_suppliername")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliername" name="productsuppliername" value="<?=set_value('productsuppliername')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliername'); ?>
                        </span>
                    </div>
                    
                    <div class="form-group <?=form_error('productsuppliertin') ? 'has-error' : '' ?>" >
                        <label for="productsuppliertin" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_tin")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliertin" name="productsuppliertin" value="<?=set_value('productsuppliertin')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliertin'); ?>
                        </span>
                    </div>
                    
                    <div class="form-group <?=form_error('productsuppliervrn') ? 'has-error' : '' ?>" >
                        <label for="productsuppliervrn" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_vrn")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsuppliervrn" name="productsuppliervrn" value="<?=set_value('productsuppliervrn')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsuppliervrn'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplieremail') ? 'has-error' : '' ?>" >
                        <label for="productsupplieremail" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_email")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplieremail" name="productsupplieremail" value="<?=set_value('productsupplieremail')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplieremail'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplierphone') ? 'has-error' : '' ?>" >
                        <label for="productsupplierphone" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplierphone" name="productsupplierphone" value="<?=set_value('productsupplierphone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplierphone'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplieralternatephone') ? 'has-error' : '' ?>" >
                        <label for="productsupplieralternatephone" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_alternate_phone")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="productsupplieralternatephone" name="productsupplieralternatephone" value="<?=set_value('productsupplieralternatephone')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplieralternatephone'); ?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('productsupplieraddress') ? 'has-error' : '' ?>" >
                        <label for="productsupplieraddress" class="col-sm-2 control-label">
                            <?=$this->lang->line("productsupplier_address")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" style="resize:none;" id="productsupplieraddress" name="productsupplieraddress"><?=set_value('productsupplieraddress')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('productsupplieraddress'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_supplier")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>