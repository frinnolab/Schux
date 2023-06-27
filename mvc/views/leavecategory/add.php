<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa iniicon-leavecategory"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("leavecategory/index")?>"><?=$this->lang->line('menu_leavecategory')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_leavecategory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <div class="form-group <?=form_error('leavecategory') ? 'has-error' : ''?>">
                        <label for="leavecategory" class="col-sm-2 control-label">
                            <?=$this->lang->line("leavecategory_category")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="leavecategory" name="leavecategory" value="<?=set_value('leavecategory')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?=form_error('leavecategory'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_leavecategory")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>