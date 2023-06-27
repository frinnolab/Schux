<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-product"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <?php if(permissionChecker('product_add')) { ?>
                    <h5 class="page-header">
                        <a href="<?php echo base_url('product/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_product')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_category')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('product_buyingprice')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('product_sellingprice')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('product_opening_stock')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('product_quantity')?></th>
                                <?php if(permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                    <th class="col-sm-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($products)) {
                                $i = 1; 
                                foreach($products as $product) { 
                            ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_product')?>">
                                        <?=$product->productname;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_category')?>">
                                        <?=isset($productcategorys[$product->productcategoryID]) ? $productcategorys[$product->productcategoryID] : ''?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_buyingprice')?>">
                                        <?=$product->productbuyingprice;?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('product_sellingprice')?>">
                                        <?=$product->productsellingprice;?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_opening_stock')?>">
                                        <?=$product->productopeningstock?>
                                    </td>
                                    
                                    <td data-title="<?=$this->lang->line('product_quantity')?>">
                                        <?=$product->total_purchase_quantity - $product->total_sale_quantity?>
                                    </td>
                                    
                                    <?php if(permissionChecker('product_edit') || permissionChecker('product_delete')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <?php echo btn_edit('product/edit/'.$product->productID, $this->lang->line('edit')) ?>
                                            <?php echo btn_delete('product/delete/'.$product->productID, $this->lang->line('delete')) ?>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; $n++; }} ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>